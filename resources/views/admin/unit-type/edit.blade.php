@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Edit Unit Type</h1>
                <p class="text-muted mb-0">Update information for unit type: {{ $unitType->name }}</p>
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
                <form action="{{ route('admin.unit-types.update', $unitType->id) }}" method="POST" id="unitTypeForm">
                    @csrf
                    @method('PUT')

                    <div class="mb-5">
                        <h5 class="fw-semibold mb-3" style="color: #1a1a1a;">Basic Information</h5>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label fw-medium mb-2">Type Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control rounded-3 @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $unitType->name) }}"
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
                                        id="sort_order" name="sort_order" value="{{ old('sort_order', $unitType->sort_order) }}"
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
                                        style="border: 1px solid #e5e7eb;">{{ old('description', $unitType->description) }}</textarea>
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
                                name="is_active" value="1" {{ old('is_active', $unitType->is_active) ? 'checked' : '' }}>
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
                            style="height: 48px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none;">
                            <span class="fw-medium">Update Type</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .form-control,
        .form-select {
            transition: all 0.2s ease;
            border: 1px solid #e5e7eb !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none;
        }

        .form-label {
            color: #374151;
            font-size: 0.875rem;
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .form-check-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .btn-outline-secondary:hover {
            background-color: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        h5 {
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 0.75rem;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .is-invalid {
            border-color: #dc2626 !important;
        }

        .is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>

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