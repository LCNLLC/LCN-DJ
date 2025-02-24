<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobExperience;
use App\Models\JobExperienceTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobExperienceController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_experience'])->only('index');
		$this->middleware(['permission:add_job_experience'])->only('create');
		$this->middleware(['permission:edit_job_experience'])->only('edit');
		$this->middleware(['permission:delete_job_experience'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$jobExperiences = JobExperience::orderBy('name', 'asc');
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$jobExperiences = $jobExperiences->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$jobExperiences = $jobExperiences->paginate(15);
       	return view('backend.job.job_experience.index', compact('jobExperiences', 'sort_search'));
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
    	$job_experience = new JobExperience;
    	$job_experience->name = $request->name_en;
    	$job_experience->status = $request->status;
    	$job_experience->save();
        //::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'job_experience_id' => $JobType->id]);
    	$job_experience_translation = new JobExperienceTranslation;
    	$job_experience_translation->job_experience_id = $job_experience->id;
    	$job_experience_translation->name = $request->name_en;
		$job_experience_translation->lang = 'en'; 
		$job_experience_translation->save();

		$job_experience_translation = new JobExperienceTranslation; 
		$job_experience_translation->job_experience_id = $job_experience->id;
		$job_experience_translation->name = $request->name_br; 
		$job_experience_translation->lang = 'br'; 
		$job_experience_translation->save();

		$job_experience_translation = new JobExperienceTranslation;
		$job_experience_translation->job_experience_id = $job_experience->id; 
		$job_experience_translation->name = $request->name_jp;
		$job_experience_translation->lang = 'jp';
		$job_experience_translation->save();


		flash(translate('Job Experience has been inserted successfully'))->success();
		return redirect()->route('job.experience');

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
    	$jobExperience  = JobExperience::findOrFail($id);
    	return view('backend.job.job_experience.edit', compact('jobExperience','lang'));
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
    	$job_experience = JobExperience::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$job_experience->name = $request->name;
    	}
    	$job_experience->status = $request->status;
    	
    	$job_experience->save();

    	$job_experience_translation = JobExperienceTranslation::firstOrNew(['lang' => $request->lang, 'job_experience_id' => $job_experience->id]);
    	$job_experience_translation->name = $request->name;
    	$job_experience_translation->save();

    	flash(translate('Job Experience has been updated successfully'))->success();
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
    	$job_experience = JobExperience::findOrFail($id);
    	JobExperienceTranslation::where('job_experience_id', $job_experience->id)->delete();
    	JobExperience::destroy($id);
    	flash(translate('Job Experience has been deleted successfully'))->success();
    	return redirect()->route('job.experience');

    }

    public function status(Request $request)
    {
        $job_experience = JobExperience::findOrFail($request->id);
        $job_experience->status = $request->status;
        if ($job_experience->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
