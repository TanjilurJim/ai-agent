<?php

use App\Events\WidgetPing;
use App\Http\Controllers\APIController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PersonalityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\WidgetLiveController;

use App\Models\Widget;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// -----------------------------
// Frontend (public)
// -----------------------------
Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/payment', [PaymentController::class, 'payment']);

Route::get('/test-ollama-connection', function () {
    try {
        $response = Http::timeout(10)->post('http://103.129.203.20:11434/api/chat', [
            'model'    => 'gemma:2b',
            'messages' => [['role' => 'user', 'content' => 'Hello']],
            'stream'   => false,
        ]);
        return $response->json();
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// -----------------------------
// Authenticated
// -----------------------------
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/users', [DashboardController::class, 'user'])->name('dashboard.user');

    // Live chat
    Route::get('/widgets/{widget}/live', [WidgetLiveController::class, 'live'])->name('widgets.live');
    Route::get('/widgets/{widget}/logs', [WidgetLiveController::class, 'logs'])->name('widgets.logs');

    // Session actions (Option 1: numeric {session} binding, names used in Blade)
    Route::post('/widgets/{widget}/sessions/{session}/operator-reply', [WidgetLiveController::class, 'operatorReply'])
        ->name('widgets.sessions.reply');
    Route::post('/widgets/{widget}/sessions/{session}/toggle-pause', [WidgetLiveController::class, 'togglePause'])
        ->name('widgets.sessions.toggle');

    // Train bot (Personality pages)
    Route::get('/dashboard/train-bot', [PersonalityController::class, 'train'])->name('dashboard.train');
    Route::get('/dashboard/train-bot/add-page', [PersonalityController::class, 'create'])->name('dashboard.page');
    Route::post('/dashboard/train-bot/add-page', [PersonalityController::class, 'store'])->name('dashboard.page.store');
    Route::get('/dashboard/train-bot/page-list', [PersonalityController::class, 'index'])->name('dashboard.page.list');
    Route::get('/dashboard/train-bot/page-list/{id}', [PersonalityController::class, 'edit'])->name('dashboard.page.edit');
    Route::post('/dashboard/train-bot/page-list/{id}', [PersonalityController::class, 'update'])->name('dashboard.page.edit.store');
    Route::delete('/dashboard/train-bot/page-list/{id}', [PersonalityController::class, 'destroy'])->name('page.destroy');
    Route::post('/dashboard/train-bot', [DashboardController::class, 'train_store']);

    // TrainController
    Route::put('/dashboard/train/{id}', [TrainController::class, 'update'])->name('train.update');
    Route::delete('/dashboard/train/{id}', [TrainController::class, 'destroy'])->name('train.destroy');

    // Subscribers / API docs
    Route::get('/dashboard/subscribers', [DashboardController::class, 'subscriber'])->name('dashboard.subscriber');

    Route::get('/dashboard/subscribers/{id}', [DashboardController::class, 'subscriber_show'])
    ->name('subscribers.show');

    Route::get('/dashboard/users/{id}', [DashboardController::class, 'user_show'])
    ->name('user.show');

    Route::post('/dashboard/subscribers', [DashboardController::class, 'subscriber_store'])->name('dashboard.subscriber_store');
    Route::delete('/dashboard/subscribers/{id}', [DashboardController::class, 'subscriber_destroy'])->name('subscribers.destroy');
    Route::get('/dashboard/api-docs', [APIController::class, 'api'])->name('dashboard.api');
    Route::put('/subscribers/{id}', [APIController::class, 'update_subscriber'])->name('subscribers.update');

    // Widgets CRUD
    Route::get('/dashboard/widgets', [WidgetController::class, 'index'])->name('widgets.index');
    Route::get('/dashboard/widgets/create', [WidgetController::class, 'create'])->name('widgets.create');
    Route::post('/dashboard/widgets', [WidgetController::class, 'store'])->name('widgets.store');
    Route::get('/dashboard/widgets/{widget}/edit', [WidgetController::class, 'edit'])->name('widgets.edit');
    Route::put('/dashboard/widgets/{widget}', [WidgetController::class, 'update'])->name('widgets.update');
    Route::delete('/dashboard/widgets/{widget}', [WidgetController::class, 'destroy'])->name('widgets.destroy');
    Route::patch('/dashboard/widgets/{widget}/toggle', [WidgetController::class, 'toggle'])->name('widgets.toggle');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Users (admin)
    Route::prefix('users')->group(function () {
        Route::get('/{id}/edit', [DashboardController::class, 'edit'])->name('user.edit');
        Route::delete('/{id}', [DashboardController::class, 'destroy'])->name('user.destroy');
    });

    // Debug / test
    Route::get('/debug/ping/{widget}', function (Widget $widget) {
        event(new WidgetPing($widget->id, 'debug ping ' . now()->format('H:i:s')));
        return 'sent ping to widgets.' . $widget->id;
    });

    Route::get('/test-broadcast/{widget}', function (Widget $widget) {
        broadcast(new \App\Events\MessageCreated(
            \App\Models\ChatMessage::firstOrCreate([
                'chat_session_id' => \App\Models\ChatSession::where('widget_id', $widget->id)->value('id'),
                'role'            => 'assistant',
                'content'         => 'Hello from test route!',
            ])
        ));
        return 'ok';
    })->middleware(['web']); // already in auth group
});

// Auth scaffolding
require __DIR__ . '/auth.php';
