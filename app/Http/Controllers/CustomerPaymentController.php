<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CustomerWithdrawRequest;
use Illuminate\Http\Request;
use App\Models\CustomerPayment;
use App\Models\User;
use App\Models\Customer;
use Auth;
class CustomerPaymentController extends Controller
{
    public function index()
    {
    	$customer_withdraw_requests = CustomerWithdrawRequest::where('user_id', Auth::user()->id)->latest()->paginate(9);
        return view('frontend.user.money_withdraw_requests.index', compact('customer_withdraw_requests'));
    }
    public function store(Request $request)
    {
        $user_withdraw_request = new CustomerWithdrawRequest;
        $user_withdraw_request->user_id = Auth::user()->id;
        $user_withdraw_request->amount = $request->amount;
        $user_withdraw_request->message = $request->message;
        $user_withdraw_request->status = '0';
        $user_withdraw_request->viewed = '0';
        if ($user_withdraw_request->save()) {
            flash(translate('Request has been sent successfully'))->success();
            return redirect()->route('user.money_withdraw_requests.index');
        }
        else{
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function payment_history()
    {
        $payments = CustomerPayment::where('customer_id', Auth::user()->id)->paginate(9);
        return view('frontend.user.payment_history', compact('payments'));
    }

    public function admin_payment_history()
    {
        $payments = CustomerPayment::latest()->paginate(9);
        return view('frontend.user.payment_history', compact('payments'));
    }
    public function withdraw_request()
    {
        $customer_withdraw_requests = CustomerWithdrawRequest::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.refer.refer_withdraw_requests.index', compact('customer_withdraw_requests'));
    }
    public function payment_modal(Request $request)
    {
        $user = User::findOrFail($request->id);
        $customer_withdraw_request = CustomerWithdrawRequest::where('id', $request->customer_withdraw_request_id)->first();
        return view('backend.refer.refer_withdraw_requests.payment_modal', compact('user','customer_withdraw_request'));
    }

    public function message_modal(Request $request)
    {
        $customer_withdraw_request = CustomerWithdrawRequest::findOrFail($request->id);
        if (Auth::user()->user_type == 'courier') {
            return view('frontend.partials.withdraw_message_modal', compact('customer_withdraw_request'));
        }
        elseif (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff') {
            return view('backend.refer.refer_withdraw_requests.withdraw_message_modal', compact('customer_withdraw_request'));
        }
    }

}
