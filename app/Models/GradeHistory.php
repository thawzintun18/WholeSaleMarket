<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeHistory extends Model
{
    protected $fillable = [
        'grade_id',
        'crop_id',
        'old_grade_name',
        'new_grade_name',
        'changed_at',
    ];

}
