<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChatTranscriptMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $widgetName;
    public string $sessionId;
    public string $transcript;

    public function __construct(string $widgetName, string $sessionId, string $transcript)
    {
        $this->widgetName = $widgetName;
        $this->sessionId  = $sessionId;
        $this->transcript = $transcript;
    }

    public function build()
    {
        $filename = sprintf(
            '%s-chat-%s-%s.txt',
            str($this->widgetName)->snake('-'),
            $this->sessionId ?: 'preview',
            now()->format('Ymd-His')
        );

        return $this->subject("Chat Transcript – {$this->widgetName}")
            ->markdown('emails.chat_transcript', [
                'widgetName' => $this->widgetName,
                'sessionId'  => $this->sessionId,
            ])
            ->attachData($this->transcript, $filename, ['mime' => 'text/plain']);
    }
}
