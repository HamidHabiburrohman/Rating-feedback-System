@extends('layouts.admin.app')

@section('title', 'Edit Profile')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .profile-card {
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, 0.06);
            overflow: visible;
            border: 1px solid #f3f4f6;
            margin-bottom: 20px;
        }

        .profile-banner {
            height: 170px;
            border-radius: 25px 25px 25px 25px;
            margin: 20px 20px 5px 20px;
            position: relative;
        }

        /* ── Classic ── */
        .profile-banner.rainbow,
        .banner-option.rainbow {
            background: linear-gradient(135deg, #f8773c, #f5a623, #f9d423, #56ccf2, #2f80ed, #1d4ed8);
        }
        .profile-banner.blue-sea,
        .banner-option.blue-sea {
            background: linear-gradient(135deg, #006994, #003d5c, #001f33);
        }
        .profile-banner.sunset,
        .banner-option.sunset {
            background: linear-gradient(135deg, #ff512f, #dd2476, #ff9a44);
        }
        .profile-banner.blue-night,
        .banner-option.blue-night {
            background: linear-gradient(135deg, #0f172a, #1e3a8a, #3b82f6, #60a5fa);
        }

        /* ── Mesh Gradient Blobs ── */
        .profile-banner.mesh-blue,
        .banner-option.mesh-blue {
            background:
                radial-gradient(ellipse at 15% 65%, rgba(96, 165, 250, 0.95) 0%, transparent 52%),
                radial-gradient(ellipse at 82% 12%, rgba(59, 130, 246, 0.9) 0%, transparent 48%),
                radial-gradient(ellipse at 68% 88%, rgba(29, 78, 216, 0.95) 0%, transparent 52%),
                radial-gradient(ellipse at 48% 48%, rgba(147, 197, 253, 0.5) 0%, transparent 65%),
                #0369a1;
        }
        .profile-banner.mesh-red,
        .banner-option.mesh-red {
            background:
                radial-gradient(ellipse at 18% 62%, rgba(252, 165, 165, 0.95) 0%, transparent 52%),
                radial-gradient(ellipse at 80% 15%, rgba(239, 68, 68, 0.9) 0%, transparent 48%),
                radial-gradient(ellipse at 65% 85%, rgba(185, 28, 28, 0.95) 0%, transparent 52%),
                radial-gradient(ellipse at 50% 45%, rgba(254, 202, 202, 0.45) 0%, transparent 65%),
                #9b1c1c;
        }
        .profile-banner.mesh-orange,
        .banner-option.mesh-orange {
            background:
                radial-gradient(ellipse at 14% 60%, rgba(251, 191, 36, 0.9) 0%, transparent 52%),
                radial-gradient(ellipse at 83% 18%, rgba(249, 115, 22, 0.95) 0%, transparent 48%),
                radial-gradient(ellipse at 62% 84%, rgba(234, 88, 12, 0.9) 0%, transparent 52%),
                radial-gradient(ellipse at 46% 50%, rgba(253, 224, 71, 0.5) 0%, transparent 65%),
                #c2410c;
        }
        .profile-banner.mesh-green,
        .banner-option.mesh-green {
            background:
                radial-gradient(ellipse at 16% 64%, rgba(134, 239, 172, 0.95) 0%, transparent 52%),
                radial-gradient(ellipse at 81% 14%, rgba(34, 197, 94, 0.9) 0%, transparent 48%),
                radial-gradient(ellipse at 66% 86%, rgba(21, 128, 61, 0.95) 0%, transparent 52%),
                radial-gradient(ellipse at 49% 47%, rgba(187, 247, 208, 0.45) 0%, transparent 65%),
                #14532d;
        }
        .profile-banner.mesh-black,
        .banner-option.mesh-black {
            background:
                radial-gradient(ellipse at 17% 63%, rgba(107, 114, 128, 0.85) 0%, transparent 52%),
                radial-gradient(ellipse at 79% 16%, rgba(75, 85, 99, 0.9) 0%, transparent 48%),
                radial-gradient(ellipse at 64% 87%, rgba(55, 65, 81, 0.85) 0%, transparent 52%),
                radial-gradient(ellipse at 47% 49%, rgba(156, 163, 175, 0.3) 0%, transparent 65%),
                #030712;
        }

        /* ── Blob Blur Abstract ── */
        .profile-banner.blob-blue,
        .banner-option.blob-blue {
            background:
                radial-gradient(circle at 25% 35%, rgba(186, 230, 253, 0.95) 0%, rgba(96, 165, 250, 0.7) 28%, transparent 55%),
                radial-gradient(circle at 78% 20%, rgba(59, 130, 246, 0.9) 0%, rgba(37, 99, 235, 0.6) 25%, transparent 52%),
                radial-gradient(circle at 60% 80%, rgba(29, 78, 216, 1) 0%, rgba(30, 64, 175, 0.7) 30%, transparent 58%),
                radial-gradient(circle at 10% 85%, rgba(147, 197, 253, 0.8) 0%, transparent 45%),
                radial-gradient(circle at 88% 70%, rgba(224, 242, 254, 0.6) 0%, transparent 40%),
                #1e40af;
        }
        .profile-banner.blob-red,
        .banner-option.blob-red {
            background:
                radial-gradient(circle at 22% 38%, rgba(254, 202, 202, 0.95) 0%, rgba(248, 113, 113, 0.7) 28%, transparent 55%),
                radial-gradient(circle at 76% 22%, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.6) 25%, transparent 52%),
                radial-gradient(circle at 58% 78%, rgba(185, 28, 28, 1) 0%, rgba(153, 27, 27, 0.7) 30%, transparent 58%),
                radial-gradient(circle at 12% 82%, rgba(252, 165, 165, 0.8) 0%, transparent 45%),
                radial-gradient(circle at 86% 68%, rgba(254, 226, 226, 0.6) 0%, transparent 40%),
                #7f1d1d;
        }
        .profile-banner.blob-orange,
        .banner-option.blob-orange {
            background:
                radial-gradient(circle at 24% 36%, rgba(254, 243, 199, 0.95) 0%, rgba(252, 211, 77, 0.75) 28%, transparent 55%),
                radial-gradient(circle at 77% 21%, rgba(249, 115, 22, 0.9) 0%, rgba(234, 88, 12, 0.65) 25%, transparent 52%),
                radial-gradient(circle at 59% 79%, rgba(194, 65, 12, 1) 0%, rgba(154, 52, 18, 0.7) 30%, transparent 58%),
                radial-gradient(circle at 11% 83%, rgba(253, 186, 116, 0.85) 0%, transparent 45%),
                radial-gradient(circle at 87% 69%, rgba(255, 237, 213, 0.6) 0%, transparent 40%),
                #7c2d12;
        }
        .profile-banner.blob-green,
        .banner-option.blob-green {
            background:
                radial-gradient(circle at 23% 37%, rgba(209, 250, 229, 0.95) 0%, rgba(110, 231, 183, 0.75) 28%, transparent 55%),
                radial-gradient(circle at 75% 23%, rgba(34, 197, 94, 0.9) 0%, rgba(22, 163, 74, 0.65) 25%, transparent 52%),
                radial-gradient(circle at 57% 77%, rgba(21, 128, 61, 1) 0%, rgba(20, 83, 45, 0.7) 30%, transparent 58%),
                radial-gradient(circle at 13% 81%, rgba(134, 239, 172, 0.85) 0%, transparent 45%),
                radial-gradient(circle at 85% 67%, rgba(220, 252, 231, 0.6) 0%, transparent 40%),
                #14532d;
        }
        .profile-banner.blob-black,
        .banner-option.blob-black {
            background:
                radial-gradient(circle at 26% 34%, rgba(148, 163, 184, 0.6) 0%, rgba(100, 116, 139, 0.4) 28%, transparent 55%),
                radial-gradient(circle at 74% 24%, rgba(71, 85, 105, 0.8) 0%, rgba(51, 65, 85, 0.55) 25%, transparent 52%),
                radial-gradient(circle at 56% 76%, rgba(30, 41, 59, 1) 0%, rgba(15, 23, 42, 0.85) 30%, transparent 58%),
                radial-gradient(circle at 14% 80%, rgba(120, 113, 108, 0.45) 0%, transparent 45%),
                radial-gradient(circle at 84% 66%, rgba(203, 213, 225, 0.2) 0%, transparent 40%),
                #020617;
        }

        /* ── Avatar ── */
        .avatar-wrap {
            position: absolute;
            bottom: -52px;
            left: 50%;
            transform: translateX(-50%);
            width: 104px;
            height: 104px;
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.12);
            overflow: hidden;
            background: #fff;
            z-index: 5;
        }
        .avatar-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .avatar-initials {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #f8773c, #e5652a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 2.4rem;
            font-weight: 800;
        }

        .profile-body {
            padding: 72px 32px 28px;
        }

        /* ── Edit Banner Button ── */
        .btn-edit-banner {
            position: absolute;
            top: 14px;
            right: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px 7px 10px;
            background: rgba(255, 255, 255, 0.22);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            border-radius: 50px;
            color: #fff;
            font-size: 0.78rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: all .2s ease;
            z-index: 10;
            white-space: nowrap;
        }
        .btn-edit-banner:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.03);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        .btn-edit-banner svg { flex-shrink: 0; }

        /* ── Alerts ── */
        .alert-success-custom {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-error-custom {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ── Banner Modal ── */
        #bannerModal .modal-content {
            border: none;
            border-radius: 24px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.14);
            overflow: hidden;
        }
        #bannerModal .modal-header {
            padding: 20px 24px 16px;
            border-bottom: 1px solid #f3f4f6;
            background: #fafafa;
        }
        #bannerModal .modal-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #111827;
        }
        #bannerModal .modal-body {
            padding: 20px 24px 24px;
            background: #fff;
        }

        .banner-category-label {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #9ca3af;
            margin-bottom: 10px;
            margin-top: 4px;
        }
        .banner-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px;
            margin-bottom: 16px;
        }
        .banner-option {
            position: relative;
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 12px;
            cursor: pointer;
            border: 2.5px solid transparent;
            transition: all 0.2s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .banner-option::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 9px;
            background: rgba(255,255,255,0);
            transition: background 0.2s;
        }
        .banner-option:hover::after { background: rgba(255,255,255,0.12); }
        .banner-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.18);
        }
        .banner-option.active {
            border-color: #f8773c;
            box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.25), 0 4px 16px rgba(0, 0, 0, 0.15);
        }
        .banner-option .check-icon {
            display: none;
            position: absolute;
            top: 5px;
            right: 5px;
            width: 20px;
            height: 20px;
            background: #f8773c;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }
        .banner-option.active .check-icon { display: flex; }
        .banner-option .check-icon svg { width: 11px; height: 11px; }

        .banner-name {
            font-size: 0.65rem;
            font-weight: 600;
            color: #6b7280;
            text-align: center;
            margin-top: 5px;
            line-height: 1.3;
        }
        .banner-item { display: flex; flex-direction: column; }

        .modal-apply-btn {
            width: 100%;
            padding: 11px;
            background: linear-gradient(135deg, #f8773c, #e5652a);
            color: #fff;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 4px;
            letter-spacing: 0.01em;
        }
        .modal-apply-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(248, 119, 60, 0.35);
        }
        .divider-modal {
            height: 1px;
            background: #f3f4f6;
            margin: 16px 0;
        }
    </style>
@endpush

@section('admin-content')
    <div class="container py-4" style="max-width: 1200px; margin: 0 auto;">
        @if (session('success'))
            <div class="alert-success-custom">
                <i class="ti ti-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert-error-custom">
                <i class="ti ti-alert-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="profile-card">
            @php
                $banner = Auth::guard('admin')->user()->getPreference('profile_banner', 'mesh-blue');
            @endphp
            <div class="profile-banner {{ $banner }}" id="profileBanner">
                <button type="button" class="btn-edit-banner" data-bs-toggle="modal" data-bs-target="#bannerModal"
                    title="Change Banner">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <polyline points="21 15 16 10 5 21"/>
                    </svg>
                    Change Banner
                </button>
                <div class="avatar-wrap">
                    @if (Auth::guard('admin')->user()->photo_url)
                        <img src="{{ Auth::guard('admin')->user()->photo_url }}" alt="Avatar">
                    @else
                        <div class="avatar-initials">{{ Auth::guard('admin')->user()->initials }}</div>
                    @endif
                </div>
            </div>

            <div class="profile-body">
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
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2"
                                value="{{ old('email', Auth::guard('admin')->user()->email) }}" required>
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Phone</label>
                            <input type="text" name="phone" class="form-control rounded-3 py-2"
                                value="{{ old('phone', Auth::guard('admin')->user()->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Position</label>
                            <input type="text" name="position" class="form-control rounded-3 py-2"
                                value="{{ old('position', Auth::guard('admin')->user()->position) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Location</label>
                            <input type="text" name="location" class="form-control rounded-3 py-2"
                                value="{{ old('location', Auth::guard('admin')->user()->location) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Bio</label>
                            <textarea name="bio" class="form-control rounded-3 py-2" rows="4">{{ old('bio', Auth::guard('admin')->user()->bio) }}</textarea>
                        </div>
                        <input type="hidden" name="profile_banner" id="profileBannerInput" value="{{ $banner }}">
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn rounded-pill px-5" style="background: #f8773c; color: white;">
                            Save Changes
                        </button>
                        <a href="{{ route('admin.profile.show') }}"
                            class="btn btn-outline-secondary rounded-pill px-4 ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Banner Picker Modal ── --}}
    <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="bannerModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="#f8773c" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"
                            style="margin-right:6px; margin-bottom:2px;">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        Choose Banner
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    {{-- Mesh Gradient --}}
                    <div class="banner-category-label">✦ Mesh Gradient</div>
                    <div class="banner-grid">
                        @foreach ([
                            'mesh-blue'   => 'Blue',
                            'mesh-red'    => 'Red',
                            'mesh-orange' => 'Orange',
                            'mesh-green'  => 'Green',
                            'mesh-black'  => 'Black',
                        ] as $key => $label)
                            <div class="banner-item">
                                <div class="banner-option {{ $key }} {{ $banner == $key ? 'active' : '' }}"
                                    onclick="selectBanner('{{ $key }}', this)">
                                    <span class="check-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="banner-name">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="divider-modal"></div>

                    {{-- Blob Blur Abstract --}}
                    <div class="banner-category-label">◉ Blob Blur Abstract</div>
                    <div class="banner-grid">
                        @foreach ([
                            'blob-blue'   => 'Blue',
                            'blob-red'    => 'Red',
                            'blob-orange' => 'Orange',
                            'blob-green'  => 'Green',
                            'blob-black'  => 'Black',
                        ] as $key => $label)
                            <div class="banner-item">
                                <div class="banner-option {{ $key }} {{ $banner == $key ? 'active' : '' }}"
                                    onclick="selectBanner('{{ $key }}', this)">
                                    <span class="check-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="banner-name">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="divider-modal"></div>

                    {{-- Classic --}}
                    <div class="banner-category-label">◈ Classic</div>
                    <div class="banner-grid" style="grid-template-columns: repeat(4, 1fr);">
                        @foreach ([
                            'rainbow'    => 'Rainbow',
                            'blue-sea'   => 'Blue Sea',
                            'sunset'     => 'Sunset',
                            'blue-night' => 'Night',
                        ] as $key => $label)
                            <div class="banner-item">
                                <div class="banner-option {{ $key }} {{ $banner == $key ? 'active' : '' }}"
                                    onclick="selectBanner('{{ $key }}', this)">
                                    <span class="check-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                            stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"/>
                                        </svg>
                                    </span>
                                </div>
                                <div class="banner-name">{{ $label }}</div>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" class="modal-apply-btn" data-bs-dismiss="modal">
                        Apply Banner
                    </button>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const ALL_BANNERS = [
                'rainbow', 'blue-sea', 'sunset', 'blue-night',
                'mesh-blue', 'mesh-red', 'mesh-orange', 'mesh-green', 'mesh-black',
                'blob-blue', 'blob-red', 'blob-orange', 'blob-green', 'blob-black'
            ];

            function selectBanner(value, element) {
                document.querySelectorAll('.banner-option').forEach(el => el.classList.remove('active'));
                element.classList.add('active');
                document.getElementById('profileBannerInput').value = value;
                const banner = document.getElementById('profileBanner');
                ALL_BANNERS.forEach(cls => banner.classList.remove(cls));
                banner.classList.add(value);
            }
        </script>
    @endpush
@endsection