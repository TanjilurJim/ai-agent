<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WidgetController extends Controller
{
    public function make()
    {
        $widget = Widget::where('user_id', auth()->id())->first();
        return view('dashboard.makebot', compact('widget'));
    }





    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'welcomeMessage' => 'required|string',
            'color' => 'required|string|max:255',
        ]);

        $api_key = Str::random(20);

        // Check if a widget already exists for the user
        $widget = Widget::where('user_id', auth()->user()->id)->first();

        if (!$widget) {
            // Create a new Widget
            $widget = new Widget();
            $widget->api_key = $api_key;
            $subscription = new Subscription();
            $subscription->status = "Pending";
            $subscription->api_key = $api_key;
            $subscription->token = 10;
            $subscription->user_id = auth()->user()->id;
            $subscription->save();
        }

        // Update widget data (both for new and existing widgets)
        $widget->name = $request->name;
        $widget->welcomeMessage = $request->welcomeMessage;
        $widget->color = $request->color;
        $widget->widgetName = $request->widgetName;
        $widget->user_id = auth()->user()->id;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads';
            $file->move(public_path($filePath), $filename);
            $widget->avatar = asset($filePath . '/' . $filename);
        }

        $widget->save();

        return redirect()->back()->with('success', $widget->wasRecentlyCreated ? 'Widget created successfully.' : 'Widget updated successfully.');
    }

}
