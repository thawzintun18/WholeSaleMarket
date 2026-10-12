<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyPrice extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'price_date',
        'crop_id',
        'grade_id',
        'reference_buy_price',
        // 'reference_sell_price',
    ];

}
