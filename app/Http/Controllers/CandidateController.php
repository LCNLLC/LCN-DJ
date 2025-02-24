<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use App\Models\Job;
use App\Models\JobType;
use App\Models\JobsType;
use App\Models\JobCategory;
use App\Models\JobSkill;
use DB;

class CandidateController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_candidates'])->only('index');
        $this->middleware(['permission:login_as_candidates'])->only('login');
        $this->middleware(['permission:ban_candidates'])->only('ban');
        $this->middleware(['permission:delete_candidates'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $users = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->whereExists(function ($query) {
            $query->select(DB::raw(1))
            ->from('jb_job_profiles') 
            ->whereColumn('user_id', 'users.id');
        })->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search){
                  $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%')->orWhere('phone', 'like', '%'.$sort_search.'%');
            });
        }
        $users = $users->paginate(15);
        return view('backend.candidate.index', compact('users', 'sort_search'));
    }

    public function all_candidate1(Request $request)
    {
        $sort_search = null;
        $candidates = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->whereExists(function ($query) {
            $query->select(DB::raw(1))
            ->from('jb_job_profiles') 
            ->whereColumn('user_id', 'users.id');
        })->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $candidates->where(function ($q) use ($sort_search){
                $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%');
            });
        }
        $candidates = $candidates->paginate(15);

        $selected_skills = array();
        $search = null;
        $jobs = Job::query();


        if ($request->has('selected_skills')) {
            $selected_skills = $request->selected_skills;
            $job_types       = JobSkill::whereIn('name', $selected_skills)->pluck('id')->toArray();
            $jobs_id         = JobsType::whereIn('job_type_id', $job_types)->groupBy('job_id')->pluck('job_id')->toArray();
            $jobs->whereIn('id', $jobs_id);
        }

        return view('frontend.candidate.listing', compact('candidates', 'sort_search', 'selected_skills', 'search'));
    }
    public function all_candidate(Request $request)
    {    
        $search = null;
        $sort_search = null;
        $selected_skills  = [];
        $candidates = User::where('user_type', 'customer')
        ->where('email_verified_at', '!=', null)
        ->whereExists(function ($query) {
            $query->select(DB::raw(1))
            ->from('jb_job_profiles')
            ->whereColumn('user_id', 'users.id');
        });

        if ($request->has('search')) {
            $sort_search = $request->search;
            $candidates->where(function ($q) use ($sort_search) {
                $q->where('name', 'like', '%'.$sort_search.'%')
                ->orWhere('email', 'like', '%'.$sort_search.'%');
            });
        }

        if ($request->has('selected_skills')) {
            $selected_skills = $request->selected_skills;
            foreach ($selected_skills as $skill) {
                $candidates->where(function ($query) use ($skill) {
                    $query->whereExists(function ($subquery) use ($skill) {
                        $subquery->select(DB::raw(1))
                        ->from('jb_job_profiles')
                        ->whereColumn('user_id', 'users.id')
                        ->whereRaw("JSON_CONTAINS(skills, '[\"$skill\"]')");
                    });
                });
            }
        }

        $candidates = $candidates->orderBy('created_at', 'desc')->paginate(15);

        return view('frontend.candidate.listing', compact('candidates', 'sort_search', 'selected_skills', 'search'));
    }

    public function candidate_details($slug)
    {

        $sort_search = null;
        $candidate = User::where('name',$slug)->where('user_type', 'customer')->where('email_verified_at', '!=', null)->whereExists(function ($query) {
            $query->select(DB::raw(1))
            ->from('jb_job_profiles') 
            ->whereColumn('user_id', 'users.id');
        })->first();




      //  return      $candidate->candidate_profile->types;


        $jobsTypes = JobType::whereIn('id', $candidate->candidate_profile->types)->get();

        $jobsCategories = JobCategory::whereIn('id', $candidate->candidate_profile->categories)->get();
        $jobsSkills    = JobSkill::whereIn('id', $candidate->candidate_profile->skills)->get();
        $applied =0;

        return view('frontend.candidate.details', compact('candidate','jobsTypes','jobsCategories','jobsSkills'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|unique:users|email',
            'phone'         => 'required|unique:users',
        ]);
        
        $response['status'] = 'Error';
        
        $user = User::create($request->all());
        
        $customer = new Customer;
        
        $customer->user_id = $user->id;
        $customer->save();
        
        if (isset($user->id)) {
            $html = '';
            $html .= '<option value="">
            '. translate("Walk In Customer") .'
            </option>';
            foreach(Customer::all() as $key => $customer){
                if ($customer->user) {
                    $html .= '<option value="'.$customer->user->id.'" data-contact="'.$customer->user->email.'">
                    '.$customer->user->name.'
                    </option>';
                }
            }
            
            $response['status'] = 'Success';
            $response['html'] = $html;
        }
        
        echo json_encode($response);
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
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        $customer->customer_products()->delete(); 
        $customer->candidate_profile()->delete(); 



        User::destroy($id);
        flash(translate('Candidate has been deleted successfully'))->success();
        return redirect()->route('candidates.index');
    }
    
    public function bulk_candidate_delete(Request $request) {
        if($request->id) {
            foreach ($request->id as $customer_id) {
                $customer = User::findOrFail($customer_id);
                $customer->customer_products()->delete(); 
                $customer->candidate_profile()->delete(); 
                $this->destroy($customer_id);
            }
        }
        
        return 1;
    }

    public function login($id)
    {
        $user = User::findOrFail(decrypt($id));

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }

    public function ban($id) {
        $user = User::findOrFail(decrypt($id));

        if($user->banned == 1) {
            $user->banned = 0;
            $account_status = 'UnBanned';
            $value = 0;
            flash(translate('Candidate UnBanned Successfully'))->success();
        } else {
            $user->banned = 1;
            $account_status = 'Banned';
            $value = 1;
            flash(translate('Candidate Banned Successfully'))->success();
        }

        $user->save();
        CustomerBanUnbendNotification($user->id,$account_status,$value);
        return back();
    }
}
