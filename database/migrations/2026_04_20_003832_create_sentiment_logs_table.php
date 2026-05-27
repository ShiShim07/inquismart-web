<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sentiment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            // Updated: Urgent/Frustrated → Positive/Negative/Neutral (panel requirement)
            $table->enum('sentiment_result', ['Positive', 'Negative', 'Neutral'])->default('Neutral');
            $table->float('confidence_score')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentiment_logs');
    }
};