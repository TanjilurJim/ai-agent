@extends('dashboard.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="page-title">Subscriber List & Widgets</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Subscriber</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flash --}}
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: @json(session('success')),
                            confirmButtonColor: '#3085d6'
                        });
                    });
                </script>
            @endif
            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: @json(session('error')),
                            confirmButtonColor: '#d33'
                        });
                    });
                </script>
            @endif

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Subscriber List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Widget</th>
                                            <th>Status</th>
                                            <th>API Key</th>
                                            <th>Token</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subscribers as $subscriber)
                                            @php
                                                $status = strtolower($subscriber->status ?? '');
                                                $badge =
                                                    [
                                                        'active' => 'success',
                                                        'pending' => 'warning',
                                                        'reject' => 'danger',
                                                    ][$status] ?? 'secondary';
                                            @endphp
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <td class="text-break">{{ $subscriber->user?->name ?? '—' }}</td>


                                                {{-- Widget column (linked to edit) --}}
                                                <td class="text-break">
                                                    @if ($subscriber->widget)
                                                        <a href="{{ route('widgets.edit', $subscriber->widget) }}"
                                                            class="text-decoration-underline">
                                                            {{ $subscriber->widget->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>

                                                {{-- Status column --}}
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $badge }}">{{ ucfirst($status ?: 'unknown') }}</span>
                                                </td>

                                                {{-- API Key + Token --}}
                                                <td class="text-break break-all"><code>{{ $subscriber->api_key }}</code>
                                                </td>
                                                <td>{{ $subscriber->token }}</td>

                                                {{-- Actions --}}
                                                <td class="text-center text-md-start text-nowrap align-middle">
                                                    <div class="d-inline-flex gap-2 align-items-center">
                                                        <!-- View button -->
                                                        <a href="{{ route('subscribers.show', $subscriber->id) }}"
                                                            class="btn btn-primary btn-sm">
                                                            <i class="fa-regular fa-eye me-1"></i>
                                                            <span class="d-none d-sm-inline">View</span>
                                                        </a>

                                                        <!-- Delete (keeps existing JS confirm behavior) -->
                                                        <form action="{{ route('subscribers.destroy', $subscriber->id) }}"
                                                            method="POST" class="m-0">
                                                            @csrf @method('DELETE')
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn">
                                                                <i class="fa-regular fa-trash-can me-1"></i>
                                                                <span class="d-none d-sm-inline">Delete</span>
                                                            </button>
                                                        </form>

                                                        <!-- Edit modal trigger -->
                                                        <button type="button" class="btn btn-warning btn-sm edit-btn"
                                                            data-bs-toggle="modal" data-bs-target="#editSubscriberModal"
                                                            data-id="{{ $subscriber->id }}"
                                                            data-status="{{ $subscriber->status }}">
                                                            <i class="fa-regular fa-pen-to-square me-1"></i>
                                                            <span class="d-none d-sm-inline">Edit</span>
                                                        </button>
                                                    </div>

                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">No subscribers yet.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="small text-muted">
                    Showing {{ $subscribers->firstItem() }} to {{ $subscribers->lastItem() }}
                    of {{ $subscribers->total() }} results
                </div>
                {{ $subscribers->links('pagination::bootstrap-5') }} {{-- 👈 Bootstrap styles --}}
            </div>

        </div>
    </div>

    {{-- Edit Subscriber Modal --}}
    <div class="modal fade" id="editSubscriberModal" tabindex="-1" aria-labelledby="editSubscriberModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editSubscriberForm" method="POST" action="">
                    @csrf @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubscriberModalLabel">Edit Subscriber</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="subscriberStatus" class="form-label">Status</label>
                            <select class="form-select" id="subscriberStatus" name="status" required>
                                <option value="active">Active</option>
                                <option value="reject">Reject</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirm
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const form = this.closest('form');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then(res => {
                        if (res.isConfirmed) form.submit();
                    });
                });
            });

            // Edit modal
            const editForm = document.getElementById('editSubscriberForm');
            const statusInput = document.getElementById('subscriberStatus');

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status');
                    // If you have a named route, prefer: editForm.action = "{{ url('/subscribers') }}/" + id";
                    editForm.action = "/subscribers/" + id;
                    statusInput.value = status || 'pending';
                });
            });
        });
    </script>
@endsection
