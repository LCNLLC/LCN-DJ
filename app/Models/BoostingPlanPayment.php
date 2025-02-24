<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoostingPlanPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'seller_id', 'product_id', 'plan_id', 'payment_id', 'payment_method','amount','tax', 'status'
    ];
}
