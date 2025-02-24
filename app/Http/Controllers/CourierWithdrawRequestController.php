<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CourierWithdrawRequest;
use App\Models\User;
use Auth;

class CourierWithdrawRequestController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_courier_payout_requests'])->only('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $courier_withdraw_requests = CourierWithdrawRequest::latest()->paginate(15);
        return view('backend.courier.courier_withdraw_requests.index', compact('courier_withdraw_requests'));
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
        $seller_withdraw_request = new CourierWithdrawRequest;
        $seller_withdraw_request->user_id = Auth::user()->courier->id;
        $seller_withdraw_request->amount = $request->amount;
        $seller_withdraw_request->message = $request->message;
        $seller_withdraw_request->status = '0';
        $seller_withdraw_request->viewed = '0';
        if ($seller_withdraw_request->save()) {
            flash(translate('Request has been sent successfully'))->success();
            return redirect()->route('courier_withdraw_requests.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
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
        //
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

    public function payment_modal(Request $request)
    {
        $user = User::findOrFail($request->id);
        $courier_withdraw_request = CourierWithdrawRequest::where('id', $request->courier_withdraw_request_id)->first();
        return view('backend.courier.courier_withdraw_requests.payment_modal', compact('user','courier_withdraw_request'));
    }

    public function message_modal(Request $request)
    {
        $courier_withdraw_request = CourierWithdrawRequest::findOrFail($request->id);
        if (Auth::user()->user_type == 'courier') {
            return view('frontend.partials.withdraw_message_modal', compact('courier_withdraw_request'));
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.courier.courier_withdraw_requests.withdraw_message_modal', compact('courier_withdraw_request'));
        }
    }
}
