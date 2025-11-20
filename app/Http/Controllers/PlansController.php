<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserDailyUsage;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    // User-facing plans page
    public function index()
    {
        $user = auth()->user();
        $plans = Plan::orderBy('id')->get();

        $widgetCount      = $user->widgets()->count();
        $widgetLimit      = $user->widgetLimit();
        $personalityCount = $user->personalities()->count();
        $personalityLimit = $user->personalityLimit();
        $dailyPromptLimit = $user->dailyPromptLimit();

        $today = now()->toDateString();
        $todayUsage = UserDailyUsage::where('user_id', $user->id)
            ->where('date', $today)
            ->value('prompt_count') ?? 0;

        return view('plans.index', compact(
            'user',
            'plans',
            'widgetCount',
            'widgetLimit',
            'personalityCount',
            'personalityLimit',
            'dailyPromptLimit',
            'todayUsage'
        ));
    }

    // Admin-only list + edit page
    public function adminIndex()
    {
        $user = auth()->user();
        abort_unless($user && $user->isAdmin(), 403);

        $plans = Plan::orderBy('id')->get();

        return view('dashboard.plans.index', compact('plans'));
    }

    // Admin updates a plan (name + numeric limits)
    public function update(Request $request, Plan $plan)
    {
        $user = auth()->user();
        abort_unless($user && $user->isAdmin(), 403);

        $data = $request->validate([
            'name'               => ['required', 'string', 'max:50'],
            'widget_limit'       => ['required', 'integer', 'min:0'],
            'personality_limit'  => ['required', 'integer', 'min:0'],
            'daily_prompt_limit' => ['required', 'integer', 'min:0'],
        ]);

        $plan->update($data);

        return back()->with('success', 'Plan updated successfully.');
    }
}
