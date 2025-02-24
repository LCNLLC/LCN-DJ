<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourierWithdrawRequest extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function courier()
    {
        return $this->belongsTo(Courier::class);
    }
}
