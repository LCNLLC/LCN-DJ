<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StripeController extends Controller
{

   	public function stripe(Request $request)
    {

        $data['payment_type'] = $request->payment_type;
        $data['combined_order_id'] = $request->combined_order_id;
        $data['amount'] = $request->amount;
        $data['user_id'] = $request->user_id;
        $data['package_id'] = 0;

        if(isset($request->package_id)) {
            $data['package_id'] = $request->package_id;
        }

        return view('frontend.payment.stripe_app', $data);
    }
}
