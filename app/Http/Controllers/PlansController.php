<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\UserDailyUsage;
use Illuminate\Http\Request;
use App\Models\PlanRequest;

use App\Models\User;
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

    public function request(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'plan_id'        => ['required', 'exists:plans,id'],
            'contact_number' => ['required', 'string', 'max:50'],
        ]);

        // Avoid multiple pending for same plan (optional)
        $alreadyPending = PlanRequest::where('user_id', $user->id)
            ->where('requested_plan_id', $data['plan_id'])
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', 'You already have a pending request for this plan.');
        }

        PlanRequest::create([
            'user_id'          => $user->id,
            'current_plan_id'  => $user->plan_id,
            'requested_plan_id' => $data['plan_id'],
            'contact_number'   => $data['contact_number'],
            'status'           => 'pending',
        ]);

        return back()->with('success', 'Your plan upgrade request has been sent to the admin.');
    }

    public function approve(PlanRequest $planRequest)
    {
        $admin = auth()->user();
        abort_unless($admin->isAdmin(), 403);

        if ($planRequest->status !== 'pending') {
            return back()->with('error', 'This request is not pending.');
        }

        $user = $planRequest->user;
        if (! $user) {
            return back()->with('error', 'User not found for this request.');
        }

        // Apply requested plan for 1 month
        $user->plan_id          = $planRequest->requested_plan_id;
        $user->plan_started_at  = now();
        $user->plan_expires_at  = now()->addMonth();
        $user->plan_auto_renews = false;
        $user->save();

        $planRequest->status              = 'approved';
        $planRequest->decided_by_user_id  = $admin->id;
        $planRequest->decided_at          = now();
        $planRequest->save();

        return redirect()->route('user.show', $user->id)
            ->with('success', 'Plan request approved and user plan updated.');
    }

    public function reject(PlanRequest $planRequest)
    {
        $admin = auth()->user();
        abort_unless($admin->isAdmin(), 403);

        if ($planRequest->status !== 'pending') {
            return back()->with('error', 'This request is not pending.');
        }

        $planRequest->status              = 'rejected';
        $planRequest->decided_by_user_id  = $admin->id;
        $planRequest->decided_at          = now();
        $planRequest->save();

        return back()->with('success', 'Plan request rejected.');
    }
}
