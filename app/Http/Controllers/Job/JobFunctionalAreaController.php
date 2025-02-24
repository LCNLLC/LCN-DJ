<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobFunctionalArea;
use App\Models\JobFunctionalAreaTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobFunctionalAreaController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_functional_area'])->only('index');
		$this->middleware(['permission:add_job_functional_area'])->only('create');
		$this->middleware(['permission:edit_job_functional_area'])->only('edit');
		$this->middleware(['permission:delete_job_functional_area'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$jobFunctionalAreas = JobFunctionalArea::orderBy('name', 'asc');
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$jobFunctionalAreas = $jobFunctionalAreas->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$jobFunctionalAreas = $jobFunctionalAreas->paginate(15);
       	return view('backend.job.job_functional_area.index', compact('jobFunctionalAreas', 'sort_search'));
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
    	$job_functional_area = new JobFunctionalArea;
    	$job_functional_area->name = $request->name_en;
    	$job_functional_area->status = $request->status;
    	$job_functional_area->save();
        //::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'job_functional_area_id' => $JobType->id]);
    	$job_functional_area_translation = new JobFunctionalAreaTranslation;
    	$job_functional_area_translation->job_functional_area_id = $job_functional_area->id;
    	$job_functional_area_translation->name = $request->name_en;
		$job_functional_area_translation->lang = 'en'; 
		$job_functional_area_translation->save();

		$job_functional_area_translation = new JobFunctionalAreaTranslation; 
		$job_functional_area_translation->job_functional_area_id = $job_functional_area->id;
		$job_functional_area_translation->name = $request->name_br; 
		$job_functional_area_translation->lang = 'br'; 
		$job_functional_area_translation->save();

		$job_functional_area_translation = new JobFunctionalAreaTranslation;
		$job_functional_area_translation->job_functional_area_id = $job_functional_area->id; 
		$job_functional_area_translation->name = $request->name_jp;
		$job_functional_area_translation->lang = 'jp';
		$job_functional_area_translation->save();


		flash(translate('Job Functional Area has been inserted successfully'))->success();
		return redirect()->route('job.functional.area');

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
    	$jobFunctionalArea  = JobFunctionalArea::findOrFail($id);
    	return view('backend.job.job_functional_area.edit', compact('jobFunctionalArea','lang'));
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
    	$job_functional_area = JobFunctionalArea::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$job_functional_area->name = $request->name;
    	}
    	$job_functional_area->status = $request->status;
    	
    	$job_functional_area->save();

    	$job_functional_area_translation = JobFunctionalAreaTranslation::firstOrNew(['lang' => $request->lang, 'job_functional_area_id' => $job_functional_area->id]);
    	$job_functional_area_translation->name = $request->name;
    	$job_functional_area_translation->save();

    	flash(translate('Job Functional Area has been updated successfully'))->success();
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
    	$job_functional_area = JobFunctionalArea::findOrFail($id);
    	JobFunctionalAreaTranslation::where('job_functional_area_id', $job_functional_area->id)->delete();
    	JobFunctionalArea::destroy($id);
    	flash(translate('Job Functional Area has been deleted successfully'))->success();
    	return redirect()->route('job.functional.area');

    }

    public function status(Request $request)
    {
        $job_functional_area = JobFunctionalArea::findOrFail($request->id);
        $job_functional_area->status = $request->status;
        if ($job_functional_area->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
