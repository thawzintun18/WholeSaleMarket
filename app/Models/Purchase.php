<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_no',
        'farmer_id',
        'purchase_date',
        'status',
        'total_amount',
        'total_deduction',
        'net_amount',
        'total_paid',
        'balance_amount',
        'note',
    ];

    protected $casts = [
        'purchase_date' => 'datetime',
        'total_amount' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];
}
