{{-- update-password-form.blade.php --}}
<section>
    <form method="post" action="{{ route('password.update') }}" class="needs-validation" novalidate>
        @csrf
        @method('put')

        <div class="row g-3">
            <!-- Current Password Field -->
            <div class="col-12">
                <label for="update_password_current_password" class="form-label fw-semibold">
                    <i class="fas fa-key me-2"></i>{{ __('Current Password') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" 
                           class="form-control @error('updatePassword.current_password') is-invalid @enderror" 
                           id="update_password_current_password" 
                           name="current_password" 
                           required
                           autocomplete="current-password"
                           placeholder="Enter your current password">
                    <button class="btn btn-outline-secondary" 
                            type="button" 
                            onclick="togglePasswordVisibility('update_password_current_password')"
                            aria-label="Toggle password visibility">
                        <i class="fas fa-eye" id="update_password_current_password_icon"></i>
                    </button>
                    <div class="invalid-feedback">
                        @error('updatePassword.current_password')
                            {{ $message }}
                        @else
                            Please enter your current password.
                        @enderror
                    </div>
                </div>
                @error('updatePassword.current_password')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- New Password Field -->
            <div class="col-12">
                <label for="update_password_password" class="form-label fw-semibold">
                    <i class="fas fa-key me-2"></i>{{ __('New Password') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" 
                           class="form-control @error('updatePassword.password') is-invalid @enderror" 
                           id="update_password_password" 
                           name="password" 
                           required
                           autocomplete="new-password"
                           placeholder="Enter your new password"
                           minlength="8"
                           onkeyup="checkPasswordStrength()">
                    <button class="btn btn-outline-secondary" 
                            type="button" 
                            onclick="togglePasswordVisibility('update_password_password')"
                            aria-label="Toggle password visibility">
                        <i class="fas fa-eye" id="update_password_password_icon"></i>
                    </button>
                    <div class="invalid-feedback">
                        @error('updatePassword.password')
                            {{ $message }}
                        @else
                            Password must be at least 8 characters long.
                        @enderror
                    </div>
                </div>
                
                <!-- Password Strength Indicator -->
                <div id="password-strength" class="mt-2" style="display: none;">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small">Strength:</span>
                        <div class="progress flex-grow-1" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                        <span class="small" id="strength-text">Weak</span>
                    </div>
                </div>

                <!-- Password Requirements -->
                <div class="mt-2">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Password should contain:
                    </small>
                    <ul class="small text-muted mt-1 mb-0" style="padding-left: 1.2rem;">
                        <li id="req-length">At least 8 characters</li>
                        <li id="req-uppercase">One uppercase letter</li>
                        <li id="req-lowercase">One lowercase letter</li>
                        <li id="req-number">One number</li>
                        <li id="req-special">One special character</li>
                    </ul>
                </div>

                @error('updatePassword.password')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Confirm Password Field -->
            <div class="col-12">
                <label for="update_password_password_confirmation" class="form-label fw-semibold">
                    <i class="fas fa-key me-2"></i>{{ __('Confirm New Password') }}
                    <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-lock text-muted"></i>
                    </span>
                    <input type="password" 
                           class="form-control @error('updatePassword.password_confirmation') is-invalid @enderror" 
                           id="update_password_password_confirmation" 
                           name="password_confirmation" 
                           required
                           autocomplete="new-password"
                           placeholder="Confirm your new password"
                           onkeyup="checkPasswordMatch()">
                    <button class="btn btn-outline-secondary" 
                            type="button" 
                            onclick="togglePasswordVisibility('update_password_password_confirmation')"
                            aria-label="Toggle password visibility">
                        <i class="fas fa-eye" id="update_password_password_confirmation_icon"></i>
                    </button>
                    <div class="invalid-feedback">
                        @error('updatePassword.password_confirmation')
                            {{ $message }}
                        @else
                            Passwords must match.
                        @enderror
                    </div>
                </div>
                
                <!-- Password Match Indicator -->
                <div id="password-match" class="mt-2" style="display: none;">
                    <small class="text-success">
                        <i class="fas fa-check me-1"></i>
                        Passwords match!
                    </small>
                </div>
                <div id="password-mismatch" class="mt-2" style="display: none;">
                    <small class="text-danger">
                        <i class="fas fa-times me-1"></i>
                        Passwords don't match.
                    </small>
                </div>

                @error('updatePassword.password_confirmation')
                    <div class="text-danger small mt-1">
                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="d-flex flex-column flex-sm-row align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-success px-4">
                <i class="fas fa-shield-alt me-2"></i>
                {{ __('Update Password') }}
            </button>

            <!-- Success Message -->
            @if (session('status') === 'password-updated')
                <div class="alert alert-success mb-0 flex-grow-1" 
                     role="alert" 
                     id="password-success-message">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Success!</strong> {{ __('Password updated successfully.') }}
                </div>
                
                <script>
                    // Auto-hide success message after 3 seconds
                    setTimeout(function() {
                        const successMessage = document.getElementById('password-success-message');
                        if (successMessage) {
                            successMessage.style.transition = 'opacity 0.5s ease';
                            successMessage.style.opacity = '0';
                            setTimeout(() => successMessage.remove(), 500);
                        }
                    }, 3000);
                </script>
            @endif

            <button type="button" class="btn btn-outline-secondary" onclick="clearPasswordFields()">
                <i class="fas fa-eraser me-2"></i>
                Clear Fields
            </button>
        </div>

        <!-- Security Tips -->
        
    </form>
</section>

<script>
// Toggle password visibility
function togglePasswordVisibility(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '_icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Check password strength
function checkPasswordStrength() {
    const password = document.getElementById('update_password_password').value;
    const strengthDiv = document.getElementById('password-strength');
    const progressBar = strengthDiv.querySelector('.progress-bar');
    const strengthText = document.getElementById('strength-text');
    
    // Requirements elements
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqLowercase = document.getElementById('req-lowercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');
    
    if (password.length === 0) {
        strengthDiv.style.display = 'none';
        resetRequirements();
        return;
    }
    
    strengthDiv.style.display = 'block';
    
    let score = 0;
    let requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[^A-Za-z0-9]/.test(password)
    };
    
    // Update requirement indicators
    updateRequirement(reqLength, requirements.length);
    updateRequirement(reqUppercase, requirements.uppercase);
    updateRequirement(reqLowercase, requirements.lowercase);
    updateRequirement(reqNumber, requirements.number);
    updateRequirement(reqSpecial, requirements.special);
    
    // Calculate score
    Object.values(requirements).forEach(met => {
        if (met) score++;
    });
    
    // Update progress bar and text
    const percentage = (score / 5) * 100;
    progressBar.style.width = percentage + '%';
    
    if (score <= 1) {
        progressBar.className = 'progress-bar bg-danger';
        strengthText.textContent = 'Very Weak';
        strengthText.className = 'small text-danger';
    } else if (score <= 2) {
        progressBar.className = 'progress-bar bg-warning';
        strengthText.textContent = 'Weak';
        strengthText.className = 'small text-warning';
    } else if (score <= 3) {
        progressBar.className = 'progress-bar bg-info';
        strengthText.textContent = 'Fair';
        strengthText.className = 'small text-info';
    } else if (score <= 4) {
        progressBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Good';
        strengthText.className = 'small text-success';
    } else {
        progressBar.className = 'progress-bar bg-success';
        strengthText.textContent = 'Strong';
        strengthText.className = 'small text-success fw-bold';
    }
}

function updateRequirement(element, met) {
    if (met) {
        element.classList.remove('text-muted');
        element.classList.add('text-success');
        element.innerHTML = element.innerHTML.replace(/^.*?(\w)/, '<i class="fas fa-check me-1"></i>$1');
    } else {
        element.classList.remove('text-success');
        element.classList.add('text-muted');
        element.innerHTML = element.innerHTML.replace('<i class="fas fa-check me-1"></i>', '');
    }
}

function resetRequirements() {
    const requirements = ['req-length', 'req-uppercase', 'req-lowercase', 'req-number', 'req-special'];
    requirements.forEach(reqId => {
        const element = document.getElementById(reqId);
        element.classList.remove('text-success');
        element.classList.add('text-muted');
        element.innerHTML = element.innerHTML.replace('<i class="fas fa-check me-1"></i>', '');
    });
}

// Check password match
function checkPasswordMatch() {
    const password = document.getElementById('update_password_password').value;
    const confirmation = document.getElementById('update_password_password_confirmation').value;
    const matchDiv = document.getElementById('password-match');
    const mismatchDiv = document.getElementById('password-mismatch');
    
    if (confirmation.length === 0) {
        matchDiv.style.display = 'none';
        mismatchDiv.style.display = 'none';
        return;
    }
    
    if (password === confirmation) {
        matchDiv.style.display = 'block';
        mismatchDiv.style.display = 'none';
    } else {
        matchDiv.style.display = 'none';
        mismatchDiv.style.display = 'block';
    }
}

// Clear all password fields
function clearPasswordFields() {
    document.getElementById('update_password_current_password').value = '';
    document.getElementById('update_password_password').value = '';
    document.getElementById('update_password_password_confirmation').value = '';
    
    // Hide indicators
    document.getElementById('password-strength').style.display = 'none';
    document.getElementById('password-match').style.display = 'none';
    document.getElementById('password-mismatch').style.display = 'none';
    
    // Reset requirements
    resetRequirements();
}
</script>

<style>
/* Password form specific styles */
.input-group-text {
    background-color: var(--bs-gray-100);
    border-color: var(--bs-border-color);
}

[data-bs-theme="dark"] .input-group-text {
    background-color: var(--bs-gray-800);
    border-color: var(--bs-border-color);
    color: var(--bs-gray-300);
}

.btn-outline-secondary {
    border-left: 0;
}

.btn-outline-secondary:hover {
    background-color: var(--bs-secondary);
    border-color: var(--bs-secondary);
}

[data-bs-theme="dark"] .btn-outline-secondary {
    border-color: var(--bs-border-color);
    color: var(--bs-gray-300);
}

[data-bs-theme="dark"] .btn-outline-secondary:hover {
    background-color: var(--bs-gray-700);
    border-color: var(--bs-gray-700);
    color: var(--bs-white);
}

/* Progress bar in dark mode */
[data-bs-theme="dark"] .progress {
    background-color: var(--bs-gray-700);
}

/* Info box styling for dark mode */
[data-bs-theme="dark"] .bg-info.bg-opacity-10 {
    background-color: rgba(13, 202, 240, 0.1) !important;
    border-color: rgba(13, 202, 240, 0.3) !important;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .btn:last-child {
        margin-bottom: 0;
    }
    
    .input-group-text {
        min-width: 45px;
        justify-content: center;
    }
}
</style>