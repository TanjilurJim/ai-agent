<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Bot;
use App\Models\BotContent;
use App\Models\Page;
use App\Models\Subscription;
use App\Models\Train;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\UserDailyUsage;
use App\Models\Plan;


class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        // If NOT admin → show user dashboard
        if (! $user->isAdmin()) {
            $plan = $user->plan; // may be null if something's off, we’ll guard in blade

            $widgetCount       = $user->widgets()->count();
            $widgetLimit       = $user->widgetLimit();

            $personalityCount  = $user->personalities()->count();
            $personalityLimit  = $user->personalityLimit();

            $dailyPromptLimit  = $user->dailyPromptLimit();
            $today             = now()->toDateString();
            $todayUsage        = UserDailyUsage::where('user_id', $user->id)
                ->where('date', $today)
                ->value('prompt_count') ?? 0;

            return view('dashboard.user-dashboard', compact(
                'user',
                'plan',
                'widgetCount',
                'widgetLimit',
                'personalityCount',
                'personalityLimit',
                'dailyPromptLimit',
                'todayUsage'
            ));
        }

        // Total users
        $totalUsers = User::count();

        // New users in the last 7 days
        $newUsersThisWeek = User::where('created_at', '>=', now()->subDays(7))->count();

        // Total widget request count (using Subscription as "widget requests")
        $totalWidgetRequests = Subscription::count();

        // Group by api_key (because your Subscription -> widget relation uses api_key)
        $widgetRequestsByWidget = Subscription::select('api_key', DB::raw('count(*) as requests_count'))
            ->groupBy('api_key')
            ->get();

        // eager-load widget for each aggregated row (uses the model relation)
        // This will attach 'widget' relation to each Subscription-like aggregate
        $widgetRequestsByWidget->loadMissing('widget');

        return view('dashboard.index', compact(
            'totalUsers',
            'newUsersThisWeek',
            'totalWidgetRequests',
            'widgetRequestsByWidget'
        ));
    }



    public function make()
    {
        $bot = Bot::where('user_id', auth()->id())->first();
        return  view('dashboard.makebot', compact('bot'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'botName' => 'required|string|max:255',
            'botDescription' => 'required|string',
            'botToken' => 'required|string|max:255',
            'botColor' => 'required',
        ]);

        $bot = Bot::where('user_id', auth()->user()->id)->first() ?: new Bot();
        $bot->botName = $request->botName;
        $bot->botDescription = $request->botDescription;
        $bot->botToken = $request->botToken;
        $bot->botColor = $request->botColor;
        $bot->user_id = auth()->user()->id;
        if ($request->hasFile('botAvatar')) {
            $file = $request->file('botAvatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = 'uploads';
            $file->move(public_path($filePath), $filename);
            $bot->botAvatar = $filePath . '/' . $filename;
        }
        $bot->save();
        return redirect()->route('dashboard.index')->with('success', 'Bot created successfully.');
    }



    public function train()
    {
        $content = BotContent::firstOrNew(['user_id' => auth()->id()]);
        return view(('dashboard.train'), compact('content'));
    }
    public function page()
    {
        return view('dashboard.page');
    }


    public function page_store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'title' => 'required',
        ]);

        $page = new Page();
        $page->content = $request->input('content');
        $page->title = $request->input('title');
        $page->user_id = auth()->id();
        $page->save();
        return redirect('/dashboard/train-bot/page-list')->with('success', 'Page create Successfull');
    }


    public function page_list()
    {
        $user_id = auth()->id();
        $pages = Page::where('user_id', $user_id)->get();
        return view('dashboard.page_list', compact('pages'));
    }


    public function page_list_edit($id)
    {
        $page = Page::where('id', $id)->where('user_id', auth()->id())->first();

        return view('dashboard.page_edit', compact('page'));
    }
    public function page_list_edit_store(Request $request, $id)
    {
        $page = Page::where('id', $id)->where('user_id', auth()->id())->first();
        $page->content = $request->input('content');
        $page->title = $request->input('title');
        $page->user_id = auth()->id();
        $page->save();
        return redirect('/dashboard/train-bot/page-list')->with('success', 'Page update Successfull');
    }


    public function destroyPage($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json(['success' => true, 'message' => 'Page deleted successfully']);
    }





    public function train_store(Request $request)
    {
        $request->validate([
            'content' => 'required',
        ]);

        $user_id = auth()->user()->id;
        $content = BotContent::firstOrNew(['user_id' => $user_id]);
        $content->user_id = $user_id;
        $content->content = $request->content;
        $content->save();
        return redirect()->back()->with('success', 'Content update successful');
    }



    public function train_edit($id)
    {
        $train = Train::find($id);
        return view('dashboard.train_edit', compact('train'));
    }


    public function train_update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'description' => 'required|string',
        ]);


        $train =  Train::find($id);
        $train->question = $request->input('question');
        $train->answer = $request->input('answer');
        $train->description = $request->input('description');
        $train->user_id = auth()->user()->id;
        $train->save();
        return redirect()->back()->with('success', 'Create Successfull');
    }




    public function user(Request $request)
    {
        $admin = auth()->user();
        abort_unless($admin && $admin->isAdmin(), 403);

        $plans = Plan::orderBy('id')->get();

        $query = User::with('plan')->orderByDesc('created_at');

        // Filters
        $search = $request->string('search')->trim();
        $planId = $request->input('plan_id');
        $role   = $request->input('role');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($planId && $planId !== 'all') {
            $query->where('plan_id', $planId);
        }

        if ($role && $role !== 'all') {
            $query->where('role', $role);
        }

        $users = $query->paginate(15)->withQueryString();

        $filters = [
            'search' => $search,
            'plan_id' => $planId,
            'role' => $role,
        ];

        return view('dashboard.user', compact('users', 'plans', 'filters'));
    }
    public function updateUserPlan(Request $request, $id)
    {
        $admin = auth()->user();
        abort_unless($admin && $admin->isAdmin(), 403);

        $data = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $user = User::findOrFail($id);
        $user->plan_id = $data['plan_id'];
        $user->save();

        return back()->with('success', 'User plan updated successfully.');
    }



    public function user_show($id)
    {
        $admin = auth()->user();
        abort_unless($admin && $admin->isAdmin(), 403);

        // Eager-load widgets, subscriptions, plan, personalities
        $user = User::with(['widgets', 'subscriptions', 'plan', 'personalities'])->find($id);

        if (! $user) {
            return redirect()->route('dashboard.user')
                ->with('error', 'User not found.');
        }

        $widgetCount       = $user->widgets->count();
        $subscriptionCount = $user->subscriptions->count();
        $recentWidgets     = $user->widgets->sortByDesc('created_at')->take(7);

        // Plan + usage info
        $widgetLimit       = $user->widgetLimit();
        $personalityCount  = $user->personalities->count();
        $personalityLimit  = $user->personalityLimit();
        $dailyPromptLimit  = $user->dailyPromptLimit();

        $today = now()->toDateString();
        $todayUsage = UserDailyUsage::where('user_id', $user->id)
            ->where('date', $today)
            ->value('prompt_count') ?? 0;

        // All plans for the change-plan dropdown
        $plans = Plan::orderBy('id')->get();

        return view('dashboard.user-view', compact(
            'user',
            'widgetCount',
            'subscriptionCount',
            'recentWidgets',
            'widgetLimit',
            'personalityCount',
            'personalityLimit',
            'dailyPromptLimit',
            'todayUsage',
            'plans'
        ));
    }



    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return back()->with('success', 'User Delete successfull');
    }


    public function subscriber()
    {
        $subscribers = Subscription::with(['user', 'widget:id,api_key,name'])
            ->latest()
            ->paginate(10);

        return view('dashboard.subscriber', compact('subscribers'));
    }

    public function subscriber_store(Request $request)
    {
        $subscription = new Subscription();
        $subscription->status = "Pending";
        $subscription->api_key = Str::random(20);
        $subscription->token =  10;
        $subscription->user_id = auth()->user()->id;
        $subscription->save();
        return redirect()->back()->with('success', 'Subscribed successfully.');
    }

    public function subscriber_destroy($id)
    {
        $subscriber = Subscription::find($id);
        if (!$subscriber) {
            return redirect()->back()->with('error', 'Subscriber not found.');
        }

        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }

    public function subscriber_show($id)
    {
        // eager load user and widget (uses your Subscription->widget relation)
        $subscriber = Subscription::with(['user', 'widget'])->find($id);

        if (! $subscriber) {
            return redirect()->route('dashboard.subscriber')
                ->with('error', 'Subscriber not found.');
        }

        return view('dashboard.subscriber-view', compact('subscriber'));
    }

    public function updateSubscriptionStatus(Request $request, Subscription $subscription)
    {
        $admin = auth()->user();
        abort_unless($admin->isAdmin(), 403);

        $request->validate([
            'status' => 'required|in:active,inactive,pending'
        ]);

        $subscription->status = $request->status;
        $subscription->save();

        return back()->with('success', 'API Key status updated.');
    }

    public function resetSubscriptionToken(Subscription $subscription)
    {
        $admin = auth()->user();
        abort_unless($admin->isAdmin(), 403);

        // Reset message tokens (your existing system uses "token")
        $subscription->token = 0;
        $subscription->save();

        return back()->with('success', 'Token count reset.');
    }

    public function regenerateApiKey(Subscription $subscription)
    {
        $admin = auth()->user();
        abort_unless($admin->isAdmin(), 403);

        $subscription->api_key = \Illuminate\Support\Str::random(20);
        $subscription->save();

        return back()->with('success', 'API Key regenerated.');
    }
}
