<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // STEP 1 — VARCHAR muna para walang ENUM restriction
        DB::statement("ALTER TABLE tickets MODIFY COLUMN sentiment VARCHAR(20) NOT NULL DEFAULT 'Neutral'");

        // STEP 2 — Safe na i-update ang data
        DB::statement("UPDATE tickets SET sentiment = 'Negative' WHERE sentiment = 'Urgent'");
        DB::statement("UPDATE tickets SET sentiment = 'Negative' WHERE sentiment = 'Frustrated'");
        DB::statement("UPDATE tickets SET sentiment = 'Neutral' WHERE sentiment NOT IN ('Positive','Negative','Neutral')");

        // STEP 3 — Ngayon palitan ng bagong ENUM
        DB::statement("ALTER TABLE tickets MODIFY COLUMN sentiment ENUM('Positive','Negative','Neutral') NOT NULL DEFAULT 'Neutral'");

        // STEP 4 — Dagdag na columns
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'processing_at')) {
                $table->timestamp('processing_at')->nullable()->after('responded_at');
            }
            if (!Schema::hasColumn('tickets', 'resolved_at')) {
                $table->timestamp('resolved_at')->nullable()->after('processing_at');
            }
            if (!Schema::hasColumn('tickets', 'sentiment_confidence')) {
                $table->string('sentiment_confidence')->nullable()->after('sentiment');
            }
        });
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tickets MODIFY COLUMN sentiment VARCHAR(20) NOT NULL DEFAULT 'Neutral'");
        DB::statement("UPDATE tickets SET sentiment = 'Urgent' WHERE sentiment = 'Negative'");
        DB::statement("ALTER TABLE tickets MODIFY COLUMN sentiment ENUM('Urgent','Frustrated','Neutral') NOT NULL DEFAULT 'Neutral'");

        Schema::table('tickets', function (Blueprint $table) {
            foreach (['processing_at', 'resolved_at', 'sentiment_confidence'] as $col) {
                if (Schema::hasColumn('tickets', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};