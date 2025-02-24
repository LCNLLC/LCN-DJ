<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobSkill;
use App\Models\JobSkillTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobSkillController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_skill'])->only('index');
		$this->middleware(['permission:add_job_skill'])->only('create');
		$this->middleware(['permission:edit_job_skill'])->only('edit');
		$this->middleware(['permission:delete_job_skill'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$jobSkills = JobSkill::orderBy('name', 'asc');
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$jobSkills = $jobSkills->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$jobSkills = $jobSkills->paginate(15);
       	return view('backend.job.job_skill.index', compact('jobSkills', 'sort_search'));
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
    	$job_skill = new JobSkill;
    	$job_skill->name = $request->name_en;
    	$job_skill->status = $request->status;
    	$job_skill->save();
        //::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'job_skill_id' => $JobType->id]);
    	$job_skill_translation = new JobSkillTranslation;
    	$job_skill_translation->job_skill_id = $job_skill->id;
    	$job_skill_translation->name = $request->name_en;
		$job_skill_translation->lang = 'en'; 
		$job_skill_translation->save();

		$job_skill_translation = new JobSkillTranslation; 
		$job_skill_translation->job_skill_id = $job_skill->id;
		$job_skill_translation->name = $request->name_br; 
		$job_skill_translation->lang = 'br'; 
		$job_skill_translation->save();

		$job_skill_translation = new JobSkillTranslation;
		$job_skill_translation->job_skill_id = $job_skill->id; 
		$job_skill_translation->name = $request->name_jp;
		$job_skill_translation->lang = 'jp';
		$job_skill_translation->save();


		flash(translate('Job Skill has been inserted successfully'))->success();
		return redirect()->route('job.skill');

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
    	$jobSkill  = JobSkill::findOrFail($id);
    	return view('backend.job.job_skill.edit', compact('jobSkill','lang'));
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
    	$job_skill = JobSkill::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$job_skill->name = $request->name;
    	}
    	$job_skill->status = $request->status;
    	
    	$job_skill->save();

    	$job_skill_translation = JobSkillTranslation::firstOrNew(['lang' => $request->lang, 'job_skill_id' => $job_skill->id]);
    	$job_skill_translation->name = $request->name;
    	$job_skill_translation->save();

    	flash(translate('Job Skill has been updated successfully'))->success();
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
    	$job_skill = JobSkill::findOrFail($id);
    	JobSkillTranslation::where('job_skill_id', $job_skill->id)->delete();
    	JobSkill::destroy($id);
    	flash(translate('Job Skill has been deleted successfully'))->success();
    	return redirect()->route('job.skill');

    }

    public function status(Request $request)
    {
        $job_skill = JobSkill::findOrFail($request->id);
        $job_skill->status = $request->status;
        if ($job_skill->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
