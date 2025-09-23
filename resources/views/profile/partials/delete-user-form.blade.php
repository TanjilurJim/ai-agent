{{-- delete-user-form.blade.php --}}
<section>
    <div class="row">
        <div class="col-12">
            <!-- Warning Information -->
            <div class="alert alert-danger d-flex align-items-start" role="alert">
                <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                <div>
                    <h6 class="alert-heading mb-2">{{ __('Permanently Delete Account') }}</h6>
                    <p class="mb-2">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                    </p>
                    <p class="mb-0 small">
                        <strong>{{ __('This action cannot be undone.') }}</strong>
                    </p>
                </div>
            </div>

            <!-- Delete Account Button -->
            <div class="d-grid d-sm-block">
                <button type="button" 
                        class="btn btn-danger" 
                        data-bs-toggle="modal" 
                        data-bs-target="#confirmDeleteModal">
                    <i class="fas fa-trash-alt me-2"></i>
                    {{ __('Delete Account') }}
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger" id="confirmDeleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ __('Confirm Account Deletion') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-user-times text-danger" style="font-size: 2rem;"></i>
                    </div>
                </div>

                <div class="text-center mb-4">
                    <h6 class="mb-3">{{ __('Are you sure you want to delete your account?') }}</h6>
                    <p class="text-muted small mb-0">
                        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                    </p>
                </div>

                <!-- Deletion Consequences -->
                <div class="border rounded p-3 mb-4" style="background-color: var(--bs-gray-50);">
                    <h6 class="text-danger mb-2">
                        <i class="fas fa-ban me-2"></i>
                        {{ __('What will be deleted:') }}
                    </h6>
                    <ul class="small mb-0 text-muted">
                        <li><i class="fas fa-user me-2"></i>Your profile and personal information</li>
                        <li><i class="fas fa-database me-2"></i>All your data and content</li>
                        <li><i class="fas fa-history me-2"></i>Your account history and activity</li>
                        <li><i class="fas fa-cog me-2"></i>All preferences and settings</li>
                    </ul>
                </div>

                <!-- Delete Form -->
                <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('delete')

                    <div class="mb-4">
                        <label for="delete_password" class="form-label fw-semibold">
                            <i class="fas fa-key me-2"></i>
                            {{ __('Enter your password to confirm') }}
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" 
                                   class="form-control @error('userDeletion.password') is-invalid @enderror" 
                                   id="delete_password" 
                                   name="password" 
                                   required
                                   placeholder="{{ __('Your current password') }}"
                                   autocomplete="current-password">
                            <button class="btn btn-outline-secondary" 
                                    type="button" 
                                    onclick="toggleDeletePasswordVisibility()"
                                    aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="delete_password_icon"></i>
                            </button>
                            <div class="invalid-feedback">
                                @error('userDeletion.password')
                                    {{ $message }}
                                @else
                                    {{ __('Please enter your password to confirm deletion.') }}
                                @enderror
                            </div>
                        </div>
                        
                        @error('userDeletion.password')
                            <div class="text-danger small mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div class="form-check mb-4">
                        <input class="form-check-input" 
                               type="checkbox" 
                               id="confirmDeletion" 
                               required>
                        <label class="form-check-label small" for="confirmDeletion">
                            <strong>{{ __('I understand that this action is permanent and cannot be undone.') }}</strong>
                        </label>
                    </div>

                    <!-- Warning Box -->
                    <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-clock me-2"></i>
                        <small>
                            {{ __('Account deletion may take up to 24 hours to complete. You will receive a confirmation email once the process is finished.') }}
                        </small>
                    </div>
                </form>
            </div>
            
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    {{ __('Cancel') }}
                </button>
                <button type="submit" 
                        form="deleteAccountForm" 
                        class="btn btn-danger" 
                        id="confirmDeleteBtn"
                        disabled>
                    <i class="fas fa-trash-alt me-2"></i>
                    {{ __('Delete Account') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility for delete form
function toggleDeletePasswordVisibility() {
    const field = document.getElementById('delete_password');
    const icon = document.getElementById('delete_password_icon');
    
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

// Enable/disable delete button based on confirmation checkbox and password
document.addEventListener('DOMContentLoaded', function() {
    const confirmCheckbox = document.getElementById('confirmDeletion');
    const passwordField = document.getElementById('delete_password');
    const deleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteForm = document.getElementById('deleteAccountForm');
    
    function updateDeleteButton() {
        if (confirmCheckbox && passwordField && deleteBtn) {
            const isChecked = confirmCheckbox.checked;
            const hasPassword = passwordField.value.length > 0;
            deleteBtn.disabled = !(isChecked && hasPassword);
        }
    }
    
    if (confirmCheckbox) {
        confirmCheckbox.addEventListener('change', updateDeleteButton);
    }
    
    if (passwordField) {
        passwordField.addEventListener('input', updateDeleteButton);
    }
    
    // Add confirmation dialog on form submission
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: '{{ __("Final Confirmation") }}',
                text: '{{ __("This is your last chance to cancel. Your account will be permanently deleted and cannot be recovered.") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ __("Yes, delete my account") }}',
                cancelButtonText: '{{ __("Cancel") }}',
                reverseButtons: true,
                focusCancel: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading state
                    const submitBtn = deleteForm.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>{{ __("Deleting...") }}';
                        submitBtn.disabled = true;
                    }
                    
                    // Submit the form
                    deleteForm.submit();
                }
            });
        });
    }
    
    // Show modal if there are validation errors
    @if ($errors->userDeletion->isNotEmpty())
        const deleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
        deleteModal.show();
    @endif
    
    // Reset form when modal is hidden
    const modalElement = document.getElementById('confirmDeleteModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function () {
            // Reset form
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
            
            // Reset button state
            updateDeleteButton();
            
            // Reset password visibility
            const passwordField = document.getElementById('delete_password');
            const passwordIcon = document.getElementById('delete_password_icon');
            if (passwordField && passwordIcon) {
                passwordField.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        });
    }
});
</script>

<style>
/* Dark mode styles for delete form */
[data-bs-theme="dark"] .modal-content {
    background-color: var(--bs-dark);
    border: 1px solid var(--bs-gray-700);
}

[data-bs-theme="dark"] .modal-header {
    border-bottom-color: var(--bs-gray-700);
}

[data-bs-theme="dark"] .modal-footer {
    border-top-color: var(--bs-gray-700);
}

[data-bs-theme="dark"] .bg-danger.bg-opacity-10 {
    background-color: rgba(220, 53, 69, 0.15) !important;
}

[data-bs-theme="dark"] .alert-warning {
    background-color: rgba(255, 193, 7, 0.1);
    border-color: rgba(255, 193, 7, 0.3);
    color: #ffecb3;
}

[data-bs-theme="dark"] .border {
    border-color: var(--bs-gray-700) !important;
}

[data-bs-theme="dark"] .form-check-input:checked {
    background-color: var(--bs-danger);
    border-color: var(--bs-danger);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-body {
        padding: 1rem;
    }
    
    .modal-footer {
        padding: 1rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .modal-footer .btn {
        width: 100%;
    }
}

/* Animation for disabled state */
#confirmDeleteBtn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Loading spinner */
.spinner-border-sm {
    width: 0.875rem;
    height: 0.875rem;
}
</style>