<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\AuctionBidMailManager;
use App\Models\AuctionProductBid;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;
use Mail;

class AuctionProductBidController extends Controller
{
    public function store(Request $request)
    {


        $product = Product::findOrFail($request->product_id);
        if($request->amount >= $product->auction_success_price)
        {
            $product->auction_product_status = 2;
            $product->save();
        }


        $bid = AuctionProductBid::where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->first();
        if ($bid == null) {
            $bid =  new AuctionProductBid;
            $bid->user_id = Auth::user()->id;
        }


        $bid->product_id = $request->product_id;
        $bid->amount = $request->amount;
        if ($bid->save()) {
        	flash(translate('Bid Placed Successfully.'))->success();

        } else {
            flash(translate('Something Went Wrong'))->error();
        }

        if($request->amount >= $product->auction_success_price)
        {
            $bid->status = 2;
            $user_id = Auth::user()->id;
            BidStatusNotification($user_id , 'Accepted' ,$product->name);
        }

        return back();
    }
    public function bid_destroy_admin($id)
    {
        $bid = AuctionProductBid::find($id);

        if ($bid) {
            $bid->delete();

            flash(translate('Bid deleted successfully'))->success();
            return redirect()->back();
        } else {
            flash(translate('Bid not found'))->error();
            return redirect()->back();
        }
    }


}

