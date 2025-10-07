@component('mail::message')
# Chat Transcript

**Widget:** {{ $widgetName }}

**Session:** {{ $sessionId ?: 'preview' }}

The full transcript is attached as a `.txt` file.

Thanks,  
{{ config('app.name') }}
@endcomponent
