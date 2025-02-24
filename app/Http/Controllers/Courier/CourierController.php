<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Courier;
use Auth;

class CourierController extends Controller
{
     public function index()
    {
        $courier = Auth::user()->courier;
        return view('courier.vehicle', compact('courier'));
    }

    public function update(Request $request)
    {
        $courier = Courier::find($request->courier_id);

        if ($request->has('name') && $request->has('address')) {
          
            $courier->name             = $request->name;
            $courier->national_identity_card             = $request->national_identity_card;
            $courier->phone            = $request->phone;
            $courier->country_id       = $request->country_id;
            $courier->state_id         = $request->state_id;
            $courier->city_id          = $request->city_id;
            $courier->address          = $request->address;
            $courier->phone            = $request->phone;
            $courier->slug             = preg_replace('/\s+/', '-', $request->name) . '-' . $courier->id;

        }

       elseif (
            $request->has('vehicle_type') ||
            $request->has('license_plate_number') ||
            $request->has('twitter') ||
            $request->has('youtube') ||
            $request->has('instagram')
        ) {
            $courier->vehicle_type             = $request->vehicle_type;
            $courier->license_plate_number             = $request->license_plate_number;
            $courier->years_of_experience            = $request->years_of_experience;
            $courier->engine_power       = $request->engine_power;
            $courier->wallet_number         = $request->wallet_number;
            $courier->delivery_rang          = $request->delivery_rang;
            $courier->driving_license          = $request->driving_license;
            $courier->vehicle_registration            = $request->vehicle_registration;
            $courier->vehicle_insurance            = $request->vehicle_insurance;
        } 

        if ($courier->save()) {
            flash(translate('Your Courier  has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

}
