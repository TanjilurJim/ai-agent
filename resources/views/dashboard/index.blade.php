@extends('dashboard.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Page Header -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="page-title">Dashboard</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Top Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small">Total Tokens</p>
                                    <h4 class="mb-0 fw-bold">1,000</h4>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    <i class="mdi mdi-lightning-bolt"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small">Total Requests</p>
                                    <h4 class="mb-0 fw-bold">{{ $totalWidgetRequests ?? 0 }}</h4>
                                </div>
                                <span class="badge bg-success rounded-pill">
                                    <i class="mdi mdi-chart-line"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small">Wallet Balance</p>
                                    <h4 class="mb-0 fw-bold">500 BDT</h4>
                                </div>
                                <span class="badge bg-warning rounded-pill">
                                    <i class="mdi mdi-wallet"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <p class="text-muted mb-2 small">Active Widgets</p>
                                    <h4 class="mb-0 fw-bold">{{ $widgetRequestsByWidget->count() ?? 0 }}</h4>
                                </div>
                                <span class="badge bg-info rounded-pill">
                                    <i class="mdi mdi-cube"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Stats Row -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title mb-3 fw-bold">Total Users</h6>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 fw-bold text-primary">{{ $totalUsers ?? 0 }}</h2>
                                <span class="badge bg-light text-dark ms-2">
                                    <i class="mdi mdi-account-multiple"></i> Registered
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title mb-3 fw-bold">New Users (Last 7 Days)</h6>
                            <div class="d-flex align-items-end">
                                <h2 class="mb-0 fw-bold text-success">{{ $newUsersThisWeek ?? 0 }}</h2>
                                <span class="badge bg-light text-dark ms-2">
                                    <i class="mdi mdi-account-plus"></i> This Week
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Widget Requests Section -->
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0 fw-bold">
                                    <i class="mdi mdi-cube-outline me-2"></i>Widget Request Activity
                                </h6>
                                <span class="badge bg-primary">{{ $totalWidgetRequests ?? 0 }} Total</span>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (!empty($widgetRequestsByWidget) && $widgetRequestsByWidget->count())
                                <!-- Table for desktop view -->
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="fw-bold">Widget Name</th>
                                                <th class="fw-bold text-end">Request Count</th>
                                                <th class="fw-bold text-end">Percentage</th>
                                                <th class="fw-bold">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $total = $widgetRequestsByWidget->sum('requests_count');
                                            @endphp
                                            @foreach ($widgetRequestsByWidget->take(7) as $wr)
                                                @php
                                                    $percentage = $total > 0 ? round(($wr->requests_count / $total) * 100, 1) : 0;
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm me-2 flex-shrink-0">
                                                                <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                    <i class="mdi mdi-cube"></i>
                                                                </span>
                                                            </div>
                                                            <div>
                                                                <p class="mb-0 fw-semibold">
                                                                    {{ $wr->widget->name ?? 'Unknown Widget' }}
                                                                </p>
                                                                <small class="text-muted">
                                                                    API: {{ substr($wr->widget->api_key ?? 'N/A', 0, 8) }}...
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="badge bg-info rounded-pill">
                                                            {{ $wr->requests_count }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end">
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <div class="progress me-2" style="width: 60px; height: 20px;">
                                                                <div class="progress-bar bg-success" 
                                                                     role="progressbar" 
                                                                     style="width: {{ $percentage }}%;"
                                                                     aria-valuenow="{{ $percentage }}" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <small class="fw-semibold">{{ $percentage }}%</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success-subtle text-success">Active</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- View All Link -->
                                <div class="mt-3 text-center">
                                    <a href="{{ route('dashboard.subscriber') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="mdi mdi-eye me-1"></i> View All Subscribers & Requests
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3">
                                        <i class="mdi mdi-cube-off text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <p class="text-muted mb-0">No widget requests yet. Widgets will appear here as they receive requests.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light border-bottom">
                            <h6 class="card-title mb-0 fw-bold">
                                <i class="mdi mdi-chart-line me-2"></i>User Requests Trend
                            </h6>
                        </div>
                        <div class="card-body">
                            <canvas id="userRequestsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('userRequestsChart').getContext('2d');
        var userRequestsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'User Requests',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endsection