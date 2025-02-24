<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Auth;
use App\Models\TicketReply;
use App\Mail\SupportMailManager;
use Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SupportTicketController extends Controller
{
    public function __construct()
    {
        // Staff Permission Check
        $this->middleware(['permission:view_all_support_tickets'])->only('admin_index');
    }

    // Frontend user ticket listing
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);
        return view('frontend.user.support_ticket.index', compact('tickets'));
    }

    // Admin index with advanced filters and search
    public function admin_index(Request $request)
    {
        $tickets = Ticket::query();

        // Advanced search: search by ticket code or subject
        if ($request->filled('search')) {
            $search = $request->search;
            $tickets->where(function($query) use ($search) {
                $query->where('code', 'like', '%' . $search . '%')
                      ->orWhere('subject', 'like', '%' . $search . '%');
            });
        }

        // Filter by status (e.g., open, in progress, closed)
        if ($request->filled('status')) {
            $tickets->where('status', $request->status);
        }

        // Filter by priority (e.g., high, medium, low)
        if ($request->filled('priority')) {
            $tickets->where('priority', $request->priority);
        }

        // Filter by creation date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $tickets->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $tickets = $tickets->orderBy('created_at', 'desc')->paginate(15);

        // Retrieve team members (assumed as users with user_type 'staff') for assignment actions
        $team_members = User::where('user_type', 'staff')->get();

        return view('backend.support.support_tickets.index', compact('tickets', 'team_members'));
    }

    public function create()
    {
        //
    }

    // Store a new ticket from a frontend user
    public function store(Request $request)
    {
        $ticket = new Ticket;
        $latestTicket = Ticket::latest()->first();
        $ticket->code = max(100000, ($latestTicket != null ? $latestTicket->code + 1 : 0)) . date('s');
        $ticket->user_id = Auth::user()->id;
        $ticket->subject = $request->subject;
        $ticket->details = $request->details;
        $ticket->files = $request->attachments;

        if ($ticket->save()) {
            $this->send_support_mail_to_admin($ticket);
            flash(translate('Ticket has been sent successfully'))->success();
            return redirect()->route('support_ticket.index');
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    public function send_support_mail_to_admin($ticket)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support ticket Code is') . ':- ' . $ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. A ticket has been created. Please check the ticket.');
        $array['link'] = route('support_ticket.admin_show', encrypt($ticket->id));
        $array['sender'] = $ticket->user->name;
        $array['details'] = $ticket->details;

        try {
            Mail::to(User::where('user_type', 'admin')->first()->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {
            // Optionally log the exception
        }
    }

    public function send_support_reply_email_to_user($ticket, $tkt_reply)
    {
        $array['view'] = 'emails.support';
        $array['subject'] = translate('Support ticket Code is') . ':- ' . $ticket->code;
        $array['from'] = env('MAIL_FROM_ADDRESS');
        $array['content'] = translate('Hi. A ticket has been updated. Please check the ticket.');
        $array['link'] = route('support_ticket.show', encrypt($ticket->id));
        $array['sender'] = $tkt_reply->user->name;
        $array['details'] = $tkt_reply->reply;

        try {
            Mail::to($ticket->user->email)->queue(new SupportMailManager($array));
        } catch (\Exception $e) {
            // Optionally log the exception
        }
    }

    // Admin reply: supports quick assignment and status update
    public function admin_store(Request $request)
    {
        // Retrieve and update the ticket directly
        $ticket = Ticket::find($request->ticket_id);
        $ticket->client_viewed = 0;
        $ticket->status = $request->status;
        if ($request->filled('assigned_to')) {
            $ticket->assigned_to = $request->assigned_to;
        }
        $ticket->save();

        // Save the reply
        $ticket_reply = new TicketReply;
        $ticket_reply->ticket_id = $ticket->id;
        $ticket_reply->user_id = Auth::user()->id;
        $ticket_reply->reply = $request->reply;
        $ticket_reply->files = $request->attachments;

        if ($ticket_reply->save()) {
            flash(translate('Reply has been sent successfully'))->success();
            $this->send_support_reply_email_to_user($ticket, $ticket_reply);
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    // Seller reply (unchanged)
    public function seller_store(Request $request)
    {
        $ticket = Ticket::find($request->ticket_id);
        $ticket->viewed = 0;
        $ticket->status = 'pending';
        $ticket->save();

        $ticket_reply = new TicketReply;
        $ticket_reply->ticket_id = $ticket->id;
        $ticket_reply->user_id = $request->user_id;
        $ticket_reply->reply = $request->reply;
        $ticket_reply->files = $request->attachments;

        if ($ticket_reply->save()) {
            flash(translate('Reply has been sent successfully'))->success();
            return back();
        } else {
            flash(translate('Something went wrong'))->error();
            return back();
        }
    }

    // Frontend ticket view; marks ticket as viewed by client
    public function show($id)
    {
        $ticket = Ticket::findOrFail(decrypt($id));
        $ticket->client_viewed = 1;
        $ticket->save();
        $ticket_replies = $ticket->ticketreplies;
        return view('frontend.user.support_ticket.show', compact('ticket', 'ticket_replies'));
    }

    // Admin ticket view; marks ticket as viewed by admin
    public function admin_show($id)
    {
        $ticket = Ticket::findOrFail(decrypt($id));
        $ticket->viewed = 1;
        $ticket->save();
        return view('backend.support.support_tickets.show', compact('ticket'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    // Delete old ticket replies for tickets with solved status
    public function delete_ticket_reply()
    {
        $thresholdDate = Carbon::now()->subDays(30);
        DB::table('ticket_replies')
            ->join('tickets', 'ticket_replies.ticket_id', '=', 'tickets.id')
            ->where('tickets.status', '=', 'solved')
            ->where('ticket_replies.created_at', '<', $thresholdDate)
            ->delete();
    }
}
