<?php

namespace App\Http\Controllers;

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
class ApplicationController extends Controller
{
	public function index($job_id)
	{
		if (Auth::check()) {
			$user = JobApplication::where('user_id',Auth()->user()->id)->first();
			$job = Job::where('id',$job_id)->first();
			//return $user;
            return view('frontend.job_application_form',compact('job_id','job'));
        } 
        else
        {
        	flash(translate('Please Longin First'))->error();
			return back();
        }
		
	}

    public function create()
    {
        if (Auth::check()) {
			if((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
				flash(translate('Admin or Customer can not be a seller'))->error();
				return back();
			} if(Auth::user()->user_type == 'seller'){
				flash(translate('This user already a seller'))->error();
				return back();
			}
            
        } else {
            return view('frontend.job_application_form');
        }
    }

	public function store(Request $request)
	{

		$rules = [
			'first_name' => 'required|max:120',
			'last_name' => 'required|max:120',
			'email' => 'required|email|max:60',
			// 'phone' => 'nullable|phone|max:60',
			'resume' => 'required',
		];

		

		$request->validate($rules);

		$application = new JobApplication;
		$application->first_name = $request->first_name;
		$application->last_name = $request->last_name;
		$application->email = $request->email;
		$application->phone = $request->phone;
		$application->job_id = $request->job_id;
		$application->message = $request->message;
		$application->user_id = Auth()->user()->id;
		if ($request->hasFile('file')) {
			$path = $request->file('resume')->store('uploads/resume');
            $url = asset('storage/' . $path);
            $fileName = basename($path);
            $application->resume = $fileName;
		}

		$application->save();   

		flash(translate('You have apply successfully'))->success();
		return redirect()->route('jobs');

	}
	public function quickApply($job_id)
	{

	
		$user_data = JobApplication::where('user_id', auth()->user()->id)->latest()->first();

		$application = new JobApplication;
		$application->first_name = $user_data->first_name;
		$application->last_name = $user_data->last_name;
		$application->email = $user_data->email;
		$application->phone = $user_data->phone;
		$application->job_id = $job_id;
		$application->message = $user_data->message;
		$application->user_id = Auth()->user()->id;
		$application->resume = $user_data->resume;

		$application->save();   

		flash(translate('You have apply successfully'))->success();
		return redirect()->route('jobs');

	}

	public function edit(Request $request, $id)
	{
		$lang   = $request->lang;
		$Job_post  = Job::findOrFail($id);

		$job_type = JobsType::where('job_id', $id)->pluck('job_type_id');
		$job_category = JobsCategory::where('job_id', $id)->pluck('category_id');
		$job_skill    = JobsSkill::where('job_id', $id)->pluck('job_skill_id');
		// return $job_category;
		// die();

		return view('backend.job.jobs.edit', compact('Job_post','job_type','job_category','job_skill','lang'));
	}

	public function update(Request $request, $id)
	{

    	//  return $types = $request->types;;
		$rules = [
			'name' => 'required|max:120',
			'email' => 'nullable|email|max:60',
			'company_id' => 'nullable|integer',
			'number_of_positions' => 'nullable|integer',
			'country_id' => 'nullable|integer',
			'state_id' => 'nullable|integer',
			'city_id' => 'nullable|integer',
			'address' => 'nullable|max:250',
			'latitude' => 'nullable|max:50',
			'longitude' => 'nullable|max:50',
			'cover_image' => 'nullable',
			'career_level_id' => 'nullable|integer',
			'salary_range' => 'nullable',
			'currency_id' => 'nullable|integer',
			'degree_level_id' => 'nullable|integer',
			'job_experience_id' => 'nullable|integer',
			'functional_area_id' => 'nullable|integer',

		];

		$request->validate($rules);

		JobsType::where('job_id', $id)->delete();
		JobsCategory::where('job_id', $id)->delete();
		JobsSkill::where('job_id', $id)->delete();

		$job_post = Job::findOrFail($id);
		$job_post->name = $request->name;
		$job_post->description = $request->description;
		$job_post->content = $request->content;
		$job_post->company_id = $request->company_id;
		$job_post->number_of_positions = $request->number_of_positions;
		$job_post->country_id = $request->country_id;
		$job_post->state_id = $request->state_id;
		$job_post->city_id = $request->city_id;
		$job_post->address = $request->address;
		$job_post->latitude = $request->latitude;
		$job_post->longitude = $request->longitude;
		$job_post->salary_from = $request->salary_from;
		$job_post->salary_to = $request->salary_to;
		$job_post->salary_range = $request->salary_range;
		$job_post->currency_id = $request->currency_id;
		$job_post->cover_image = $request->cover_image;
		$job_post->employer_colleagues = $request->email;
		$job_post->job_shift_id = $request->job_shift_id;
		$job_post->career_level_id = $request->career_level_id;
		$job_post->functional_area_id = $request->functional_area_id;
		$job_post->degree_level_id = $request->degree_level_id;
		$job_post->job_experience_id = $request->job_experience_id;
		$job_post->author_id = Auth()->user()->id;
		$job_post->status = $request->status;
		$job_post->start_date = $request->start_date;
		$job_post->application_closing_date   = $request->application_closing_date;

		$job_post->save();   

		$skills = $request->skills;
		if (is_array($skills)) {
			foreach ($skills as $skillId) {
				$job_skill = new JobsSkill;
				$job_skill->job_id = $job_post->id;
				$job_skill->job_skill_id = $skillId;
				$job_skill->save();  
			}  
		}

		$categories = $request->categories;
		if (is_array($categories)) {
			foreach ($categories as $categoryId) {
				$job_category = new JobsCategory;
				$job_category->job_id = $job_post->id;
				$job_category->category_id = $categoryId;
				$job_category->save();  
			}  
		}
		$types = $request->types;
		if (is_array($types)) {
			foreach ($types as $typeId) {
				$job_type = new JobsType;
				$job_type->job_id = $job_post->id;
				$job_type->job_type_id = $typeId;
				$job_type->save();  
			}  
		}

		flash(translate('Job  has been updated successfully'))->success();
		return back();
		return redirect()->back();
	}

	public function destroy($id)
	{
		$job_post = Job::findOrFail($id);
		Job::destroy($id);
		JobsType::where('job_id', $id)->delete();
		JobsCategory::where('job_id', $id)->delete();
		JobsSkill::where('job_id', $id)->delete();
		flash(translate('Job has been deleted successfully'))->success();
		return redirect()->route('job');

	}

	public function status(Request $request)
	{
		$job_post = Job::findOrFail($request->id);
		$job_post->status = $request->status;
		if ($job_post->save()) {
			Artisan::call('view:clear');
			Artisan::call('cache:clear');
			return 1;
		}
		return 0;
	}

}

?>


