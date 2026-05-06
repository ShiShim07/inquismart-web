<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    // Get all notifications ng current user
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->get();

        return response()->json($notifications);
    }

    // Mark all as read
    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'All marked as read']);
    }

    // Mark specific notification as read
    public function markRead($id){
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail()
            ->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}