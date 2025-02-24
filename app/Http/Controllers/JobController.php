<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogCategory;
use App\Models\Blog;
use App\Models\Job;
use App\Models\JobType;
use App\Models\JobsType;
use App\Models\JobsCategory;
use App\Models\JobsSkill;
use App\Models\JobApplication;
use Auth;

class JobController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_blogs'])->only('index');
        $this->middleware(['permission:add_blog'])->only('create');
        $this->middleware(['permission:edit_blog'])->only('edit');
        $this->middleware(['permission:delete_blog'])->only('destroy');
        $this->middleware(['permission:publish_blog'])->only('change_status');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $blogs = Blog::orderBy('created_at', 'desc');
        
        if ($request->search != null){
            $blogs = $blogs->where('title', 'like', '%'.$request->search.'%');
            $sort_search = $request->search;
        }

        $blogs = $blogs->paginate(15);

        return view('backend.blog_system.blog.index', compact('blogs','sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $blog_categories = BlogCategory::all();
        return view('backend.blog_system.blog.create', compact('blog_categories'));
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
            'category_id' => 'required',
            'title' => 'required|max:255',
        ]);

        $blog = new Blog;
        
        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->banner = $request->banner;
        $blog->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        $blog->short_description = $request->short_description;
        $blog->description = $request->description;
        
        $blog->meta_title = $request->meta_title;
        $blog->meta_img = $request->meta_img;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        
        $blog->save();

        flash(translate('Blog post has been created successfully'))->success();
        return redirect()->route('blog.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $blog = Blog::find($id);
        $blog_categories = BlogCategory::all();
        
        return view('backend.blog_system.blog.edit', compact('blog','blog_categories'));
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
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|max:255',
        ]);

        $blog = Blog::find($id);

        $blog->category_id = $request->category_id;
        $blog->title = $request->title;
        $blog->banner = $request->banner;
        $blog->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        $blog->short_description = $request->short_description;
        $blog->description = $request->description;
        
        $blog->meta_title = $request->meta_title;
        $blog->meta_img = $request->meta_img;
        $blog->meta_description = $request->meta_description;
        $blog->meta_keywords = $request->meta_keywords;
        
        $blog->save();

        flash(translate('Blog post has been updated successfully'))->success();
        return redirect()->route('blog.index');
    }
    
    public function change_status(Request $request) {
        $blog = Blog::find($request->id);
        $blog->status = $request->status;
        
        $blog->save();
        return 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Blog::find($id)->delete();
        
        return redirect('admin/blogs');
    }


    public function all_job(Request $request) {
        $selected_types = array();
        $search = null;
        $jobs = Job::query();

    
        
        if ($request->has('selected_types')) {
            $selected_types = $request->selected_types;
            $job_types = JobType::whereIn('name', $selected_types)->pluck('id')->toArray();

            $jobs_id = JobsType::whereIn('job_type_id', $job_types)->groupBy('job_id')->pluck('job_id')->toArray();

           // return  $jobs_id;
            $jobs->whereIn('id', $jobs_id);
        }
        
        $jobs = $jobs->where('status', 1)->orderBy('created_at', 'desc')->paginate(12);

        $recent_jobs = Job::where('status', 1)->orderBy('created_at', 'desc')->limit(9)->get();

        return view("frontend.job.listing", compact('jobs', 'selected_types', 'search', 'recent_jobs'));
    }
    
    public function job_details($slug) {
        $job = Job::where('name', $slug)->first();
        $id = $job->id;
        $recent_jobs = Job::where('status', 1)->orderBy('created_at', 'desc')->limit(9)->get();

        $jobsTypes = JobsType::where('job_id', $id)->get();
        $jobsCategories = JobsCategory::where('job_id', $id)->get();
        $jobsSkills    = JobsSkill::where('job_id', $id)->get();
        $applied =0;
        if (Auth::check()) {
            $applied = JobApplication::where('job_id', $id)->where('user_id',Auth()->user()->id)->pluck('id')->first();
        }
        return view("frontend.job.details", compact('job', 'recent_jobs','jobsTypes','jobsCategories','jobsSkills','applied'));
    }
}
