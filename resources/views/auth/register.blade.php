<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="dark">
<head>
    <meta charset="utf-8" />
    <title>Register | Approx - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="container-xxl">
    <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                <div class="text-center p-3">
                                    <a href="index.html" class="logo logo-admin">
                                        <img src="{{asset('assets/images/logo.svg')}}" height="50" alt="logo" class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Create Your Account</h4>
                                    <p class="text-muted fw-medium mb-0">Sign up to get started with Approx.</p>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <!-- Name -->
                                    <div class="form-group mt-3 mb-2">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" value="{{ old('name') }}" required autofocus>
                                        @error('name')
                                            <p class="my-2 text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <!-- Email Address -->
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <p class="my-2 text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                        @error('password')
                                            <p class="my-2 text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm your password" required>
                                        @error('password_confirmation')
                                            <p class="my-2 text-danger">{{$message}}</p>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <div class="d-grid mt-3">
                                                <button class="btn btn-primary" type="submit">Register <i class="fas fa-user-plus ms-1"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                                <div class="text-center mt-3 mb-2">
                                    <p class="text-muted">Already have an account? <a href="{{ route('login') }}" class="text-primary ms-2">Login</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
