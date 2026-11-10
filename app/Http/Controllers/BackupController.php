<?php
namespace App\Http\Controllers;

use App\Models\BackupLog;
use App\Services\BackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BackupController extends Controller
{
    //index
    public function index(BackupService $backupService)
    {
        $backups = BackupLog::latest()
            ->paginate(15);

        // Local Database Backup Files
        $databaseBackupFiles = $backupService->getDatabaseBackupFiles();

        return view('backups.index', compact(
            'backups',
            'databaseBackupFiles'
        ));
    }

    //manual
    public function manual(BackupService $backupService)
    {
        try {
            $backup = $backupService->createBackup('Manual');

            if ($backup->status === 'Success') {
                return redirect()
                    ->route('Backup#list')
                    ->with('success', 'Backup အောင်မြင်ပါသည်။');
            }

            return redirect()
                ->route('Backup#list')
                ->with('warning', 'Local Backup အောင်မြင်ပြီး USB Backup မအောင်မြင်ပါ။');

        } catch (\Throwable $e) {

            return redirect()
                ->route('Backup#list')
                ->with('error', 'Backup မအောင်မြင်ပါ။ ' . $e->getMessage());
        }
    }

    //retryUsb
    public function retryUsb($id,
        BackupService $backupService
    ) {
        $backup = BackupLog::findOrFail($id);

        try {
            $backupService->retryUsb($backup);

            return redirect()
                ->route('Backup#list')
                ->with('success', 'USB Backup ပြန်လည်ကူးယူပြီးပါပြီ။');

        } catch (\Throwable $e) {

            return redirect()
                ->route('Backup#list')
                ->with('error', 'USB Backup မအောင်မြင်ပါ။ ' . $e->getMessage());
        }
    }

    // restore
    public function restore($id, BackupService $backupService)
    {
        $backup = BackupLog::findOrFail($id);

        // Restore ပြီးသားမှတ်တမ်းကို ပြန်ရွေးခြင်း မပြုရန်
        if ($backup->backup_type === 'Restore') {
            return redirect()
                ->route('Backup#list')
                ->with('error', 'Restore မှတ်တမ်းကို မူရင်း Backup အဖြစ် ရွေးချယ်၍မရပါ။');
        }

        // Local Backup အောင်မြင်ထားမှ Restore လုပ်ခွင့်ပြုရန်
        if ($backup->local_status !== 'Success') {
            return redirect()
                ->route('Backup#list')
                ->with('error', 'Local Backup အောင်မြင်ထားသော ဖိုင်ကိုသာ Restore လုပ်နိုင်ပါသည်။');
        }

        // လက်ရှိ Service က SQL GZIP ကိုသာ Restore လုပ်နိုင်သည်
        if (! str_ends_with(strtolower($backup->file_name ?? ''), '.sql.gz')) {
            return redirect()
                ->route('Backup#list')
                ->with('error', 'ဤ Backup ဖိုင်အမျိုးအစားကို Restore လုပ်၍မရပါ။');
        }

        // Backup File Path
        $backupFile = $backup->local_path ?: $backup->file_path;

        if (empty($backupFile)) {
            return redirect()
                ->route('Backup#list')
                ->with('error', 'Backup File Path မတွေ့ပါ။');
        }

        try {

            $restore = $backupService->restoreFromBackup($backupFile);

            return redirect()
                ->route('Backup#list')
                ->with(
                    'success',
                    'Database Restore အောင်မြင်ပါသည်။ Restore History ID: ' . $restore->id
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('Backup#list')
                ->with('error', 'Database Restore မအောင်မြင်ပါ။ ' . $e->getMessage());
        }
    }

    // Restore Local Database Backup
    public function restoreLocal(Request $request,
        BackupService $backupService
    ) {
        // Validate Request
        $validated = $request->validate([
            'relative_path' => [
                'required',
                'string',
                'max:500',
            ],
        ]);

        try {

            // Resolve and Validate Selected Backup File
            $backupFile = $backupService->resolveLocalDatabaseBackup(
                $validated['relative_path']
            );

            // Restore Database
            $restore = $backupService->restoreFromBackup(
                $backupFile
            );

            return redirect()
                ->route('Backup#list')
                ->with(
                    'success',
                    'Database Restore အောင်မြင်ပါသည်။ Restore History ID: ' . $restore->id
                );

        } catch (\Throwable $e) {

            return redirect()
                ->route('Backup#list')
                ->with(
                    'error',
                    'Database Restore မအောင်မြင်ပါ။ ' . $e->getMessage()
                );
        }
    }

    //delete
    public function delete($id)
    {
        $backup = BackupLog::findOrFail($id);

        // Restore record ဆိုရင် Log ပဲဖျက်မယ်
        if ($backup->backup_type == 'Restore') {
            $backup->delete();

            return back()->with('success', 'Backup History ကို ဖျက်ပြီးပါပြီ။');
        }

        $localRoot = rtrim(config('backup.local_path'), '\\/');
        $usbRoot   = rtrim(config('backup.usb_path'), '\\/');

        // Local file path
        $localPath = $backup->local_path ?: $backup->file_path;

        if (! $localPath) {
            return back()->with('error', 'Backup File Path မတွေ့ပါ။');
        }

        // Path separator တူညီအောင် ပြောင်းမယ်
        $localRoot = str_replace('\\', '/', $localRoot);
        $localPath = str_replace('\\', '/', $localPath);

        // Local root အောက်မှာရှိတဲ့ file ဟုတ်မဟုတ် စစ်မယ်
        if (! str_starts_with(
            strtolower($localPath),
            strtolower(rtrim($localRoot, '/') . '/')
        )) {
            return back()->with('error', 'Invalid Backup File Path ဖြစ်နေပါသည်။');
        }

        // Local path ကနေ relative path ထုတ်မယ်
        $relativePath = substr($localPath, strlen(rtrim($localRoot, '/')) + 1);

        // USB path တည်ဆောက်မယ်
        $usbPath = $usbRoot . DIRECTORY_SEPARATOR .
        str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

        // USB ထဲမှာ Backup ရှိတယ်လို့ မှတ်တမ်းတင်ထားပြီး
        // USB မချိတ်ထားရင် မဖျက်သေးဘူး
        if ($backup->usb_status == 'Success' && ! File::isDirectory($usbRoot)) {
            return back()->with('error', 'USB Drive ကို ချိတ်ဆက်ပြီးမှ ဖျက်ပေးပါ။');
        }

        try {

            // USB File ဖျက်မယ်
            if (File::exists($usbPath)) {
                File::delete($usbPath);
            }

            // Local File ဖျက်မယ်
            if (File::exists($localPath)) {
                File::delete($localPath);
            }

            // Database Record ဖျက်မယ်
            $backup->delete();

            return back()->with('success', 'Backup File နှင့် History ကို ဖျက်ပြီးပါပြီ။');

        } catch (\Exception $e) {

            return back()->with('error', 'Backup ဖျက်ရာတွင် အမှားဖြစ်နေပါသည်။');
        }
    }

}
