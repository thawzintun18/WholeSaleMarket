<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'crop_id',
        'quantity',
        'bag_count',
        'avg_price',
        'sale_price',
        'gross_amount',
        'remainder_amount',
        'note',
    ];

}
