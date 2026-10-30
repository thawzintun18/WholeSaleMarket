<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarFeePaymentItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'car_fee_payment_id',
        'purchase_id',
    ];
}
