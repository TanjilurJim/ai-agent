@extends('dashboard.layout')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Train your bot</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Approx</a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>                            
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
        <div class="">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        @csrf
                        <div class="form-group my-2">
                            <label for="question">Question</label>
                            <input type="text" class="form-control" id="question" name="question" required>
                        </div>
                        <div class="form-group my-2">
                            <label for="answer">Answer</label>
                            <input type="text" class="form-control" id="answer" name="answer" required>
                        </div>
                        <div class="form-group my-2">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>

    

        <div class="card-body pt-0">
            <div class="accordion" id="accordionExample-faq">
                @foreach ($train as $item)
                <div class="accordion-item">
                    <h5 class="accordion-header m-0 d-flex justify-content-between align-items-center" id="heading{{ $item->id }}">
                        <button class="accordion-button  collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $item->id }}" aria-expanded="false" aria-controls="collapse{{ $item->id }}">
                            {{ $item->question }}
                        </button>
                        <div class="d-flex gap-2">
                            <a href="/dashboard/train-bot/{{$item->id}}" class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            
                            <!-- Delete Button -->
                            <form action="{{ route('train.destroy', $item->id) }}" method="POST" onsubmit="return confirmDelete(event);">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </h5>
                    <div id="collapse{{ $item->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $item->id }}" data-bs-parent="#accordionExample-faq">
                        <div class="accordion-body">
                            {{ $item->answer }} <!-- Dynamic answer -->
                        </div>
                    </div>
                </div>
            @endforeach
            
            </div>           
        </div>
        

        </div>
</div>

<script>
    function confirmDelete(event) {
        event.preventDefault();
        const form = event.target;

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
    }
</script>

@endsection


