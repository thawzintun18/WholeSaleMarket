<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBalance extends Model
{
    use HasFactory;

    protected $table = 'stock_balances';

    protected $fillable = [
        'crop_id',
        'quantity_on_hand',
        'average_cost',
    ];

    protected $casts = [
        'quantity_on_hand' => 'decimal:3',
        'average_cost' => 'decimal:2',
    ];
}
