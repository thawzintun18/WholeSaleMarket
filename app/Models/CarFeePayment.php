<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarFeePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'car_no',
        'driver_name',
        'total_amount',
        'payment_date',
    ];

}
