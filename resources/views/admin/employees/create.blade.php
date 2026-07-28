@extends('layouts.admin.app')

@section('title', 'Create Employee')

@section('admin-content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Create Employee</h1>
            <p class="text-muted mb-0 mt-1">Add a new employee to the system</p>
        </div>
        <a href="{{ route('admin.employees.index') }}" class="btn btn-light border rounded-pill px-4">
            <i class="ti ti-arrow-left me-2"></i>Back to Employees
        </a>
    </div>

    <div class="card border rounded-4 shadow-sm">
        <div class="card-body p-4">
            <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data" id="employeeForm">
                @csrf

                {{-- Photo Upload Section --}}
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-semibold mb-3">Profile Photo</h5>
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <div class="photo-preview-wrapper" style="width: 120px; height: 120px; border-radius: 50%; overflow: hidden; border: 2px dashed #d1d5db; display: flex; align-items: center; justify-content: center; background: #f9fafb; cursor: pointer; position: relative;" onclick="document.getElementById('photoInput').click();">
                                <img id="photoPreview" src="" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                                <div id="photoPlaceholder" class="text-center">
                                    <i class="ti ti-camera" style="font-size: 32px; color: #9ca3af;"></i>
                                    <p class="mb-0 mt-2" style="font-size: 12px; color: #6b7280;">Upload Photo</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/jpg,image/png" class="d-none" onchange="previewPhoto(this)">
                            <p class="mb-1 text-muted" style="font-size: 14px;">Click the photo area to upload</p>
                            <p class="mb-0 text-muted" style="font-size: 12px;">Supported formats: JPG, JPEG, PNG (Max 2MB)</p>
                            @error('photo')
                                <div class="text-danger mt-2" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Personal Information --}}
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-semibold mb-3">Personal Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="name" class="form-label fw-medium">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control rounded-3 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Enter employee name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-medium">Employee ID <span class="text-danger">*</span></label>
                            <input type="text" name="employee_id" id="employee_id" class="form-control rounded-3 @error('employee_id') is-invalid @enderror" value="{{ old('employee_id') }}" placeholder="e.g., EMP001" required>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control rounded-3 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="employee@company.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label fw-medium">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control rounded-3 @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="+62 812 3456 7890">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Account Information --}}
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-semibold mb-3">Account Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-medium">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" id="password" class="form-control rounded-3 @error('password') is-invalid @enderror" placeholder="Minimum 8 characters" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-medium">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control rounded-3" placeholder="Re-enter password" required>
                        </div>
                    </div>
                </div>

                {{-- Work Information --}}
                <div class="mb-4 pb-4 border-bottom">
                    <h5 class="fw-semibold mb-3">Work Information</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="position" class="form-label fw-medium">Position</label>
                            <input type="text" name="position" id="position" class="form-control rounded-3 @error('position') is-invalid @enderror" value="{{ old('position') }}" placeholder="e.g., Software Engineer">
                            @error('position')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="department" class="form-label fw-medium">Department</label>
                            <input type="text" name="department" id="department" class="form-control rounded-3 @error('department') is-invalid @enderror" value="{{ old('department') }}" placeholder="e.g., Engineering">
                            @error('department')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="timezone" class="form-label fw-medium">Timezone</label>
                            <select name="timezone" id="timezone" class="form-select rounded-3 @error('timezone') is-invalid @enderror">
                                <option value="">Select timezone</option>
                                <option value="Asia/Jakarta" {{ old('timezone') == 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                                <option value="Asia/Makassar" {{ old('timezone') == 'Asia/Makassar' ? 'selected' : '' }}>Asia/Makassar (WITA)</option>
                                <option value="Asia/Jayapura" {{ old('timezone') == 'Asia/Jayapura' ? 'selected' : '' }}>Asia/Jayapura (WIT)</option>
                            </select>
                            @error('timezone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <span class="fw-medium">Active</span>
                                    <span class="text-muted d-block" style="font-size: 12px;">Employee can access the system</span>
                                </label>
                            </div>
                            @error('is_active')
                                <div class="text-danger mt-1" style="font-size: 13px;">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-light border rounded-pill px-4">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4" id="submitBtn" disabled>
                        <span class="spinner-border spinner-border-sm d-none me-2" id="submitSpinner" role="status"></span>
                        <span id="submitText">Create Employee</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function previewPhoto(input) {
    const preview = document.getElementById('photoPreview');
    const placeholder = document.getElementById('photoPlaceholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('employeeForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitSpinner = document.getElementById('submitSpinner');
    const submitText = document.getElementById('submitText');
    
    const requiredFields = form.querySelectorAll('[required]');
    
    function checkFormValidity() {
        let isValid = true;
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
            }
        });
        submitBtn.disabled = !isValid;
    }
    
    requiredFields.forEach(field => {
        field.addEventListener('input', checkFormValidity);
        field.addEventListener('change', checkFormValidity);
    });
    
    checkFormValidity();
    
    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitSpinner.classList.remove('d-none');
        submitText.textContent = 'Creating...';
    });
});
</script>
@endpush
@endsection