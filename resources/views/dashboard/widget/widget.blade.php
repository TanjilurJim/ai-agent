@section('css')
    <link rel="stylesheet" href="{{asset('assets/admin/style.css')}}">
@endsection

<div class="">
    <div class="chat-widget " id="chat-widget">
        <div class="chat-box" id="chatBox">
            <div class="chat-header">
                <div class="d-flex gap-3 align-items-center">
                    <i class="fa-solid fa-bars"></i>
                    {{$widget->widgetName ?? 'Widget name'}}
                </div>
            </div>
            <div class="chat-body">
                <div class="chat-bot">
                    <img class="rounded" id="avatar_preview_w"  src="{{$widget->avatar ?? asset('assets/images/upload-placeholder.jpg')}}" alt="">
                    <h6 class="mt-2" id="widget_name"> {{$widget->name ?? 'Widget name'}}</h6>
                </div>
                <div class="messages" id="messages">
                    <div id="welcome_message_w" class="message bot-message">{{$widget->welcomeMessage ?? 'Hi! How can Ai Agent assist you today?'}}</div>
                </div>
            </div>
            <div class="chat-footer">
                <input type="text" id="chatInput" placeholder="Type your message..." onkeypress="sendMessage(event)">
                <button onclick="sendMessage()" id="send-button">
                    <i class="fa-regular fa-paper-plane"></i>
                </button>
            </div>
            <div style="padding: 3px 5px; text-align: center;"><small class="text-[#]"><a style="color: #7e7c7c;" href="http://bot.test/">Powered by Rafusoft AI Agent</a></small></div>
        </div>
    </div>
</div>


<script>
    document.documentElement.style.setProperty(
        '--primary-widget-color', 
        '{{$widget->color ?? "#00AA5"}}'
    );
</script>
