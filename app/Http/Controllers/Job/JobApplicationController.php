<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCompany;
use App\Models\Category;
use App\Models\Job;
use App\Models\State;
use App\Models\JobsSkill;
use App\Models\JobsType;
use App\Models\JobsCategory;
use App\Models\JobApplication;
use Artisan;
use Auth;
use Validator;
class JobApplicationController extends Controller
{
	public function index(Request $request)
	{
		$sort_search =null;
		$jobApplications = JobApplication::orderBy('created_at', 'asc');

		$jobApplications = $jobApplications->paginate(15);
		return view('backend.job.job_application.index', compact('jobApplications', 'sort_search'));
	}

    public function analytics(Request $request,$id)
    {
        $sort_search =null;
        $jobApplications = JobApplication::where('job_id',$id)->orderBy('created_at', 'asc');

        $jobApplications = $jobApplications->paginate(15);
        return view('backend.job.job_application.index', compact('jobApplications', 'sort_search'));
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
    	// $lang   = $request->lang;
    	// $jobShift  = JobShift::findOrFail($id);
    	// return view('backend.job.job_shift.edit', compact('jobShift','lang'));
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

    	flash(translate('Job Application has been updated successfully'))->success();
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
    	$job_application = JobApplication::findOrFail($id);
    	JobApplication::destroy($id);
    	flash(translate('Job Application has been deleted successfully'))->success();
    	return redirect()->route('job.application');

    }

    public function status(Request $request)
    {
    	$job_application = JobApplication::findOrFail($request->id);
    	$job_application->status = $request->status;
    	if ($job_application->save()) {
    		Artisan::call('view:clear');
    		Artisan::call('cache:clear');
    		return 1;
    	}
    	return 0;
    }

}

?>


