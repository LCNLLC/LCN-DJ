<?php

namespace App\Http\Controllers\Courier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourierWithdrawRequest;
use Auth;

class CourierWithdrawRequestController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courier_withdraw_requests = CourierWithdrawRequest::where('user_id', Auth::user()->id)->latest()->paginate(9);
        return view('courier.money_withdraw_requests.index', compact('courier_withdraw_requests'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $courier_withdraw_request = new CourierWithdrawRequest;
        $courier_withdraw_request->user_id = Auth::user()->id;
        $courier_withdraw_request->amount = $request->amount;
        $courier_withdraw_request->message = $request->message;
        $courier_withdraw_request->status = '0';
        $courier_withdraw_request->viewed = '0';
        if ($courier_withdraw_request->save()) {
            flash(translate('Request has been sent successfully'))->success();
            return redirect()->route('courier.money_withdraw_requests.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }
}
