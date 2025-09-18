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
            <div class="row gx-5 px-5">
               <div class="col-md-6 card">
                    @include('dashboard.widget.form')
               </div>
               <div class="col-md-6">
                    @include('dashboard.widget.widget')
               </div>
            </div>
        </div>
        @include('dashboard.widget.script')
    @endsection
