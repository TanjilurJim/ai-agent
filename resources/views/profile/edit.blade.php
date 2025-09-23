@extends('dashboard.layout')

@section('css')
<style>
    /* Enhanced responsive profile styles */
    .profile-avatar {
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    [data-bs-theme="dark"] .profile-avatar {
        box-shadow: 0 4px 12px rgba(255,255,255,0.1);
    }
    
    .profile-avatar:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }
    
    [data-bs-theme="dark"] .profile-avatar:hover {
        box-shadow: 0 8px 20px rgba(255,255,255,0.15);
    }
    
    .card {
        border: 1px solid rgba(0,0,0,0.08);
        transition: all 0.3s ease;
    }
    
    [data-bs-theme="dark"] .card {
        border: 1px solid rgba(255,255,255,0.08);
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    [data-bs-theme="dark"] .card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.3);
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    [data-bs-theme="dark"] .form-control:focus {
        border-color: #6ea8fe;
        box-shadow: 0 0 0 0.2rem rgba(110, 168, 254, 0.25);
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    /* Mobile specific adjustments */
    @media (max-width: 576px) {
        .profile-header {
            text-align: center;
        }
        
        .profile-avatar {
            width: 80px !important;
            height: 80px !important;
        }
        
        .card-body {
            padding: 1rem;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .btn:last-child {
            margin-bottom: 0;
        }
    }
    
    /* Tablet adjustments */
    @media (min-width: 577px) and (max-width: 991px) {
        .profile-avatar {
            width: 90px !important;
            height: 90px !important;
        }
    }
    
    /* Animation for form validation */
    .was-validated .form-control:valid {
        border-color: #198754;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.8-.77-.8-.77-.8.77.8.77Zm1.54-4.96L6.77 4.7l-.8.77-2.93-2.93-.8-.77.8-.77Z'/%3e%3c/svg%3e");
    }
    
    .was-validated .form-control:invalid {
        border-color: #dc3545;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4 1.4-1.4M8.6 7.4 7.2 6 5.8 7.4'/%3e%3c/svg%3e");
    }
    
    /* Dark mode specific adjustments */
    [data-bs-theme="dark"] .alert {
        border: none;
    }
    
    [data-bs-theme="dark"] .alert-success {
        background-color: rgba(25, 135, 84, 0.2);
        color: #75b798;
    }
    
    [data-bs-theme="dark"] .alert-danger {
        background-color: rgba(220, 53, 69, 0.2);
        color: #ea868f;
    }
    
    /* Loading state for buttons */
    .btn-loading {
        position: relative;
        pointer-events: none;
    }
    
    .btn-loading::after {
        content: "";
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-radius: 50%;
        animation: button-loading-spinner 1s linear infinite;
    }
    
    @keyframes button-loading-spinner {
        from { transform: rotate(0turn); }
        to { transform: rotate(1turn); }
    }
</style>
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title mb-0">
                        <i class="fas fa-user-edit me-2"></i>Profile Settings
                    </h4>
                    <div>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                        <i class="fas fa-home me-1"></i>Dashboard
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Profile</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Success!</strong> Your profile has been updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-shield-alt me-2"></i>
                <strong>Success!</strong> Your password has been updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Error!</strong> Please correct the following issues:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Overview Card -->
            <div class="col-12 col-lg-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        @php
                            $user = auth()->user();
                            $gravatar = 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->email))) . '?s=200&d=mp';
                        @endphp
                        
                        <div class="profile-header mb-4">
                            <img src="{{ $gravatar }}" 
                                 class="profile-avatar rounded-circle mb-3" 
                                 width="120" 
                                 height="120" 
                                 alt="Profile Avatar"
                                 loading="lazy">
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <p class="text-muted mb-0">{{ $user->email }}</p>
                        </div>
                        
                        <div class="row text-center g-3">
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <h6 class="mb-1">Member Since</h6>
                                    <p class="text-muted mb-0 small">
                                        {{ $user->created_at->format('M Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="border rounded p-3">
                                    <h6 class="mb-1">Last Updated</h6>
                                    <p class="text-muted mb-0 small">
                                        {{ $user->updated_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <p class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Profile picture is managed through 
                                <a href="https://gravatar.com" target="_blank" class="text-decoration-none">Gravatar</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Information Form -->
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>Profile Information
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Update your account's profile information and email address.</p>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            </div>

            <!-- Update Password Form -->
            <div class="col-12 col-lg-12">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-lock me-2"></i>Update Password
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Account Security -->
            {{-- <div class="col-12 col-lg-6">
                <div class="card">
                    <div class="card-header border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shield-alt me-2"></i>Account Security
                        </h5>
                        <p class="text-muted small mb-0 mt-1">Manage your account security settings.</p>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="mb-1">Two-Factor Authentication</h6>
                                <p class="text-muted small mb-0">Add an extra layer of security to your account</p>
                            </div>
                            <span class="badge bg-warning">Coming Soon</span>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <h6 class="mb-1">Login Notifications</h6>
                                <p class="text-muted small mb-0">Get notified when someone logs into your account</p>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="loginNotifications" checked disabled>
                                <label class="form-check-label" for="loginNotifications"></label>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-1">Account Verification</h6>
                                <p class="text-muted small mb-0">Your email address is verified</p>
                            </div>
                            <span class="badge bg-success">
                                <i class="fas fa-check me-1"></i>Verified
                            </span>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Danger Zone -->
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger bg-opacity-10 border-bottom border-danger">
                        <h5 class="card-title text-danger mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>Danger Zone
                        </h5>
                        <p class="text-muted small mb-0 mt-1">These actions are irreversible. Please proceed with caution.</p>
                    </div>
                    <div class="card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading state to form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                
                // Re-enable after 3 seconds as fallback
                setTimeout(() => {
                    submitBtn.classList.remove('btn-loading');
                    submitBtn.disabled = false;
                }, 3000);
            }
        });
    });
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert && alert.querySelector('.btn-close')) {
                alert.querySelector('.btn-close').click();
            }
        }, 5000);
    });
    
    // Form validation enhancement
    const forms_with_validation = document.querySelectorAll('.needs-validation');
    forms_with_validation.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Password strength indicator (if password field exists)
    const passwordField = document.querySelector('input[name="password"]');
    if (passwordField) {
        passwordField.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            updatePasswordStrengthIndicator(strength);
        });
    }
    
    function calculatePasswordStrength(password) {
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;
        return strength;
    }
    
    function updatePasswordStrengthIndicator(strength) {
        const indicator = document.querySelector('.password-strength');
        if (!indicator) return;
        
        const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        const colors = ['danger', 'warning', 'info', 'success', 'success'];
        
        if (strength > 0) {
            indicator.innerHTML = `<small class="text-${colors[strength-1]}">Password Strength: ${labels[strength-1]}</small>`;
            indicator.style.display = 'block';
        } else {
            indicator.style.display = 'none';
        }
    }
});
</script>
@endsection