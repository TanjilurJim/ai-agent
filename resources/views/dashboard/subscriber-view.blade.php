@extends('dashboard.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <!-- Title / breadcrumbs -->
            <div class="row mb-3">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="page-title">Subscriber Details</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard.subscriber') }}">Subscribers</a></li>
                                <li class="breadcrumb-item active">View</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main card -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- header row -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1">{{ $subscriber->user?->name ?? 'Unknown user' }}</h5>
                                    <small class="text-muted">
                                        Subscriber ID: {{ $subscriber->id }} •
                                        Joined: {{ optional($subscriber->created_at)->format('Y-m-d H:i') ?? 'N/A' }}
                                    </small>
                                </div>

                                <div class="text-end">
                                    <span
                                        class="badge bg-{{ strtolower($subscriber->status ?? '') === 'active' ? 'success' : (strtolower($subscriber->status ?? '') === 'pending' ? 'warning' : 'secondary') }}">
                                        {{ ucfirst($subscriber->status ?? 'unknown') }}
                                    </span>
                                </div>
                            </div>

                            <hr>

                            <!-- Details -->
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="text-muted small">Name</h6>
                                    <p class="mb-0">{{ $subscriber->user?->name ?? '—' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-muted small">Email</h6>
                                    <p class="mb-0">{{ $subscriber->user?->email ?? '—' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-muted small">Widget</h6>
                                    @if ($subscriber->widget)
                                        <p class="mb-0">
                                            <a href="{{ route('widgets.edit', $subscriber->widget) }}">
                                                {{ $subscriber->widget->name }}
                                            </a>
                                        </p>
                                        <small class="text-muted">API: {{ $subscriber->widget->api_key }}</small>
                                    @else
                                        <p class="mb-0">—</p>
                                    @endif
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-muted small">API Key</h6>
                                    <p class="mb-0"><code>{{ $subscriber->api_key }}</code></p>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-muted small">Token</h6>
                                    <p class="mb-0">{{ $subscriber->token ?? '—' }}</p>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-muted small">Created At</h6>
                                    <p class="mb-0">{{ optional($subscriber->created_at)->toDayDateTimeString() ?? '—' }}
                                    </p>
                                </div>

                                <div class="col-12 mt-3">
                                    <h6 class="text-muted small">Notes</h6>
                                    <p class="mb-0 text-muted">You can edit status from the Edit button or delete this
                                        subscriber below.</p>
                                </div>
                            </div>

                            <hr>

                            <!-- Actions -->
                            <div class="d-flex gap-2 justify-content-between align-items-center mt-3">
                                <div>
                                    <a href="{{ route('dashboard.subscriber') }}" class="btn btn-outline-secondary">
                                        <i class="mdi mdi-arrow-left me-1"></i> Back to list
                                    </a>
                                </div>

                                <div class="d-flex gap-2">
                                    <!-- Edit modal trigger (reuses existing modal in list view if included globally)
                                         If you want a dedicated edit page, point to it instead. -->
                                    <button type="button" class="btn btn-warning" id="openEditModalBtn"
                                        data-bs-toggle="modal" data-bs-target="#editSubscriberModal"
                                        data-id="{{ $subscriber->id }}" data-status="{{ $subscriber->status }}">
                                        <i class="fa-regular fa-pen-to-square me-1"></i> Edit Status
                                    </button>

                                    <form action="{{ route('subscribers.destroy', $subscriber->id) }}" method="POST"
                                        class="m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-danger" id="deleteBtn">
                                            <i class="fa-regular fa-trash-can me-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Reuse the Edit Subscriber Modal markup (same as in list) -->
            <!-- If that modal is placed in a layout or included partial, you can skip duplicating it here. -->
            <div class="modal fade" id="editSubscriberModal" tabindex="-1" aria-labelledby="editSubscriberModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editSubscriberForm" method="POST" action="">
                            @csrf @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSubscriberModalLabel">Edit Subscriber</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Delete confirm (same behavior as the list view)
            document.getElementById('deleteBtn')?.addEventListener('click', function() {
                const form = this.closest('form');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will permanently delete the subscriber.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(res => {
                    if (res.isConfirmed) form.submit();
                });
            });

            // Edit modal wiring: set form action and status when opening the modal
            const editModalBtn = document.getElementById('openEditModalBtn');
            const editForm = document.getElementById('editSubscriberForm');
            const statusInput = document.getElementById('subscriberStatus');

            if (editModalBtn) {
                editModalBtn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status') || 'pending';
                    editForm.action = "/subscribers/" + id;
                    statusInput.value = status;
                });
            }
        });
    </script>
@endsection
