<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupRestoreLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_backup_id',
        'safety_backup_id',
        'backup_path',
        'status',
        'error_message',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function sourceBackup()
    {
        return $this->belongsTo(BackupLog::class, 'source_backup_id');
    }

    public function safetyBackup()
    {
        return $this->belongsTo(BackupLog::class, 'safety_backup_id');
    }

}
