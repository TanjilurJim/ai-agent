<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Widget;
use App\Models\ChatSession;
use App\Models\User;

class WidgetLiveController extends Controller
{
    //
    public function live(Widget $widget)
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $widget->user_id === $user->id, 403);

        return view('dashboard.widget.live', compact('widget'));
    }

    public function logs(Widget $widget)
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $widget->user_id === $user->id, 403);

        $sessions = ChatSession::where('widget_id', $widget->id)
            ->with(['messages' => fn($q) => $q->latest()])
            ->latest()
            ->paginate(20);

        return view('dashboard.widget.logs', compact('widget','sessions'));
    }

}
