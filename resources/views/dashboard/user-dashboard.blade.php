@extends('dashboard.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-between align-items-center">
                        <h4 class="page-title">Welcome, {{ $user->name }} 👋</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Friendly welcome message -->
            <div class="alert alert-primary mt-2">
                <strong>Hello {{ $user->name }}!</strong><br>
                This is your personal dashboard.
                You can manage your widgets, see your subscription status, and track your activity.
            </div>

            <!-- User widgets -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header">
                            <h5 class="card-title m-0">Your Widgets</h5>
                        </div>
                        <div class="card-body">

                            @php
                                $widgets = $user->widgets ?? [];
                            @endphp

                            @if ($widgets && count($widgets))
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>API Key</th>
                                            <th>Status</th>
                                            <th>Edit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($widgets as $widget)
                                            <tr>
                                                <td>{{ $widget->name }}</td>
                                                <td><code>{{ $widget->api_key }}</code></td>
                                                <td>{{ $widget->is_active ? 'Active' : 'Inactive' }}</td>
                                                <td>
                                                    <a href="{{ route('widgets.edit', $widget) }}"
                                                        class="btn btn-sm btn-primary">Edit</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="text-center py-4 text-muted">
                                    You don't have any widgets yet.
                                    <br>
                                    <a href="{{ route('widgets.create') }}" class="btn btn-primary mt-3">Create Your First
                                        Widget</a>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
