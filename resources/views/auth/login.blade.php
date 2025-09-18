<!DOCTYPE  html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="dark">

<head>
    <meta charset="utf-8" />
    <title>Login |Rafusoft Smart Bot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- App css -->
    <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css" />
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
                                            <img src="{{asset('assets/images/logo.svg')}}" height="50" alt="logo"
                                                class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Rafusoft Smart Bot</h4>
                                        <p class="text-muted fw-medium mb-0">Sign in to continue</p>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <!-- Email Address -->
                                        <div class="form-group mt-3 mb-2">
                                            <label class="form-label" for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Enter email" value="{{ old('email') }}" required
                                                autofocus>
                                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <!-- Password -->
                                        <div class="form-group">
                                            <label class="form-label" for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                placeholder="Enter password" required>
                                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Remember Me -->
                                        <div class="form-group row mt-3">
                                            <div class="col-sm-6">
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input" type="checkbox" id="remember_me"
                                                        name="remember">
                                                    <label class="form-check-label" for="remember_me">Remember
                                                        me</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6 text-end">
                                                @if (Route::has('password.request'))
                                                    <a href="{{ route('password.request') }}"
                                                        class="text-muted font-13"><i class="dripicons-lock"></i> Forgot
                                                        password?</a>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button class="btn btn-primary" type="submit">Log In <i
                                                            class="fas fa-sign-in-alt ms-1"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="text-center my-2">
                                        <p class="text-muted">Don't have an account? <a href="{{ route('register') }}"
                                                class="text-primary ms-2">Free Register</a></p>

                                    </div>

                                    <a href="{{ route('google.login') }}" class="btn w-100 border d-flex justify-content-center align-items-center gap-3"> <img height="20px" src="https://cdn4.iconfinder.com/data/icons/logos-brands-7/512/google_logo-google_icongoogle-512.png" alt=""> Login with Google</a>
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