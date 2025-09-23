<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('personality_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personality_id')->index('idx_personality_items_pid');
            $table->string('heading')->nullable();
            $table->longText('body');
            $table->unsignedInteger('order')->default(1)->index();
            $table->timestamps();

            $table->foreign('personality_id', 'fk_personality_items_personality')
                  ->references('id')->on('personalities')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personality_items');
    }
};
