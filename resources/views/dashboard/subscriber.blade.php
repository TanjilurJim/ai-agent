@extends('dashboard.layout')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Subscriber List</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                            <li class="breadcrumb-item active">Subscriber</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success and Error Messages -->
        @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                });
            });
        </script>
        @endif

        @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>API Key</th>
                                    <th>Token</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($subscribers as $subscriber)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $subscriber->user->name }}</td>
                                    <td>{{ $subscriber->status }}</td>
                                    <td>{{ $subscriber->api_key }}</td>
                                    <td>{{ $subscriber->token }}</td>
                                    <td class="text-center d-flex justify-content-center gap-2 align-items-center">
                                        <form class="" style="height: 14px !important;" action="{{ route('subscribers.destroy', $subscriber->id) }}" method="POST" data-id="{{ $subscriber->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                        </form>
                                        <button type="button" class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editSubscriberModal" data-id="{{ $subscriber->id }}" data-name="{{ $subscriber->name }}" data-status="{{ $subscriber->status }}">Edit</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Subscriber Modal -->
<div class="modal fade" id="editSubscriberModal" tabindex="-1" aria-labelledby="editSubscriberModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSubscriberForm" method="POST" action="">
                @csrf
                @method('PUT')
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
    document.addEventListener('DOMContentLoaded', function () {
        // Delete Confirmation
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
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

        // Edit Subscriber Modal
        const editButtons = document.querySelectorAll('.edit-btn');
        const editForm = document.getElementById('editSubscriberForm');
        const subscriberNameInput = document.getElementById('subscriberName');
        const subscriberStatusInput = document.getElementById('subscriberStatus');

        editButtons.forEach(button => {
            button.addEventListener('click', function () {
                const subscriberId = this.getAttribute('data-id');
                const subscriberName = this.getAttribute('data-name');
                const subscriberStatus = this.getAttribute('data-status');

                editForm.action = `/subscribers/${subscriberId}`;
                subscriberNameInput.value = subscriberName;
                subscriberStatusInput.value = subscriberStatus;
            });
        });
    });
</script>
@endsection
+