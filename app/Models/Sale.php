<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_no',
        'customer_id',
        'sale_date',
        'total_amount',
        'total_deduction',
        'net_amount',
        'total_paid',
        'balance_amount',
        'note',
    ];

}
