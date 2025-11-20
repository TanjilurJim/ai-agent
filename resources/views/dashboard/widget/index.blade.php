@extends('dashboard.layout')
@section('content')
    @php
        $user = auth()->user();
        $widgetCount = $user->widgets()->count();
        $planLimit = $user->widgetLimit(); // from User model helper
        $canCreateMore = $user->isAdmin() || $widgetCount < $planLimit;
    @endphp

    <div class="page-content">
        <div class="container-fluid">

            <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                <h4 class="page-title">Your Widgets</h4>

                @if ($canCreateMore)
                    <a href="{{ route('widgets.create') }}" class="btn btn-primary">
                        <i class="las la-plus me-1"></i> New Widget
                    </a>
                @else
                    <button class="btn btn-secondary" type="button" disabled>
                        <i class="las la-lock me-1"></i> Limit reached
                    </button>
                @endif


            </div>
            @if (!$user->isAdmin())
                <div class="alert alert-{{ $canCreateMore ? 'info' : 'warning' }} mt-2">
                    You are using <strong>{{ $widgetCount }} / {{ $planLimit }}</strong> widgets allowed on your plan.
                    @unless ($canCreateMore)
                        <br>
                        Please upgrade your plan to create more widgets.
                    @endunless
                    {{-- Later you can link to a pricing/upgrade page --}}
                    {{-- <a href="{{ route('billing.upgrade') }}">Upgrade now</a> --}}
                </div>
            @endif


            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-3">
                @forelse($widgets as $w)
                    <div class="col-12 col-md-6 col-xl-4">
                        <div class="card h-100">
                            <div class="card-body d-flex">
                                <img src="{{ $w->avatar ? asset($w->avatar) : asset('assets/images/users/avatar-1.jpg') }}"
                                    class="rounded-circle me-3" width="48" height="48" alt="">
                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-0 text-truncate">{{ $w->name }}</h6>
                                        <span
                                            class="badge bg-{{ $w->is_active ? 'success' : 'secondary' }}">{{ $w->is_active ? 'Active' : 'Inactive' }}</span>
                                    </div>
                                    <div class="small text-body-secondary text-truncate">{{ $w->widgetName ?? '—' }}</div>
                                    <div class="small mt-1">API Key:
                                        <code>{{ \Illuminate\Support\Str::limit($w->api_key, 16) }}</code>
                                    </div>
                                    <div class="mt-2 d-flex flex-wrap gap-2">
                                        <a class="btn btn-sm btn-outline-primary"
                                            href="{{ route('widgets.edit', $w) }}">Edit</a>
                                        <form method="POST" action="{{ route('widgets.toggle', $w) }}">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-secondary" type="submit">
                                                {{ $w->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('widgets.destroy', $w) }}"
                                            onsubmit="return confirm('Delete this widget?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                                        </form>
                                        <a class="btn btn-sm btn-outline-success"
                                            href="{{ route('widgets.live', $w) }}">Live</a>
                                        <a class="btn btn-sm btn-outline-info"
                                            href="{{ route('widgets.logs', $w) }}">Logs</a>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer small d-flex flex-column gap-2">
                                @php
                                    // Build from .env APP_URL safely
                                    $base = rtrim(config('app.url'), '/'); // uses APP_URL
                                    $scriptSrc = $base . '/chat/script/main.js?api_key=' . $w->api_key;
                                    $scriptTag = '<script src="' . $scriptSrc . '" defer></script>';
                                @endphp

                                <div>
                                    Embed:
                                    <code class="d-inline-block">{!! e($scriptTag) !!}</code>
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-secondary align-self-start copy-btn"
                                    data-script-tag="{{ $scriptTag }}" onclick="copyToClipboard(this)">
                                    Copy
                                </button>
                            </div>

                            <script>
                                function copyToClipboard(button) {
                                    const scriptTag = button.getAttribute('data-script-tag');

                                    // Try modern clipboard API first
                                    if (navigator.clipboard && window.isSecureContext) {
                                        navigator.clipboard.writeText(scriptTag).then(() => {
                                            showCopiedFeedback(button);
                                        }).catch(err => {
                                            console.error('Failed to copy: ', err);
                                            fallbackCopyTextToClipboard(scriptTag, button);
                                        });
                                    } else {
                                        // Fallback for older browsers or non-HTTPS
                                        fallbackCopyTextToClipboard(scriptTag, button);
                                    }
                                }

                                function fallbackCopyTextToClipboard(text, button) {
                                    const textArea = document.createElement("textarea");
                                    textArea.value = text;
                                    textArea.style.position = "fixed";
                                    textArea.style.left = "-999999px";
                                    textArea.style.top = "-999999px";
                                    document.body.appendChild(textArea);
                                    textArea.focus();
                                    textArea.select();

                                    try {
                                        document.execCommand('copy');
                                        showCopiedFeedback(button);
                                    } catch (err) {
                                        console.error('Fallback: Oops, unable to copy', err);
                                        button.textContent = "Copy failed";
                                        setTimeout(() => button.textContent = "Copy", 2000);
                                    }

                                    document.body.removeChild(textArea);
                                }

                                function showCopiedFeedback(button) {
                                    button.textContent = "Copied!";
                                    button.classList.add('btn-success');
                                    button.classList.remove('btn-outline-secondary');

                                    setTimeout(() => {
                                        button.textContent = "Copy";
                                        button.classList.remove('btn-success');
                                        button.classList.add('btn-outline-secondary');
                                    }, 1200);
                                }
                            </script>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info mb-0">No widgets yet. Create your first one!</div>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">
                {{ $widgets->links() }}
            </div>
        </div>
    </div>
@endsection
