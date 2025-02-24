<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\CourierPayment;
use App\Models\CustomerPayment;
use App\Models\User;

class PaymentController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:seller_payment_history'])->only('payment_histories');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $payments = Payment::where('seller_id', Auth::user()->seller->id)->paginate(9);
    //     return view('seller.payment_history', compact('payments'));
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function payment_histories(Request $request)
    {
        $payments = Payment::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.sellers.payment_histories.index', compact('payments'));
    }

    public function courier_payment_histories(Request $request)
    {
        $payments = CourierPayment::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.courier.payment_histories.index', compact('payments'));
    }
    public function customer_payment_histories(Request $request)
    {
        $payments = CustomerPayment::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.refer.payment_histories.index', compact('payments'));
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
        $user = User::find(decrypt($id));
        $payments = Payment::where('seller_id', $user->id)->orderBy('created_at', 'desc')->get();
        if($payments->count() > 0){
            return view('backend.sellers.payment', compact('payments', 'user'));
        }
        flash(translate('No payment history available for this seller'))->warning();
        return back();
    }

    public function courier_show($id)
    {
        $user = User::find(decrypt($id));
        $payments = CourierPayment::where('courier_id', $user->id)->orderBy('created_at', 'desc')->get();
        if($payments->count() > 0){
            return view('backend.courier.payment', compact('payments', 'user'));
        }
        flash(translate('No payment history available for this courier'))->warning();
        return back();
    }
    
    public function customer_show($id)
    {

        $user = User::find(decrypt($id));
        $payments = CustomerPayment::where('customer_id', $user->id)->orderBy('created_at', 'desc')->get();
        if($payments->count() > 0){
            return view('backend.refer.payment', compact('payments', 'user'));
        }
        flash(translate('No payment history available for this customer'))->warning();
        return back();
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
}
