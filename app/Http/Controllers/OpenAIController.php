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

    //working one

    //     public function chat(Request $request, string $api_key)
    //     {
    //         $request->validate([
    //             'message'    => ['required', 'string'],
    //             'session_id' => ['required', 'uuid'],   // ⬅️ now required
    //         ]);

    //         // Resolve widget by api_key
    //         $widget = Widget::where('api_key', $api_key)
    //             ->with([
    //                 'personalities' => fn($q) => $q->orderBy('personality_widget.order'),
    //                 'personalities.items' => fn($q) => $q->orderBy('order'),
    //             ])
    //             ->first();

    //         if (!$widget) return response()->json(['reply' => 'Invalid API key.'], 404);
    //         if (!$widget->is_active) return response()->json(['reply' => 'Widget is inactive.']);

    //         // Subscription guard
    //         $subscriber = Subscription::where('api_key', $api_key)->first();
    //         if (!$subscriber) return response()->json(['reply' => 'Invalid API key.'], 404);
    //         if ($subscriber->status !== 'active') return response()->json(['reply' => 'Subscription limit reached or inactive.']);

    //         // Validate session (created by /start)
    //         $session = \App\Models\ChatSession::where([
    //             'api_key'    => $api_key,
    //             'session_id' => $request->string('session_id'),
    //         ])->first();

    //         if (!$session) {
    //             return response()->json(['reply' => 'Please start the chat first.'], 422);
    //         }

    //         // Business content
    //         $about = \App\Models\BotContent::where('user_id', $widget->user_id)->value('content') ?? '';

    //         // Build context
    //         $ctx = [];
    //         if ($about !== '') {
    //             $ctx[] = "ABOUT THE BUSINESS:\n" . trim($about);
    //         }

    //         // Personalize with lead info from the session
    //         $ctx[] = "USER PROFILE:\n"
    //             . "Name: "   . ($session->name   ?: 'N/A') . "\n"
    //             . "Mobile: " . ($session->mobile ?: 'N/A') . "\n"
    //             . "Email: "  . ($session->email  ?: 'N/A');

    //         // Personalities (multi)
    //         if ($widget->personalities->isNotEmpty()) {
    //             foreach ($widget->personalities as $p) {
    //                 $ctx[] = "PERSONALITY: {$p->name}";
    //                 foreach ($p->items as $it) {
    //                     $heading = $it->heading ? " - {$it->heading}" : '';
    //                     $ctx[] = "•{$heading}\n" . trim($it->body);
    //                 }
    //             }
    //         } else {
    //             // optional fallback to latest persona for this user
    //             $fallback = \App\Models\Personality::where('user_id', $widget->user_id)
    //                 ->with(['items' => fn($q) => $q->orderBy('order')])
    //                 ->latest()->first();
    //             if ($fallback) {
    //                 $ctx[] = "PERSONALITY: {$fallback->name}";
    //                 foreach ($fallback->items as $it) {
    //                     $heading = $it->heading ? " - {$it->heading}" : '';
    //                     $ctx[] = "•{$heading}\n" . trim($it->body);
    //                 }
    //             }
    //         }

    //         $contextBlock = trim(implode("\n\n", $ctx));

    //         $system = trim("
    // - Answer using only the provided business information below.
    // - If unsure, say your knowledge is limited to the provided data.
    // - If there are multiple questions, answer them one by one.
    // - Refuse to tell jokes unless a 'Humor' personality explicitly provides them.
    // - Search google or website only if a personality explicitly provides instructions.

    // $contextBlock
    //     ");

    //         // (Optional) include short history for continuity (last 6 messages)
    //         $history = $session->messages()
    //             ->latest('id')->take(6)->get()->reverse()
    //             ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
    //             ->values()->all();

    //         // Store the user message now
    //         \App\Models\ChatMessage::create([
    //             'chat_session_id' => $session->id,
    //             'role'            => 'user',
    //             'content'         => $request->string('message'),
    //         ]);

    //         $messages = array_merge(
    //             [['role' => 'system', 'content' => $system]],
    //             $history,
    //             [['role' => 'user', 'content' => $request->string('message')]]
    //         );

    //         // Call the model (DeepSeek)
    //         $payload = [
    //             'model'    => 'deepseek-chat',
    //             'messages' => $messages,
    //         ];

    //         $response = \Illuminate\Support\Facades\Http::withHeaders([
    //             'Content-Type'  => 'application/json',
    //             'Authorization' => 'Bearer ' . $this->authorization,
    //         ])->post($this->endpoint, $payload);

    //         if ($response->failed()) {
    //             return response()->json([
    //                 'error'  => 'Error contacting model.',
    //                 'detail' => $response->body(),
    //             ], 500);
    //         }

    //         $reply = data_get($response->json(), 'choices.0.message.content', 'Sorry, something went wrong.');

    //         // Store assistant reply
    //         \App\Models\ChatMessage::create([
    //             'chat_session_id' => $session->id,
    //             'role'            => 'assistant',
    //             'content'         => $reply,
    //         ]);

    //         return response()->json(['reply' => $reply]);
    //     }

    //    public function widget($api_key)
    // {
    //     $bot = Widget::where('api_key', $api_key)->first();
    //     if ($bot) {
    //         return response()->json(['status' => 'success', 'bot' => $bot]);
    //     }
    //     return response()->json(['status' => 'error', 'message' => 'Bot not found'], 404);
    // }

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

        if (!$widget)     return response()->json(['reply' => 'Invalid API key.'], 404);
        if (!$widget->is_active) return response()->json(['reply' => 'Widget is inactive.']);

        $subscriber = Subscription::where('api_key', $api_key)->first();
        if (!$subscriber)                 return response()->json(['reply' => 'Invalid API key.'], 404);
        if ($subscriber->status !== 'active') return response()->json(['reply' => 'Subscription limit reached or inactive.']);

        $session = ChatSession::where([
            'api_key'    => $api_key,
            'session_id' => $request->string('session_id'),
        ])->first();

        if (!$session) return response()->json(['reply' => 'Please start the chat first.'], 422);

        // ---- Build system/context ----
        $about = BotContent::where('user_id', $widget->user_id)->value('content') ?? '';

        $ctx = [];
        if ($about !== '') {
            $ctx[] = "ABOUT THE BUSINESS:\n" . trim($about);
        }
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

        // ---- Get short history BEFORE saving new user message ----
        $history = $session->messages()
            ->latest('id')->take(6)->get()->reverse()
            ->map(fn($m) => ['role' => $m->role, 'content' => $m->content])
            ->values()->all();

        $userText = $request->string('message');

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
}
