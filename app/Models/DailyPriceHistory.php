<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyPriceHistory extends Model
{
    protected $fillable = [
        'daily_price_id',
        'crop_id',
        'grade_id',
        'price_date',
        'old_reference_buy_price',
        'new_reference_buy_price',
        'changed_at',
    ];

}
