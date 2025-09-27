@extends('dashboard.layout')

@section('css')
<style>
  :root{
    --bg:#f5f7fb;
    --card:#ffffff;
    --border:#e9edf2;
    --text:#0f172a;
    --muted:#64748b;
    --incoming:#ffffff;                 /* user bubble */
    --outgoing:#d1fadf;                 /* bot bubble (soft green) */
    --accent:#10b981;
  }
  .chat-shell{background:var(--card); border:1px solid var(--border); border-radius:14px; overflow:hidden;}
  .chat-grid{display:grid; grid-template-columns: 320px 1fr; min-height:72vh}
  .sessions{border-right:1px solid var(--border); background:var(--card)}
  .sessions__hdr{padding:12px 14px; font-weight:600; border-bottom:1px solid var(--border)}
  .sessions__list{height:calc(72vh - 48px); overflow:auto}
  .session{padding:12px 14px; border-bottom:1px solid var(--border); cursor:pointer}
  .session:hover{background:#f8fafc}
  .session--active{background:#f1f5f9}
  .session__id{font:12px ui-monospace, SFMono-Regular, Menlo, monospace; color:#6b7280}
  .session__line{font-size:13px; color:#334155; white-space:nowrap; overflow:hidden; text-overflow:ellipsis}
  .session__meta{font-size:11px; color:#94a3b8; margin-top:4px}

  .pane{display:flex; flex-direction:column}
  .pane__hdr{display:flex; align-items:center; gap:10px; padding:12px 16px; border-bottom:1px solid var(--border)}
  .avatar{width:34px; height:34px; border-radius:50%; background:#e5e7eb; display:grid; place-items:center; font-weight:700; color:#374151}
  .title{font-weight:700}
  .subtitle{font-size:12px; color:var(--muted)}
  .pane__body{flex:1; overflow:auto; background:var(--bg); padding:16px}
  .day{position:relative; text-align:center; margin:14px 0}
  .day span{background:var(--bg); color:var(--muted); padding:4px 10px; border-radius:12px; font-size:12px; border:1px solid var(--border)}

  .row{display:flex; gap:10px; margin:6px 0}
  .row--me{justify-content:flex-end}
  .bubble{max-width:70%; padding:10px 12px; border-radius:16px; box-shadow:0 1px 0 rgba(15,23,42,.05); white-space:pre-wrap; word-break:break-word}
  .bubble--in {background:var(--incoming); border:1px solid var(--border); border-top-left-radius:4px}
  .bubble--out{background:var(--outgoing); border:1px solid #b7f0cc; border-top-right-radius:4px}
  .meta{display:block; font-size:11px; color:var(--muted); margin-top:6px; text-align:right}

  @media (max-width: 992px){ .chat-grid{grid-template-columns:1fr} .sessions{display:none} }
</style>
@endsection

@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="page-title mb-0">Live: {{ $widget->name }}</h4>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('widgets.edit', $widget) }}">Back to Widget</a>
        <a class="btn btn-outline-info" href="{{ route('widgets.logs', $widget) }}">Logs</a>
      </div>
    </div>

    <div class="chat-shell">
      <div class="chat-grid">
        <!-- Sessions -->
        <aside class="sessions">
          <div class="sessions__hdr">Sessions</div>
          <div class="sessions__list" id="sessionsList"></div>
        </aside>

        <!-- Conversation -->
        <section class="pane">
          <div class="pane__hdr">
            <div class="avatar">
              @if($widget->avatar)
                <img src="{{ asset($widget->avatar) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:50%">
              @else
                {{ strtoupper(mb_substr($widget->name,0,1)) }}
              @endif
            </div>
            <div>
              <div class="title" id="convTitle">Select a session</div>
              <div class="subtitle" id="convSubtitle">Waiting for messages…</div>
            </div>
          </div>

          <div class="pane__body" id="chatScroll">
            <!-- bubbles injected here -->
          </div>
        </section>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://js.pusher.com/8.2/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1/dist/echo.iife.js"></script>
<script>
  // ---- Echo init (same creds) ----
  const PUSHER_KEY     = "{{ config('broadcasting.connections.pusher.key') }}";
  const PUSHER_CLUSTER = "{{ config('broadcasting.connections.pusher.options.cluster') }}";
  const USE_TLS        = {{ config('broadcasting.connections.pusher.options.useTLS', true) ? 'true' : 'false' }};
  const WS_HOST        = "{{ config('broadcasting.connections.pusher.options.host') ?? '' }}";
  const WS_PORT        = Number("{{ config('broadcasting.connections.pusher.options.port') ?? '' }}") || (USE_TLS ? 443 : 80);
  const widgetId       = @json($widget->id);
  const echo = new Echo({
    broadcaster: 'pusher',
    key: PUSHER_KEY,
    cluster: PUSHER_CLUSTER || undefined,
    forceTLS: USE_TLS,
    wsHost: WS_HOST || undefined, wsPort: WS_HOST ? WS_PORT : undefined, wssPort: WS_HOST ? WS_PORT : undefined,
    enabledTransports: ['ws','wss'],
    authEndpoint: "{{ url('/broadcasting/auth') }}",
    auth: { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content } }
  });

  const sessionsList = document.getElementById('sessionsList');
  const chatScroll   = document.getElementById('chatScroll');
  const convTitle    = document.getElementById('convTitle');
  const convSubtitle = document.getElementById('convSubtitle');

  // State
  const threads = new Map();  // sessionUuid -> [{role,content,ts}]
  let active = null;
  let lastDayShown = null;

  // Subscribe
  echo.private(`widgets.${widgetId}`)
    .subscribed(() => console.log('[live] subscribed widgets.'+widgetId))
    .listen('.message.created', e => onMsg(e.payload));

  function onMsg(p){
    const sid = p.session_id || p.session_pk; // prefer UUID
    const ts  = p.created_at || new Date().toISOString();
    if(!threads.has(sid)) threads.set(sid, []);
    threads.get(sid).push({ role: p.role, content: p.content, ts });

    upsertSessionItem(sid, p.content, ts);

    if(!active){ setActive(sid); }
    if(active === sid){ appendBubble(p.role, p.content, ts); autoscroll(); }
  }

  // Sidebar
  function upsertSessionItem(sid, preview, ts){
    let el = sessionsList.querySelector(`[data-sid="${CSS.escape(sid)}"]`);
    if(!el){
      el = document.createElement('div');
      el.className = 'session';
      el.dataset.sid = sid;
      el.innerHTML = `
        <div class="session__line" data-line></div>
        <div class="session__id">${escapeHtml(sid)}</div>
        <div class="session__meta" data-meta></div>
      `;
      el.onclick = () => setActive(sid);
      sessionsList.prepend(el);
    }
    el.querySelector('[data-line]').textContent = truncate(preview || '', 80);
    el.querySelector('[data-meta]').textContent = fmtTime(ts);
    // move to top on activity
    if(sessionsList.firstElementChild !== el){ sessionsList.removeChild(el); sessionsList.prepend(el); }
  }

  function setActive(sid){
    active = sid; lastDayShown = null;
    convTitle.textContent = 'Session ' + sid;
    convSubtitle.textContent = (threads.get(sid)?.length || 0) + ' messages';
    sessionsList.querySelectorAll('.session').forEach(n => n.classList.toggle('session--active', n.dataset.sid===sid));
    // render thread
    chatScroll.innerHTML = '';
    (threads.get(sid) || []).forEach(m => appendBubble(m.role, m.content, m.ts));
    autoscroll(true);
  }

  // Bubbles
  function appendBubble(role, content, ts){
    const isBot = (role||'').toLowerCase() === 'assistant';
    const sideClass = isBot ? 'row' : 'row row--me'; // bot left, user right
    const bubClass  = isBot ? 'bubble bubble--in' : 'bubble bubble--out';

    // day divider
    const day = new Date(ts || Date.now()).toDateString();
    if(day !== lastDayShown){
      chatScroll.insertAdjacentHTML('beforeend', `<div class="day"><span>${day}</span></div>`);
      lastDayShown = day;
    }

    const html = `
      <div class="${sideClass}">
        <div class="${bubClass}">
          ${escapeHtml(content || '')}
          <span class="meta">${fmtTime(ts)} • ${escapeHtml((role||'').toLowerCase())}</span>
        </div>
      </div>`;
    chatScroll.insertAdjacentHTML('beforeend', html);
  }

  // Utils
  function escapeHtml(s=''){ return s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c])); }
  function truncate(s, n){ return (s.length>n) ? (s.slice(0,n-1)+'…') : s; }
  function fmtTime(ts){ try{ return new Date(ts).toLocaleString(); } catch{ return ''; } }
  function autoscroll(force=false){
    const nearBottom = chatScroll.scrollTop + chatScroll.clientHeight >= chatScroll.scrollHeight - 80;
    if(force || nearBottom){ chatScroll.scrollTop = chatScroll.scrollHeight; }
  }
</script>
@endpush
