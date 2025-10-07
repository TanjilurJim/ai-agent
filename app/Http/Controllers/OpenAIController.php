<?php

namespace App\Http\Controllers;

use App\Models\Bot;
use App\Models\Widget;
use App\Models\BotContent;
use App\Models\Page;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Personality;
use App\Models\PersonalityItem;
use App\Events\MessageCreated;
use App\Models\Lead;
use App\Models\ChatSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChatTranscriptMail;

class OpenAIController extends Controller
{


    /**
     * The authorization token for the API.
     *
     * @var string
     */
    private $authorization;

    /**
     * The endpoint URL for the OpenAI API.
     *
     * @var string
     */
    private $endpoint;

    /**
     * ChatBotController constructor.
     */
    public function __construct()
    {
        // Retrieve the token from the .env file
        $this->authorization = env('OPENAI_API_KEY');
        $this->endpoint = 'https://api.deepseek.com/chat/completions';
    }

    /**
     * Handle sending a message to the ChatBot.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function start(Request $request, string $api_key)
    {
        $data = $request->validate([
            'name'   => ['required', 'string', 'max:120'],
            'mobile' => ['required', 'string', 'max:40'],
            'email'  => ['nullable', 'email', 'max:120'],
        ]);

        $widget = Widget::where('api_key', $api_key)->firstOrFail();

        $session = ChatSession::firstOrCreate(
            // keys to find an existing session; we always generate a new UUID once
            ['session_id' => (string) Str::uuid()],
            [
                'api_key'    => $api_key,
                'widget_id'  => $widget->id,
                'ip'         => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
                'name'       => $data['name'],
                'mobile'     => $data['mobile'],
                'email'      => $data['email'] ?? null,
            ]
        );

        // (optional) persist to leads table
        Lead::updateOrCreate(
            ['session_id' => $session->session_id],
            [
                'api_key'   => $api_key,
                'widget_id' => $widget->id,
                'name'      => $session->name,
                'mobile'    => $session->mobile,
                'email'     => $session->email,
            ]
        );

        $greetingText = "Thanks, {$session->name}! How can I help you today?";
        $greet = $session->messages()->create([
            'role'    => 'assistant',
            'content' => $greetingText,
        ]);
        event(new MessageCreated($greet));

        return response()->json([
            'ok'         => true,
            'session_id' => $session->session_id,
            'greeting'   => $greetingText,
        ]);
    }

    public function chat(Request $request, string $api_key)
    {
        $request->validate([
            'message'    => ['required', 'string'],
            'session_id' => ['required', 'uuid'],
        ]);

        $widget = Widget::where('api_key', $api_key)
            ->with([
                'personalities' => fn($q) => $q->orderBy('personality_widget.order'),
                'personalities.items' => fn($q) => $q->orderBy('order'),
            ])->first();

        if (!$widget) return response()->json(['reply' => 'Invalid API key.'], 404);
        if (!$widget->is_active) return response()->json(['reply' => 'Widget is inactive.']);

        $subscriber = Subscription::where('api_key', $api_key)->first();
        if (!$subscriber) return response()->json(['reply' => 'Invalid API key.'], 404);
        if ($subscriber->status !== 'active')
            return response()->json(['reply' => 'Subscription limit reached or inactive.']);

        $session = ChatSession::where([
            'api_key'    => $api_key,
            'session_id' => $request->string('session_id'),
        ])->first();

        if (!$session) return response()->json(['reply' => 'Please start the chat first.'], 422);

        // ── NEW: pull user text early + pause guard ────────────────────────────────
        $userText = $request->string('message');

        if ($session->bot_paused_until && now()->lt($session->bot_paused_until)) {
            // store user msg and broadcast
            $userMsg = $session->messages()->create([
                'role'    => 'user',
                'content' => $userText,
            ]);
            event(new MessageCreated($userMsg));

            $notice = "A human operator is replying — the bot is paused.";
            $botMsg = $session->messages()->create([
                'role'    => 'assistant',
                'content' => $notice,
            ]);
            event(new MessageCreated($botMsg));

            return response()->json(['reply' => $notice]);
        }
        // ──────────────────────────────────────────────────────────────────────────

        // ---- Build system/context ----
        $about = BotContent::where('user_id', $widget->user_id)->value('content') ?? '';

        $ctx = [];
        if ($about !== '') $ctx[] = "ABOUT THE BUSINESS:\n" . trim($about);
        $ctx[] = "USER PROFILE:\n"
            . "Name: "   . ($session->name   ?: 'N/A') . "\n"
            . "Mobile: " . ($session->mobile ?: 'N/A') . "\n"
            . "Email: "  . ($session->email  ?: 'N/A');

        if ($widget->personalities->isNotEmpty()) {
            foreach ($widget->personalities as $p) {
                $ctx[] = "PERSONALITY: {$p->name}";
                foreach ($p->items as $it) {
                    $heading = $it->heading ? " - {$it->heading}" : '';
                    $ctx[] = "•{$heading}\n" . trim($it->body);
                }
            }
        } else {
            $fallback = Personality::where('user_id', $widget->user_id)
                ->with(['items' => fn($q) => $q->orderBy('order')])
                ->latest()->first();
            if ($fallback) {
                $ctx[] = "PERSONALITY: {$fallback->name}";
                foreach ($fallback->items as $it) {
                    $heading = $it->heading ? " - {$it->heading}" : '';
                    $ctx[] = "•{$heading}\n" . trim($it->body);
                }
            }
        }

        $system = "- Answer using only the provided business information below.\n"
            . "- If unsure, say your knowledge is limited to the provided data.\n"
            . "- If there are multiple questions, answer them one by one.\n"
            . "- Refuse to tell jokes unless a 'Humor' personality explicitly provides them.\n"
            . "- Search google or website only if a personality explicitly provides instructions.\n\n"
            . implode("\n\n", $ctx);

        // ---- Short history BEFORE saving new user message ----
        $history = $session->messages()
            ->latest('id')->take(6)->get()->reverse()
            ->filter(fn($m) => in_array($m->role, ['user', 'assistant'])) // exclude 'operator'
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
            ->values()->all();


        $messages = array_merge(
            [['role' => 'system', 'content' => $system]],
            $history,
            [['role' => 'user', 'content' => $userText]]
        );

        // ---- Call model ----
        $payload = ['model' => 'deepseek-chat', 'messages' => $messages];

        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Authorization' => 'Bearer ' . $this->authorization,
        ])->post($this->endpoint, $payload);

        if ($response->failed()) {
            return response()->json([
                'error'  => 'Error contacting model.',
                'detail' => $response->body(),
            ], 500);
        }

        $reply = data_get($response->json(), 'choices.0.message.content', 'Sorry, something went wrong.');

        // ---- Persist both messages (now) + broadcast ----
        $userMsg = $session->messages()->create([
            'role'    => 'user',
            'content' => $userText,
        ]);
        event(new MessageCreated($userMsg));

        $botMsg = $session->messages()->create([
            'role'    => 'assistant',
            'content' => $reply,
        ]);
        event(new MessageCreated($botMsg));

        return response()->json(['reply' => $reply]);
    }



    public function widget($api_key)
    {
        $w = Widget::where('api_key', $api_key)->first();
        if (!$w) return response()->json(['status' => 'error', 'message' => 'Bot not found'], 404);

        return response()->json([
            'status' => 'success',
            'bot' => [
                'name'           => $w->name,
                'widgetName'     => $w->widgetName,
                'welcomeMessage' => $w->welcomeMessage,
                'color'          => $w->color,
                'avatar'         => $w->avatar_url,   // absolute
                'requireLead'    => true,             // or from DB/setting
            ],
        ]);
    }

    public function emailTranscript(Request $request, string $api_key)
    {
        $data = $request->validate([
            'to'         => ['required', 'email', 'max:190'],
            'transcript' => ['required', 'string'],
            'session_id' => ['nullable', 'string', 'max:100'],
        ]);

        $widget = \App\Models\Widget::where('api_key', $api_key)->firstOrFail();

        // (optional) same subscription guard you use in chat()
        $subscriber = \App\Models\Subscription::where('api_key', $api_key)->first();
        if (!$subscriber || $subscriber->status !== 'active') {
            return response()->json(['ok' => false, 'error' => 'inactive'], 403);
        }

        Mail::to($data['to'])->send(
            new ChatTranscriptMail(
                $widget->widgetName ?? $widget->name ?? 'Widget',
                $data['session_id'] ?? 'preview',
                $data['transcript']
            )
        );

        return response()->json(['ok' => true]);
    }
}
