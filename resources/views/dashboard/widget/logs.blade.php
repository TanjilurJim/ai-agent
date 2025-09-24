@extends('dashboard.layout')

@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="page-title mb-0">Logs: {{ $widget->name }}</h4>
      <div class="d-flex gap-2">
        <a class="btn btn-outline-secondary" href="{{ route('widgets.edit', $widget) }}">Back to Widget</a>
        <a class="btn btn-outline-success" href="{{ route('widgets.live', $widget) }}">Live</a>
      </div>
    </div>

    @foreach($sessions as $s)
      <div class="card mb-3">
        <div class="card-header">
          Session <code>{{ $s->session_id }}</code>
          <small class="text-muted"> • started {{ $s->created_at->diffForHumans() }}</small>
          @if($s->name || $s->mobile || $s->email)
            <div class="small mt-1 text-muted">
              Lead: {{ $s->name ?? '—' }} • {{ $s->mobile ?? '—' }} • {{ $s->email ?? '—' }}
            </div>
          @endif
        </div>
        <ul class="list-group list-group-flush">
          @foreach($s->messages()->oldest()->get() as $m)
            <li class="list-group-item">
              <span class="badge {{ $m->role === 'user' ? 'bg-primary' : 'bg-success' }}">
                {{ $m->role }}
              </span>
              <span class="ms-2" style="white-space:pre-wrap">{{ $m->content }}</span>
              <small class="text-muted float-end">{{ $m->created_at->format('Y-m-d H:i:s') }}</small>
            </li>
          @endforeach
        </ul>
      </div>
    @endforeach

    {{ $sessions->links() }}
  </div>
</div>
@endsection
