<?php

use App\Http\Controllers\OpenAIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
return $request->user();
});


Route::post('/chat/{api_key}/start', [OpenAIController::class, 'start']);

Route::post('/chat/{api_key}', [OpenAIController::class, 'chat']);
Route::get('/widget/{api_key}', [OpenAIController::class, 'widget']);

// ✅ Add this:
Route::post('/chat/{api_key}/email',  [OpenAIController::class, 'emailTranscript'])
     ->middleware('throttle:10,1'); // optional rate limit