<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FarmerDebt extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farmer_id',
        'debt_type',
        'debt_date',
        'amount',
        'settled_amount',
        'balance_amount',
        'status',
    ];

}
