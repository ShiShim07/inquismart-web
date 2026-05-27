<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Notification;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with('user')->orderByRaw("FIELD(sentiment, 'Urgent', 'Frustrated', 'Neutral')")
            ->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('sentiment') && $request->sentiment != '') {
            $query->where('sentiment', $request->sentiment);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('subject', 'like', '%' . $request->search . '%');
        }

        $tickets = $query->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('user');
        return view('admin.tickets.show', compact('ticket'));
    }

    public function respond(Request $request, Ticket $ticket)
    {
        $request->validate([
            'staff_response' => 'required|string|min:5',
        ]);

        $ticket->update([
            'staff_response' => $request->staff_response,
            'status'         => 'Resolved',
            'staff_id'       => auth()->id(),
            'responded_at'   => now(),
        ]);

        // ✅ Notify customer — ticket resolved with response
        Notification::create([
            'user_id'   => $ticket->user_id,
            'title'     => 'Ticket Resolved! ✅',
            'message'   => 'Your ticket "' . $ticket->subject . '" has been resolved. Tap to view the response.',
            'type'      => 'ticket_resolved',
            'ticket_id' => $ticket->id,
            'is_read'   => false,
        ]);

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Response sent and ticket marked as Resolved!');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate(['status' => 'required|in:Pending,Processing,Resolved']);

        $oldStatus = $ticket->status;
        $newStatus = $request->status;

        $ticket->update(['status' => $newStatus]);

        // ✅ Notify customer only if status actually changed
        if ($oldStatus !== $newStatus) {
            if ($newStatus === 'Processing') {
                Notification::create([
                    'user_id'   => $ticket->user_id,
                    'title'     => 'Ticket Update 🔄',
                    'message'   => 'Your ticket "' . $ticket->subject . '" is now being processed by our staff.',
                    'type'      => 'ticket_processing',
                    'ticket_id' => $ticket->id,
                    'is_read'   => false,
                ]);
            } elseif ($newStatus === 'Resolved') {
                Notification::create([
                    'user_id'   => $ticket->user_id,
                    'title'     => 'Ticket Resolved! ✅',
                    'message'   => 'Your ticket "' . $ticket->subject . '" has been marked as resolved.',
                    'type'      => 'ticket_resolved',
                    'ticket_id' => $ticket->id,
                    'is_read'   => false,
                ]);
            }
        }

        return back()->with('success', 'Ticket status updated!');
    }
}