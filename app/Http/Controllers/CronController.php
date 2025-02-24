<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Product;
use App\Models\Job;
use App\Models\Shop;
use Carbon\Carbon;
use Artisan;
use DB;
use Cache;


class CronController extends Controller
{

    // Delete old Support Tickets
    public function delete_old_support_tickets(){
        $old_date=date('Y-m-d', strtotime("-30 days"));
        $tickets = Ticket::where('status', 'solved')->where('updated_at', '<', $old_date)->delete();
        echo "Old Tickets Deleted"; 


    }
    public function daily()
    {
    	//Delete old tickets
    	$thresholdDate = Carbon::now()->subDays(30);
        DB::table('ticket_replies')
        ->join('tickets', 'ticket_replies.ticket_id', '=', 'tickets.id')
        ->where('tickets.status', '=', 'solved')
        ->where('ticket_replies.created_at', '<', $thresholdDate)
        ->delete();
        //inactive plan 

        $products = Product::where('boosting_status',1)->where('boosting_type','days')->where('boosting_plan_end', '<', now())->get();
        foreach ($products as $product) {
            $product->update(['boosting_status' =>2]);
        }
    }

    public function all_products(Request $request)
    {
        $col_name = null;
        $query = null;
        $seller_id = null;
        $sort_search = null;
        $products = Product::orderBy('created_at', 'desc')->where('auction_product', 0)->where('wholesale_product', 0);
        if ($request->has('user_id') && $request->user_id != null) {
            $products = $products->where('user_id', $request->user_id);
            $seller_id = $request->user_id;
        }
        if ($request->search != null) {
            $sort_search = $request->search;
            $products = $products
            ->where('name', 'like', '%' . $sort_search . '%')
            ->orWhereHas('stocks', function ($q) use ($sort_search) {
                $q->where('sku', 'like', '%' . $sort_search . '%');
            });
        }
        if ($request->type != null) {
            $var = explode(",", $request->type);
            $col_name = $var[0];
            $query = $var[1];
            $products = $products->orderBy($col_name, $query);
            $sort_type = $request->type;
        }

        $products = $products->paginate(15);
        $type = 'All';

        return view('backend.product.products.low_stock_notification', compact('products', 'type', 'col_name', 'query', 'seller_id', 'sort_search'));
    }

    public function products_adversment()
    {
        $products = Product::orderBy('created_at', 'desc')
        ->whereDate('created_at', today())
        ->get(); 
        foreach ($products as $product) 
        {
            $productName = $product->name;
            $productDescription = $product->description;
            $productPrice = home_discounted_base_price($product);
            subscribeNotification($productName, $productDescription, $productPrice);
        }
    } 

    public function jobs_adversment()
    {

        $jobs = Job::orderBy('created_at', 'desc')
        ->whereDate('created_at', today())
        ->get(); 
        foreach ($jobs as $job) 
        {
            $jobId   = $job->id;
            $jobName = $job->name;
            $jobDescription = $job->description;
            jobNotification($jobName, $jobDescription, $jobId);
        }
    } 

    public function auction_end_notification()
    {

        $currentDateTime1 = Carbon::now();
        $currentDateTime2 = $currentDateTime1->timestamp;

        $date = date('Y-m-d H:i:s', $currentDateTime2);

        // return $date;
        //  return date('Y-m-d H:i:s', 1706637708 );
        //  dd($currentDateTime->timestamp);

        $products = Product::where('auction_end_date', '>', $currentDateTime2) 
        ->where('auction_end_date', '<', $currentDateTime1->addMinutes(10)->timestamp)
        ->get();
        
        foreach ($products as $product) 
        {
            auctionEndNotification($product->id);
        }

    }
    public function approvedSeller(Request $request)
    {
        $users = DB::table('users')
        ->join('shops', 'users.id', '=', 'shops.user_id')
        ->where('users.user_type', 'seller')
        ->where('users.banned',  '=', 0)
        ->where('shops.verification_status', '=', 0)
        ->select('users.*','shops.id as shop_id')
        ->get();
  
        foreach ($users as $user) 
        {
            $shop = Shop::findOrFail($user->shop_id);
            $shop->verification_status = 1;
            if ($shop->save()) {
               SellerAccountStatusNotification($user->id,1);
                Cache::forget('verified_sellers_id');
                return 1;
            }
            return 0;
       }
    }  
}
