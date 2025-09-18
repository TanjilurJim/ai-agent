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
                            <input value="{{$train->question}}" type="text" class="form-control" id="question" name="question" required>
                        </div>
                        <div class="form-group my-2">
                            <label for="answer">Answer</label>
                            <input  value="{{$train->answer}}" type="text" class="form-control" id="answer" name="answer" required>
                        </div>
                        <div class="form-group my-2">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{$train->description}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
        </div>
</div>
@endsection