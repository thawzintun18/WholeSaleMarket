<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAveragePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_date',
        'crop_id',
        'total_gross_amount',
        'quantity_on_hand',
        'average_cost',
        'average_basket_price',
    ];

}
