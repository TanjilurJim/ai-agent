@extends('dashboard.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Title / breadcrumb -->
            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="page-title">User Profile</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.user') }}">Users</a></li>
                                <li class="breadcrumb-item active">View</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic info and stats -->
            <div class="row mb-4">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="mb-1">
                                        {{ $user->name }}
                                        @if ($user->isAdmin())
                                            <span class="badge bg-dark ms-1">Admin</span>
                                        @endif
                                    </h5>
                                    <p class="text-muted mb-1">{{ $user->email }}</p>
                                    <div class="mb-1">
                                        <small class="text-muted">Role: {{ $user->role ?? 'user' }}</small>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            Joined: {{ optional($user->created_at)->toDayDateTimeString() ?? 'N/A' }}
                                        </small>
                                    </div>

                                    <div class="mt-2">
                                        <span class="badge bg-primary text-uppercase">
                                            Plan: {{ $user->plan->name ?? 'free' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a href="{{ route('dashboard.user') }}" class="btn btn-outline-secondary btn-sm mb-2">
                                        <i class="mdi mdi-arrow-left me-1"></i> Back to list
                                    </a>
                                    <div class="mt-2">
                                        <span class="badge bg-primary">Widgets: {{ $widgetCount }} /
                                            {{ $widgetLimit }}</span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            {{-- Usage stats --}}
                            <h6 class="mb-3">Usage & Limits</h6>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="small text-muted">Widgets</div>
                                    <div><strong>{{ $widgetCount }} / {{ $widgetLimit }}</strong></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Personalities</div>
                                    <div><strong>{{ $personalityCount }} / {{ $personalityLimit }}</strong></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small text-muted">Daily messages (today)</div>
                                    <div><strong>{{ $todayUsage }} / {{ $dailyPromptLimit }}</strong></div>
                                </div>
                            </div>

                            <hr>

                            <h6 class="mb-3">Recent Widgets</h6>
                            @if ($recentWidgets->isNotEmpty())
                                <div class="list-group list-group-flush">
                                    @foreach ($recentWidgets as $w)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $w->name ?? ($w->widgetName ?? 'Unnamed') }}</strong>
                                                <div class="small text-muted">Created:
                                                    {{ optional($w->created_at)->format('Y-m-d') }}</div>
                                                <div class="small text-muted">API: {{ $w->api_key }}</div>
                                            </div>
                                            <div>
                                                <a href="{{ route('widgets.edit', $w) }}"
                                                    class="btn btn-sm btn-outline-primary">Edit</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted">No widgets for this user yet.</p>
                            @endif

                            <hr>

                            <h6 class="mb-3">Subscriptions</h6>
                            @if ($user->subscriptions->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>API Key</th>
                                                {{-- <th>Tokens</th> --}}
                                                <th>Status</th>
                                                <th>Actions</th>
                                                <th>Created</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($user->subscriptions as $sub)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>

                                                    <td class="text-break">
                                                        <code>{{ $sub->api_key }}</code>
                                                        <br>
                                                        <form
                                                            action="{{ route('subscription.regenerateApiKey', $sub->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('PUT')
                                                            <button
                                                                class="btn btn-sm btn-link text-danger p-0">Regenerate</button>
                                                        </form>
                                                    </td>

                                                    {{-- <td>{{ $sub->token }}</td> --}}

                                                    <td>
                                                        @php $status = strtolower($sub->status ?? ''); @endphp
                                                        <span
                                                            class="badge bg-{{ $status === 'active' ? 'success' : ($status === 'pending' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst($sub->status ?? 'unknown') }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        {{-- Activate --}}
                                                        <form action="{{ route('subscription.updateStatus', $sub->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="active">
                                                            <button class="btn btn-sm btn-success">Activate</button>
                                                        </form>

                                                        {{-- Deactivate --}}
                                                        <form action="{{ route('subscription.updateStatus', $sub->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('PUT')
                                                            <input type="hidden" name="status" value="inactive">
                                                            <button class="btn btn-sm btn-warning">Deactivate</button>
                                                        </form>

                                                        {{-- Reset Tokens --}}
                                                        {{-- <form action="{{ route('subscription.resetToken', $sub->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf @method('PUT')
                                                            <button class="btn btn-sm btn-secondary">Reset Tokens</button>
                                                        </form> --}}
                                                    </td>

                                                    <td>{{ optional($sub->created_at)->format('Y-m-d') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No subscriptions found for this user.</p>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Right column: profile & quick actions -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body">
                            <h6 class="card-title">Profile</h6>
                            <p class="mb-1"><strong>Name:</strong> {{ $user->name }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                            <p class="mb-1"><strong>Role:</strong> {{ $user->role ?? 'user' }}</p>
                            <p class="mb-1"><strong>Joined:</strong>
                                {{ optional($user->created_at)->toDateString() ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Quick Actions</h6>

                            {{-- Change Plan --}}
                            <form action="{{ route('user.updatePlan', $user->id) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PUT')
                                <div class="mb-2">
                                    <label class="form-label small">Plan</label>
                                    <select name="plan_id" class="form-select form-select-sm">
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}"
                                                {{ $user->plan_id == $plan->id ? 'selected' : '' }}>
                                                {{ ucfirst($plan->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                    Update Plan
                                </button>
                            </form>

                            {{-- Delete user --}}
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('Delete this user? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100">Delete User</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div> <!-- row -->
        </div>
    </div>
@endsection
