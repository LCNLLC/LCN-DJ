<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Shop;
use Auth;
use Hash;

class ProfileController extends Controller
{
    public function __construct() {
      $this->middleware(['permission:profile.index'])->only('index','create','edit');
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $id =  Auth::user()->id;
        // $address_data = Address::where('user_id', $id)->get();
        $shop = Auth::user()->shop;
       //  return     $shop;
       // //return     $address_data;
        return view('backend.admin_profile.index',compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // if(env('DEMO_MODE') == 'On'){
        //     flash(translate('Sorry! the action is not permitted in demo '))->error();
        //     return back();
        // }
      //  return $request;
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->new_password != null && ($request->new_password == $request->confirm_password)){
            $user->password = Hash::make($request->new_password);
        }
        $user->avatar_original = $request->avatar;
        if($user->save()){

            try {
                $address = Shop::where('user_id', $id)->firstOrFail();
                $address->country_id = $request->country_id;
                $address->state_id = $request->state_id;
                $address->city_id = $request->city_id;
                $address->save();
            } catch (ModelNotFoundException $e) {

                $address = new Shop();
                $address->user_id = $id;
                $address->country_id = $request->country_id;
                $address->state_id = $request->state_id;
                $address->city_id = $request->city_id;
                $address->save();
            }


            flash(translate('Your Profile has been updated successfully!'))->success();
            return back();
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
