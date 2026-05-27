<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ChatHistory Model
 * Module 3 / Module 16 — Chatbot Engine
 *
 * Place this file at: app/Models/ChatHistory.php
 */
class ChatHistory extends Model
{
    protected $fillable = [
        'user_id',
        'role',    // 'user' or 'bot'
        'message',
        'intent',  // detected chatbot intent
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}