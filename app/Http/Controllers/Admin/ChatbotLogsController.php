<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatHistory;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * ChatbotLogsController
 * Module 3 / Module 16 — Chatbot Engine
 * Shows all chatbot conversations in the admin panel.
 */
class ChatbotLogsController extends Controller
{
    public function index(Request $request)
    {
        // Get all users who have chatbot history
        $query = ChatHistory::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by user if requested
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by intent if requested
        if ($request->filled('intent')) {
            $query->where('intent', $request->intent);
        }

        $logs = $query->paginate(20);

        // Get unique intents for filter dropdown
        $intents = ChatHistory::whereNotNull('intent')
            ->distinct()
            ->pluck('intent');

        // Get users who have chat history
        $users = User::whereIn('id', ChatHistory::distinct()->pluck('user_id'))
            ->get(['id', 'name', 'email']);

        // Stats
        $totalMessages  = ChatHistory::count();
        $totalUsers     = ChatHistory::distinct('user_id')->count();
        $topIntent      = ChatHistory::whereNotNull('intent')
            ->selectRaw('intent, COUNT(*) as count')
            ->groupBy('intent')
            ->orderByDesc('count')
            ->first();

        return view('admin.chatbot.logs', compact(
            'logs', 'intents', 'users',
            'totalMessages', 'totalUsers', 'topIntent'
        ));
    }

    public function destroy($id)
    {
        ChatHistory::where('user_id', $id)->delete();
        return back()->with('success', 'Chat history cleared for this user.');
    }
}