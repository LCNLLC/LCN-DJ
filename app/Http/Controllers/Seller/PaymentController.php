<?php

namespace App\Http\Controllers\Seller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Package;
use App\Models\BoostingPlanPayment;
use App\Models\Product;
use App\Models\TaxValue;
use Auth;
use DB;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Exception\CardException;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::where('seller_id', Auth::user()->id)->paginate(9);
        return view('seller.payment_history', compact('payments'));
    }
    public function PaymentMethod(Request $request)
    {
     
        $package = Package::where('status',1)->where('id',$request->plan_id)->first();
        if($request->plan_type == 'click')
        {
            $package->price  = ($package->price * $request->quantity);
            Session::put('quantity', $request->input('quantity'));
        }
        $stripe_public_key = env('STRIPE_KEY');
        return view('seller.boost_plan.payment',compact('stripe_public_key','package','request'));
    }
    public function stripe(Request $request)
    {
        
        $stripe_secret_key = env('STRIPE_SECRET');
        Stripe::setApiKey($stripe_secret_key);
        $package = Package::where('status',1)->where('id',$request->plan_id)->first();
        $tax = TaxValue::where('product_type', 3)->latest()->first();
        $taxAmount = $tax ? $tax->tax_value : 0;
        $taxType = $tax ? $tax->tax_type : null;

        $basePrice = $package->price;

        if ($taxType === 'amount') 
        {
            $taxValue = $taxAmount;
        } 
        elseif ($taxType === 'percent') 
        {
            $taxValue = ($basePrice * $taxAmount) / 100;
        } 
        else 
        {
            $taxValue = 0; 
        }

        $package->price_with_tax = $basePrice + $taxValue;
        if (Session::has('quantity')) {
            $quantity = Session::get('quantity');
            $package->price  = ($package->price * $quantity );
        }
        try {
            // Call a Stripe API method that may throw an error
            $charge = Charge::create([
                'amount' =>round($package->price_with_tax,0),
                'currency' => $package->currency->code,
                'source' => $request->stripeToken,
                'description' => 'Buy Product Boosting Plan'
            ]);

            $Boosting_plan_payment = new BoostingPlanPayment([
                'payment_id' => $charge->id,
                'seller_id' => auth()->user()->id,
                'product_id' => $request->product_id,
                'plan_id' => $package->id,
                'payment_method' =>'Stripe',
                'amount' => $request->ammount,
                'tax' => $taxValue,
                'status' =>1,
            ]);

         

            if($Boosting_plan_payment->save()){
                $product = Product::findOrFail($request->product_id);
                $product->boosting_status   = 1;
                if($package->plan_type=='days')
                {
                    $currentDate = date('Y-m-d'); 
                    $futureDate = date('Y-m-d', strtotime($currentDate . ' +'.$package->promo_duration.' days'));
                    $product->boosting_plan_end = $futureDate;
                    $product->boosting_type     = 'days';
                }
                else
                {
                    $product->boosting_click = $quantity;
                    $product->boosting_type  = 'click';
                    Session::forget('quantity');
                }
          
                $product->save();
            }
            flash(translate("You're product added in boosting plan successfully"))->success();

            return redirect()->route('seller.products.boost');

        } catch (CardException $e) {
            flash($e->getMessage())->error();
            return redirect()->route('seller.products');

        } catch (\Stripe\Exception\StripeException $e) {
            flash($e->getMessage())->error();
            return redirect()->route('seller.products');
        }

    }

    public function payment_success()
    {
        return view('Employee.payment-success');
    }
}
