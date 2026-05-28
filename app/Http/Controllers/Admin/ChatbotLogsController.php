<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * ChatbotLogsController
 * Module 3 / Module 16 / Module 17 — Chatbot Engine + Human Handoff
 */
class ChatbotLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = ChatHistory::with('user')
            ->orderBy('created_at', 'desc');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('intent')) {
            $query->where('intent', $request->intent);
        }
        if ($request->filled('needs_human') && $request->needs_human == '1') {
            $query->where('needs_human', true);
        }

        $logs = $query->paginate(20);

        $intents = ChatHistory::whereNotNull('intent')->distinct()->pluck('intent');

        $users = User::whereIn('id', ChatHistory::distinct()->pluck('user_id'))
            ->get(['id', 'name', 'email']);

        $totalMessages  = ChatHistory::count();
        $totalUsers     = ChatHistory::distinct('user_id')->count('user_id');
        $needsHuman     = ChatHistory::where('needs_human', true)
                            ->where('role', 'bot')
                            ->count();
        $topIntent      = ChatHistory::whereNotNull('intent')
            ->selectRaw('intent, COUNT(*) as count')
            ->groupBy('intent')
            ->orderByDesc('count')
            ->first();

        // Group conversations by user for the conversation view
        $conversations = ChatHistory::with('user')
            ->select('user_id')
            ->selectRaw('MAX(created_at) as last_message_at')
            ->selectRaw('COUNT(*) as message_count')
            ->selectRaw('SUM(needs_human) as escalated_count')
            ->groupBy('user_id')
            ->orderByDesc('last_message_at')
            ->paginate(15);

        return view('admin.chatbot.logs', compact(
            'logs', 'intents', 'users',
            'totalMessages', 'totalUsers', 'topIntent',
            'needsHuman', 'conversations'
        ));
    }

    /**
     * Show full conversation for a specific user
     */
    public function conversation(Request $request, $userId)
    {
        $customer = User::findOrFail($userId);

        $messages = ChatHistory::where('user_id', $userId)
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark all as read by staff
        ChatHistory::where('user_id', $userId)
            ->where('is_read_by_staff', false)
            ->update(['is_read_by_staff' => true]);

        return view('admin.chatbot.conversation', compact('customer', 'messages'));
    }

    /**
     * POST /admin/chatbot/{userId}/reply
     * Staff sends a message to a customer
     */
    public function reply(Request $request, $userId)
    {
        $request->validate(['message' => 'required|string|max:2000']);

        $customer = User::findOrFail($userId);

        ChatHistory::create([
            'user_id'          => $userId,
            'staff_id'         => Auth::id(),
            'role'             => 'staff',
            'message'          => trim($request->message),
            'intent'           => 'staff_reply',
            'is_read_by_staff' => true,
            'needs_human'      => false,
        ]);

        return back()->with('success', 'Reply sent to ' . $customer->name . '!');
    }

    /**
     * DELETE — clear chat history for a user
     */
    public function destroy($userId)
    {
        ChatHistory::where('user_id', $userId)->delete();
        return back()->with('success', 'Chat history cleared.');
    }
}