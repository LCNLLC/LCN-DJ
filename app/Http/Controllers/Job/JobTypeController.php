<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobType;
use App\Models\JobTypeTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobTypeController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_type'])->only('index');
		$this->middleware(['permission:add_job_type'])->only('create');
		$this->middleware(['permission:edit_job_type'])->only('edit');
		$this->middleware(['permission:delete_job_type'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$jobTypes = JobType::orderBy('name', 'asc');
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$jobTypes = $jobTypes->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$jobTypes = $jobTypes->paginate(15);
       	return view('backend.job.job_type.index', compact('jobTypes', 'sort_search'));
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
    	$JobType = new JobType;
    	$JobType->name = $request->name_en;
    	$JobType->status = $request->status;
    	$JobType->save();
        //::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'job_type_id' => $JobType->id]);
    	$job_type_translation = new JobTypeTranslation;
    	$job_type_translation->job_type_id = $JobType->id;
    	$job_type_translation->name = $request->name_en;
		$job_type_translation->lang = 'en'; 
		$job_type_translation->save();

		$job_type_translation = new JobTypeTranslation; 
		$job_type_translation->job_type_id = $JobType->id;
		$job_type_translation->name = $request->name_br; 
		$job_type_translation->lang = 'br'; 
		$job_type_translation->save();

		$job_type_translation = new JobTypeTranslation;
		$job_type_translation->job_type_id = $JobType->id; 
		$job_type_translation->name = $request->name_jp;
		$job_type_translation->lang = 'jp';
		$job_type_translation->save();


		flash(translate('Job Type has been inserted successfully'))->success();
		return redirect()->route('job.type');

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
    	$jobType  = JobType::findOrFail($id);
    	return view('backend.job.job_type.edit', compact('jobType','lang'));
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
    	$JobType = JobType::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$JobType->name = $request->name;
    	}
    	$JobType->status = $request->status;
    	
    	$JobType->save();

    	$job_type_translation = JobTypeTranslation::firstOrNew(['lang' => $request->lang, 'job_type_id' => $JobType->id]);
    	$job_type_translation->name = $request->name;
    	$job_type_translation->save();

    	flash(translate('Job type has been updated successfully'))->success();
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
    	$job_type = JobType::findOrFail($id);
    	JobTypeTranslation::where('job_type_id', $job_type->id)->delete();
    	JobType::destroy($id);
    	flash(translate('Job type has been deleted successfully'))->success();
    	return redirect()->route('job.type');

    }

    public function status(Request $request)
    {
        $job_type = JobType::findOrFail($request->id);
        $job_type->status = $request->status;
        if ($job_type->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
