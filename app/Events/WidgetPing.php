<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class WidgetPing implements ShouldBroadcastNow
{
    public array $payload;
    protected int $widgetId;

    public function __construct(int $widgetId, string $message = 'hello')
    {
        $this->widgetId = $widgetId;
        $this->payload = [
            'widget_id'  => $widgetId,
            'message'    => $message,
            'sent_at'    => now()->toISOString(),
        ];
    }

    public function broadcastOn(): array
    {
        return [ new PrivateChannel("widgets.{$this->widgetId}") ];
    }

    public function broadcastAs(): string
    {
        return 'ping';
    }
}
