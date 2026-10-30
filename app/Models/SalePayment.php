<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalePayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'payment_date',
        'amount',
        'payment_type',
        'note',
    ];

}
