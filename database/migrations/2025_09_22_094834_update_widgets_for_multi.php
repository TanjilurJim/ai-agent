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
            if (!Schema::hasColumn('widgets', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('widgets', 'api_key')) {
                $table->string('api_key', 40)->unique()->after('user_id');
            }
            if (!Schema::hasColumn('widgets', 'widgetName')) {
                $table->string('widgetName')->nullable()->after('name');
            }
            if (Schema::hasColumn('widgets', 'avatar')) {
                $table->string('avatar')->nullable()->change(); // make nullable
            } else {
                $table->string('avatar')->nullable()->after('id');
            }
            if (!Schema::hasColumn('widgets', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('color')->index();
            }
            // Helpful indexes
            if (!Schema::hasColumn('widgets', 'created_at')) {
                $table->timestamps();
            }
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('widgets', function (Blueprint $table) {
            if (Schema::hasColumn('widgets', 'is_active')) $table->dropColumn('is_active');
            if (Schema::hasColumn('widgets', 'widgetName')) $table->dropColumn('widgetName');
            if (Schema::hasColumn('widgets', 'api_key')) $table->dropColumn('api_key');
            if (Schema::hasColumn('widgets', 'user_id')) $table->dropConstrainedForeignId('user_id');
        });
    }
};
