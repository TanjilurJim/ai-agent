@extends('dashboard.layout')

@section('css')
    <style>
        :root {
            --bg: #f5f7fb;
            --card: #ffffff;
            --border: #e9edf2;
            --text: #0f172a;
            --muted: #64748b;
            --incoming: #ffffff;
            /* user bubble */
            --outgoing: #d1fadf;
            /* bot bubble (soft green) */
            --accent: #10b981;
        }

        .chat-shell {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }

        .chat-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            min-height: 72vh
        }

        .sessions {
            border-right: 1px solid var(--border);
            background: var(--card)
        }

        .sessions__hdr {
            padding: 12px 14px;
            font-weight: 600;
            border-bottom: 1px solid var(--border)
        }

        .sessions__list {
            height: calc(72vh - 48px);
            overflow: auto
        }

        .session {
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            cursor: pointer
        }

        .session:hover {
            background: #f8fafc
        }

        .session--active {
            background: #f1f5f9
        }

        .session__id {
            font: 12px ui-monospace, SFMono-Regular, Menlo, monospace;
            color: #6b7280
        }

        .session__line {
            font-size: 13px;
            color: #334155;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .session__meta {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 4px
        }

        .pane {
            display: flex;
            flex-direction: column
        }

        .pane__hdr {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border)
        }

        .avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #e5e7eb;
            display: grid;
            place-items: center;
            font-weight: 700;
            color: #374151
        }

        .title {
            font-weight: 700
        }

        .subtitle {
            font-size: 12px;
            color: var(--muted)
        }

        .pane__body {
            flex: 1;
            overflow: auto;
            background: var(--bg);
            padding: 16px
        }

        .day {
            position: relative;
            text-align: center;
            margin: 14px 0
        }

        .day span {
            background: var(--bg);
            color: var(--muted);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            border: 1px solid var(--border)
        }

        .row {
            display: flex;
            gap: 10px;
            margin: 6px 0
        }

        .row--me {
            justify-content: flex-end
        }

        .bubble {
            max-width: 70%;
            padding: 10px 12px;
            border-radius: 16px;
            box-shadow: 0 1px 0 rgba(15, 23, 42, .05);
            white-space: pre-wrap;
            word-break: break-word
        }

        .bubble--in {
            background: var(--incoming);
            border: 1px solid var(--border);
            border-top-left-radius: 4px
        }

        .bubble--out {
            background: var(--outgoing);
            border: 1px solid #b7f0cc;
            border-top-right-radius: 4px
        }

        .meta {
            display: block;
            font-size: 11px;
            color: var(--muted);
            margin-top: 6px;
            text-align: right
        }

        @media (max-width: 992px) {
            .chat-grid {
                grid-template-columns: 1fr
            }

            .sessions {
                display: none
            }
        }
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
                                @if ($widget->avatar)
                                    <img src="{{ asset($widget->avatar) }}" alt=""
                                        style="width:100%;height:100%;object-fit:cover;border-radius:50%">
                                @else
                                    {{ strtoupper(mb_substr($widget->name, 0, 1)) }}
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
                        <div class="p-3 border-top" style="background:#fff">
                            <div class="d-flex gap-2">
                                <input id="opInput" class="form-control" placeholder="Type operator reply…" />
                                <button id="opSend" class="btn btn-success">Send</button>
                                <button id="opToggle" class="btn btn-outline-warning" data-paused="0">Pause Bot</button>
                            </div>
                            <small id="pauseHint" class="text-muted"></small>
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
        const PUSHER_KEY = "{{ config('broadcasting.connections.pusher.key') }}";
        const PUSHER_CLUSTER = "{{ config('broadcasting.connections.pusher.options.cluster') }}";
        const USE_TLS = {{ config('broadcasting.connections.pusher.options.useTLS', true) ? 'true' : 'false' }};
        const WS_HOST = "{{ config('broadcasting.connections.pusher.options.host') ?? '' }}";
        const WS_PORT = Number("{{ config('broadcasting.connections.pusher.options.port') ?? '' }}") || (USE_TLS ? 443 :
            80);
        const widgetId = @json($widget->id);
        const echo = new Echo({
            broadcaster: 'pusher',
            key: PUSHER_KEY,
            cluster: PUSHER_CLUSTER || undefined,
            forceTLS: USE_TLS,
            wsHost: WS_HOST || undefined,
            wsPort: WS_HOST ? WS_PORT : undefined,
            wssPort: WS_HOST ? WS_PORT : undefined,
            enabledTransports: ['ws', 'wss'],

            // 🔑 these two make auth actually succeed
            withCredentials: true,
            authEndpoint: "{{ url('/broadcasting/auth') }}",
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                }
            }
        });

        echo.private(`widgets.${widgetId}`)
            .subscribed(() => console.log('[live] subscribed widgets.' + widgetId))
            .error(err => console.error('[live] subscription error', err))
            .listen('.ping', e => {
                console.log('PING', e);
                const p = e.payload || {};
                upsertSessionItem('PING', p.message, p.sent_at || new Date().toISOString());
                if (!active) setActive('PING');
            })
            .listen('.message.created', e => {
                console.log('message.created', e);
                onMsg(e.payload);
            });

        const sessionsList = document.getElementById('sessionsList');
        const chatScroll = document.getElementById('chatScroll');
        const convTitle = document.getElementById('convTitle');
        const convSubtitle = document.getElementById('convSubtitle');

        // State
        const threads = new Map();
        const sessionPkByUuid = new Map(); // sessionUuid -> [{role,content,ts}]
        let active = null;
        let lastDayShown = null;
        const seen = new Set();
        // const sessionPkByUuid = new Map();
        // Subscribe



        function onMsg(p) {

            // Prefer UUID for UI identity, but keep PK for routes
            const uuid = p.session_id || p.session_uuid || null;
            const pk = p.session_pk || p.chat_session_id || p.session_db_id || null;

            // Remember mapping if we have both
            if (uuid && pk) sessionPkByUuid.set(uuid, pk);

            const sid = uuid || (pk != null ? String(pk) : null);
            if (!sid) return; // prefer UUID
            const ts = p.created_at || new Date().toISOString();
            const dedupKey = `${sid}:${p.id ?? ts}:${p.role}:${(p.content || '').slice(0,20)}`;
            if (seen.has(dedupKey)) return;
            seen.add(dedupKey);

            if (!threads.has(sid)) threads.set(sid, []);
            threads.get(sid).push({
                role: p.role,
                content: p.content,
                ts
            });

            upsertSessionItem(sid, p.content, ts);

            // If no active session yet, render the full thread and stop.
            if (active === null) {
                setActive(sid);
                return; // <- prevents double-append
            }

            // If this isn't the visible thread, don't append now; it will render on selection.
            if (active !== sid) return;

            // Otherwise, it's the active thread: append only once.
            appendBubble(p.role, p.content, ts);
            autoscroll();
        }

        // Sidebar
        function upsertSessionItem(sid, preview, ts) {
            let el = sessionsList.querySelector(`[data-sid="${CSS.escape(sid)}"]`);
            if (!el) {
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
            if (sessionsList.firstElementChild !== el) {
                sessionsList.removeChild(el);
                sessionsList.prepend(el);
            }
        }

        function setActive(sid) {
            active = sid;
            lastDayShown = null;
            convTitle.textContent = 'Session ' + sid;
            convSubtitle.textContent = (threads.get(sid)?.length || 0) + ' messages';
            sessionsList.querySelectorAll('.session').forEach(n => n.classList.toggle('session--active', n.dataset.sid ===
                sid));
            // render thread
            chatScroll.innerHTML = '';
            (threads.get(sid) || []).forEach(m => appendBubble(m.role, m.content, m.ts));
            autoscroll(true);
        }

        // Bubbles
        // Bubbles
        function appendBubble(role, content, ts) {
            const r = (role || '').toLowerCase();

            const isOperator = r === 'operator';
            const isAssistant = r === 'assistant' || r === 'bot' || r === 'ai';
            const isVisitor = r === 'user' || r === 'visitor';
            const isSystem = r === 'system' || r === 'event';

            // Convenience: staff = assistant OR operator
            const isStaff = isAssistant || isOperator;

            // Side: operators appear on the right (admin UI). Everyone else on left
            const sideClass = isOperator ? 'row row--me' : 'row';

            // Bubble visuals — pick "in" for incoming (bot/visitor/system),
            // and "out" for operator (your messages) — adjust if you prefer otherwise.
            const bubClass = isSystem ?
                'bubble bubble--in' :
                isAssistant ?
                'bubble bubble--in' :
                isVisitor ?
                'bubble bubble--out' :
                isOperator ?
                'bubble bubble--out' :
                'bubble bubble--in';

            // day divider
            const day = new Date(ts || Date.now()).toDateString();
            if (day !== lastDayShown) {
                chatScroll.insertAdjacentHTML('beforeend', `<div class="day"><span>${day}</span></div>`);
                lastDayShown = day;
            }

            const label = isOperator ? 'Operator' :
                isAssistant ? 'Assistant' :
                isVisitor ? 'Visitor' :
                isSystem ? r.toUpperCase() :
                (r || 'Message');

            const html = `
    <div class="${sideClass}">
      <div class="${bubClass}">
        ${escapeHtml(content || '')}
        <span class="meta">${fmtTime(ts)} • ${escapeHtml(label)}</span>
      </div>
    </div>`;
            chatScroll.insertAdjacentHTML('beforeend', html);
        }

        // Utils
        function escapeHtml(s = '') {
            return s.replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));
        }

        function truncate(s, n) {
            return (s.length > n) ? (s.slice(0, n - 1) + '…') : s;
        }

        function fmtTime(ts) {
            try {
                return new Date(ts).toLocaleString();
            } catch {
                return '';
            }
        }

        function autoscroll(force = false) {
            const nearBottom = chatScroll.scrollTop + chatScroll.clientHeight >= chatScroll.scrollHeight - 80;
            if (force || nearBottom) {
                chatScroll.scrollTop = chatScroll.scrollHeight;
            }
        }

        // Elements
        const opInput = document.getElementById('opInput');
        const opSend = document.getElementById('opSend');
        const opToggle = document.getElementById('opToggle');
        const pauseHint = document.getElementById('pauseHint');

        // Send operator message (saved + broadcast via MessageCreated)
        async function sendOperatorReply() {
            if (!active) {
                alert('Select a session first.');
                return;
            }
            const text = (opInput.value || '').trim();
            if (!text) return;

            opSend.disabled = true;
            try {
                const targetId = sessionPkByUuid.get(active) || active; // UUID→PK when available
                const res = await fetch(
                    `{{ url('/widgets/' . $widget->id) }}/sessions/${encodeURIComponent(targetId)}/operator-reply`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            message: text
                        }),
                    }
                );
                if (!res.ok) throw new Error(await res.text());
                // Don't append here—MessageCreated broadcast will render it
                opInput.value = '';
            } catch (e) {
                console.error('operator reply failed', e);
                alert('Failed to send operator reply.');
            } finally {
                opSend.disabled = false;
            }
        }

        // Pause / resume the bot for this session
        async function togglePause() {
            if (!active) {
                alert('Select a session first.');
                return;
            }
            const paused = opToggle.dataset.paused === '1';
            const payload = paused ? {
                pause: false
            } : {
                pause: true,
                minutes: 30
            };

            opToggle.disabled = true;
            try {
                const targetId = sessionPkByUuid.get(active) || active; // UUID → numeric PK if we have it
                const res = await fetch(
                    `{{ url('/widgets/' . $widget->id) }}/sessions/${encodeURIComponent(targetId)}/toggle-pause`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(payload),
                    }
                );
                const json = await res.json().catch(() => ({}));
                if (!res.ok) throw new Error(JSON.stringify(json));

                const nowPaused = !!json.paused_until;
                opToggle.dataset.paused = nowPaused ? '1' : '0';
                opToggle.textContent = nowPaused ? 'Resume Bot' : 'Pause Bot';
                pauseHint.textContent = nowPaused ? `Paused until ${new Date(json.paused_until).toLocaleString()}` : '';
            } catch (e) {
                console.error('pause toggle failed', e);
                alert('Failed to toggle pause.');
            } finally {
                opToggle.disabled = false;
            }
        }

        // Hook up UI events
        opSend?.addEventListener('click', sendOperatorReply);
        opInput?.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendOperatorReply();
            }
        });
        opToggle?.addEventListener('click', togglePause);
    </script>
@endpush
