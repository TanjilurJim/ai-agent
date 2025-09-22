<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_sessions', function (Blueprint $t) {
            $t->id();
            $t->string('api_key', 64)->index();        // identifies widget/tenant
            $t->foreignId('widget_id')->nullable();    // optional, if you want
            $t->string('session_id', 64)->index();     // from client (UUID)
            $t->string('ip', 45)->nullable();
            $t->string('user_agent')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_sessions');
    }
};
