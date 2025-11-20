@extends('dashboard.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-between align-items-center">
                        <h4 class="page-title">Manage Plans</h4>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Plans</li>
                        </ol>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted small">
                        Here you can edit the limits for each plan. Changes affect all users on that plan.
                    </p>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Widget limit</th>
                                    <th>Personality limit</th>
                                    <th>Daily messages</th>
                                    <th>Save</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $plan)
                                    <tr>
                                        <form method="POST" action="{{ route('dashboard.plans.update', $plan) }}">
                                            @csrf
                                            @method('PUT')
                                            <td style="max-width: 150px;">
                                                <input type="text"
                                                    name="name"
                                                    class="form-control form-control-sm @error('name') is-invalid @enderror"
                                                    value="{{ old('name', $plan->name) }}">
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="widget_limit"
                                                    min="0"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('widget_limit', $plan->widget_limit) }}">
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="personality_limit"
                                                    min="0"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('personality_limit', $plan->personality_limit) }}">
                                            </td>
                                            <td>
                                                <input type="number"
                                                    name="daily_prompt_limit"
                                                    min="0"
                                                    class="form-control form-control-sm"
                                                    value="{{ old('daily_prompt_limit', $plan->daily_prompt_limit) }}">
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    Save
                                                </button>
                                            </td>
                                        </form>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
