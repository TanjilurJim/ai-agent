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
        $this->endpoint = 'https://api.openai.com/v1/chat/completions';
    }

    /**
     * Handle sending a message to the ChatBot.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */


    public function chat(Request $request, $api_key)
    {
        $request->validate([
            'message' => 'required|string',
        ]);


        $subscriber = Subscription::where('api_key', $api_key)->first();

        if ($subscriber->status !== "active") {
            return response()->json(['reply' => "Subscription Limit Reached"]);
        }
        $about_us = BotContent::where('user_id', $subscriber->user_id)->first();
        $page_content = Page::where('user_id', $subscriber->user_id)->get();
        $page_content_text = $page_content->pluck('content')->implode("\n\n");


        $data = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "
                        - If the message is an inquiry, answer it using only the provided information.
                        - If unsure about the answer to an inquiry, state that your knowledge is limited to the specific information provided by this business.
                        - If there are multiple inquiries in a message, answer them one by one.
                        - Refuse to tell jokes.
                          $about_us->content
                           $page_content_text
                          "
                ],
                [
                    'role' => 'user',
                    'content' => $request->message,
                ],
            ],
            'model' => 'gpt-4',
        ];

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->authorization,
        ])->post($this->endpoint, $data);

        if ($response->failed()) {
            return response()->json(['error' => 'Error sending the message: ' . $response->body()], 500);
        }
        $resultMessage = $response->json()['choices'][0]['message']['content'];
        return response()->json(['reply' => $resultMessage]);

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
