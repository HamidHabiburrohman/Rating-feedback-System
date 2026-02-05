@extends('layouts.admin.app')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Add New Unit Type</h1>
                <p class="text-muted mb-0">Create a new unit type/category</p>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" class="me-2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div>
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border rounded-4" style="border-color: #e5e7eb;">
            <div class="card-body p-4">
                <form action="{{ route('admin.unit-types.store') }}" method="POST" id="unitTypeForm">
                    @csrf

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Basic Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label fw-medium mb-2">Type Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control rounded-3 @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}"
                                        placeholder="e.g., Kesehatan, Akademik, Administrasi"
                                        style="height: 48px; border: 1px solid #e5e7eb;"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order" class="form-label fw-medium mb-2">Sort Order</label>
                                    <input type="number"
                                        class="form-control rounded-3 @error('sort_order') is-invalid @enderror"
                                        id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}"
                                        placeholder="Enter sort order"
                                        style="height: 48px; border: 1px solid #e5e7eb;"
                                        min="0">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Lower numbers appear first</small>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-label fw-medium mb-2">Description</label>
                                    <textarea class="form-control rounded-3 @error('description') is-invalid @enderror" 
                                        id="description" name="description" rows="3" 
                                        placeholder="Enter description for this unit type..."
                                        style="border: 1px solid #e5e7eb;">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Status</h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="is_active">
                                Active Status
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 pt-4 border-top" style="border-color: #c1c1c1 !important;">
                        <a href="{{ route('admin.unit-types.index') }}"
                            class="btn btn-outline-secondary rounded-pill px-7 d-flex align-items-center gap-2"
                            style="height: 48px; border: 1px solid #c1c1c1;">
                            <span class="fw-medium">Cancel</span>
                        </a>
                        <button type="submit" class="btn btn-primary rounded-pill px-7 d-flex align-items-center gap-2"
                            style="height: 48px; background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none;">
                            <span class="fw-medium">Create Type</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('unitTypeForm');

            form.addEventListener('submit', function (e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    const firstError = form.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });

            // Real-time validation for required fields
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                field.addEventListener('input', function () {
                    if (this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        });
    </script>
@endsection