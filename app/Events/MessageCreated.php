<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Now = no queue worker needed
use Illuminate\Contracts\Broadcasting\ShouldBroadcast; // in production
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcastNow
{
    use SerializesModels;

    public array $payload;
    protected int $widgetId;
    protected int $sessionId;

    public function __construct(ChatMessage $message)
    {
        // make sure we have the session relation
        $message->loadMissing('session');

        $this->payload = [
            'id'         => $message->id,
            'session_id' => $message->chat_session_id,
            'widget_id'  => $message->session->widget_id ?? null,
            'role'       => $message->role,          // 'user' or 'assistant'
            'content'    => $message->content,
            'created_at' => optional($message->created_at)->toISOString(),
        ];

        $this->widgetId  = (int) ($this->payload['widget_id'] ?? 0);
        $this->sessionId = (int) $this->payload['session_id'];
    }

    public function broadcastOn(): array
    {
        // broadcast to both scopes (widget-wide + per-session)
        return [
            new PrivateChannel("widgets.{$this->widgetId}"),
            new PrivateChannel("sessions.{$this->sessionId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.created';
    }
}
