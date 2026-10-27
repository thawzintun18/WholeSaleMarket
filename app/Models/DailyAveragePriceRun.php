<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyAveragePriceRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_date',
        'calculated_at',
    ];

}
