<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WidgetController extends Controller
{
    public function index()
    {
        $widgets = Widget::mine()->latest()->paginate(12);
        return view('dashboard.widget.index', compact('widgets'));
    }

    public function create()
    {
        $widget = new Widget();
        return view('dashboard.widget.edit', compact('widget'));
    }

    private function syncPersonalities(\App\Models\Widget $widget, array $ids = null, array $orders = null): void
    {
        $ids = $ids ?? [];
        $orders = $orders ?? [];

        // Ownership guard: all selected personalities must belong to this user
        $count = \App\Models\Personality::where('user_id', auth()->id())
            ->whereIn('id', $ids)->count();
        abort_unless($count === count($ids), 403);

        // Build pivot payload with order (default to 1..N)
        $payload = [];
        $i = 1;
        foreach ($ids as $pid) {
            $payload[$pid] = ['order' => isset($orders[$pid]) ? max(1, (int)$orders[$pid]) : $i++];
        }

        $widget->personalities()->sync($payload); // atomic sync
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'widgetName'     => 'nullable|string|max:255',
            'welcomeMessage' => 'required|string|max:255',
            'color'          => 'required|string|max:20',
            'avatar'         => 'nullable|image|max:2048',
            'personality_ids' => 'array',
            'personality_ids.*' => 'integer|exists:personalities,id',
            'personality_orders' => 'array',
        ]);

        $widget = new \App\Models\Widget($data);
        $widget->user_id = auth()->id();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $widget->avatar = 'uploads/' . $filename;
        }

        $widget->save();

        $this->syncPersonalities(
            $widget,
            $data['personality_ids'] ?? [],
            $data['personality_orders'] ?? []
        );

        \App\Models\Subscription::firstOrCreate(
            ['api_key' => $widget->api_key],
            ['status' => 'Pending', 'token' => 10, 'user_id' => auth()->id()]
        );

        return redirect()->route('widgets.edit', $widget)->with('success', 'Widget created successfully.');
    }




    public function edit(Widget $widget)
    {
        abort_unless($widget->user_id === auth()->id(), 403);
        return view('dashboard.widget.edit', compact('widget'));
    }

    public function update(Request $request, \App\Models\Widget $widget)
{
    abort_unless($widget->user_id === auth()->id(), 403);

    $data = $request->validate([
        'name'           => 'required|string|max:255',
        'widgetName'     => 'nullable|string|max:255',
        'welcomeMessage' => 'required|string|max:255',
        'color'          => 'required|string|max:20',
        'avatar'         => 'nullable|image|max:2048',
        'is_active'      => 'nullable|boolean',
        'personality_ids'=> 'array',
        'personality_ids.*' => 'integer|exists:personalities,id',
        'personality_orders' => 'array',
    ]);

    $widget->fill($data);

    if ($request->hasFile('avatar')) {
        $file = $request->file('avatar');
        $filename = time().'_'.\Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                    .'.'.$file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);
        $widget->avatar = 'uploads/'.$filename;
    }

    $widget->save();

    $this->syncPersonalities(
        $widget,
        $data['personality_ids'] ?? [],
        $data['personality_orders'] ?? []
    );

    return back()->with('success', 'Widget updated successfully.');
}


    public function toggle(Widget $widget)
    {
        abort_unless($widget->user_id === auth()->id(), 403);
        $widget->is_active = ! $widget->is_active;
        $widget->save();

        return back()->with('success', $widget->is_active ? 'Widget activated.' : 'Widget deactivated.');
    }

    public function destroy(Widget $widget)
    {
        abort_unless($widget->user_id === auth()->id(), 403);
        $widget->delete();
        return redirect()->route('widgets.index')->with('success', 'Widget deleted successfully.');
    }
}
