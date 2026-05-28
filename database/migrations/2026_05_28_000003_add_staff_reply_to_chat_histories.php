<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1 — Change role enum to VARCHAR first (safe migration)
        DB::statement("ALTER TABLE chat_histories MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user'");

        // Step 2 — Add new columns
        Schema::table('chat_histories', function (Blueprint $table) {
            $table->unsignedBigInteger('staff_id')->nullable()->after('user_id');
            $table->boolean('is_read_by_staff')->default(false)->after('intent');
            $table->boolean('needs_human')->default(false)->after('is_read_by_staff');

            $table->foreign('staff_id')->references('id')->on('users')->onDelete('set null');
        });

        // Step 3 — Re-apply enum with 'staff' added
        DB::statement("ALTER TABLE chat_histories MODIFY COLUMN role ENUM('user','bot','staff') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE chat_histories MODIFY COLUMN role VARCHAR(20) NOT NULL DEFAULT 'user'");

        Schema::table('chat_histories', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropColumn(['staff_id', 'is_read_by_staff', 'needs_human']);
        });

        DB::statement("ALTER TABLE chat_histories MODIFY COLUMN role ENUM('user','bot') NOT NULL DEFAULT 'user'");
    }
};