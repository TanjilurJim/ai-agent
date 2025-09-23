{{-- update-profile-information-form.blade.php --}}
<section>
    <!-- Email Verification Form (Hidden) -->
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
        @csrf
    </form>

    <!-- Profile Update Form -->
    <form method="post" action="{{ route('profile.update') }}" class="needs-validation" novalidate>
        @csrf
        @method('patch')

        <div class="row g-3">
            <!-- Name Field -->
            <div class="col-12">
                <label for="name" class="form-label fw-semibold">
                    <i class="fas fa-user me-2"></i>{{ __('Name') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-user text-muted"></i>
                    </span>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           autofocus 
                           autocomplete="name"
                           placeholder="Enter your full name">
                    <div class="valid-feedback">
                        <i class="fas fa-check me-1"></i>Looks good!
                    </div>
                    <div class="invalid-feedback">
                        @error('name')
                            {{ $message }}
                        @else
                            Please provide your name.
                        @enderror
                    </div>
                </div>
                @error('name')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="col-12">
                <label for="email" class="form-label fw-semibold">
                    <i class="fas fa-envelope me-2"></i>{{ __('Email') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-envelope text-muted"></i>
                    </span>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required 
                           autocomplete="username"
                           placeholder="Enter your email address">
                    <div class="valid-feedback">
                        <i class="fas fa-check me-1"></i>Email format is valid!
                    </div>
                    <div class="invalid-feedback">
                        @error('email')
                            {{ $message }}
                        @else
                            Please provide a valid email address.
                        @enderror
                    </div>
                </div>
                @error('email')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror

                <!-- Email Verification Status -->
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-3">
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div class="flex-grow-1">
                                <strong>{{ __('Email Verification Required') }}</strong><br>
                                <span class="small">{{ __('Your email address is unverified.') }}</span>
                            </div>
                        </div>
                        
                        <button type="button" 
                                class="btn btn-outline-warning btn-sm" 
                                onclick="document.getElementById('send-verification').submit()">
                            <i class="fas fa-paper-plane me-2"></i>
                            {{ __('Resend Verification Email') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <div class="alert alert-success mt-2 mb-0" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        @endif
                    </div>
                @else
                    <div class="mt-2">
                        <small class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Email address verified
                        </small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex flex-column flex-sm-row align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-2"></i>
                {{ __('Save Changes') }}
            </button>

            <!-- Success Message -->
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success mb-0 flex-grow-1" 
                     role="alert" 
                     id="profile-success-message">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success!</strong> {{ __('Profile updated successfully.') }}
                </div>
                
                <script>
                    // Auto-hide success message after 3 seconds
                    setTimeout(function() {
                        const successMessage = document.getElementById('profile-success-message');
                        if (successMessage) {
                            successMessage.style.transition = 'opacity 0.5s ease';
                            successMessage.style.opacity = '0';
                            setTimeout(() => successMessage.remove(), 500);
                        }
                    }, 3000);
                </script>
            @endif

            <div class="text-muted small">
                <i class="fas fa-info-circle me-1"></i>
                Last updated: {{ $user->updated_at->diffForHumans() }}
            </div>
        </div>

        <!-- Form Help Text -->
        <div class="mt-3">
            <small class="text-muted">
                <i class="fas fa-shield-alt me-1"></i>
                Your personal information is secure and will not be shared with third parties.
            </small>
        </div>
    </form>
</section>

<style>
/* Profile form specific styles */
.input-group-text {
    background-color: var(--bs-gray-100);
    border-color: var(--bs-border-color);
}

[data-bs-theme="dark"] .input-group-text {
    background-color: var(--bs-gray-800);
    border-color: var(--bs-border-color);
    color: var(--bs-gray-300);
}

.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

[data-bs-theme="dark"] .form-control:focus {
    border-color: #6ea8fe;
    box-shadow: 0 0 0 0.25rem rgba(110, 168, 254, 0.25);
}

/* Enhanced validation feedback */
.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #198754;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%23198754' d='m2.3 6.73.8-.77-.8-.77-.8.77.8.77Zm1.54-4.96L6.77 4.7l-.8.77-2.93-2.93-.8-.77.8-.77Z'/%3e%3c/svg%3e");
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
    border-color: #dc3545;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4 1.4-1.4M8.6 7.4 7.2 6 5.8 7.4'/%3e%3c/svg%3e");
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .btn {
        width: 100%;
    }
    
    .d-flex.flex-column.flex-sm-row {
        align-items: stretch !important;
    }
    
    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }
}
</style>