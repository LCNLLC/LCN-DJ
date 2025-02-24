<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourierProfileRequest;
use App\Models\User;
use App\Models\Language;
use Auth;
use Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $langs = Language::where('status',1)->get();
        $addresses = $user->addresses; 
        return view('courier.profile.index', compact('user','addresses','langs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CourierProfileRequest $request , $id)
    {
        if(env('DEMO_MODE') == 'On'){
            flash(translate('Sorry! the action is not permitted in demo '))->error();
            return back();
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->language = $request->language;

        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }
        
        $user->avatar_original = $request->photo;

        $courier = $user->courier;

        if($courier){
            $courier->cash_on_delivery_status = $request->cash_on_delivery_status;
            $courier->bank_payment_status = $request->bank_payment_status;
            $courier->bank_name = $request->bank_name;
            $courier->bank_acc_name = $request->bank_acc_name;
            $courier->bank_acc_no = $request->bank_acc_no;
            $courier->bank_routing_no = $request->bank_routing_no;

            $courier->save();
        }

        $user->save();

        flash(translate('Your Profile has been updated successfully!'))->success();
        return back();
    }
}

