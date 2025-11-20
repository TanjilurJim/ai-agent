<?php

// database/migrations/2025_01_01_000100_add_plan_id_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('plan_id')
                ->after('id')
                ->nullable()
                ->constrained('plans');
        });

        // Set all existing users to 'free' plan by default
        $freePlanId = DB::table('plans')->where('name', 'free')->value('id');

        if ($freePlanId) {
            DB::table('users')->update(['plan_id' => $freePlanId]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('plan_id');
        });
    }
};
