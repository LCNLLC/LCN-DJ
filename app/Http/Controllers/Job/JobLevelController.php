<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobLevel;
use App\Models\JobLevelTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobLevelController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_level'])->only('index');
		$this->middleware(['permission:add_job_level'])->only('create');
		$this->middleware(['permission:edit_job_level'])->only('edit');
		$this->middleware(['permission:delete_job_level'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$jobLevels = JobLevel::orderBy('name', 'asc');
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$jobLevels = $jobLevels->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$jobLevels = $jobLevels->paginate(15);
       	return view('backend.job.job_level.index', compact('jobLevels', 'sort_search'));
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
    	$job_level = new JobLevel;
    	$job_level->name = $request->name_en;
    	$job_level->status = $request->status;
    	$job_level->save();
        //::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'job_level_id' => $JobType->id]);
    	$job_level_translation = new JobLevelTranslation;
    	$job_level_translation->job_level_id = $job_level->id;
    	$job_level_translation->name = $request->name_en;
		$job_level_translation->lang = 'en'; 
		$job_level_translation->save();

		$job_level_translation = new JobLevelTranslation; 
		$job_level_translation->job_level_id = $job_level->id;
		$job_level_translation->name = $request->name_br; 
		$job_level_translation->lang = 'br'; 
		$job_level_translation->save();

		$job_level_translation = new JobLevelTranslation;
		$job_level_translation->job_level_id = $job_level->id; 
		$job_level_translation->name = $request->name_jp;
		$job_level_translation->lang = 'jp';
		$job_level_translation->save();


		flash(translate('Job Level has been inserted successfully'))->success();
		return redirect()->route('job.level');

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
    	$jobLevel  = JobLevel::findOrFail($id);
    	return view('backend.job.job_level.edit', compact('jobLevel','lang'));
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
    	$job_level = JobLevel::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$job_level->name = $request->name;
    	}
    	$job_level->status = $request->status;
    	
    	$job_level->save();

    	$job_level_translation = JobLevelTranslation::firstOrNew(['lang' => $request->lang, 'job_level_id' => $job_level->id]);
    	$job_level_translation->name = $request->name;
    	$job_level_translation->save();

    	flash(translate('Job Level has been updated successfully'))->success();
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
    	$job_level = JobLevel::findOrFail($id);
    	JobLevelTranslation::where('job_level_id', $job_level->id)->delete();
    	JobLevel::destroy($id);
    	flash(translate('Job Level has been deleted successfully'))->success();
    	return redirect()->route('job.level');

    }

    public function status(Request $request)
    {
        $job_level = JobLevel::findOrFail($request->id);
        $job_level->status = $request->status;
        if ($job_level->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
