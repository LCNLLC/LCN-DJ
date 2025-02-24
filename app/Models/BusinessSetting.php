<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    protected $fillable = [
            'type',
            'value',
            'lang'
        ];

        protected $casts = [
            'created_at' => 'datetime',
            'updated_at' => 'datetime'
        ];

}
