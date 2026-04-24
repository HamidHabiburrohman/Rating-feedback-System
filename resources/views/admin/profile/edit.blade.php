@extends('layouts.admin.app')

@section('title', 'Edit Profile')

@section('admin-content')
    <div class="container-fluid px-4 py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Edit Profile</h1>
                <p class="text-muted mt-1 mb-0">Update your personal information</p>
            </div>
            <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary rounded-pill px-4">
                Cancel
            </a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">

                        {{-- Profile Photo Upload --}}
                        <div class="d-flex align-items-center gap-4 mb-4 pb-3 border-bottom">
                            <div class="position-relative">
                                @if (Auth::guard('admin')->user()->photo_url)
                                    <img src="{{ Auth::guard('admin')->user()->photo_url }}" alt="Profile Photo"
                                        class="rounded-4 object-fit-cover" id="photoPreview"
                                        style="width: 100px; height: 100px;">
                                @else
                                    <div id="photoPreview"
                                        class="rounded-4 d-flex align-items-center justify-content-center text-white fw-bold"
                                        style="width: 100px; height: 100px; background: linear-gradient(135deg, #f8773c, #e5652a); font-size: 2.5rem;">
                                        {{ Auth::guard('admin')->user()->initials }}
                                    </div>
                                @endif
                                <label for="photoUpload" class="position-absolute bottom-0 end-0 cursor-pointer"
                                    style="cursor: pointer; transform: translate(25%, 25%);">
                                    <div class="rounded-circle bg-white p-2 shadow-sm border"
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="#f8773c" stroke-width="2">
                                            <path
                                                d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
                                            <circle cx="12" cy="13" r="4" />
                                        </svg>
                                    </div>
                                </label>
                                <input type="file" id="photoUpload" accept="image/*" style="display: none;">
                            </div>
                            <div>
                                <button type="button" id="uploadPhotoBtn" class="btn btn-sm rounded-pill px-4"
                                    style="background: #f8773c; color: white;">
                                    Upload Photo
                                </button>
                                @if (Auth::guard('admin')->user()->photo)
                                    <button type="button" id="removePhotoBtn"
                                        class="btn btn-sm btn-outline-danger rounded-pill px-4 ms-2">
                                        Remove
                                    </button>
                                @endif
                                <p class="small text-muted mt-2 mb-0">JPG, PNG, GIF up to 2MB</p>
                            </div>
                        </div>

                        {{-- Basic Info Form --}}
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Full Name</label>
                                    <input type="text" name="nama" class="form-control rounded-3 py-2"
                                        value="{{ old('nama', Auth::guard('admin')->user()->nama) }}" required>
                                    @error('nama')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" name="email" class="form-control rounded-3 py-2"
                                        value="{{ old('email', Auth::guard('admin')->user()->email) }}" required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="text" name="phone" class="form-control rounded-3 py-2"
                                        value="{{ old('phone', Auth::guard('admin')->user()->phone) }}">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Position</label>
                                    <input type="text" name="position" class="form-control rounded-3 py-2"
                                        value="{{ old('position', Auth::guard('admin')->user()->position) }}">
                                    @error('position')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Location</label>
                                    <input type="text" name="location" class="form-control rounded-3 py-2"
                                        value="{{ old('location', Auth::guard('admin')->user()->location) }}">
                                    @error('location')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">Bio</label>
                                    <textarea name="bio" class="form-control rounded-3 py-2" rows="4">{{ old('bio', Auth::guard('admin')->user()->bio) }}</textarea>
                                    @error('bio')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4 pt-3 border-top">
                                <button type="submit" class="btn rounded-pill px-5"
                                    style="background: #f8773c; color: white;">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Change Password Card --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <h3 class="h6 fw-bold text-uppercase mb-3" style="color: #6b7280; letter-spacing: 0.05em;">Change
                            Password</h3>

                        <form action="{{ route('admin.profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Current Password</label>
                                <input type="password" name="current_password" class="form-control rounded-3 py-2"
                                    required>
                                @error('current_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-semibold">New Password</label>
                                <input type="password" name="new_password" class="form-control rounded-3 py-2" required>
                                @error('new_password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-semibold">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation"
                                    class="form-control rounded-3 py-2" required>
                            </div>

                            <button type="submit" class="btn btn-outline-secondary rounded-pill w-100">
                                Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const photoUpload = document.getElementById('photoUpload');
                const uploadPhotoBtn = document.getElementById('uploadPhotoBtn');
                const removePhotoBtn = document.getElementById('removePhotoBtn');
                const photoPreview = document.getElementById('photoPreview');

                // Ketika file dipilih, langsung upload
                if (photoUpload) {
                    photoUpload.addEventListener('change', function(e) {
                        if (e.target.files.length > 0) {
                            const file = e.target.files[0];

                            // Preview dulu
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                if (photoPreview.tagName === 'IMG') {
                                    photoPreview.src = e.target.result;
                                } else {
                                    const img = document.createElement('img');
                                    img.src = e.target.result;
                                    img.alt = 'Preview';
                                    img.className = 'rounded-4 object-fit-cover';
                                    img.style.width = '100px';
                                    img.style.height = '100px';
                                    photoPreview.parentNode.replaceChild(img, photoPreview);
                                }
                            };
                            reader.readAsDataURL(file);

                            // Langsung upload
                            uploadFile(file);
                        }
                    });
                }

                // Tombol upload cukup untuk trigger file picker
                if (uploadPhotoBtn) {
                    uploadPhotoBtn.addEventListener('click', function() {
                        photoUpload.click();
                    });
                }

                // Function upload
                function uploadFile(file) {
                    const formData = new FormData();
                    formData.append('photo', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    // Tampilkan loading (opsional)
                    uploadPhotoBtn.disabled = true;
                    uploadPhotoBtn.innerHTML = 'Uploading...';

                    fetch('{{ route('admin.profile.photo.update') }}', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Photo uploaded successfully!');
                                location.reload();
                            } else {
                                alert(data.message || 'Upload failed');
                            }
                        })
                        .catch(error => {
                            alert('Upload failed: ' + error.message);
                        })
                        .finally(() => {
                            uploadPhotoBtn.disabled = false;
                            uploadPhotoBtn.innerHTML = 'Upload Photo';
                        });
                }

                // Remove photo
                if (removePhotoBtn) {
                    removePhotoBtn.addEventListener('click', function() {
                        if (confirm('Remove profile photo?')) {
                            fetch('{{ route('admin.profile.photo.remove') }}', {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        location.reload();
                                    } else {
                                        alert(data.message || 'Remove failed');
                                    }
                                });
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
