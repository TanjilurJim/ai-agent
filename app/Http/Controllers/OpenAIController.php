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


    public function chat(Request $request, string $api_key)
    {
        $request->validate([
            'message' => ['required', 'string'],
        ]);

        // Resolve the widget first (api_key is on widgets)
        $widget = Widget::where('api_key', $api_key)->first();
        if (!$widget) {
            return response()->json(['reply' => 'Invalid API key.'], 404);
        }
        if (!$widget->is_active) {
            return response()->json(['reply' => 'Widget is inactive.']);
        }

        // 1) Subscriber + guardrails
        $subscriber = Subscription::where('api_key', $api_key)->first();
        if (!$subscriber) {
            return response()->json(['reply' => 'Invalid API key.'], 404);
        }
        if ($subscriber->status !== 'active') {
            return response()->json(['reply' => 'Subscription limit reached or inactive.']);
        }

        // 2) About text (optional)
        // $about = BotContent::where('user_id', $subscriber->user_id)->value('content') ?? '';
        $about = BotContent::where('user_id', $widget->user_id)->value('content') ?? '';

        // 3) Personalities + items for this user
        $personality = $widget->personality
            ? $widget->personality->load(['items' => fn($q) => $q->orderBy('order')])
            : Personality::where('user_id', $widget->user_id)
            ->with(['items' => fn($q) => $q->orderBy('order')])
            ->latest()
            ->first();

        $ctx = [];
        if ($about !== '') {
            $ctx[] = "ABOUT THE BUSINESS:\n" . trim($about);
        }

        if ($personality) {
            $ctx[] = "PERSONALITY: {$personality->name}";
            foreach ($personality->items as $it) {
                $heading = $it->heading ? " - {$it->heading}" : '';
                $ctx[] = "•{$heading}\n" . trim($it->body);
            }
        }

        $contextBlock = trim(implode("\n\n", $ctx));

        $system = trim("
- Answer using only the provided business information below.
- If unsure, say your knowledge is limited to the provided data.
- If there are multiple questions, answer them one by one.
- Refuse to tell jokes unless a 'Humor' personality explicitly provides them.

$contextBlock
    ");

        $payload = [
            'model' => 'deepseek-chat',
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user',   'content' => $request->string('message')],
            ],
        ];

        $response = \Illuminate\Support\Facades\Http::withHeaders([
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
        return response()->json(['reply' => $reply]);
    }













    public function widget($api_key)
    {
        $bot = Widget::where('api_key', $api_key)->first();
        if ($bot) {
            return response()->json(['status' => 'success', 'bot' => $bot]);
        }
        return response()->json(['status' => 'error', 'message' => 'Bot not found'], 404);
    }
}
