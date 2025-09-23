<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Copy every page into a personality with a single item (order=1).
        // Uses raw DB to avoid model events/fillables interfering during migration.
        if (!DB::getSchemaBuilder()->hasTable('pages')) return;

        $now = now();
        $pages = DB::table('pages')->select('id', 'title', 'content', 'user_id', 'created_at', 'updated_at')->get();

        foreach ($pages as $p) {
            // Create personality
            $personalityId = DB::table('personalities')->insertGetId([
                'user_id'     => $p->user_id,
                'name'        => $p->title ?: 'Untitled',
                'description' => null,
                'created_at'  => $p->created_at ?? $now,
                'updated_at'  => $p->updated_at ?? $now,
            ]);

            // Create first (and only) item
            DB::table('personality_items')->insert([
                'personality_id' => $personalityId,
                'heading'        => 'Details',
                // your pages.content was a string field; still safe to insert into longText
                'body'           => (string)($p->content ?? ''),
                'order'          => 1,
                'created_at'     => $p->created_at ?? $now,
                'updated_at'     => $p->updated_at ?? $now,
            ]);
        }
    }

    public function down(): void
    {
        // Rollback only removes the migrated personalities/items that match the old pages
        // We can't reliably map back 1:1 if user added new personalities after migration.
        // So this down simply NOOPs to avoid data loss.
    }
};
