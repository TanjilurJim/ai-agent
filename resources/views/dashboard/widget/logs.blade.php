{{-- resources/views/dashboard/widget/logs.blade.php --}}
@extends('dashboard.layout')

@section('css')
    <style>
        /* Custom styles to fix Bootstrap + Tailwind conflicts */
        .logs-container {
            margin-left: 0;
            margin-top: 0;
            width: 100%;
        }

        @media (min-width: 1024px) {
            .logs-container {
                margin-left: 250px;
                /* Sidebar width */
            }
        }

        /* Ensure proper spacing below topbar */
        .page-wrapper {
            padding-top: 70px;
            /* Topbar height */
            min-height: calc(100vh - 70px);
        }

        /* Fix for mobile menu */
        @media (max-width: 1023.98px) {
            .logs-container {
                margin-left: 0;
            }

            .startbar-open .logs-container {
                transform: translateX(250px);
            }
        }
    </style>
@endsection

@section('content')
    <script src="https://cdn.tailwindcss.com/3.4.1"></script>

    {{-- @php
        $selectedId = (int) request('session');
        /** @var \App\Models\ChatSession|null $selected */
        $selected = $sessions->firstWhere('id', $selectedId) ?? $sessions->first();
        $selectedId = $selected?->id;

        $counts = [
            'unattended' => $sessions->filter(fn($s) => !$s->messages->firstWhere('role', 'operator'))->count(),
            'open' => $sessions->filter(fn($s) => is_null($s->closed_at))->count(),
            'closed' => $sessions->filter(fn($s) => !is_null($s->closed_at))->count(),
        ];
    @endphp --}}

    {{-- @php
        $isLeads = request('filter') === 'leads';

        // counts are needed for the sidebar on every tab
        $counts = [
            'unattended' => $sessions->filter(fn($s) => !$s->messages->firstWhere('role', 'operator'))->count(),
            'open' => $sessions->filter(fn($s) => is_null($s->closed_at))->count(),
            'closed' => $sessions->filter(fn($s) => !is_null($s->closed_at))->count(),
        ];

        // only pick a selected session when not on the Leads tab
        $selected = null;
        $selectedId = null;
        if (!$isLeads) {
            $selectedId = (int) request('session');
            $selected = $sessions->firstWhere('id', $selectedId) ?? $sessions->first();
            $selectedId = $selected?->id;
        }
    @endphp --}}



    <div class="logs-container container-fluid p-0">
        {{-- [!!] KEY CHANGE HERE [!!] --}}
        {{-- Changed min-h-[...] to h-[...] to constrain the height and enable internal scrolling. --}}
        <div class="h-[calc(100vh-140px)]">
            <div class="flex h-full overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm">

                {{-- Sidebar - Hidden on mobile, shown on lg+ --}}
                <aside class="hidden lg:flex w-[280px] min-w-0 flex-shrink-0 flex-col border-r border-neutral-200">
                    <div class="sticky top-0 z-10 border-b border-neutral-200 bg-white px-4 py-3">
                        <div class="text-xs font-semibold uppercase tracking-wide text-neutral-500">Inbox</div>
                    </div>

                    <div class="flex-1 space-y-1 overflow-y-auto px-2 py-3">
                        <a href="{{ request()->fullUrlWithQuery(['filter' => null, 'session' => null]) }}"
                            class="flex items-center justify-between rounded-md px-3 py-2 text-sm transition hover:bg-neutral-100 {{ request('filter') === null ? 'bg-neutral-100' : '' }}">
                            <span class="text-neutral-800">Unattended</span>
                            <span
                                class="rounded-md bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">{{ $counts['unattended'] }}</span>
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'open', 'session' => null]) }}"
                            class="flex items-center justify-between rounded-md px-3 py-2 text-sm transition hover:bg-neutral-100 {{ request('filter') === 'open' ? 'bg-neutral-100' : '' }}">
                            <span class="text-neutral-800">Open</span>
                            <span
                                class="rounded-md bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">{{ $counts['open'] }}</span>
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'closed', 'session' => null]) }}"
                            class="flex items-center justify-between rounded-md px-3 py-2 text-sm transition hover:bg-neutral-100 {{ request('filter') === 'closed' ? 'bg-neutral-100' : '' }}">
                            <span class="text-neutral-800">Closed</span>
                            <span
                                class="rounded-md bg-neutral-200 px-2 py-0.5 text-xs font-semibold text-neutral-700">{{ $counts['closed'] }}</span>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['filter' => 'leads', 'session' => null, 'lead' => null]) }}"
   class="flex items-center justify-between rounded-md px-3 py-2 text-sm transition hover:bg-neutral-100 {{ request('filter') === 'leads' ? 'bg-neutral-100' : '' }}">
  <span class="text-neutral-800">Leads</span>
  <span class="rounded-md bg-blue-100 px-2 py-0.5 text-xs font-semibold text-blue-700">{{ $leadCount }}</span>
</a>

                        <div class="my-3 border-t border-neutral-200"></div>

                        <div class="px-1">
                            <div class="mb-2 text-xs font-semibold uppercase tracking-wide text-neutral-500">Quick Actions
                            </div>
                            <div class="space-y-2">
                                <a href="{{ route('widgets.index') }}"
                                    class="flex items-center gap-2 rounded-md border border-neutral-300 bg-white px-3 py-2 text-sm font-medium text-neutral-700 shadow-sm hover:bg-neutral-50 focus:outline-none focus:ring-2 focus:ring-neutral-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3 5a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5zm11 1H6v8l4-2 4 2V6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    All Widgets
                                </a>
                                <a href="{{ route('widgets.live', $widget) }}"
                                    class="flex items-center gap-2 rounded-md bg-emerald-600 px-3 py-2 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Live View
                                </a>
                                <div class="mt-1 text-sm text-neutral-500">
                                    Widget: <span class="font-medium text-neutral-700">{{ $widget->name }}</span>
                                    <span class="mx-1">•</span>
                                    API: <code
                                        class="rounded bg-neutral-100 px-1 py-0.5 text-neutral-700">{{ $widget->api_key }}</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- Sessions List - Full width on mobile, fixed width on lg+ --}}
                {{-- Sessions / Leads List --}}
                <section
                    class="{{ !$isLeads && ($selectedId ?? null) ? 'hidden lg:flex' : 'flex' }} w-full lg:w-[380px] min-w-0 flex-shrink-0 flex-col border-r border-neutral-200">
                    {{-- Mobile Header --}}
                    <div class="lg:hidden flex items-center justify-between border-b border-neutral-200 bg-white px-4 py-3">
                        <div class="flex items-center gap-3">
                            <button onclick="toggleMobileMenu()" class="p-2 text-neutral-600 hover:bg-neutral-100 rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <h1 class="text-lg font-semibold text-neutral-900">{{ $isLeads ? 'Leads' : 'Chat Sessions' }}
                            </h1>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('widgets.live', $widget) }}" target="_blank"
                                class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-medium text-white">
                                Live View
                            </a>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto">
  @if($isLeads)
      @forelse ($leads as $L)
          <a href="{{ request()->fullUrlWithQuery(['filter'=>'leads','lead'=>$L->id,'session'=>null]) }}"
             class="block border-b border-neutral-100 transition hover:bg-neutral-50 {{ $L->id === $selectedLeadId ? 'bg-neutral-50' : 'bg-white' }}">
              <div class="flex items-center gap-3 px-4 py-3.5">
                  <div class="grid h-9 w-9 place-items-center rounded-full bg-blue-100 text-sm font-extrabold text-blue-800">
                      {{ strtoupper(mb_substr(($L->name ?: $L->email ?: $L->mobile ?: 'L'), 0, 1)) }}
                  </div>
                  <div class="min-w-0 flex-1">
                      <div class="flex items-center justify-between gap-2">
                          <div class="truncate font-medium text-neutral-900">
                              {{ $L->name ?: ($L->email ?: ($L->mobile ?: 'Lead #'.$L->id)) }}
                          </div>
                          <span class="whitespace-nowrap text-xs text-neutral-500">
                              {{ $L->created_at->diffForHumans() }}
                          </span>
                      </div>
                      <div class="mt-0.5 space-x-3 truncate text-sm text-neutral-600">
                          @if($L->email)
                              <span> {{ $L->email }}</span>
                          @endif
                          @if($L->mobile)
                              <span>📞 {{ $L->mobile }}</span>
                          @endif
                      </div>
                  </div>
              </div>
          </a>
      @empty
          <div class="p-6 text-center text-sm text-neutral-500">No leads yet.</div>
      @endforelse
  @else
      {{-- original sessions list (unchanged) --}}
      @forelse ($sessions as $s)
          @php
              $last = $s->messages->first();
              $tag = !$s->messages->firstWhere('role', 'operator') ? 'unattended' : ($s->closed_at ? 'closed' : 'open');
          @endphp
          <a href="{{ request()->fullUrlWithQuery(['session' => $s->id, 'lead' => null]) }}"
             class="block border-b border-neutral-100 transition hover:bg-neutral-50 {{ $s->id === $selectedId ? 'bg-neutral-50' : 'bg-white' }}">
              <div class="flex items-center gap-3 px-4 py-3.5">
                  <div class="grid h-9 w-9 place-items-center rounded-full bg-amber-300 text-sm font-extrabold text-amber-900">
                      {{ $s->display_initial }}
                  </div>
                  <div class="min-w-0 flex-1">
                      <div class="flex items-center justify-between gap-2">
                          <div class="truncate font-medium text-neutral-900">{{ $s->display_name }}</div>
                          <div class="flex items-center gap-2">
                              @if ($tag === 'unattended')
                                  <span class="rounded-md bg-amber-100 px-2 py-0.5 text-xs font-semibold text-amber-700">Unattended</span>
                              @elseif($tag === 'open')
                                  <span class="rounded-md bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-700">Open</span>
                              @else
                                  <span class="rounded-md bg-neutral-200 px-2 py-0.5 text-xs font-semibold text-neutral-700">Closed</span>
                              @endif
                              <span class="whitespace-nowrap text-xs text-neutral-500">
                                  {{ optional($last?->created_at ?? $s->created_at)->diffForHumans() }}
                              </span>
                          </div>
                      </div>
                      <div class="truncate text-sm text-neutral-600">
                          {{ $last?->content ? Str::limit(strip_tags($last->content), 70) : 'Started a conversation' }}
                      </div>
                  </div>
              </div>
          </a>
      @empty
          <div class="p-6 text-center text-sm text-neutral-500">No sessions yet.</div>
      @endforelse
  @endif
</div>

<div class="border-t border-neutral-200 px-3 py-2">
  @if($isLeads)
      {{ $leads->appends(request()->except('page'))->links() }}
  @else
      {{ $sessions->appends(request()->except('page'))->links() }}
  @endif
</div>


                    <div class="border-t border-neutral-200 px-3 py-2">
                        {{ ($isLeads ? $leads : $sessions)->appends(request()->except('page'))->links() }}
                    </div>
                </section>


                {{-- Chat View - Hidden on mobile when no session selected, shown when session selected --}}
                {{-- Right pane: Lead details OR Chat view --}}
<section class="{{ $isLeads ? 'flex' : ($selectedId ? 'flex' : 'hidden lg:flex') }} w-full flex-1 min-w-0 flex-col relative bg-white">
  @if($isLeads)
      @if(empty($selectedLead))
          <div class="grid h-full place-items-center text-sm text-neutral-500">
              Select a lead to view.
          </div>
      @else
          <div class="h-full overflow-auto p-6">
              <div class="mx-auto w-full max-w-2xl">
                  <div class="mb-4">
                      <h2 class="text-xl font-semibold text-neutral-900">
                          {{ $selectedLead->name ?: 'Lead #'.$selectedLead->id }}
                      </h2>
                      <div class="text-sm text-neutral-500">
                          Captured {{ $selectedLead->created_at->diffForHumans() }}
                      </div>
                  </div>

                  <div class="rounded-lg border border-neutral-200 bg-white">
                      <div class="divide-y divide-neutral-200">
                          <div class="flex items-center justify-between p-4">
                              <div>
                                  <div class="text-xs uppercase text-neutral-500">Email</div>
                                  <div class="text-sm text-neutral-900">{{ $selectedLead->email ?: '—' }}</div>
                              </div>
                              @if($selectedLead->email)
                                  <a href="mailto:{{ $selectedLead->email }}" class="text-sm text-sky-700 hover:underline">Email</a>
                              @endif
                          </div>

                          <div class="flex items-center justify-between p-4">
                              <div>
                                  <div class="text-xs uppercase text-neutral-500">Mobile</div>
                                  <div class="text-sm text-neutral-900">{{ $selectedLead->mobile ?: '—' }}</div>
                              </div>
                              @if($selectedLead->mobile)
                                  <a href="tel:{{ preg_replace('/\s+/', '', $selectedLead->mobile) }}" class="text-sm text-sky-700 hover:underline">Call</a>
                              @endif
                          </div>

                          <div class="p-4">
                              <div class="text-xs uppercase text-neutral-500">Session</div>
                              <div class="mt-1 flex items-center gap-2">
                                  <code class="rounded bg-neutral-100 px-1 py-0.5 text-sm text-neutral-800">
                                      {{ $selectedLead->session_id }}
                                  </code>
                                  @if($selectedLead->session)
                                      <a href="{{ request()->fullUrlWithQuery(['filter'=>null,'session'=>$selectedLead->session->id,'lead'=>null]) }}"
                                         class="text-sm text-emerald-700 hover:underline">Open conversation</a>
                                  @endif
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      @endif
  @else
      @if (!$selected)
          <div class="grid h-full place-items-center text-sm text-neutral-500">
              Select a conversation to view.
          </div>
      @else
          <div id="chatWrap" data-widget-id="{{ $widget->id }}" data-session-id="{{ $selected->id }}"
               class="flex h-full min-w-0 flex-col">
              {{-- Chat Header --}}
              <div class="flex flex-shrink-0 items-center justify-between border-b border-neutral-200 bg-white px-4 py-3">
                  <div class="flex items-center">
                      <button onclick="backToSessions()"
                              class="lg:hidden -ml-2 mr-2 rounded-full p-2 text-neutral-600 transition hover:bg-neutral-100 hover:text-neutral-800">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                          </svg>
                      </button>
                      <div>
                          <div class="font-medium text-neutral-900">{{ $selected->display_name }}</div>
                          <div class="text-xs text-neutral-500">Session started {{ $selected->created_at->diffForHumans() }}</div>
                      </div>
                  </div>
                  <div class="flex items-center gap-2">
                      <form id="pauseForm" class="inline">
                          @csrf
                          <input type="hidden" name="pause" value="{{ $selected->bot_paused_until ? 0 : 1 }}">
                          <button type="submit"
                                  class="inline-flex items-center rounded-md border border-neutral-300 bg-white px-2.5 py-1.5 text-xs font-medium text-neutral-700 hover:bg-neutral-50">
                              @if ($selected->bot_paused_until) Resume Bot @else Pause Bot (30m) @endif
                          </button>
                      </form>
                  </div>
              </div>

              {{-- Chat Messages --}}
              <div id="chatFeed" class="flex-1 overflow-y-auto bg-neutral-50 p-4">
                  @foreach ($selected->messages->sortBy('id') as $m)
                      @php
                          $role = strtolower($m->role ?? '');
                          $isOperator = $role === 'operator';
                          $isSystem   = in_array($role, ['system','event']);
                          $isVisitor  = in_array($role, ['user','visitor']);
                          $isAssistant= in_array($role, ['assistant','bot','ai']);
                          $sideClass  = $isOperator ? 'justify-end' : 'justify-start';
                          if($isSystem){ $bubbleClass='bg-neutral-200 italic text-neutral-700 border border-neutral-200'; $roleLabel=strtoupper($role); $pillClass='bg-neutral-300 text-neutral-700'; }
                          elseif($isOperator){ $bubbleClass='bg-emerald-600 text-white shadow-sm'; $roleLabel='Operator'; $pillClass='bg-emerald-600/90 text-white'; }
                          elseif($isAssistant){ $bubbleClass='bg-white text-neutral-900 border border-neutral-200 shadow-sm'; $roleLabel='Assistant'; $pillClass='bg-sky-100 text-sky-800'; }
                          elseif($isVisitor){ $bubbleClass='bg-neutral-100 text-neutral-900 border border-neutral-200'; $roleLabel='Visitor'; $pillClass='bg-neutral-100 text-neutral-700'; }
                          else { $bubbleClass='bg-white text-neutral-900 border border-neutral-200'; $roleLabel=ucfirst($role ?: 'Message'); $pillClass='bg-neutral-100 text-neutral-700'; }
                      @endphp
                      <div class="mb-2 flex {{ $sideClass }}">
                          <div class="max-w-[85%] sm:max-w-[75%] rounded-2xl px-4 py-2.5 text-[15px] leading-relaxed {{ $bubbleClass }}">
                              {!! $isSystem ? e($m->content) : nl2br(e($m->content)) !!}
                              <div class="mt-1 flex items-center gap-2">
                                  <span class="text-[11px] {{ $isOperator ? 'text-white/90' : 'text-neutral-500' }}">{{ $m->created_at->format('g:i A') }}</span>
                                  <span class="rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide {{ $pillClass }}">{{ $roleLabel }}</span>
                              </div>
                          </div>
                      </div>
                  @endforeach
                  <div class="h-3"></div>
              </div>

              {{-- Message Input --}}
              <div class="flex-shrink-0 border-t border-neutral-200 bg-white px-4 py-3">
                  <form id="replyForm" class="flex items-end gap-2">
                      @csrf
                      <textarea id="replyBox" name="message"
                                class="h-12 max-h-40 w-full flex-1 resize-none overflow-y-auto rounded-lg border border-neutral-300 bg-white p-3 text-sm text-neutral-800 shadow-sm outline-none focus:border-neutral-400"
                                placeholder="Type your message..."></textarea>
                      <button id="sendBtn" type="submit"
                              class="inline-flex h-11 items-center rounded-md bg-emerald-600 px-4 text-sm font-medium text-white shadow-sm hover:bg-emerald-700">
                          SEND
                      </button>
                  </form>
              </div>
          </div>
      @endif
  @endif
</section>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            document.body.classList.toggle('startbar-open');
        }

        // Back to sessions list on mobile
        function backToSessions() {
            const url = new URL(window.location);
            url.searchParams.delete('session');
            window.location.href = url.toString();
        }

        // Auto-resize textarea
        function autosize(el) {
            el.style.height = 'auto';
            const max = 160;
            el.style.height = Math.min(el.scrollHeight, max) + 'px';
        }

        // Chat functionality
        document.addEventListener('DOMContentLoaded', function() {
            const wrap = document.getElementById('chatWrap');
            if (!wrap) return;

            const widgetId = wrap.dataset.widgetId;
            const sessionId = wrap.dataset.sessionId;
            const feed = document.getElementById('chatFeed');

            const scrollBottom = () => {
                if (feed) feed.scrollTop = feed.scrollHeight;
            };
            scrollBottom();

            // Textarea autosize
            const replyBox = document.getElementById('replyBox');
            if (replyBox) {
                autosize(replyBox);
                replyBox.addEventListener('input', () => autosize(replyBox));
            }

            // Pause/Resume functionality
            const pauseForm = document.getElementById('pauseForm');
            if (pauseForm) {
                pauseForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const form = new FormData(pauseForm);
                    if (form.get('pause') === '1') form.append('minutes', '30');

                    try {
                        const res = await fetch(
                            `/widgets/${widgetId}/sessions/${sessionId}/toggle-pause`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: form
                            });
                        if (res.ok) location.reload();
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Message sending
            const replyForm = document.getElementById('replyForm');
            const sendBtn = document.getElementById('sendBtn');
            if (replyForm) {
                replyForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    const text = (replyBox.value || '').trim();
                    if (!text) return;

                    const body = new FormData();
                    body.append('message', text);

                    sendBtn.disabled = true;
                    try {
                        const res = await fetch(
                            `/widgets/${widgetId}/sessions/${sessionId}/operator-reply`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body
                            });

                        if (!res.ok) throw new Error('Send failed');

                        // Add message to chat
                        const row = document.createElement('div');
                        row.className = 'mb-2 flex justify-end';
                        const bubble = document.createElement('div');
                        bubble.className =
                            'max-w-[85%] sm:max-w-[75%] rounded-2xl bg-sky-100 px-4 py-2.5 text-[15px] leading-relaxed text-neutral-900';
                        bubble.innerHTML =
                            `${text.replace(/\n/g, '<br>')}<div class="mt-1 text-[11px] text-neutral-500"><span>just now </span> <span class="rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide bg-emerald-600/90 text-white">Operator</span></div>`;
                        row.appendChild(bubble);
                        feed.appendChild(row);

                        replyBox.value = '';
                        autosize(replyBox);
                        scrollBottom();
                    } catch (error) {
                        alert('Could not send reply. Please try again.');
                    } finally {
                        sendBtn.disabled = false;
                    }
                });

                // Enter to send, Shift+Enter for newline
                replyBox.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' && !e.shiftKey) {
                        e.preventDefault();
                        sendBtn.click();
                    }
                });
            }

            // Close mobile menu when clicking outside
            document.addEventListener('click', (e) => {
                if (!e.target.closest('.logs-container') && document.body.classList.contains(
                        'startbar-open')) {
                    document.body.classList.remove('startbar-open');
                }
            });
        });
    </script>
@endpush
