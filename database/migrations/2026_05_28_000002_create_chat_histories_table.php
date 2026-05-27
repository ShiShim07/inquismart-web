<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Create chat_histories table
 * Module 3 / Module 16 — Chatbot Engine
 *
 * Stores per-user chatbot conversation history.
 * Run: php artisan migrate
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['user', 'bot'])->default('user');
            $table->text('message');
            $table->string('intent')->nullable(); // detected intent (warranty, repair, etc.)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_histories');
    }
};