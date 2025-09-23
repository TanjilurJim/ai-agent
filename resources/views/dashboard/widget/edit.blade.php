@extends('dashboard.layout')

@section('content')
<div class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
          <h5 class="fw-medium">{{ $widget->exists ? 'Edit Widget' : 'Create Widget' }}</h5>
          <div>
            <ol class="breadcrumb mb-0">
              <li class="breadcrumb-item"><a href="{{ route('widgets.index') }}">Widgets</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    {{-- Flash + errors --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0 ps-3">
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <div class="container-fluid px-3 px-md-5">
      <div class="row g-3 g-md-5">
        <div class="col-12 col-md-6">
          <div class="card h-100">
            {{-- 🔽 pass the $widget into the form partial --}}
            @include('dashboard.widget.form', ['widget' => $widget])
          </div>
        </div>

        <div class="col-12 col-md-6">
          {{-- 🔽 pass the $widget into the preview partial --}}
          @include('dashboard.widget.widget', ['widget' => $widget])
        </div>
      </div>
    </div>
  </div>
</div>

{{-- keep your live preview JS/etc. --}}
@include('dashboard.widget.script')
@endsection
