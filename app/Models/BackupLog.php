<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'backup_type',

        'file_name',
        'file_size',

        'local_path',
        'local_status',

        'usb_path',
        'usb_status',

        'cloud_file_id',
        'cloud_status',

        'status',

        'error_message',

        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'file_size'    => 'integer',

        'started_at'   => 'datetime',
        'completed_at' => 'datetime',
    ];

}
