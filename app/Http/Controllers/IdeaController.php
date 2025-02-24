<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Idea;
use App\Models\User;
use Auth;
use App\Models\IdeaReply;
use App\Mail\SupportMailManager;
use Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IdeaController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_ideas'])->only('admin_index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Idea::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('frontend.user.idea.index', compact('ideas'));
    }

    public function admin_index(Request $request)
    {
        $sort_search = null;
        $ideas = Idea::orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $ideas = $ideas->where('code', 'like', '%' . $sort_search . '%');
        }
        $ideas = $ideas->paginate(15);
        return view('backend.support.idea.index', compact('ideas', 'sort_search'));
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
        //dd();
        $idea = new Idea;
        $idea->code = max(100000, (Idea::latest()->first() != null ? Idea::latest()->first()->code + 1 : 0)) . date('s');
        $idea->user_id = Auth::user()->id;
        $idea->subject = $request->subject;
        $idea->details = $request->details;
        $idea->files = $request->attachments;

        if ($idea->save()) {
            $this->send_support_mail_to_admin($idea);
            flash(translate('Idea has been sent successfully'))->success();
            return redirect()->route('idea.index');
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    public function send_support_mail_to_admin($idea)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support idea Code is') . ':- ' . $idea->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. A idea has been created. Please check the idea.');
        $array['link'] = route('idea.admin_show', encrypt($idea->id));
        $array['sender'] = $idea->user->name;
        $array['details'] = $idea->details;

        // dd($array);
        // dd(User::where('user_type', 'admin')->first()->email);
        try {
            Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }

    public function send_support_reply_email_to_user($idea, $idea_reply)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support idea Code is') . ':- ' . $idea->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. A idea has been created. Please check the idea.');
        $array['link'] = route('idea.show', encrypt($idea->id));
        $array['sender'] = $idea_reply->user->name;
        $array['details'] = $idea_reply->reply;

        try {
            Mail::to($idea->user->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {
            //dd($e->getMessage());
        }
    }

    public function admin_store(Request $request)
    {
        $idea_reply = new IdeaReply;
        $idea_reply->idea_id = $request->idea_id;
        $idea_reply->user_id = Auth::user()->id;
        $idea_reply->reply = $request->reply;
        $idea_reply->files = $request->attachments;
        $idea_reply->idea->client_viewed = 0;
        $idea_reply->idea->status = $request->status;
        $idea_reply->idea->save();

        if ($idea_reply->save()) {
            flash(translate('Reply has been sent successfully'))->success();
            $this->send_support_reply_email_to_user($idea_reply->idea, $idea_reply);
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    public function seller_store(Request $request)
    {
        $idea_reply = new IdeaReply;
        $idea_reply->idea_id = $request->idea_id;
        $idea_reply->user_id = $request->user_id;
        $idea_reply->reply = $request->reply;
        $idea_reply->files = $request->attachments;
        $idea_reply->idea->viewed = 0;
        $idea_reply->idea->status = 'pending';
        $idea_reply->idea->save();
        if ($idea_reply->save()) {

            flash(translate('Reply has been sent successfully'))->success();
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $idea = Idea::findOrFail(decrypt($id));
        $idea->client_viewed = 1;
        $idea->save();
        $idea_replies = $idea->ideareplies;
        return view('frontend.user.idea.show', compact('idea', 'idea_replies'));
    }

    public function admin_show($id)
    {
        $idea = Idea::findOrFail(decrypt($id));
        $idea->viewed = 1;
        $idea->save();
        return view('backend.support.idea.show', compact('idea'));
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
        //
    }
    public function delete_ticket_reply()
    {
    
        $thresholdDate = Carbon::now()->subDays(30);
        DB::table('idea_replies')
        ->join('ideas', 'idea_replies.idea_id', '=', 'ideas.id')
        ->where('ideas.status', '=', 'solved')
        ->where('idea_replies.created_at', '<', $thresholdDate)
        ->delete();
    }
}
