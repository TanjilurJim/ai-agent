@extends('dashboard.layout')
@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h5 class="fw-medium">Customize the widget to suit your brand</h5>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#">Widget </a>
                                </li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid px-3 px-md-5">
                <div class="row g-3 g-md-5">
                    <div class="col-12 col-md-6">
                        <div class="card h-100">
                            @include('dashboard.widget.form')
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        {{-- <div class="card h-100"> --}}
                            @include('dashboard.widget.widget')
                        {{-- </div> --}}
                    </div>
                </div>
            </div>
        </div>
        @include('dashboard.widget.script')
    @endsection
