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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'widgetName'     => 'nullable|string|max:255',
            'welcomeMessage' => 'required|string|max:255',
            'color'          => 'required|string|max:20',
            'avatar'         => 'nullable|image|max:2048',
            'personality_id' => 'nullable|exists:personalities,id',
        ]);

        // Ownership guard: prevent attaching someone else’s personality
        if (!empty($data['personality_id'])) {
            abort_unless(
                \App\Models\Personality::where('id', $data['personality_id'])
                    ->where('user_id', auth()->id())
                    ->exists(),
                403
            );
        }

        $widget = new Widget($data);
        $widget->user_id = auth()->id();

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $widget->avatar = 'uploads/' . $filename; // store relative path consistently
        }

        $widget->save();

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

    public function update(Request $request, Widget $widget)
    {
        abort_unless($widget->user_id === auth()->id(), 403);

        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'widgetName'     => 'nullable|string|max:255',
            'welcomeMessage' => 'required|string|max:255',
            'color'          => 'required|string|max:20',
            'avatar'         => 'nullable|image|max:2048',
            'is_active'      => 'nullable|boolean',
            'personality_id' => 'nullable|exists:personalities,id',
        ]);

        if (!empty($data['personality_id'])) {
            abort_unless(
                \App\Models\Personality::where('id', $data['personality_id'])
                    ->where('user_id', auth()->id())
                    ->exists(),
                403
            );
        }

        $widget->fill($data);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . \Illuminate\Support\Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $widget->avatar = 'uploads/' . $filename; // keep relative
        }

        $widget->save();

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
