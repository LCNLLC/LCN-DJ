<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\CityTranslation;
use App\Models\State;
use App\Models\DiscountDay;
use Auth;

class DiscountController extends Controller
{
    public function __construct() {
        // Staff Permission Check
       $this->middleware(['permission:discount_day'])->only('index','create','destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Initialize query with user-specific discount days
        $query = DiscountDay::where('user_id', auth()->user()->id);

        // Apply filters based on request parameters
        if ($request->filled('date')) {
            $query->whereDate('discount_date', $request->date);
        }
        if ($request->filled('discount')) {
            $query->where('discount', $request->discount);
        }

        // Paginate the filtered results
        $discount_days = $query->paginate(6);

        // Return the view with the filtered data
        return view('backend.setup_configurations.discount_day.index', compact('discount_days'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'discount_date' => 'required|date',
            'discount' => 'required|numeric|min:1',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);
        $discount_day = new DiscountDay;

        $discount_day->user_id = auth()->user()->id;
        $discount_day->name = $request->name;
        $discount_day->discount_date = $request->discount_date;
        $discount_day->discount = $request->discount;
        $discount_day->start_time = $request->start_time;
        $discount_day->end_time = $request->end_time;

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
         return view('backend.setup_configurations.discount_day.edit', compact('lang','discount_day'));
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
        // Validate the form data
        $request->validate([
            'name' => 'required|string|max:255',
            'discount_date' => 'required|date',
            'discount' => 'required|numeric|min:1|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time', // Ensure end time is after start time
        ]);

        // Retrieve and update the record
        $discount_day = DiscountDay::findOrFail($id);
        $discount_day->name = $request->name;
        $discount_day->discount_date = $request->discount_date;
        $discount_day->discount = $request->discount;
        $discount_day->start_time = $request->start_time;
        $discount_day->end_time = $request->end_time;

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
        $discount_day = DiscountDay::findOrFail($id);
        DiscountDay::destroy($id);

        flash(translate('Record has been deleted successfully'))->success();
        return redirect()->route('discount_day.index');
    }

    public function updateStatus(Request $request){
        $discount_day = DiscountDay::findOrFail($request->id);
        $discount_day->status = $request->status;
        $discount_day->save();

        return 1;
    }

    // In DiscountController.php
public function clone($id)
{
    $discountDay = DiscountDay::find($id);

    if ($discountDay) {
        // Clone the discount day
        $newDiscountDay = $discountDay->replicate(); // Copy all attributes
        $newDiscountDay->name = $discountDay->name . ' (Copy)'; // Modify name to avoid duplicates
        $newDiscountDay->save();

        return redirect()->route('discount_day.index')->with('success', 'Discount day cloned successfully.');
    }

    return redirect()->route('discount_day.index')->with('error', 'Discount day not found.');
}

}
