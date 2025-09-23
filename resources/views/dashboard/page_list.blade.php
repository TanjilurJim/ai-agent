@extends('dashboard.layout')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h5 class="fw-medium"><i class="fa-solid fa-file-lines"></i> Personality List</h5>
                    <a href="/dashboard/train-bot/add-page" class="btn btn-outline-primary"><i  style="margin-right: 5px" class="fa-solid fa-plus"></i> Add New Personality</a>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row mt-3">
            <div class="col-md-6">
                <input type="text" id="search" class="form-control" placeholder="🔍 Search...">
            </div>
        </div>

        <!-- Page List -->
        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-striped align-middle">
                    <tbody id="pageTable">
                        @foreach ($pages as $index => $page)
                        <tr id="row-{{ $page->id }}">
                            <td><i class="fa-solid fa-file-lines"></i></td>
                            <td>{{ $page->title }}</td>
                            <td class="text-end">
                                <a href="/dashboard/train-bot/page-list/{{$page->id}}" class="btn btn-warning btn-sm"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $page->id }}"><i class="fa-solid fa-trash-can"></i> Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($pages->isEmpty())
                <p class="text-center text-muted mt-3">No pages found. Create a new one!</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript for Search and Delete -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("search");
        const tableRows = document.querySelectorAll("#pageTable tr");

        searchInput.addEventListener("keyup", function () {
            let filter = searchInput.value.toLowerCase();
            tableRows.forEach(row => {
                let title = row.cells[1].innerText.toLowerCase();
                row.style.display = title.includes(filter) ? "" : "none";
            });
        });

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                let pageId = this.getAttribute('data-id');

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/dashboard/train-bot/page-list/${pageId}`, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`row-${pageId}`).remove();
                                Swal.fire("Deleted!", "Your page has been deleted.", "success");
                            } else {
                                Swal.fire("Error!", "Something went wrong.", "error");
                            }
                        })
                        .catch(error => {
                            Swal.fire("Error!", "Could not delete the page.", "error");
                        });
                    }
                });
            });
        });
    });
</script>

@endsection
