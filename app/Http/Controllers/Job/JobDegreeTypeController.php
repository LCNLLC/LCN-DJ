<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobDegreeType;
use App\Models\JobDegreeLevel;
use App\Models\JobDegreeTypeTranslation;
use Illuminate\Support\Facades\Config;
use Artisan;

class JobDegreeTypeController extends Controller
{
	public function __construct() {
        // Staff Permission Check
		$this->middleware(['permission:view_all_job_degree_type'])->only('index');
		$this->middleware(['permission:add_job_degree_type'])->only('create');
		$this->middleware(['permission:edit_job_degree_type'])->only('edit');
		$this->middleware(['permission:delete_job_degree_type'])->only('destroy');
	}
       /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
       public function index(Request $request)
       {
       	$sort_search =null;
       	$JobDegreeTypes = JobDegreeType::with('jobDegreeLevel')->orderBy('name', 'asc');
       	
       	if ($request->has('search')){
       		$sort_search = $request->search;
       		$JobDegreeTypes = $jobSkills->where('name', 'like', '%'.$sort_search.'%');
       	}
       	$JobDegreeTypes = $JobDegreeTypes->paginate(15);
       	$jobDegreeLevels = jobDegreeLevel::orderBy('name', 'asc')->get();

       	return view('backend.job.job_degree_type.index', compact('JobDegreeTypes','jobDegreeLevels', 'sort_search'));
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
    	$job_degree_type = new JobDegreeType;
    	$job_degree_type->name = $request->name_en;
        $job_degree_type->job_degree_level_id = $request->job_degree_level_id;
    	$job_degree_type->status = $request->status;
    	$job_degree_type->save();
      
    	$job_degree_type_translation = new JobDegreeTypeTranslation;
    	$job_degree_type_translation->job_degree_type_id = $job_degree_type->id;
    	$job_degree_type_translation->name = $request->name_en;
		$job_degree_type_translation->lang = 'en'; 
		$job_degree_type_translation->save();

		$job_degree_type_translation = new JobDegreeTypeTranslation; 
		$job_degree_type_translation->job_degree_type_id = $job_degree_type->id;
		$job_degree_type_translation->name = $request->name_br; 
		$job_degree_type_translation->lang = 'br'; 
		$job_degree_type_translation->save();

		$job_degree_type_translation = new JobDegreeTypeTranslation;
		$job_degree_type_translation->job_degree_type_id = $job_degree_type->id; 
		$job_degree_type_translation->name = $request->name_jp;
		$job_degree_type_translation->lang = 'jp';
		$job_degree_type_translation->save();


		flash(translate('Job Degee Type has been inserted successfully'))->success();
		return redirect()->route('job.degree.type');

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
    	$JobDegreeType  = JobDegreeType::findOrFail($id);
        $jobDegreeLevels = jobDegreeLevel::orderBy('name', 'asc')->get();
    	return view('backend.job.job_degree_type.edit', compact('JobDegreeType','jobDegreeLevels','lang'));
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
    	$job_degree_type = JobDegreeType::findOrFail($id);
    	if($request->lang == env("DEFAULT_LANGUAGE")){
    		$job_degree_type->name = $request->name;
    	}
    	$job_degree_type->status = $request->status;
        $job_degree_type->job_degree_level_id = $request->job_degree_level_id;
    	
    	$job_degree_type->save();

    	$job_degree_type_translation = JobDegreeTypeTranslation::firstOrNew(['lang' => $request->lang, 'job_degree_type_id' => $job_degree_type->id]);
    	$job_degree_type_translation->name = $request->name;
    	$job_degree_type_translation->save();

    	flash(translate('Job Degee Type has been updated successfully'))->success();
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
    	$job_degree_type = JobDegreeType::findOrFail($id);
    	JobDegreeTypeTranslation::where('job_degree_type_id', $job_degree_type->id)->delete();
    	JobDegreeType::destroy($id);
    	flash(translate('Job Degee Type has been deleted successfully'))->success();
    	return redirect()->route('job.degree.type');

    }

    public function status(Request $request)
    {
        $job_degree_type = JobDegreeType::findOrFail($request->id);
        $job_degree_type->status = $request->status;
        if ($job_degree_type->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}
