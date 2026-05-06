<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->after('user_id');
            }
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->after('title');
            }
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->default('general')->after('message');
            }
            if (!Schema::hasColumn('notifications', 'ticket_id')) {
                $table->unsignedBigInteger('ticket_id')->nullable()->after('type');
            }
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('ticket_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'title', 'message', 'type', 'ticket_id', 'is_read']);
        });
    }
};