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
        Schema::table('widgets', function (Blueprint $table) {
            //
            $table->foreignId('personality_id')->nullable()->constrained()->nullOnDelete();
            $table->index('api_key'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('widgets', function (Blueprint $table) {
            //
            $table->dropConstrainedForeignId('personality_id');
            $table->dropIndex(['api_key']);
        });
    }
};
