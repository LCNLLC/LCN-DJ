<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobDegreeLevel;
use App\Models\JobDegreeLevelTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobDegreeLevelController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_degree_level'])->only('index');
		$this->middleware(['permission:add_job_degree_level'])->only('create');
		$this->middleware(['permission:edit_job_degree_level'])->only('edit');
		$this->middleware(['permission:delete_job_degree_level'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$JobDegreeLevels = JobDegreeLevel::orderBy('name', 'asc');
       	
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$JobDegreeLevels = $JobDegreeLevels->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$JobDegreeLevels = $JobDegreeLevels->paginate(15);

       	return view('backend.job.job_degree_level.index', compact('JobDegreeLevels','sort_search'));
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
    	$job_degree_level = new JobDegreeLevel;
    	$job_degree_level->name = $request->name_en;
    	$job_degree_level->status = $request->status;
    	$job_degree_level->save();
        //::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'job_degree_level_id' => $JobType->id]);
    	$job_degree_level_translation = new JobDegreeLevelTranslation;
    	$job_degree_level_translation->job_degree_level_id = $job_degree_level->id;
    	$job_degree_level_translation->name = $request->name_en;
		$job_degree_level_translation->lang = 'en'; 
		$job_degree_level_translation->save();

		$job_degree_level_translation = new JobDegreeLevelTranslation; 
		$job_degree_level_translation->job_degree_level_id = $job_degree_level->id;
		$job_degree_level_translation->name = $request->name_br; 
		$job_degree_level_translation->lang = 'br'; 
		$job_degree_level_translation->save();

		$job_degree_level_translation = new JobDegreeLevelTranslation;
		$job_degree_level_translation->job_degree_level_id = $job_degree_level->id; 
		$job_degree_level_translation->name = $request->name_jp;
		$job_degree_level_translation->lang = 'jp';
		$job_degree_level_translation->save();


		flash(translate('Job Degree Level has been inserted successfully'))->success();
		return redirect()->route('job.degree.level');

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
    	$JobDegreeLevel  = JobDegreeLevel::findOrFail($id);
    	return view('backend.job.job_degree_level.edit', compact('JobDegreeLevel','lang'));
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
    	$job_degree_level = JobDegreeLevel::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$job_degree_level->name = $request->name;
    	}
    	$job_degree_level->status = $request->status;
    	
    	$job_degree_level->save();

    	$job_degree_level_translation = JobDegreeLevelTranslation::firstOrNew(['lang' => $request->lang, 'job_degree_level_id' => $job_degree_level->id]);
    	$job_degree_level_translation->name = $request->name;
    	$job_degree_level_translation->save();

    	flash(translate('Job Degree Level has been updated successfully'))->success();
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
    	$job_degree_level = JobDegreeLevel::findOrFail($id);
    	JobDegreeLevelTranslation::where('job_degree_level_id', $job_degree_level->id)->delete();
    	JobDegreeLevel::destroy($id);
    	flash(translate('Job Degree Level has been deleted successfully'))->success();
    	return redirect()->route('job.degree.level');

    }

    public function status(Request $request)
    {
        $job_degree_level = JobDegreeLevel::findOrFail($request->id);
        $job_degree_level->status = $request->status;
        if ($job_degree_level->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
