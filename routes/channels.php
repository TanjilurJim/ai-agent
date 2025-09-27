<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Widget;
use App\Models\ChatSession;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('widgets.{widgetId}', function ($user, $widgetId) {
    // Admins see all widgets
    if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return true;
    }

    // Otherwise only the owner of the widget may listen
    return Widget::where('id', $widgetId)
        ->where('user_id', $user->id)
        ->exists();
});

Broadcast::channel('sessions.{sessionId}', function ($user, $sessionId) {
    // Admins see all sessions
    if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return true;
    }

    // Resolve the session → widget → owner
    $session = ChatSession::find($sessionId);
    if (!$session) return false;

    $widget = Widget::find($session->widget_id);
    return $widget && $widget->user_id === $user->id;
});


Broadcast::channel('sessions.uuid.{sessionUuid}', function ($user, $sessionUuid) {
    // Admins see all sessions
    if (method_exists($user, 'isAdmin') && $user->isAdmin()) {
        return true;
    }

    // Look up by UUID (chat_sessions.session_id), not the numeric PK
    $session = \App\Models\ChatSession::where('session_id', $sessionUuid)->first();
    if (!$session) return false;

    $widget = \App\Models\Widget::find($session->widget_id);
    return $widget && $widget->user_id === $user->id;
});


Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
