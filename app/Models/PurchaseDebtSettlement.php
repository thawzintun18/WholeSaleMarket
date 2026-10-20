<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDebtSettlement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'farmer_debt_id',
        'amount',
        'settlement_date',
    ];

}
