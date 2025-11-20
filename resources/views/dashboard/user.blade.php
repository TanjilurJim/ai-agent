@extends('dashboard.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="page-title">User List</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">User</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('dashboard.user') }}" class="card card-body border-0 shadow-sm">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       value="{{ $filters['search'] ?? '' }}"
                                       placeholder="Search by name or email">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Plan</label>
                                <select name="plan_id" class="form-select">
                                    <option value="all">All plans</option>
                                    @foreach ($plans as $plan)
                                        <option value="{{ $plan->id }}"
                                            {{ ($filters['plan_id'] ?? 'all') == $plan->id ? 'selected' : '' }}>
                                            {{ ucfirst($plan->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="all" {{ ($filters['role'] ?? 'all') == 'all' ? 'selected' : '' }}>All</option>
                                    <option value="admin" {{ ($filters['role'] ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ ($filters['role'] ?? '') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-magnifying-glass me-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- User table --}}
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">User List</h4>
                            @if (session('success'))
                                <span class="text-success small">{{ session('success') }}</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Plan</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $item)
                                        <tr>
                                            <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->role ?? 'user' }}</td>
                                            <td>
                                                <form action="{{ route('user.updatePlan', $item->id) }}"
                                                      method="POST"
                                                      class="d-flex gap-2 align-items-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="plan_id" class="form-select form-select-sm w-auto">
                                                        @foreach ($plans as $plan)
                                                            <option value="{{ $plan->id }}"
                                                                {{ $item->plan_id == $plan->id ? 'selected' : '' }}>
                                                                {{ ucfirst($plan->name) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        Update
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="{{ route('user.show', $item->id) }}"
                                                   class="btn btn-primary btn-sm me-1">
                                                    <i class="fa-regular fa-eye me-1"></i>
                                                    <span class="d-none d-sm-inline">View</span>
                                                </a>

                                                <form action="{{ route('user.destroy', $item->id) }}"
                                                      method="POST"
                                                      class="delete-form d-inline-block"
                                                      data-id="{{ $item->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button"
                                                            class="btn btn-danger btn-sm delete-btn">
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                No users found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert delete confirm (unchanged) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    const form = this.closest('form');
                    const userId = form.dataset.id;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
