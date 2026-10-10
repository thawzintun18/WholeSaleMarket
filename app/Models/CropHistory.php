<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CropHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'crop_id',
        'old_crop_name',
        'old_commission_amount',
        'old_unit',
        'old_quantity_per_basket',
        'new_crop_name',
        'new_commission_amount',
        'new_unit',
        'new_quantity_per_basket',
        'changed_at',
    ];
}
