<?php

// namespace App\Http\Controllers;

// use App\Models\Bot;
// use App\Models\Subscription;
// use App\Models\Train;
// use App\Services\OpenAIService;
// use Illuminate\Http\Request;

// class OpenAIController extends Controller
// {
//     protected $openAIService;

//     public function __construct(OpenAIService $openAIService)
//     {
//         $this->openAIService = $openAIService;
//     }

//     public function chat(Request $request, $api_key)
//     {
//         $request->validate([
//             'message' => 'required|string',
//         ]);

//         $checksubscripetion = Subscription::where('api_key', $api_key)->where('status', 'active')->first();

//         if (!$checksubscripetion) {
//             return response()->json(['reply' => 'Subscription Limit Reached']);
//         }
//         if ($request->message == "Hi" || $request->message == "Hello") {
//             return response()->json(['reply' => 'How can i assist you?']);
//         }

//         $train = Train::where('user_id', $checksubscripetion->user_id)->get();
//         $prompt = "User asked: \"{$request->message}\". Below are some predefined questions. Find the most related question and provide its answer , or say 'No match' if none are related:\n\n";

//         foreach ($train as $item) {
//             $prompt .= "- Question: {$item->question}\n  Answer: {$item->answer}\n";
//         }

//         $prompt .= "\nProvide only the answer or 'No match':";

//         $messages = [
//             ['role' => 'system', 'content' => 'You are a helpful assistant.'],
//             ['role' => 'user', 'content' => $prompt],
//         ];

//         $reply = $this->openAIService->chatCompletion($messages);

//         if (stripos($reply, 'No match') !== false) {
//             return response()->json(['reply' => 'Sorry, I could not find a related question.']);
//         }

//         return response()->json(['reply' => $reply]);
//     }





//     public function widget($api_key)
//     {
//         $bot = Bot::where('botToken', $api_key)->first();
//         if ($bot) {
//             return response()->json(['status' => 'success', 'bot' => $bot]);
//         }
//         return response()->json(['status' => 'error', 'message' => 'Bot not found'], 404);
//     }



// }
