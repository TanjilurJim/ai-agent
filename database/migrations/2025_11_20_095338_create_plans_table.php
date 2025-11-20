<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // free, regular, pro
            $table->unsignedInteger('widget_limit');
            $table->unsignedInteger('personality_limit');
            $table->unsignedInteger('daily_prompt_limit');
            $table->timestamps();
        });

        // Seed the 3 base plans
        DB::table('plans')->insert([
            [
                'name'               => 'free',
                'widget_limit'       => 1,
                'personality_limit'  => 2,
                'daily_prompt_limit' => 10,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'regular',
                'widget_limit'       => 3,
                'personality_limit'  => 6,
                'daily_prompt_limit' => 15,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'name'               => 'pro',
                'widget_limit'       => 5,
                'personality_limit'  => 10,
                'daily_prompt_limit' => 22,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
