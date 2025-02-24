<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobShift;
use App\Models\JobShiftTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobShiftController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_shift'])->only('index');
		$this->middleware(['permission:add_job_shift'])->only('create');
		$this->middleware(['permission:edit_job_shift'])->only('edit');
		$this->middleware(['permission:delete_job_shift'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$jobShifts = JobShift::orderBy('name', 'asc');
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$jobShifts = $jobSkills->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$jobShifts = $jobShifts->paginate(15);
       	return view('backend.job.job_shift.index', compact('jobShifts', 'sort_search'));
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
    	//return $request->input();
    	$job_shift = new JobShift;
    	$job_shift->name = $request->name_en;
    	$job_shift->status = $request->status;
    	$job_shift->save();
        //::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'job_shift_id' => $JobType->id]);
    	$job_shift_translation = new JobShiftTranslation;
    	$job_shift_translation->job_shift_id = $job_shift->id;
    	$job_shift_translation->name = $request->name_en;
		$job_shift_translation->lang = 'en'; 
		$job_shift_translation->save();

		$job_shift_translation = new JobShiftTranslation; 
		$job_shift_translation->job_shift_id = $job_shift->id;
		$job_shift_translation->name = $request->name_br; 
		$job_shift_translation->lang = 'br'; 
		$job_shift_translation->save();

		$job_shift_translation = new JobShiftTranslation;
		$job_shift_translation->job_shift_id = $job_shift->id; 
		$job_shift_translation->name = $request->name_jp;
		$job_shift_translation->lang = 'jp';
		$job_shift_translation->save();


		flash(translate('Job Shift has been inserted successfully'))->success();
		return redirect()->route('job.shift');

	}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
    	$lang   = $request->lang;
    	$jobShift  = JobShift::findOrFail($id);
    	return view('backend.job.job_shift.edit', compact('jobShift','lang'));
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
    	$job_shift = JobShift::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$job_shift->name = $request->name;
    	}
    	$job_shift->status = $request->status;
    	
    	$job_shift->save();

    	$job_shift_translation = JobShiftTranslation::firstOrNew(['lang' => $request->lang, 'job_shift_id' => $job_shift->id]);
    	$job_shift_translation->name = $request->name;
    	$job_shift_translation->save();

    	flash(translate('Job Shift has been updated successfully'))->success();
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
    	$job_shift = JobShift::findOrFail($id);
    	JobShiftTranslation::where('job_shift_id', $job_shift->id)->delete();
    	JobShift::destroy($id);
    	flash(translate('Job Shift has been deleted successfully'))->success();
    	return redirect()->route('job.shift');

    }

    public function status(Request $request)
    {
        $job_shift = JobShift::findOrFail($request->id);
        $job_shift->status = $request->status;
        if ($job_shift->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
