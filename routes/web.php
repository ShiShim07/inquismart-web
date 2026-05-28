<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\FAQController;
use App\Http\Controllers\Admin\ChatbotLogsController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Admin Routes (protected)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/respond', [TicketController::class, 'respond'])->name('tickets.respond');
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.status');

    // FAQs
    Route::get('/faqs', [FAQController::class, 'index'])->name('faqs.index');
    Route::post('/faqs', [FAQController::class, 'store'])->name('faqs.store');
    Route::put('/faqs/{faq}', [FAQController::class, 'update'])->name('faqs.update');
    Route::delete('/faqs/{faq}', [FAQController::class, 'destroy'])->name('faqs.destroy');

    // Analytics (Module 15 — Service Analytics)
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');

    // Chatbot Logs (Module 3 / Module 16)
    Route::get('/chatbot/logs', [ChatbotLogsController::class, 'index'])->name('chatbot.logs');
    Route::delete('/chatbot/logs/{id}', [ChatbotLogsController::class, 'destroy'])->name('chatbot.destroy');
});