<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCareerLevel;
use App\Models\JobCareerLevelTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobCareerLevelController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_career_level'])->only('index');
		$this->middleware(['permission:add_job_career_level'])->only('create');
		$this->middleware(['permission:edit_job_career_level'])->only('edit');
		$this->middleware(['permission:delete_job_career_level'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$jobCareerLevels = JobCareerLevel::orderBy('name', 'asc');
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$jobCareerLevels = $jobCareerLevels->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$jobCareerLevels = $jobCareerLevels->paginate(15);
       	return view('backend.job.job_career_level.index', compact('jobCareerLevels', 'sort_search'));
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
    	$job_career_level = new JobCareerLevel;
    	$job_career_level->name = $request->name_en;
    	$job_career_level->status = $request->status;
    	$job_career_level->save();
        //::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'job_career_level_id' => $JobType->id]);
    	$job_career_level_translation = new JobCareerLevelTranslation;
    	$job_career_level_translation->job_career_level_id = $job_career_level->id;
    	$job_career_level_translation->name = $request->name_en;
		$job_career_level_translation->lang = 'en'; 
		$job_career_level_translation->save();

		$job_career_level_translation = new JobCareerLevelTranslation; 
		$job_career_level_translation->job_career_level_id = $job_career_level->id;
		$job_career_level_translation->name = $request->name_br; 
		$job_career_level_translation->lang = 'br'; 
		$job_career_level_translation->save();

		$job_career_level_translation = new JobCareerLevelTranslation;
		$job_career_level_translation->job_career_level_id = $job_career_level->id; 
		$job_career_level_translation->name = $request->name_jp;
		$job_career_level_translation->lang = 'jp';
		$job_career_level_translation->save();


		flash(translate('Job Career Level has been inserted successfully'))->success();
		return redirect()->route('job.career.level');

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
    	$jobCareerLevel  = JobCareerLevel::findOrFail($id);
    	return view('backend.job.job_career_level.edit', compact('jobCareerLevel','lang'));
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
    	$job_career_level = JobCareerLevel::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$job_career_level->name = $request->name;
    	}
    	$job_career_level->status = $request->status;
    	
    	$job_career_level->save();

    	$job_career_level_translation = JobCareerLevelTranslation::firstOrNew(['lang' => $request->lang, 'job_career_level_id' => $job_career_level->id]);
    	$job_career_level_translation->name = $request->name;
    	$job_career_level_translation->save();

    	flash(translate('Job Career Level has been updated successfully'))->success();
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
    	$job_career_level = JobCareerLevel::findOrFail($id);
    	JobCareerLevelTranslation::where('job_career_level_id', $job_career_level->id)->delete();
    	JobCareerLevel::destroy($id);
    	flash(translate('Job Career Level has been deleted successfully'))->success();
    	return redirect()->route('job.career.level');

    }

    public function status(Request $request)
    {
        $job_career_level = JobCareerLevel::findOrFail($request->id);
        $job_career_level->status = $request->status;
        if ($job_career_level->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
