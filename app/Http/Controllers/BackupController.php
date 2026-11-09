<?php
namespace App\Http\Controllers;

use App\Models\BackupLog;
use App\Services\BackupService;
use Illuminate\Http\Request;

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

}
