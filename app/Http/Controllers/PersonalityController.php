<?php

namespace App\Http\Controllers;

use App\Models\BotContent;
use App\Models\Personality;
use App\Models\PersonalityItem;
use Illuminate\Http\Request;
use App\Models\UserDailyUsage;
use App\Models\User;
class PersonalityController extends Controller
{
    /** Big textarea page (Describe About Bot) stays as-is */
    public function train()
    {
        $content = BotContent::firstOrNew(['user_id' => auth()->id()]);
        return view('dashboard.train', compact('content'));
    }

    /** GET /dashboard/train-bot/add-page  (Create Personality) */
    public function create()
    {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->canCreateMorePersonalities()) {
            $limit = $user->personalityLimit();

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Your current plan allows only {$limit} personality/ies. Please upgrade your plan to create more personalities."
                );
        }

        // existing code
        $personality = new Personality();
        $personality->setRelation('items', collect()); // empty collection for the form
        return view('dashboard.page', compact('personality'));
    }

    /** POST /dashboard/train-bot/add-page  (Store Personality + items) */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (! $user->isAdmin() && ! $user->canCreateMorePersonalities()) {
            $limit = $user->personalityLimit();

            return redirect()
                ->back()
                ->with(
                    'error',
                    "Your current plan allows only {$limit} personality/ies. Please upgrade your plan to create more personalities."
                );
        }

        // For now, accept one title+content (your current form);
        // but also allow multiple items posted as arrays: items[0][heading], items[0][body], ...
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],          // Personality name
            'content' => ['nullable', 'string', 'max:65535'],      // Optional first item body
            'items' => ['array'],                                // optional multi-items
            'items.*.heading' => ['nullable', 'string', 'max:255'],
            'items.*.body' => ['required', 'string', 'max:65535'],
        ]);

        $personality = new Personality();
        $personality->user_id = auth()->id();
        $personality->name = $data['title'];         // map "title" -> name
        $personality->description = null;            // optional; you can add a textarea later
        $personality->save();

        $order = 1;

        // Item from the single Content box (for backward compatibility)
        if (!empty($data['content'])) {
            PersonalityItem::create([
                'personality_id' => $personality->id,
                'heading'        => 'Details',
                'body'           => $data['content'],
                'order'          => $order++,
            ]);
        }

        // Additional FAQ-style items (if provided)
        if (!empty($data['items'])) {
            foreach ($data['items'] as $item) {
                if (!isset($item['body']) || trim($item['body']) === '') continue;
                PersonalityItem::create([
                    'personality_id' => $personality->id,
                    'heading'        => $item['heading'] ?? null,
                    'body'           => $item['body'],
                    'order'          => $order++,
                ]);
            }
        }

        return redirect('/dashboard/train-bot/page-list')->with('success', 'Personality created successfully.');
    }

    /** GET /dashboard/train-bot/page-list  (List Personalities) */
    public function index()
    {
        $personalities = Personality::where('user_id', auth()->id())
            ->withCount('items')
            ->latest()
            ->get();

        // Temporarily reuse your page_list blade (it prints $pages).
        // Map the variable name so the existing blade works without changes.
        $pages = $personalities->map(function ($p) {
            return (object)[
                'id' => $p->id,
                'title' => $p->name . ($p->items_count ? " ({$p->items_count})" : ''),
            ];
        });

        return view('dashboard.page_list', compact('pages'));
    }

    /** GET /dashboard/train-bot/page-list/{id}  (Edit Personality + items) */
    public function edit($id)
    {
        $personality = Personality::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['items' => function ($q) {
                $q->orderBy('order');
            }])
            ->firstOrFail();

        // Temporarily reuse page_edit blade (expects $page->title and $page->content)
        // We'll present the first item body in the textarea; others can be added via JS later.
        $firstItem = $personality->items->first();
        $page = (object)[
            'id'      => $personality->id,
            'title'   => $personality->name,
            'content' => $firstItem?->body ?? '',
        ];

        return view('dashboard.page_edit', compact('page', 'personality'));
    }

    /** POST /dashboard/train-bot/page-list/{id}  (Update Personality + items) */
    public function update(Request $request, $id)
    {
        $personality = Personality::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],          // new name
            'content' => ['nullable', 'string', 'max:65535'],      // overwrite first item's body
            // optional arrays for full edit UI:
            'items' => ['array'],
            'items.*.id' => ['nullable', 'integer', 'exists:personality_items,id'],
            'items.*.heading' => ['nullable', 'string', 'max:255'],
            'items.*.body' => ['required', 'string', 'max:65535'],
            'items.*.order' => ['nullable', 'integer', 'min:1'],
            'delete_item_ids' => ['array'],
            'delete_item_ids.*' => ['integer', 'exists:personality_items,id'],
        ]);

        $personality->name = $data['title'];
        $personality->save();

        // Backward-compat: ensure there is a "first item" representing the old textarea content
        $firstItem = $personality->items()->orderBy('order')->first();
        if (!empty($data['content'])) {
            if ($firstItem) {
                $firstItem->update(['body' => $data['content']]);
            } else {
                $firstItem = PersonalityItem::create([
                    'personality_id' => $personality->id,
                    'heading'        => 'Details',
                    'body'           => $data['content'],
                    'order'          => 1,
                ]);
            }
        }

        // Process deletes (from multi-item UI)
        if (!empty($data['delete_item_ids'])) {
            PersonalityItem::whereIn('id', $data['delete_item_ids'])
                ->where('personality_id', $personality->id)
                ->delete();
        }

        // Upsert additional items
        if (!empty($data['items'])) {
            foreach ($data['items'] as $row) {
                // Skip if empty body
                if (!isset($row['body']) || trim($row['body']) === '') continue;

                $payload = [
                    'heading' => $row['heading'] ?? null,
                    'body'    => $row['body'],
                ];
                if (isset($row['order'])) $payload['order'] = (int)$row['order'];

                if (!empty($row['id'])) {
                    // update existing
                    PersonalityItem::where('id', $row['id'])
                        ->where('personality_id', $personality->id)
                        ->update($payload);
                } else {
                    // create new
                    $payload['personality_id'] = $personality->id;
                    $payload['order'] = $payload['order'] ?? (($personality->items()->max('order') ?? 0) + 1);
                    PersonalityItem::create($payload);
                }
            }
        }

        return redirect('/dashboard/train-bot/page-list')->with('success', 'Personality updated successfully.');
    }

    /** DELETE /dashboard/train-bot/page-list/{id}  (Delete Personality) */
    public function destroy($id)
    {
        $personality = Personality::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $personality->items()->delete();
        $personality->delete();

        return response()->json(['success' => true, 'message' => 'Personality deleted successfully']);
    }
}
