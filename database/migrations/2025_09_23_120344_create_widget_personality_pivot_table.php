<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        Schema::create('personality_widget', function (Blueprint $table) {
            $table->id();
            $table->foreignId('widget_id')->constrained()->cascadeOnDelete();
            $table->foreignId('personality_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('order')->default(1);
            $table->timestamps();

            $table->unique(['widget_id', 'personality_id']);
        });

        // Backfill from old single-column if it exists
        if (Schema::hasColumn('widgets', 'personality_id')) {
            $rows = DB::table('widgets')
                ->whereNotNull('personality_id')
                ->select('id as widget_id', 'personality_id')
                ->get();

            foreach ($rows as $r) {
                DB::table('personality_widget')->insert([
                    'widget_id'      => $r->widget_id,
                    'personality_id' => $r->personality_id,
                    'order'          => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }

            Schema::table('widgets', function (Blueprint $table) {
                $table->dropConstrainedForeignId('personality_id');
            });
        }
    }

    public function down(): void {
        // (optional) you could add the column back, but usually unnecessary
        Schema::dropIfExists('personality_widget');
    }
};
