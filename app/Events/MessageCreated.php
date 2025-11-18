<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class MessageCreated implements ShouldBroadcastNow
{
    use SerializesModels;

    public array $payload;

    protected int $widgetId;
    protected int $sessionPk;       // numeric FK: chat_session_id
    protected string $sessionUuid;  // external UUID: chat_sessions.session_id

    public function __construct(ChatMessage $message)
    {
        // Try eager relation first; fall back to direct lookup
        $message->loadMissing(['session', 'attachments']);

        $session = $message->session
            ?: \App\Models\ChatSession::find($message->chat_session_id); // fallback if relation missing

        // If still no session, bail to a harmless channel to avoid errors
        $this->widgetId    = $session ? (int) $session->widget_id : 0;
        $this->sessionPk   = (int) $message->chat_session_id;
        $this->sessionUuid = $session ? (string) $session->session_id : '';

        // Map attachments to a safe, frontend-friendly shape.
        // We saved url/path when uploading; url points to /public/uploads/...
        $attachments = ($message->attachments ?? collect())->map(function ($a) {
            return [
                'id'       => $a->id,
                'url'      => $a->url ?: asset($a->path),  // public URL
                'mime'     => $a->mime,
                'size'     => $a->size,
                'width'    => $a->width,
                'height'   => $a->height,
                'filename' => basename($a->path),
            ];
        })->values()->all();



        $this->payload = [
            'id'          => $message->id,
            'widget_id'   => $this->widgetId,
            'session_pk'  => $this->sessionPk,
            'session_id'  => $this->sessionUuid,
            'role'        => $message->role,
            'content'     => $message->content,
             'attachments' => $attachments,  
            'created_at'  => optional($message->created_at)->toISOString(),
        ];

        // (optional) quick debug to verify channels computed
        \Log::info('Broadcasting MessageCreated', [
            'widget_channel'   => "widgets.{$this->widgetId}",
            'session_channel'  => "sessions.{$this->sessionPk}",
            'session_uuid_ch'  => "sessions.uuid.{$this->sessionUuid}",
            'payload_has_atts' => !empty($attachments),
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("widgets.{$this->widgetId}"),            // widget-wide stream
            new PrivateChannel("sessions.{$this->sessionPk}"),          // per-session (numeric)
            // new PrivateChannel("sessions.uuid.{$this->sessionUuid}"),   // per-session (UUID) <-- NEW
            new Channel("sessions.uuid.{$this->sessionUuid}")
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.created';
    }
     public function broadcastWith(): array
    {
        return $this->payload;
    }
}
