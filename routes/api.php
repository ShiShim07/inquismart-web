<?php

// routes/api.php
// InquiSmart — Customer Helpdesk System for NAN CS
// with Predictive Inquiry Recommendation and Service Analytics
// Own Laravel API — All routes

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\PredictiveController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\DashboardController;

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
    Route::post('/logout',          [AuthController::class, 'logout']);
    Route::get('/profile',          [AuthController::class, 'profile']);
    Route::put('/profile',          [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // M2 — Ticket Management
    Route::get('/tickets',          [TicketController::class, 'index']);
    Route::post('/tickets',         [TicketController::class, 'store']);
    Route::get('/tickets/{id}',     [TicketController::class, 'show']);
    Route::delete('/tickets/{id}',  [TicketController::class, 'destroy']);

    // M3 — Chatbot (Rule-based NLP)
    Route::post('/chatbot/message', [ChatbotController::class, 'message']);
    Route::get('/chatbot/history',  [ChatbotController::class, 'history']);
    Route::delete('/chatbot/clear', [ChatbotController::class, 'clear']);

    // M4 — Notifications
    Route::get('/notifications',                        [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/mark-read',        [NotificationController::class, 'markRead']);
    Route::post('/notifications/mark-all-read',         [NotificationController::class, 'markAllRead']);

    // M5 — FAQ & Knowledge Base
    Route::get('/faqs',             [FaqController::class, 'index']);
    Route::get('/faqs/search',      [FaqController::class, 'search']);

    // M6 — Ticket Tracking (status timeline per ticket)
    Route::get('/tickets/{id}/timeline', [TicketController::class, 'timeline']);

    // M7 — User Profile (already under M1, extended)
    Route::get('/activity',         [AuthController::class, 'activityLog']);

    // M8 — Feedback & Rating
    Route::post('/tickets/{id}/feedback', [FeedbackController::class, 'store']);
    Route::get('/tickets/{id}/feedback',  [FeedbackController::class, 'show']);

    // M14 — Predictive Inquiry Recommendation (called while typing)
    Route::post('/predict/inquiry',  [PredictiveController::class, 'suggest']);
    // Returns: { suggestions: ["Try checking our FAQ on warranties", ...] }

});

// ──────────────────────────────────────────
// ADMIN / STAFF ROUTES
// ──────────────────────────────────────────
Route::middleware(['auth:sanctum', 'role:admin,staff'])->prefix('admin')->group(function () {

    // M10 — Admin Dashboard Stats
    Route::get('/dashboard',         [DashboardController::class, 'index']);
    // Returns: ticket counts, avg resolution time, sentiment breakdown, daily volume

    // M11 — Staff Ticket Response
    Route::get('/tickets',           [AdminTicketController::class, 'index']);
    Route::get('/tickets/{id}',      [AdminTicketController::class, 'show']);
    Route::put('/tickets/{id}',      [AdminTicketController::class, 'update']);
    // Body: { status, staff_response }

    // M13 — Sentiment Analysis (triggered on ticket create, viewable here)
    Route::get('/tickets/{id}/sentiment', [SentimentController::class, 'show']);

    // M15 — Service Analytics
    Route::get('/analytics/overview',        [AnalyticsController::class, 'overview']);
    Route::get('/analytics/sentiment-trends',[AnalyticsController::class, 'sentimentTrends']);
    Route::get('/analytics/volume',          [AnalyticsController::class, 'ticketVolume']);
    Route::get('/analytics/resolution-time', [AnalyticsController::class, 'resolutionTime']);
    Route::get('/analytics/top-inquiries',   [AnalyticsController::class, 'topInquiries']);

    // M17 — Report Generation
    Route::post('/reports/generate', [ReportController::class, 'generate']);
    // Body: { type: 'weekly'|'monthly', format: 'pdf'|'csv' }
    Route::get('/reports',           [ReportController::class, 'index']);

    // M18 — Audit Log
    Route::get('/audit-logs',        [AuditLogController::class, 'index']);

    // Admin User Management
    Route::get('/users',             [AdminUserController::class, 'index']);
    Route::put('/users/{id}',        [AdminUserController::class, 'update']);

    // FAQ Management (admin only)
    Route::post('/faqs',             [FaqController::class, 'store']);
    Route::put('/faqs/{id}',         [FaqController::class, 'update']);
    Route::delete('/faqs/{id}',      [FaqController::class, 'destroy']);
});