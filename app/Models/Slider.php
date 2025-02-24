<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'image',
        'position',
        'status',
        'link',
        'start_date',
        'end_date',


    ];
}
