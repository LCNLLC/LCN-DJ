<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AffiliateController;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\OrderDetail;
use App\Models\CouponUsage;
use App\Models\Coupon;
use App\Models\User;
use App\Models\CombinedOrder;
use App\Models\SmsTemplate;
use App\Models\Currency;
use App\Models\Package;
use Auth;
use Mail;
use App\Mail\InvoiceEmailManager;
use App\Utility\NotificationUtility;
use CoreComponentRepository;
use App\Utility\SmsUtility;
use Illuminate\Support\Facades\Route;

class PackageController extends Controller
{

	public function __construct()
	{
        // Staff Permission Check
		$this->middleware(['permission:create_package'])->only('create');
		$this->middleware(['permission:view_all_package'])->only('show');
        // $this->middleware(['permission:delete_order'])->only('destroy','bulk_order_delete');
	}

    // All Orders
	public function all_orders(Request $request)
	{

	}

	public function show(Request $request)
	{
		$sort_search = null;
        $packages = Package::orderBy('created_at', 'desc');
        
        if ($request->search != null){
            $packages = $packages->where('title', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $packages = $packages->paginate(10);

        return view('backend.package.index', compact('packages','sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$currencies = Currency::where('status', 1)->get();
    	return view('backend.package.create',compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_type' => 'required',
        ]);
        if($request->plan_type =='days')
        {
        	$request->validate([
        		'name' => 'required',
        		'short_name' => 'required',
        		'price' => 'required|numeric|min:80',
        		'currency_id' => 'required',
        		'promo_duration' => 'required|integer',
        		'status' => 'boolean',
        	]);

            $package = new Package([
                'name' => $request->input('name'),
                'short_name' => $request->input('short_name'),
                'plan_type' => $request->input('plan_type'),
                'price' => $request->input('price'),
                'currency_id' => $request->input('currency_id'),
                'promo_duration' => $request->input('promo_duration'),
                'minimum_click_buy' =>'',
                'status' => $request->input('status', false), 
            ]);
        }
        else
        {
            $request->validate([
                'name' => 'required',
                'short_name' => 'required',
                'price' => 'required|numeric',
                'currency_id' => 'required',
                'minimum_click_buy' => 'required|integer',
                'status' => 'boolean',
            ]);

            $package = new Package([
                'name' => $request->input('name'),
                'short_name' => $request->input('short_name'),
                'plan_type' => $request->input('plan_type'),
                'price' => $request->input('price'),
                'currency_id' => $request->input('currency_id'),
                'promo_duration' => '',
                'minimum_click_buy' => $request->input('minimum_click_buy'),
                'status' => $request->input('status', false), 
            ]);
        }

        $package->save();
        flash(translate('The package added successfully'))->success();
        return redirect()->route('package.create');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $package = Package::find($id);
        $currencies = Currency::where('status', 1)->get();
        return view('backend.package.edit', compact('package','currencies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'plan_type' => 'required',
        ]);
        if($request->plan_type =='days')
        {
            $request->validate([
                'name' => 'required',
                'short_name' => 'required',
                'price' => 'required|numeric|min:80',
                'currency_id' => 'required',
                'promo_duration' => 'required|integer',
                'status' => 'boolean',
            ]);

            $package = Package::find($id);

            $package->name = $request->name;
            $package->short_name = $request->short_name;
            $package->plan_type = $request->plan_type;
            $package->minimum_click_buy = '';
            $package->promo_duration = $request->promo_duration;
            $package->price = $request->price;
            $package->currency_id = $request->currency_id;
            $package->status = $request->status;

        }
        else
        {
            $request->validate([
                'name' => 'required',
                'short_name' => 'required',
                'price' => 'required|numeric',
                'currency_id' => 'required',
                'minimum_click_buy' => 'required|integer',
                'status' => 'boolean',
            ]);

            $package = Package::find($id);

            $package->name = $request->name;
            $package->short_name = $request->short_name;
            $package->plan_type = $request->plan_type;
            $package->minimum_click_buy = $request->minimum_click_buy;
            $package->promo_duration = '';
            $package->price = $request->price;
            $package->currency_id = $request->currency_id;
            $package->status = $request->status;

        }

        $package->save();

        flash(translate('Package has been updated successfully'))->success();
        return redirect()->route('package.show');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function change_status(Request $request) {
        $package = Package::find($request->id);
        $package->status = $request->status;
        
        $package->save();
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Package::find($id)->delete();
        flash(translate('The package deleted successfully'))->success();
        return  redirect()->route('package.show');
    }



}

