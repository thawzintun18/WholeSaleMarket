<?php
namespace App\Console\Commands;

use App\Services\BackupService;
use Illuminate\Console\Command;
use Throwable;

class DatabaseBackup extends Command
{
    protected $signature = 'backup:database {--type=Automatic}';

    protected $description = 'Create database backup to local and USB drives';

    public function handle(BackupService $backupService): int
    {
        $this->info('Starting database backup...');

        try {

            $backup = $backupService->createBackup(
                $this->option('type')
            );

            $this->newLine();

            $this->info('Backup process completed.');

            $this->table(
                ['Backup Destination', 'Status'],
                [
                    ['Local Drive (C:)', $backup->local_status],
                    ['USB Drive (E:)', $backup->usb_status],
                    ['Overall Status', $backup->status],
                ]
            );

            if ($backup->status === 'Success') {
                $this->info('Backup completed successfully.');
            } else {
                $this->warn('Backup completed with some errors.');
            }

            return self::SUCCESS;

        } catch (Throwable $e) {

            $this->error('Backup failed!');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
