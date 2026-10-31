<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyStockBalanceRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'balance_date',
        'calculated_at',
    ];
}
