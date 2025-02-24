<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Conversation extends Model
{
    public function messages(){
        if (Auth::check() && isAdmin()) {
            return $this->hasMany(Message::class);
        } else {
            return $this->hasMany(Message::class)->where('deleted', 'No');
        }
    }

    public function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(){
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
