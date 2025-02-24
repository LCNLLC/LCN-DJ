<?php

namespace App\Http\Controllers\Job;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobCompany;
use App\Models\Category;
use App\Models\State;
use Artisan;

class JobCategoryController extends Controller
{
    public function index(Request $request)
    {
        $search = null;
        $type = 'All';
        $companies = JobCompany::orderBy('created_at', 'desc')->paginate(10);
        return view('backend.job.job_company.index', compact('companies', 'search','type'));
    }

    public function create()
    {

        $categories = Category::where('parent_id', 0)
            ->where('digital', 0)
            ->with('childrenCategories')
            ->get();

             $states = State::orderBy('name', 'desc')->get();
            // return $states;
        return view('backend.job.job_company.create', compact('categories','states'));
    }

    public function store(Request $request)
    {
        //return $request->input();

        $rules = [
            'name' => 'required|max:120',
            'description' => 'nullable|max:400',
            'content' => 'nullable',
            'ceo' => 'nullable|max:50',
            'email' => 'nullable|email|max:60',
            'phone' => 'nullable|max:50',
            'website' => 'nullable|url|max:120',
            'year_founded' => 'nullable|integer',
            'number_of_offices' => 'nullable|integer',
            'number_of_employees' => 'nullable|integer',
            'annual_revenue' => 'nullable|numeric',
            'country_id' => 'nullable|integer',
            'state_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'address' => 'nullable|max:250',
            'postal_code' => 'nullable|max:50',
            'latitude' => 'nullable|max:50',
            'longitude' => 'nullable|max:50',
            'logo' => 'nullable',
            'cover_image' => 'nullable',
            'facebook' => 'nullable|url|max:250',
            'twitter' => 'nullable|url|max:250',
            'linkedin' => 'nullable|url|max:250',
            'instagram' => 'nullable|url|max:250',
        ];

        $request->validate($rules);

        $job_company = new JobCompany;
        $job_company->name = $request->name;
        $job_company->description = $request->description;
        $job_company->content = $request->content;
        $job_company->ceo = $request->ceo;
        $job_company->email = $request->email;
        $job_company->phone = $request->phone;
        $job_company->website = $request->website;
        $job_company->year_founded = $request->year_founded;
        $job_company->number_of_offices = $request->number_of_offices;
        $job_company->number_of_employees = $request->number_of_employees;
        $job_company->annual_revenue = $request->annual_revenue;
        $job_company->country_id = $request->country_id;
        $job_company->state_id = $request->state_id;
        $job_company->city_id = $request->city_id;
        $job_company->address = $request->address;
        $job_company->postal_code = $request->postal_code;
        $job_company->latitude = $request->latitude;
        $job_company->longitude = $request->longitude;
        $job_company->logo = $request->logo;
        $job_company->cover_image = $request->cover_image;
        $job_company->facebook = $request->facebook;
        $job_company->twitter = $request->twitter;
        $job_company->linkedin = $request->linkedin;
        $job_company->instagram = $request->instagram;

        $job_company->save();     

        flash(translate('Company has been inserted successfully'))->success();
        return redirect()->route('job.company');

    }

    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $Job_company  = JobCompany::findOrFail($id);
        return view('backend.job.job_company.edit', compact('Job_company','lang'));
    }

    public function update(Request $request, $id)
    {
            //return $request->input();
            $rules = [
            'name' => 'required|max:120',
            'description' => 'nullable|max:400',
            'content' => 'nullable',
            'ceo' => 'nullable|max:50',
            'email' => 'nullable|email|max:60',
            'phone' => 'nullable|max:50',
            'website' => 'nullable|url|max:120',
            'year_founded' => 'nullable|integer',
            'number_of_offices' => 'nullable|integer',
            'number_of_employees' => 'nullable|integer',
            // 'annual_revenue' => 'nullable|numeric',
            'country_id' => 'nullable|integer',
            'state_id' => 'nullable|integer',
            'city_id' => 'nullable|integer',
            'address' => 'nullable|max:250',
            'postal_code' => 'nullable|max:50',
            'latitude' => 'nullable|max:50',
            'longitude' => 'nullable|max:50',
            'logo' => 'nullable',
            'cover_image' => 'nullable',
            'facebook' => 'nullable|url|max:250',
            'twitter' => 'nullable|url|max:250',
            'linkedin' => 'nullable|url|max:250',
            'instagram' => 'nullable|url|max:250',
        ];

        $request->validate($rules);

        $job_company = JobCompany::findOrFail($id);
        $job_company->name = $request->name;
        $job_company->description = $request->description;
        $job_company->content = $request->content;
        $job_company->ceo = $request->ceo;
        $job_company->email = $request->email;
        $job_company->phone = $request->phone;
        $job_company->website = $request->website;
        $job_company->year_founded = $request->year_founded;
        $job_company->number_of_offices = $request->number_of_offices;
        $job_company->number_of_employees = $request->number_of_employees;
        $job_company->annual_revenue = $request->annual_revenue;
        $job_company->country_id = $request->country_id;
        $job_company->state_id = $request->state_id;
        $job_company->city_id = $request->city_id;
        $job_company->address = $request->address;
        $job_company->postal_code = $request->postal_code;
        $job_company->latitude = $request->latitude;
        $job_company->longitude = $request->longitude;
        $job_company->logo = $request->logo;
        $job_company->cover_image = $request->cover_image;
        $job_company->facebook = $request->facebook;
        $job_company->twitter = $request->twitter;
        $job_company->linkedin = $request->linkedin;
        $job_company->instagram = $request->instagram;

        $job_company->save();     

        flash(translate('Job Company has been updated successfully'))->success();
        return back();
    }

    public function destroy($id)
    {
        $job_company = JobCompany::findOrFail($id);
        JobCompany::destroy($id);
        flash(translate('Company has been deleted successfully'))->success();
        return redirect()->route('job.company');

    }

    public function status(Request $request)
    {
        $job_company = JobCompany::findOrFail($request->id);
        $job_company->status = $request->status;
        if ($job_company->save()) {
            Artisan::call('view:clear');
            Artisan::call('cache:clear');
            return 1;
        }
        return 0;
    }

}

?>


