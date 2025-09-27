<?php

use App\Http\Controllers\APIController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\WidgetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalityController;

use App\HttpControllers\WidgetLiveController;



// frontend 

Route::get('/', [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/payment', [PaymentController::class, 'payment']);


Route::get('/test-ollama-connection', function() {
    try {
        $response = Http::timeout(10)
            ->post('http://103.129.203.20:11434/api/chat', [
                'model' => 'gemma:2b',
                'messages' => [['role' => 'user', 'content' => 'Hello']],
                'stream' => false
            ]);
            
        return $response->json();
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/users', [DashboardController::class, 'user'])->name('dashboard.user');


    // live chat

    Route::get('/widgets/{widget}/live', [\App\Http\Controllers\WidgetLiveController::class, 'live'])
        ->name('widgets.live');

    Route::get('/widgets/{widget}/logs', [\App\Http\Controllers\WidgetLiveController::class, 'logs'])
        ->name('widgets.logs');
    
    // Route::get('/dashboard/train-bot',  [DashboardController::class, 'train'])->name('dashboard.train');
    Route::get('/dashboard/train-bot',              [PersonalityController::class, 'train'])->name('dashboard.train');

    // Route::get('/dashboard/train-bot/add-page',  [DashboardController::class, 'page'])->name('dashboard.page');
    // Route::post('/dashboard/train-bot/add-page',  [DashboardController::class, 'page_store'])->name('dashboard.page.store');

    Route::get('/dashboard/train-bot/add-page',     [PersonalityController::class, 'create'])->name('dashboard.page');
    Route::post('/dashboard/train-bot/add-page',    [PersonalityController::class, 'store'])->name('dashboard.page.store');

    // Route::get('/dashboard/train-bot/page-list',  [DashboardController::class, 'page_list'])->name('dashboard.page.store');
    // Route::get('/dashboard/train-bot/page-list/{id}',  [DashboardController::class, 'page_list_edit'])->name('dashboard.page.edit');
    // Route::post('/dashboard/train-bot/page-list/{id}',  [DashboardController::class, 'page_list_edit_store'])->name('dashboard.page.edit.store');
    // Route::delete('/dashboard/train-bot/page-list/{id}', [DashboardController::class, 'destroyPage'])->name('page.destroy');

    Route::get('/dashboard/train-bot/page-list',    [PersonalityController::class, 'index'])->name('dashboard.page.list');
    Route::get('/dashboard/train-bot/page-list/{id}',  [PersonalityController::class, 'edit'])->name('dashboard.page.edit');
    Route::post('/dashboard/train-bot/page-list/{id}', [PersonalityController::class, 'update'])->name('dashboard.page.edit.store');
    Route::delete('/dashboard/train-bot/page-list/{id}', [PersonalityController::class, 'destroy'])->name('page.destroy');

    Route::post('/dashboard/train-bot', [DashboardController::class, 'train_store']);


    Route::put('/dashboard/train/{id}', [TrainController::class, 'update'])->name('train.update');
    Route::delete('/dashboard/train/{id}', [TrainController::class, 'destroy'])->name('train.destroy');
    Route::get('/dashboard/subscribers', [DashboardController::class, 'subscriber'])->name('dashboard.subscriber');
    Route::post('/dashboard/subscribers', [DashboardController::class, 'subscriber_store'])->name('dashboard.subscriber_store');
    Route::delete('/dashboard/subscribers/{id}', [DashboardController::class, 'subscriber_destroy'])->name('subscribers.destroy');
    Route::get('/dashboard/api-docs', [APIController::class, 'api'])->name('dashboard.api');
    Route::put('/subscribers/{id}', [APIController::class, 'update_subscriber'])->name('subscribers.update');
    
    
    // Route::post('/dashboard/widget', [WidgetController::class, 'store'])->name('dashboard.store');
    // Route::get('/dashboard/widget', [WidgetController::class, 'make'])->name('dashboard.make');

    Route::get('/dashboard/widgets',               [WidgetController::class, 'index'])->name('widgets.index');
    Route::get('/dashboard/widgets/create',        [WidgetController::class, 'create'])->name('widgets.create');
    Route::post('/dashboard/widgets',              [WidgetController::class, 'store'])->name('widgets.store');
    Route::get('/dashboard/widgets/{widget}/edit', [WidgetController::class, 'edit'])->name('widgets.edit');
    Route::put('/dashboard/widgets/{widget}',      [WidgetController::class, 'update'])->name('widgets.update');
    Route::delete('/dashboard/widgets/{widget}',   [WidgetController::class, 'destroy'])->name('widgets.destroy');
    Route::patch('/dashboard/widgets/{widget}/toggle', [WidgetController::class, 'toggle'])->name('widgets.toggle');


});


// routes/web.php
Route::get('/test-broadcast/{widget}', function (\App\Models\Widget $widget) {
    broadcast(new \App\Events\MessageCreated(
        \App\Models\ChatMessage::firstOrCreate([
            'chat_session_id' => \App\Models\ChatSession::where('widget_id',$widget->id)->value('id'),
            'role' => 'assistant',
            'content' => 'Hello from test route!'
        ])
    ));
    return 'ok';
})->middleware(['web','auth']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::prefix('users')->group(function () {
    Route::get('/{id}/edit', [DashboardController::class, 'edit'])->name('user.edit');
    Route::delete('/{id}', [DashboardController::class, 'destroy'])->name('user.destroy');
});


require __DIR__.'/auth.php';
