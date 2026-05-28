<?php

use Illuminate\Support\Facades\Route;

// ── Correct namespaces matching actual files in app/Http/Controllers/Api/ ──
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PredictiveController;

// ──────────────────────────────────────────
// PUBLIC ROUTES (no token required)
// ──────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ──────────────────────────────────────────
// AUTHENTICATED CUSTOMER ROUTES
// ──────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // M1 — Auth & Profile
    Route::post('/logout',  [AuthController::class, 'logout']);
    Route::get('/user',     [AuthController::class, 'user']);

    // M2 — Ticket Management
    Route::get('/tickets',              [TicketController::class, 'index']);
    Route::post('/tickets',             [TicketController::class, 'store']);
    Route::get('/tickets/{id}',         [TicketController::class, 'show']);
    Route::delete('/tickets/{id}',      [TicketController::class, 'destroy']);

    // M6 — Ticket Tracking timeline
    Route::get('/tickets/{id}/timeline', [TicketController::class, 'timeline']);

    // M3 — Chatbot
    Route::post('/chatbot/message', [ChatbotController::class, 'message']);
    Route::get('/chatbot/history',  [ChatbotController::class, 'history']);
    Route::delete('/chatbot/clear', [ChatbotController::class, 'clear']);

    // M4 — Notifications
    Route::get('/notifications',                     [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/mark-read',     [NotificationController::class, 'markRead']);
    Route::post('/notifications/mark-all-read',      [NotificationController::class, 'markAllRead']);

    // M5 — FAQ (read-only for customers)
    Route::get('/faqs',        [App\Http\Controllers\Admin\FAQController::class, 'apiIndex']);
    Route::get('/faqs/search', [App\Http\Controllers\Admin\FAQController::class, 'search']);

    // M14 — Predictive Inquiry
    Route::post('/predict/inquiry', [PredictiveController::class, 'suggest']);

});