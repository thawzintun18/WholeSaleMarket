<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmerAdvance extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'farmer_id',
        'advance_date',
        'amount',
    ];

    protected $casts = [
        'advance_date' => 'datetime',
        'amount' => 'decimal:2',
    ];
}
