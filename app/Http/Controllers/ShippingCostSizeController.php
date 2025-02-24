<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\ShippingCostSize;
use App\Models\CityTranslation;
use App\Models\State;

class ShippingCostSizeController extends Controller
{
    public function __construct() {
        // Staff Permission Check
     $this->middleware(['permission:shipping_cost_size'])->only('index','create','destroy');
 }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_city = $request->sort_city;
        $sort_state = $request->sort_state;
        $cities_queries = ShippingCostSize::query();
        if($request->sort_city) {
         $cities_queries->where('city_id', $request->sort_city);
     }
     if($request->sort_state) {
        $cities_queries->where('state_id', $request->sort_state);
    }
    $shipping_details = $cities_queries->orderBy('status', 'desc')->paginate(15);

    $cities = City::where('status', 1)->get();
    $states = State::where('status', 1)->get();

    return view('backend.setup_configurations.shipping_size_cost.index', compact('cities', 'states', 'sort_city', 'sort_state','shipping_details'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $sort_city = $request->sort_city;
        $sort_state = $request->sort_state;
        $cities_queries = ShippingCostSize::query();
        if($request->sort_city) {
         $cities_queries->where('city_id', $request->sort_city);
     }
     if($request->sort_state) {
        $cities_queries->where('state_id', $request->sort_state);
    }
    $shipping_details = $cities_queries->orderBy('status', 'desc')->paginate(15);

    $cities = City::where('status', 1)->get();
    $states = State::where('status', 1)->get();
    return view('backend.setup_configurations.shipping_size_cost.create', compact('cities', 'states', 'sort_city', 'sort_state','shipping_details'));
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $Shipping_cost_size = new ShippingCostSize;

        $Shipping_cost_size->from_state_id = $request->from_state_id;
        $Shipping_cost_size->from_city_id = $request->from_city_id;
        $Shipping_cost_size->to_state_id = $request->to_state_id;
        $Shipping_cost_size->to_city_id = $request->to_city_id;
        $Shipping_cost_size->cost = $request->cost;
        $Shipping_cost_size->size = $request->size;


        $Shipping_cost_size->save();

        flash(translate('Record has been inserted successfully'))->success();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
       $lang  = $request->lang;
       $Shipping_cost_size  = ShippingCostSize::findOrFail($id);
       $states = State::where('status', 1)->get();
       $cities = City::where('status', 1)->get();
       return view('backend.setup_configurations.shipping_size_cost.edit', compact('cities', 'lang', 'states','Shipping_cost_size'));
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
        $shipping_cost_size = ShippingCostSize::findOrFail($id);

        $shipping_cost_size->from_state_id = $request->from_state_id;
        $shipping_cost_size->from_city_id = $request->from_city_id;
        $shipping_cost_size->to_state_id = $request->to_state_id;
        $shipping_cost_size->to_city_id = $request->to_city_id;
        $shipping_cost_size->size = $request->size;
        $shipping_cost_size->cost = $request->cost;

        $shipping_cost_size->save();

        flash(translate('Record has been updated successfully'))->success();
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
        $ShippingCostSize = ShippingCostSize::findOrFail($id);
        ShippingCostSize::destroy($id);

        flash(translate('Record has been deleted successfully'))->success();
        return redirect()->route('shipping_cost_size.index');
    }

    public function updateStatus(Request $request){
        $Shipping_cost_size = ShippingCostSize::findOrFail($request->id);
        $Shipping_cost_size->status = $request->status;
        $Shipping_cost_size->save();

        return 1;
    }
    public function showShippingDetailsToseller(Request $request)
    {
        $sort_city = $request->sort_city;
        $sort_state = $request->sort_state;
        $cities_queries = ShippingCostSize::query();
        if($request->sort_city) {
            $cities_queries->where('city_id', $request->sort_city);
         }
        if($request->sort_state) {
           $cities_queries->where('state_id', $request->sort_state);
        }
           $shipping_details = $cities_queries->orderBy('status', 'desc')->paginate(15);

           $cities = City::where('status', 1)->get();
           $states = State::where('status', 1)->get();

           return view('seller.shipping_cost_size.index', compact('cities', 'states', 'sort_city', 'sort_state','shipping_details'));
    }

    public function showShippingDetailsToCourier(Request $request)
    {
        $sort_city = $request->sort_city;
        $sort_state = $request->sort_state;
        $cities_queries = ShippingCostSize::query();
        if($request->sort_city) {
            $cities_queries->where('city_id', $request->sort_city);
         }
        if($request->sort_state) {
           $cities_queries->where('state_id', $request->sort_state);
        }
           $shipping_details = $cities_queries->orderBy('status', 'desc')->paginate(15);

           $cities = City::where('status', 1)->get();
           $states = State::where('status', 1)->get();

           return view('courier.shipping_cost_size.index', compact('cities', 'states', 'sort_city', 'sort_state','shipping_details'));
    }
}







