<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageCreated;
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

        return view('dashboard.widget.logs', compact('widget', 'sessions'));
    }

    // 🔽 NEW: operator reply
    public function operatorReply(Request $request, Widget $widget, $session)
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $widget->user_id === $user->id, 403);

        // 🔑 Resolve by numeric PK or session UUID
        $sessionModel = is_numeric($session)
            ? ChatSession::findOrFail($session)
            : ChatSession::where('session_id', $session)->firstOrFail();

        abort_unless($sessionModel->widget_id === $widget->id, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:6000'],
        ]);

        $msg = $sessionModel->messages()->create([
            'role'    => 'operator',
            'content' => $data['message'],
        ]);

        event(new MessageCreated($msg));

        return response()->json(['ok' => true]);
    }


    // 🔽 NEW: pause / resume bot on a session
    public function togglePause(Request $request, Widget $widget, $session)
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $widget->user_id === $user->id, 403);

        // 🔑 Resolve by numeric PK or session UUID
        $sessionModel = is_numeric($session)
            ? ChatSession::findOrFail($session)
            : ChatSession::where('session_id', $session)->firstOrFail();

        abort_unless($sessionModel->widget_id === $widget->id, 404);

        $pause = $request->boolean('pause');
        if ($pause) {
            $minutes = max(1, (int) $request->input('minutes', 30));
            $sessionModel->bot_paused_until = now()->addMinutes($minutes);
            $sessionModel->paused_by_user_id = $user->id;
        } else {
            $sessionModel->bot_paused_until = null;
            $sessionModel->paused_by_user_id = null;
        }
        $sessionModel->save();

        return response()->json([
            'ok'           => true,
            'paused_until' => optional($sessionModel->bot_paused_until)->toISOString(),
        ]);
    }
}
