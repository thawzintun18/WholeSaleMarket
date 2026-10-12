<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'farmer_code',
        'old_name',
        'old_phone',
        'old_village',
        'old_notes',
        'new_name',
        'new_phone',
        'new_village',
        'new_notes',
        'changed_at',
    ];
}
