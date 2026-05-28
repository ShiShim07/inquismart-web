<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatHistory;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * ChatbotLogsController
 * Module 3 / Module 16 — Chatbot Logs (View Only)
 * Bot-only — no staff reply feature.
 */
class ChatbotLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatHistory::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by intent
        if ($request->filled('intent')) {
            $query->where('intent', $request->intent);
        }

        $logs = $query->paginate(20);

        // Unique intents for filter dropdown
        $intents = ChatHistory::whereNotNull('intent')
            ->whereNotIn('intent', ['staff_reply'])
            ->distinct()
            ->pluck('intent');

        // Users who have chat history
        $users = User::whereIn('id', ChatHistory::distinct()->pluck('user_id'))
            ->get(['id', 'name', 'email']);

        // Stats
        $totalMessages = ChatHistory::count();
        $totalUsers    = ChatHistory::distinct('user_id')->count();
        $topIntent     = ChatHistory::whereNotNull('intent')
            ->where('role', 'bot')
            ->selectRaw('intent, COUNT(*) as count')
            ->groupBy('intent')
            ->orderByDesc('count')
            ->first();

        // Grouped conversations per customer
        $conversations = ChatHistory::with('user')
            ->selectRaw('user_id,
                          COUNT(*) as message_count,
                          MAX(created_at) as last_message_at,
                          SUM(CASE WHEN needs_human = 1 THEN 1 ELSE 0 END) as escalated_count')
            ->when($request->filled('user_id'), fn($q) => $q->where('user_id', $request->user_id))
            ->groupBy('user_id')
            ->orderByDesc('last_message_at')
            ->paginate(10);

        return view('admin.chatbot.logs', compact(
            'logs', 'intents', 'users',
            'totalMessages', 'totalUsers', 'topIntent',
            'conversations'
        ));
    }

    public function conversation($userId)
    {
        $customer = User::findOrFail($userId);

        $messages = ChatHistory::where('user_id', $userId)
            ->whereIn('role', ['user', 'bot']) // bot-only — exclude staff
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.chatbot.conversation', compact('customer', 'messages'));
    }

    public function destroy($id)
    {
        ChatHistory::where('user_id', $id)->delete();
        return back()->with('success', 'Chat history cleared for this user.');
    }
}