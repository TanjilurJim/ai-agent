<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageCreated;
use App\Models\Widget;
use App\Models\ChatSession;
use App\Models\User;
use App\Models\Lead;

class WidgetLiveController extends Controller
{
    //
    public function live(Widget $widget)
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $widget->user_id === $user->id, 403);

        return view('dashboard.widget.live', compact('widget'));
    }

    public function logs(Request $request, Widget $widget)
    {
        $user = auth()->user();
        abort_unless($user->isAdmin() || $widget->user_id === $user->id, 403);

        // ✅ this is the bug fix
        $isLeads = $request->query('filter') === 'leads';
        // or: $isLeads = (string)$request->string('filter') === 'leads';

        $sessions = ChatSession::where('widget_id', $widget->id)
            ->with(['messages' => fn($q) => $q->latest()])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // counts for the sidebar
        $counts = [
            'unattended' => $sessions->getCollection()
                ->filter(fn($s) => ! $s->messages->firstWhere('role', 'operator'))->count(),
            'open'       => $sessions->getCollection()
                ->filter(fn($s) => is_null($s->closed_at))->count(),
            'closed'     => $sessions->getCollection()
                ->filter(fn($s) => ! is_null($s->closed_at))->count(),
        ];

        // leads (with session eager-loaded for “Open conversation”)
        $leads = Lead::where('widget_id', $widget->id)
            ->with(['session:id,session_id'])
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $leadCount = $leads->total();

        // which item is selected?
        $selectedId = $selectedLeadId = null;
        $selected = $selectedLead = null;

        if ($isLeads) {
            $selectedLeadId = (int) $request->query('lead');
            $selectedLead   = $leads->getCollection()->firstWhere('id', $selectedLeadId)
                ?? $leads->getCollection()->first();
            $selectedLeadId = $selectedLead?->id;
        } else {
            $selectedId = (int) $request->query('session');
            $selected   = $sessions->getCollection()->firstWhere('id', $selectedId)
                ?? $sessions->getCollection()->first();
            $selectedId = $selected?->id;
        }

        return view('dashboard.widget.logs', compact(
            'widget',
            'sessions',
            'leads',
            'leadCount',
            'counts',
            'isLeads',
            'selected',
            'selectedId',
            'selectedLead',
            'selectedLeadId'
        ));
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
