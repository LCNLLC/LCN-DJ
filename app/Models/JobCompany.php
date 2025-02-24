<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobCompany extends Model
{
    use HasFactory;
    protected $table = 'jb_companies';

    protected $fillable = [
        'name',
        'status',
        'account_id',
        'address',
        'email',
        'phone',
        'year_founded',
        'number_of_offices',
        'number_of_employees',
        'annual_revenue',
        'description',
        'content',
        'website',
        'logo',
        'latitude',
        'longitude',
        'postal_code',
        'cover_image',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'ceo',
        'is_featured',
        'country_id',
        'state_id',
        'city_id',
    ];

     public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
