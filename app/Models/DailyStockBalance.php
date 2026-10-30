<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStockBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'balance_date',
        'daily_balance',
        'total_balance',
    ];
}
