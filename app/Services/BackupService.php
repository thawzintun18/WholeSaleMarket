<?php
namespace App\Services;

use App\Models\BackupLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class BackupService
{
    public function createBackup(string $type = 'Manual'): BackupLog
    {
        $log = BackupLog::create([
            'backup_type'  => $type,
            'status'       => 'Pending',
            'local_status' => 'Pending',
            'usb_status'   => 'Pending',
            'started_at'   => now(),
        ]);

        $localFile = null;
        $gzip      = null;

        try {

            // Database Configuration
            $db = config('database.connections.mysql');

            if (! $db) {
                throw new RuntimeException(
                    'Database configuration not found.'
                );
            }

            // mysqldump Path
            $mysqldump = config('backup.mysqldump_path');

            if (! file_exists($mysqldump)) {
                throw new RuntimeException(
                    'mysqldump.exe not found.'
                );
            }

            // Backup Filename
            $fileName = 'backup_' .
            now()->format('Ymd_His') . '_' .
            Str::random(6) . '.sql.gz';

            // Date Folder
            $dateFolder = now()->format('Y\\m');

            // Local Backup Path
            $localFolder = rtrim(
                config('backup.local_path'),
                '\\/'
            ) . '\\database\\' . $dateFolder;

            if (
                ! is_dir($localFolder) &&
                ! mkdir($localFolder, 0755, true) &&
                ! is_dir($localFolder)
            ) {
                throw new RuntimeException(
                    'Unable to create local backup directory.'
                );
            }

            $localFile = $localFolder . '\\' . $fileName;

            // Create Gzip File
            $gzip = gzopen($localFile, 'wb9');

            if ($gzip === false) {
                throw new RuntimeException(
                    'Unable to create backup file.'
                );
            }

            // mysqldump Command
            $command = [
                $mysqldump,
                '--host=' . $db['host'],
                '--port=' . $db['port'],
                '--user=' . $db['username'],
                '--single-transaction',
                '--quick',
                '--routines',
                '--triggers',
                '--events',
                '--default-character-set=utf8mb4',

                // Backup History ကို Restore လုပ်ရာတွင် မပျောက်စေရန်
                '--ignore-table=' . $db['database'] . '.backup_logs',

                $db['database'],
            ];

            // Preserve current Windows environment variables
            $env = getenv();

            if (! is_array($env)) {
                $env = [];
            }

            // Database Password
            if (! empty($db['password'])) {
                $env['MYSQL_PWD'] = $db['password'];
            }

            // Execute Database Export
            $process = new Process(
                $command,
                base_path(),
                $env
            );

            $process->setTimeout(
                config('backup.timeout', 3600)
            );

            $errorOutput = '';

            $process->run(function ($type, $buffer) use (
                &$gzip,
                &$errorOutput
            ) {

                if ($type === Process::OUT) {

                    if ($gzip === false) {
                        throw new RuntimeException(
                            'Gzip file is not available.'
                        );
                    }

                    if (
                        gzwrite($gzip, $buffer) !== strlen($buffer)
                    ) {
                        throw new RuntimeException(
                            'Unable to write backup data.'
                        );
                    }

                } else {

                    $errorOutput .= $buffer;

                }
            });

            // Close Gzip
            gzclose($gzip);
            $gzip = null;

            // Check Export Result
            if (! $process->isSuccessful()) {
                throw new RuntimeException(
                    $errorOutput ?: $process->getErrorOutput()
                );
            }

            // Check Local Backup File
            if (
                ! file_exists($localFile) ||
                filesize($localFile) <= 0
            ) {
                throw new RuntimeException(
                    'Local backup file is empty.'
                );
            }

            $fileSize = filesize($localFile);

            // Local Backup Success
            $log->update([
                'file_name'    => $fileName,
                'file_path'    => $localFile,
                'local_path'   => $localFile,
                'file_size'    => $fileSize,
                'local_status' => 'Success',
            ]);

            // USB Backup
            $usbRoot = rtrim(
                config('backup.usb_path'),
                '\\/'
            );

            $usbFolder = $usbRoot .
                '\\database\\' . $dateFolder;

            $usbFile = $usbFolder . '\\' . $fileName;

            try {

                // Check USB Drive
                $drive = substr($usbRoot, 0, 3);

                if (! is_dir($drive)) {
                    throw new RuntimeException(
                        'USB drive is not connected.'
                    );
                }

                // Create USB Folder
                if (
                    ! is_dir($usbFolder) &&
                    ! mkdir($usbFolder, 0755, true) &&
                    ! is_dir($usbFolder)
                ) {
                    throw new RuntimeException(
                        'Unable to create USB backup directory.'
                    );
                }

                // Copy Backup File
                if (! copy($localFile, $usbFile)) {
                    throw new RuntimeException(
                        'Unable to copy backup to USB.'
                    );
                }

                // Verify USB File
                if (
                    ! file_exists($usbFile) ||
                    filesize($usbFile) !== $fileSize
                ) {
                    throw new RuntimeException(
                        'USB backup verification failed.'
                    );
                }

                $log->update([
                    'usb_path'     => $usbFile,
                    'usb_status'   => 'Success',
                    'status'       => 'Success',
                    'completed_at' => now(),
                ]);

            } catch (Throwable $usbError) {

                $log->update([
                    'usb_path'      => $usbFile,
                    'usb_status'    => 'Failed',
                    'status'        => 'Partial',
                    'error_message' => Str::limit(
                        $usbError->getMessage(),
                        5000
                    ),
                    'completed_at'  => now(),
                ]);
            }

            return $log->fresh();

        } catch (Throwable $e) {

            // Close Gzip if necessary
            if ($gzip !== null && $gzip !== false) {
                gzclose($gzip);
            }

            // Delete Incomplete Local File
            if (
                $localFile !== null &&
                file_exists($localFile)
            ) {
                unlink($localFile);
            }

            $log->update([
                'status'        => 'Failed',
                'local_status'  => 'Failed',
                'usb_status'    => 'Pending',
                'error_message' => Str::limit(
                    $e->getMessage(),
                    5000
                ),
                'completed_at'  => now(),
            ]);

            throw new RuntimeException(
                'Database backup failed: ' . $e->getMessage(),
                0,
                $e
            );
        }
    }

    public function retryUsb(BackupLog $log): BackupLog
    {
        if ($log->local_status !== 'Success') {
            throw new \RuntimeException(
                'Local Backup အောင်မြင်ထားခြင်းမရှိပါ။'
            );
        }

        $localFile = $log->local_path ?: $log->file_path;

        if (! $localFile || ! file_exists($localFile)) {
            throw new \RuntimeException(
                'Local Backup File မတွေ့ပါ။'
            );
        }

        $usbRoot = rtrim(
            config('backup.usb_path'),
            '\\/'
        );

        $drive = substr($usbRoot, 0, 3);

        if (! is_dir($drive)) {
            throw new \RuntimeException(
                'USB Drive မချိတ်ဆက်ထားပါ။'
            );
        }

        $dateFolder = $log->started_at
            ? $log->started_at->format('Y\\m')
            : now()->format('Y\\m');

        $usbFolder = $usbRoot .
            '\\database\\' . $dateFolder;

        if (
            ! is_dir($usbFolder) &&
            ! mkdir($usbFolder, 0755, true) &&
            ! is_dir($usbFolder)
        ) {
            throw new \RuntimeException(
                'USB Backup Directory ဖန်တီး၍မရပါ။'
            );
        }

        $usbFile = $usbFolder . '\\' . basename($localFile);

        if (! copy($localFile, $usbFile)) {
            throw new \RuntimeException(
                'USB သို့ File ကူးယူ၍မရပါ။'
            );
        }

        $localSize = filesize($localFile);
        $usbSize   = filesize($usbFile);

        if ($localSize === false || $usbSize !== $localSize) {
            throw new \RuntimeException(
                'USB Backup Verification မအောင်မြင်ပါ။'
            );
        }

        $log->update([
            'usb_path'      => $usbFile,
            'usb_status'    => 'Success',
            'status'        => 'Success',
            'error_message' => null,
            'completed_at'  => now(),
        ]);

        return $log->fresh();
    }

    //Main RestoreMethod
    // ဒီ MethodကRestoreProcessတစ်ခုလုံးကိုထိန်းချုပ်ပါမယ်။
    public function restoreFromBackup(string $backupFile): BackupLog
    {

        $lock = Cache::lock(
            'whole_sale_market_restore_lock',
            max((int) config('backup.timeout', 3600) + 300, 3600)
        );

        if (! $lock->get()) {
            throw new RuntimeException(
                'အခြား Restore လုပ်ငန်းတစ်ခု လုပ်ဆောင်နေပါသည်။ ပြီးဆုံးသည်အထိ စောင့်ပေးပါ။'
            );
        }

        try {

            $startedAt = now();
            $sqlFile   = null;

            try {

                // 1. Validate Backup File
                $backupFile = $this->validateBackupFile($backupFile);

                // 2. Validate GZIP Integrity
                $this->validateGzipFile($backupFile);

                // 3. Create Safety Backup
                $safetyBackup = $this->createBackup('BeforeRestore');

                if ($safetyBackup->local_status !== 'Success') {
                    throw new RuntimeException(
                        'Safety Backup မအောင်မြင်ပါ။ Restore လုပ်ခွင့်မရှိပါ။'
                    );
                }

                // 4. Extract GZIP to Temporary SQL
                $sqlFile = $this->extractGzipToSql($backupFile);

                // 5. Restore Database
                $this->restoreDatabase($sqlFile);

                // 6. Verify Database
                $this->verifyDatabase();

                // 7. Save Restore Success Log
                return BackupLog::create([
                    'backup_type'  => 'Restore',
                    'file_name'    => basename($backupFile),
                    'file_path'    => $backupFile,
                    'local_path'   => $backupFile,
                    'file_size'    => filesize($backupFile),

                    'local_status' => 'Success',
                    'usb_status'   => 'Pending',
                    'status'       => 'Success',

                    'started_at'   => $startedAt,
                    'completed_at' => now(),
                ]);

            } catch (Throwable $e) {

                $this->logRestoreError($backupFile, $e);

                throw new RuntimeException(
                    'Database Restore Failed: ' . $e->getMessage(),
                    0,
                    $e
                );

            } finally {

                // Delete Temporary SQL File
                if ($sqlFile !== null && file_exists($sqlFile)) {
                    unlink($sqlFile);
                }
            }

        } finally {

            $lock->release();

        }

    }

    //validateBackupFile
    // Backup Fileရဲ့တည်နေရာ၊Extensionနဲ့Fileရှိမရှိစစ်ပါမယ်။;
    // Local BackupFolderနဲ့USBBackupFolderအတွင်းကဖိုင်တွေကိုသာလက်ခံပါမယ်။
    private function validateBackupFile(string $backupFile): string
    {
        if (! file_exists($backupFile) || ! is_file($backupFile)) {
            throw new RuntimeException(
                'Backup File မတွေ့ပါ။'
            );
        }

        $realPath = realpath($backupFile);

        if ($realPath === false) {
            throw new RuntimeException(
                'Backup File Path မမှန်ပါ။'
            );
        }

        // Only .sql.gz
        if (! Str::endsWith(strtolower($realPath), '.sql.gz')) {
            throw new RuntimeException(
                'Invalid Backup File Extension.'
            );
        }

        // Allowed Backup Directories
        $allowedPaths = [
            config('backup.local_path'),
            config('backup.usb_path'),
        ];

        $isAllowed = false;

        foreach ($allowedPaths as $path) {

            if (empty($path)) {
                continue;
            }

            $root = realpath($path);

            if ($root === false) {
                continue;
            }

            $root = rtrim($root, '\\/') . DIRECTORY_SEPARATOR;

            if (str_starts_with(
                strtolower($realPath),
                strtolower($root)
            )) {
                $isAllowed = true;
                break;
            }
        }

        if (! $isAllowed) {
            throw new RuntimeException(
                'ခွင့်မပြုထားသော Backup Folder ဖြစ်ပါသည်။'
            );
        }

        if (filesize($realPath) <= 0) {
            throw new RuntimeException(
                'Backup File သည် Empty ဖြစ်နေပါသည်။'
            );
        }

        return $realPath;
    }

    // validateGzipFile
    // GZIP ဖိုင်ကိုဖွင့်ပြီးအဆုံးအထိဖတ်စစ်ပါမယ်။;
    // GZIP ရဲ့CRC / Integrity Errorရှိမရှိစစ်ဆေးပါမယ်။
    private function validateGzipFile(string $backupFile): void
    {
        $handle = @gzopen($backupFile, 'rb');

        if ($handle === false) {
            throw new RuntimeException('GZIP ဖိုင်ကို ဖွင့်၍မရပါ။');
        }

        try {
            while (! gzeof($handle)) {
                $data = gzread($handle, 1024 * 1024);

                if ($data === false) {
                    throw new RuntimeException('GZIP ဖိုင် ပျက်စီးနေပါသည်။');
                }
            }
        } finally {
            gzclose($handle);
        }
    }

    //Temporary SQLExtractMethod
    // GZIP ကိုSQLFileအဖြစ်TemporaryFolderထဲမှာထုတ်ပါမယ်။;
    // ဒီလိုလုပ်ခြင်းအားဖြင့် SQLဖိုင်တစ်ခုလုံးကိုPHPMemoryထဲမတင်ရတော့ပါဘူး။
    private function extractGzipToSql(string $gzipFile): string
    {
        $tempFolder = storage_path('app/restore-temp');

        if (! is_dir($tempFolder)) {
            mkdir($tempFolder, 0755, true);
        }

        $sqlFile = tempnam($tempFolder, 'restore_');

        if ($sqlFile === false) {
            throw new RuntimeException(
                'Temporary SQL File ဖန်တီးမရပါ။'
            );
        }

        $gzip   = gzopen($gzipFile, 'rb');
        $output = fopen($sqlFile, 'wb');

        if ($gzip === false || $output === false) {

            if ($gzip !== false) {
                gzclose($gzip);
            }

            if ($output !== false) {
                fclose($output);
            }

            @unlink($sqlFile);

            throw new RuntimeException(
                'Unable to extract GZIP File.'
            );
        }

        try {

            while (! gzeof($gzip)) {

                $data = gzread($gzip, 1024 * 1024);

                if ($data === false) {
                    throw new RuntimeException(
                        'GZIP Read Failed.'
                    );
                }

                if ($data !== '' && fwrite($output, $data) !== strlen($data)) {
                    throw new RuntimeException(
                        'SQL File Write Failed.'
                    );
                }
            }

        } catch (Throwable $e) {

            @unlink($sqlFile);

            throw $e;

        } finally {

            fclose($output);
            gzclose($gzip);
        }

        if (filesize($sqlFile) <= 0) {
            @unlink($sqlFile);

            throw new RuntimeException(
                'Extracted SQL File is Empty.'
            );
        }

        return $sqlFile;
    }

    //restoreDatabase
    // ဒီ Methodကmysql . exe ကိုအသုံးပြုပြီးDatabaseRestoreလုပ်ပါမယ်။
    private function restoreDatabase(string $sqlFile): void
    {
        $mysql = config('backup.mysql_path');

        if (! file_exists($mysql)) {
            throw new RuntimeException('mysql.exe not found.');
        }

        $db = config('database.connections.mysql');

        if (! $db) {
            throw new RuntimeException(
                'Database Configuration Not Found.'
            );
        }

        // Absolute SQL File Path
        $realPath = realpath($sqlFile);

        if ($realPath === false || ! is_file($realPath)) {
            throw new RuntimeException(
                'SQL Backup File Not Found.'
            );
        }

        // Windows Path ကို Forward Slash ပြောင်းခြင်း
        $sqlPath = str_replace('\\', '/', $realPath);

        // MySQL Source Command
        $sourceCommand = 'source ' . $sqlPath;

        $command = [
            $mysql,
            '--host=' . $db['host'],
            '--port=' . $db['port'],
            '--user=' . $db['username'],
            '--default-character-set=utf8mb4',
            $db['database'],
            '--execute=' . $sourceCommand,
        ];

        // Preserve current Windows environment variables
        $env = getenv();

        if (! is_array($env)) {
            $env = [];
        }

        // Database Password
        if (! empty($db['password'])) {
            $env['MYSQL_PWD'] = $db['password'];
        }

        // Execute Database Restore
        $process = new Process(
            $command,
            base_path(),
            $env
        );

        $process->setTimeout(
            config('backup.timeout', 3600)
        );

        $process->run();

        if (! $process->isSuccessful()) {
            throw new RuntimeException(
                $process->getErrorOutput() ?:
                $process->getOutput() ?:
                'MySQL Restore Process Failed.'
            );
        }

    }

    //verifyDatabase
    // Restore ပြီးတဲ့အခါDatabaseConnectionပြန်ချိတ်ပြီးအရေးကြီးတဲ့Tablesတွေရှိမရှိစစ်ပါမယ်။;
    // သင့် WholeSaleMarketရဲ့PurchaseSystemမှာအဓိကအသုံးပြုနေတဲ့Tablesတွေကိုထည့်ထားပါတယ်။
    private function verifyDatabase(): bool
    {

        // Reset Database Connection
        DB::purge('mysql');

        DB::reconnect('mysql');

        // Test Database Connection
        DB::connection('mysql')->getPdo();

        // Important Tables
        $requiredTables = [
            'farmers',
            'crops',
            'purchases',
            'purchase_items',
        ];

        foreach ($requiredTables as $table) {

            if (! Schema::connection('mysql')->hasTable($table)) {

                throw new RuntimeException(
                    "Restore Verification Failed: {$table} table not found."
                );
            }
        }

        // Test Database Query
        DB::connection('mysql')->select('SELECT 1');

        return true;

    }

    //logRestoreError
    // Restore လုပ်တဲ့အချိန်Errorတက်ခဲ့ရင်DatabaseLogနဲ့LaravelLogနှစ်မျိုးလုံးမှာမှတ်တမ်းတင်ပါမယ်။
    private function logRestoreError(?string $backupFile,
        Throwable $e
    ): void {

        $errorMessage = Str::limit(
            $e->getMessage(),
            5000
        );

        // Laravel Log
        Log::error('Database Restore Failed', [
            'backup_file' => $backupFile,
            'error'       => $errorMessage,
        ]);

        // Database Log
        try {

            BackupLog::create([
                'backup_type'   => 'Restore',

                'file_name'     => $backupFile
                    ? basename($backupFile)
                    : null,

                'file_path'     => $backupFile,
                'local_path'    => $backupFile,

                'file_size'     => (
                    $backupFile && file_exists($backupFile)
                ) ? filesize($backupFile) : 0,

                'local_status'  => 'Failed',
                'usb_status'    => 'Pending',
                'status'        => 'Failed',

                'error_message' => $errorMessage,

                'started_at'    => now(),
                'completed_at'  => now(),
            ]);

        } catch (Throwable $logError) {

            Log::critical('Unable to save Restore Error to Database', [
                'error' => $logError->getMessage(),
            ]);
        }
    }

    //Local DatabaseBackupFileListMethod*****
    public function getDatabaseBackupFiles(): array
    {
        // Database Backup Root Folder
        $databaseRoot = rtrim(
            config('backup.local_path'),
            '\\/'
        ) . DIRECTORY_SEPARATOR . 'database';

        // Folder မရှိသေးရင် Empty Array ပြန်ပေးမယ်
        if (! is_dir($databaseRoot)) {
            return [];
        }

        $realDatabaseRoot = realpath($databaseRoot);

        if ($realDatabaseRoot === false) {
            return [];
        }

        $allowedRoot = rtrim(
            $realDatabaseRoot,
            '\\/'
        ) . DIRECTORY_SEPARATOR;

        $backups = [];

        // Search all year/month folders recursively
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $realDatabaseRoot,
                \FilesystemIterator::SKIP_DOTS
            )
        );

        foreach ($iterator as $file) {

            // Only regular files; skip symbolic links
            if (! $file->isFile() || $file->isLink()) {
                continue;
            }

            // Only .sql.gz files
            if (! Str::endsWith(
                strtolower($file->getFilename()),
                '.sql.gz'
            )) {
                continue;
            }

            $realPath = realpath($file->getPathname());

            if ($realPath === false) {
                continue;
            }

            // Ensure file is inside database backup folder
            if (! str_starts_with(
                strtolower($realPath),
                strtolower($allowedRoot)
            )) {
                continue;
            }

            // Validate backup file
            try {
                $realPath = $this->validateBackupFile($realPath);
            } catch (Throwable $e) {
                continue;
            }

            $modifiedAt = filemtime($realPath);

            $relativePath = substr(
                $realPath,
                strlen($allowedRoot)
            );

            $backups[] = [
                'file_name'     => basename($realPath),

                // Relative path only; no absolute server path exposed
                'relative_path' => str_replace(
                    '\\',
                    '/',
                    $relativePath
                ),

                'file_size'     => filesize($realPath),

                'modified_at'   => date(
                    'd-m-Y h:i A',
                    $modifiedAt
                ),

                '_timestamp'    => $modifiedAt,
            ];
        }

        // Newest backup first
        usort($backups, function ($a, $b) {
            return $b['_timestamp'] <=> $a['_timestamp'];
        });

        // Remove internal sorting field
        foreach ($backups as &$backup) {
            unset($backup['_timestamp']);
        }

        unset($backup);

        return $backups;

    }

    //အခု RestoreModalကနေrelative_pathကိုပို့လာတဲ့အခါServerကအဲဒီဖိုင်ဟာသတ်မှတ်ထားတဲ့DatabaseBackupFolderအတွင်းကဖိုင်ဟုတ်မဟုတ်စစ်ဆေးဖို့Methodတစ်ခုထပ်ထည့်ပါမယ်။
    public function resolveLocalDatabaseBackup(string $relativePath): string
    {
        // Empty path check
        if (
            trim($relativePath) === '' ||
            str_contains($relativePath, "\0")
        ) {
            throw new RuntimeException(
                'Backup File Path မမှန်ပါ။'
            );
        }

        // Normalize Windows / Unix path separators
        $relativePath = str_replace(
            ['/', '\\'],
            DIRECTORY_SEPARATOR,
            $relativePath
        );

        // Prevent absolute paths
        if (
            str_starts_with($relativePath, DIRECTORY_SEPARATOR) ||
            preg_match('/^[A-Za-z]:/', $relativePath)
        ) {
            throw new RuntimeException(
                'Absolute File Path ကို ခွင့်မပြုပါ။'
            );
        }

        // Prevent directory traversal
        $segments = explode(DIRECTORY_SEPARATOR, $relativePath);

        foreach ($segments as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..') {
                throw new RuntimeException(
                    'Invalid Backup File Path.'
                );
            }
        }

        // Database Backup Root
        $databaseRoot = rtrim(
            config('backup.local_path'),
            '\\/'
        ) . DIRECTORY_SEPARATOR . 'database';

        $realRoot = realpath($databaseRoot);

        if ($realRoot === false || ! is_dir($realRoot)) {
            throw new RuntimeException(
                'Database Backup Folder မတွေ့ပါ။'
            );
        }

        // Resolve selected file
        $candidate = realpath(
            $realRoot . DIRECTORY_SEPARATOR . $relativePath
        );

        if ($candidate === false || ! is_file($candidate)) {
            throw new RuntimeException(
                'ရွေးချယ်ထားသော Backup File မတွေ့ပါ။'
            );
        }

        // Ensure file stays inside database folder
        $allowedRoot = rtrim(
            $realRoot,
            '\\/'
        ) . DIRECTORY_SEPARATOR;

        if (! str_starts_with(
            strtolower($candidate),
            strtolower($allowedRoot)
        )) {
            throw new RuntimeException(
                'ခွင့်မပြုထားသော Backup Folder ဖြစ်ပါသည်။'
            );
        }

        // Reuse existing validation
        return $this->validateBackupFile($candidate);
    }

}
