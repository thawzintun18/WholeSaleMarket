<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
   use HasFactory, SoftDeletes;

    protected $table = 'stock_movements';

    protected $fillable = [
        'crop_id',
        'movement_date',
        'quantity_in',
        'quantity_out',
        'unit',
        'unit_cost',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'movement_date' => 'datetime',
        'quantity_in' => 'decimal:3',
        'quantity_out' => 'decimal:3',
        'unit_cost' => 'decimal:2',
    ];
}
