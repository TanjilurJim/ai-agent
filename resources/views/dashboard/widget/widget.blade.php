@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/style.css') }}">
    <style>
        /* --- Mobile-first fixes --- */
        /* Make both boxes fluid on phones */
        @media (max-width: 575.98px) {

            .chat-box-left,
            .chat-box-right {
                float: none !important;
                width: 100% !important;
                margin: 0 !important;
                height: auto !important;
            }

            /* If your widget sits inside a card, keep it centred and not wider than the screen */
            #chat-widget .chat-box {
                width: 100% !important;
                max-width: 320px;
                /* slightly smaller for iPhone SE width */
                margin: 0 auto;
                /* center it */
                border-radius: .75rem;
            }

            /* Let the body and messages size naturally, and scroll only the messages */
            .chat-box-right .chat-body,
            .chat-box-left .chat-body-left {
                height: auto !important;
                padding: 12px;
            }

            .messages {
                max-height: 55vh;
                /* use viewport height on phones */
                overflow: auto;
            }

            /* Absolute footer causes overflow—make it static on phones */
            .chat-box-right .chat-footer {
                position: static !important;
                width: auto !important;
                left: auto !important;
                bottom: auto !important;
                margin-top: .5rem;
                border-radius: .5rem;
                padding: .75rem;
            }
        }

        /* --- Tablet: stack or full-width when necessary --- */
        @media (min-width: 576px) and (max-width: 991.98px) {
            .chat-box-left {
                float: none !important;
                width: 100% !important;
                height: auto !important;
                margin: 0 0 1rem 0 !important;
            }

            .chat-box-right {
                width: 100% !important;
                margin-left: 0 !important;
                height: auto !important;
            }

            #chat-widget .chat-box {
                max-width: 420px;
                margin: 0 auto;
            }

            .chat-box-right .chat-footer {
                position: static !important;
            }

            .messages {
                max-height: 60vh;
                overflow: auto;
            }
        }

        /* --- Desktop keeps your original look --- */
        @media (min-width: 992px) {
            .chat-box-left {
                width: 340px;
                float: left;
                height: 710px;
            }

            .chat-box-right {
                width: auto;
                height: 710px;
                margin-left: 361px;
                position: relative;
            }

            .chat-box-right .chat-footer {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
            }
        }

        /* Misc cleanups */
        /* Replace invalid Tailwind-ish class usage */
        small.text-[#] {
            color: inherit;
        }

        /* no-op safeguard */
    </style>
    <style>
        /* Footer: make it a flex row */
        .chat-footer {
            display: flex;
            align-items: center;
            gap: .5rem;
            /* space between input & button */
            padding: .75rem;
            /* keep some padding */
        }

        .lead-gate {
            padding: 12px;
        }

        .lead-form {
            display: grid;
            gap: 8px;
            grid-template-columns: 1fr;
        }

        .lead-form input {
            padding: .6rem .75rem;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        /* Input: grow, but allow shrinking on tiny screens */
        .chat-footer input {
            flex: 1 1 auto;
            /* grow + shrink */
            min-width: 0;
            /* lets it actually shrink */
        }

        /* Button: fixed size so it never pushes outside */
        #send-button {
            flex: 0 0 auto;
            width: 44px;
            /* circle size */
            height: 44px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            /* circle stays inside */
        }

        /* If any previous CSS positions it absolutely, cancel that */
        .chat-footer,
        #send-button {
            position: static !important;
        }

        /* On very narrow phones, allow stacking if needed */
        @media (max-width: 360px) {
            .chat-footer {
                flex-wrap: wrap;
            }

            #send-button {
                width: 40px;
                height: 40px;
            }
        }
    </style>
@endsection


<div class="">
    <div class="chat-widget " id="chat-widget">
        <div class="chat-box" id="chatBox">
            <div class="chat-header">
                <div class="d-flex gap-3 align-items-center">
                    <i class="fa-solid fa-bars"></i>
                    {{ $widget->widgetName ?? 'Widget name' }}
                </div>
            </div>
            <div class="chat-body">
                <div class="chat-bot">
                    <img class="rounded" id="avatar_preview_w"
                        src="{{ $widget->avatar ? asset($widget->avatar) : asset('assets/images/upload-placeholder.jpg') }}"
                        alt="">
                    <h6 class="mt-2" id="widget_name"> {{ $widget->name ?? 'Widget name' }}</h6>
                </div>

                <div id="leadGate" class="lead-gate" style="display:none">
                    <div class="lead-form">
                        <input type="text" id="leadName" placeholder="Your name *">
                        <input type="tel" id="leadMobile" placeholder="Mobile *">
                        <input type="email" id="leadEmail" placeholder="Email (optional)">
                        <button id="leadStartBtn" type="button" class="btn btn-primary">Start</button>
                    </div>
                </div>

                <div class="messages" id="messages">
                    <div id="welcome_message_w" class="message bot-message">
                        {{ $widget->welcomeMessage ?? 'Hi! How can Ai Agent assist you today?' }}</div>
                </div>
            </div>
            <div class="chat-footer">
                <input type="text" id="chatInput" placeholder="Type your message..." onkeypress="sendMessage(event)">
                <button onclick="sendMessage()" id="send-button" class="btn btn-danger">
                    <i class="fa-regular fa-paper-plane"></i>
                </button>
            </div>
            <div style="padding: 3px 5px; text-align: center;"><small class="text-[#]"><a style="color: #7e7c7c;"
                        href="http://bot.test/">Powered by Rafusoft AI Agent</a></small></div>
        </div>
    </div>
</div>


<script>
    document.documentElement.style.setProperty(
        '--primary-widget-color',
        '{{ $widget->color ?? '#00AA5' }}'
    );
</script>
