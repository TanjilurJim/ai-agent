@extends('dashboard.layout')

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

    <div class="card">
      <div class="card-header">Realtime Messages (widget #{{ $widget->id }})</div>
      <div class="card-body">
        <table class="table table-striped">
          <thead>
            <tr><th>Session</th><th>Role</th><th>Message</th><th>Time</th></tr>
          </thead>
          <tbody id="liveRows"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script type="module">
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
  // If dashboard is same-origin with backend, auth is cookie-based automatically.
});

const widgetId = {{ $widget->id }};
window.Echo.private(`widgets.${widgetId}`)
  .listen('.message.created', (e) => {
    appendLiveRow(e.payload);
  });

function appendLiveRow(msg) {
  const tr = document.createElement('tr');
  tr.innerHTML = `
    <td>${escapeHtml(msg.session_id ?? '')}</td>
    <td>
      <span class="badge ${msg.role === 'user' ? 'bg-primary' : 'bg-success'}">
        ${escapeHtml(msg.role)}
      </span>
    </td>
    <td style="max-width:600px; white-space:pre-wrap">${escapeHtml(msg.content ?? '')}</td>
    <td>${new Date(msg.created_at).toLocaleString()}</td>
  `;
  document.getElementById('liveRows')?.prepend(tr);
}

function escapeHtml(s=''){
  return s.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
}
</script>
@endpush
