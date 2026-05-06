<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->latest()->get();
        return response()->json($tickets);
    }

    public function store(Request $request)
{
    $request->validate([
        'subject'     => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // AI Sentiment Detection
    $description = strtolower($request->description);
    $urgentWords     = ['urgent', 'asap', 'immediately', 'emergency', 'now', 'today', 'agad', 'need it now'];
    $frustratedWords = ['frustrated', 'angry', 'disappointed', 'worst', 'terrible', 'bad service', 'galit', 'hindi maganda'];

    $sentiment = 'Neutral';
    foreach ($urgentWords as $word) {
        if (str_contains($description, $word)) { $sentiment = 'Urgent'; break; }
    }
    if ($sentiment === 'Neutral') {
        foreach ($frustratedWords as $word) {
            if (str_contains($description, $word)) { $sentiment = 'Frustrated'; break; }
        }
    }

    $ticket = Ticket::create([
        'user_id'     => auth()->id(),
        'subject'     => $request->subject,
        'description' => $request->description,
        'status'      => 'Pending',
        'sentiment'   => $sentiment,  // dynamic 
    ]);

    return response()->json($ticket, 201);
}

    public function show(Ticket $ticket)
    {
        return response()->json($ticket);
    }
}