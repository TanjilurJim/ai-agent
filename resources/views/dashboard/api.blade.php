@extends('dashboard.layout')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                        <h4 class="page-title">Integrate Widget</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#">Make Bot</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center mt-3">
                <h5 class="mt-5"># Use the CDN: Insert Into the Head Tag</h5>
                <div>
                    <div class="card-body card font-monospace p-4 rounded shadow-lg mx-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="fw-bold">CDN</p>
                            @php
                                $scriptTag = '<script src="' . env('APP_URL') . 'chat/script/main.js?api_key=' . ($subscription ? $subscription->api_key : '') . '" defer></script>';
                                $escapedScriptTag = str_replace(['<', '>'], ['&lt;', '&gt;'], $scriptTag);
                            @endphp
                            <button 
                                class="btn btn-outline-secondary btn-sm d-flex gap-2 align-items-center"
                                onclick="copyToClipboard(`{{ addslashes($scriptTag) }}`, 'CDN script copied to clipboard!')">
                                <i class="fa-solid fa-copy"></i>
                                Copy
                            </button>
                        </div>
                        <div class="mt-3">
                            <code class="d-block border text-success p-3 rounded">
                                {!! $escapedScriptTag !!}
                            </code>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function copyToClipboard(text, successMessage) {
            const tempTextarea = document.createElement("textarea");
            tempTextarea.value = text;
            document.body.appendChild(tempTextarea);
            tempTextarea.select();
            document.execCommand("copy");
            document.body.removeChild(tempTextarea);
            
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: successMessage,
                timer: 1500,
                showConfirmButton: false
            });
        }
    </script>
@endsection
