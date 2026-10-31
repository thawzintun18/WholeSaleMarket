<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'old_name',
        'old_phone',
        'old_address',
        'old_notes',
        'new_name',
        'new_phone',
        'new_address',
        'new_notes',
        'changed_at',
    ];

}
