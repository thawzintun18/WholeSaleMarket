<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Crop extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'client_uuid',
        'crop_name',
        'commission_amount',
        'unit',
        'quantity_per_basket',
    ];
}
