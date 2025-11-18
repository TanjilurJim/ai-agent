<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chat_attachments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('chat_message_id')->constrained()->cascadeOnDelete();
            $t->string('disk', 32)->default('public');
            $t->string('path');            // storage path
            $t->string('url');             // public URL (Storage::url)
            $t->string('mime', 64)->nullable();
            $t->unsignedBigInteger('size')->nullable();  // bytes
            $t->unsignedInteger('width')->nullable();    // for images
            $t->unsignedInteger('height')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('chat_attachments'); }
};