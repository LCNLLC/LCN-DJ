<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\ShippingCostSize;
use App\Models\CityTranslation;
use App\Models\State;
use App\Models\DiscountDay;
use Auth;

class DiscountController extends Controller
{
    public function __construct() {
        // Staff Permission Check
      // $this->middleware(['permission:discount_day'])->only('index','create','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      
        $discount_days = DiscountDay::where('user_id', auth()->user()->id)->paginate(15);

        return view('seller.discount_day.index', compact('discount_days'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $discount_day = new DiscountDay;

        $discount_day->user_id = auth()->user()->id;
        $discount_day->name = $request->name;
        $discount_day->discount_date = $request->discount_date;
        $discount_day->discount = $request->discount;

        $discount_day->save();

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
         $discount_day  = DiscountDay::findOrFail($id);
         return view('seller.discount_day.edit', compact('lang','discount_day'));
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
        $discount_day = DiscountDay::findOrFail($id);
        $discount_day->name = $request->name;
        $discount_day->discount_date = $request->discount_date;
        $discount_day->discount = $request->discount;

        $discount_day->save();

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
        $ShippingCostSize = DiscountDay::findOrFail($id);
        DiscountDay::destroy($id);

        flash(translate('Record has been deleted successfully'))->success();
        return redirect()->route('seller.discount_day.index');
    }

    public function updateStatus(Request $request){
        $discount_day = DiscountDay::findOrFail($request->id);
        $discount_day->status = $request->status;
        $discount_day->save();

        return 1;
    }
}

?>