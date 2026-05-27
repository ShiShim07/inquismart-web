<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Notification;
use App\Services\SentimentService;
use Illuminate\Http\Request;

/**
 * Api/TicketController — Updated for revised capstone
 *
 * Changes from original:
 * - Sentiment detection now uses SentimentService (Positive/Negative/Neutral)
 * - Added timeline() method for Module 6 — Ticket Tracking
 * - Added destroy() method
 * - Notification on ticket create
 */
class TicketController extends Controller
{
    public function __construct(private SentimentService $sentiment) {}

    /**
     * GET /api/tickets
     */
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->latest()->get();
        return response()->json($tickets);
    }

    /**
     * POST /api/tickets
     * Sentiment is auto-analyzed via SentimentService on creation.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // M13 — Sentiment Analysis (Positive / Negative / Neutral)
        $combined  = $request->subject . ' ' . $request->description;
        $analysis  = $this->sentiment->analyze($combined);

        $ticket = Ticket::create([
            'user_id'              => auth()->id(),
            'subject'              => $request->subject,
            'description'          => $request->description,
            'status'               => 'Pending',
            'sentiment'            => $analysis['sentiment'],
            'sentiment_confidence' => $analysis['confidence'],
        ]);

        // Auto-notify the customer (M4)
        Notification::create([
            'user_id'   => auth()->id(),
            'title'     => 'Ticket received! 🎫',
            'message'   => 'Your ticket "' . $ticket->subject . '" (#' . $ticket->ticket_number . ') has been submitted. We\'ll respond within 24 hours.',
            'type'      => 'ticket_received',
            'ticket_id' => $ticket->id,
            'is_read'   => false,
        ]);

        return response()->json($ticket, 201);
    }

    /**
     * GET /api/tickets/{id}
     */
    public function show($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return response()->json($ticket);
    }

    /**
     * DELETE /api/tickets/{id}
     * Only allowed if ticket is still Pending.
     */
    public function destroy($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($ticket->status !== 'Pending') {
            return response()->json([
                'message' => 'Cannot delete a ticket that is already being processed.'
            ], 422);
        }

        $ticket->delete();
        return response()->json(['message' => 'Ticket deleted.']);
    }

    /**
     * GET /api/tickets/{id}/timeline
     * Module 6 — Ticket Tracking: returns the step-by-step progress of a ticket.
     */
    public function timeline($id)
    {
        $ticket = Ticket::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $steps = [
            [
                'step'        => 'Submitted',
                'completed'   => true,
                'timestamp'   => $ticket->created_at,
                'description' => 'Your ticket was received by our system.',
            ],
            [
                'step'        => 'Processing',
                'completed'   => in_array($ticket->status, ['Processing', 'Resolved']),
                'timestamp'   => $ticket->processing_at,
                'description' => 'Our staff is reviewing your concern.',
            ],
            [
                'step'        => 'Resolved',
                'completed'   => $ticket->status === 'Resolved',
                'timestamp'   => $ticket->resolved_at,
                'description' => 'Your ticket has been resolved.',
            ],
        ];

        return response()->json([
            'ticket_id'     => $ticket->id,
            'ticket_number' => $ticket->ticket_number,
            'status'        => $ticket->status,
            'sentiment'     => $ticket->sentiment,
            'timeline'      => $steps,
        ]);
    }
}