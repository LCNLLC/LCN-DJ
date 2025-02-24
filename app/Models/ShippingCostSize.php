<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App;


class ShippingCostSize extends Model
{
    use HasFactory;
	protected $table = 'shipping_cost_sizes';

    public function city_translations()
    {
       return $this->hasMany(CityTranslation::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function to_state()
    {
        return $this->belongsTo(State::class, 'to_state_id'); 
    }

    public function to_city()
    {
        return $this->belongsTo(City::class, 'to_city_id'); 
    }
        public function from_state()
    {
        return $this->belongsTo(State::class, 'from_state_id');
    }

    public function from_city()
    {
        return $this->belongsTo(City::class, 'from_city_id');
    }
}

?>