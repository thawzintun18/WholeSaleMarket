<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_id',
        'crop_id',
        'grade_id',
        'quantity',
        'unit',
        'reference_price',
        'actual_buy_price',
        'commission_amount',
        'car_fee_amount',
        'gross_amount',
        'net_amount',
        'note',
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'reference_price' => 'decimal:2',
        'actual_buy_price' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'car_fee_amount' => 'decimal:2',
        'gross_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
    ];
}
