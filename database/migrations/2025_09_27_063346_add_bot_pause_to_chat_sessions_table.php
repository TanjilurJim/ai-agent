<?php

// database/migrations/2025_09_27_000001_add_bot_pause_to_chat_sessions.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('chat_sessions', function (Blueprint $t) {
            $t->timestamp('bot_paused_until')->nullable()->index();
            $t->foreignId('paused_by_user_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }
    public function down(): void {
        Schema::table('chat_sessions', function (Blueprint $t) {
            $t->dropConstrainedForeignId('paused_by_user_id');
            $t->dropColumn(['bot_paused_until','paused_by_user_id']);
        });
    }
};