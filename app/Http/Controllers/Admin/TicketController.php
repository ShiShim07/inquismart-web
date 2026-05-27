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

        // ─────────────────────────────────────────────────────────
        // AI Sentiment Analysis Module
        // Classification: Positive | Negative | Neutral
        // Method: Keyword-based NLP classification
        // ─────────────────────────────────────────────────────────
        $description = strtolower($request->description);

        // Positive keywords — customer is happy, satisfied, or praising
        $positiveWords = [
            'thank', 'thanks', 'salamat', 'happy', 'satisfied', 'great',
            'excellent', 'amazing', 'good', 'appreciate', 'wonderful',
            'love', 'perfect', 'awesome', 'best', 'pleased', 'glad',
            'helpful', 'fast', 'maganda', 'magaling', 'masaya',
        ];

        // Negative keywords — customer is frustrated, angry, or dissatisfied
        $negativeWords = [
            'frustrated', 'angry', 'disappointed', 'worst', 'terrible',
            'bad', 'horrible', 'unacceptable', 'poor', 'awful', 'rude',
            'broken', 'defective', 'sira', 'galit', 'hindi maganda',
            'scam', 'fake', 'lie', 'cheated', 'never', 'useless',
            'waste', 'refund', 'urgent', 'asap', 'immediately',
            'emergency', 'agad', 'need it now', 'not working',
        ];

        $sentiment = 'Neutral'; // default

        // Check Positive first
        foreach ($positiveWords as $word) {
            if (str_contains($description, $word)) {
                $sentiment = 'Positive';
                break;
            }
        }

        // Check Negative (overrides Positive if both detected — negative wins)
        foreach ($negativeWords as $word) {
            if (str_contains($description, $word)) {
                $sentiment = 'Negative';
                break;
            }
        }

        $ticket = Ticket::create([
            'user_id'     => auth()->id(),
            'subject'     => $request->subject,
            'description' => $request->description,
            'status'      => 'Pending',
            'sentiment'   => $sentiment,
        ]);

        return response()->json($ticket, 201);
    }

    public function show(Ticket $ticket)
    {
        return response()->json($ticket);
    }
}