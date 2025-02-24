<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRegisterReferralHistory extends Model
{
    use HasFactory;

    protected $table='user_register_referrel_history';

    protected $fillable = [
         'user_ref_by', 'user_id',  'amount'
    ];
}
