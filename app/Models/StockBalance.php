<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    use HasFactory;
    protected $fillable = [
        'crop_id',
        'quantity_on_hand',
        'average_cost',
    ];

    protected $casts = [
        'crop_id' => 'integer',
        'quantity_on_hand' => 'decimal:2',
        'average_cost' => 'decimal:2',
        'updated_at' => 'datetime',
    ];
}
