<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ChatHistory Model
 * Module 3 / Module 16 / Module 17 — Chatbot Engine + Human Handoff
 *
 * Place this file at: app/Models/ChatHistory.php
 */
class ChatHistory extends Model
{
    protected $fillable = [
        'user_id',
        'staff_id',
        'role',             // 'user', 'bot', or 'staff'
        'message',
        'intent',           // detected chatbot intent
        'is_read_by_staff', // true when admin has seen it
        'needs_human',      // true when bot couldn't answer (fallback)
    ];

    protected $casts = [
        'is_read_by_staff' => 'boolean',
        'needs_human'      => 'boolean',
    ];

    /** The customer who sent/received this message */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** The staff member who replied (if role = 'staff') */
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}