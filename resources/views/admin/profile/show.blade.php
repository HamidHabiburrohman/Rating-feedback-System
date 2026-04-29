@extends('layouts.admin.app')

@section('title', 'My Profile')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .profile-card {
        background: #fff;
        border-radius: 28px;
        box-shadow: 0 8px 40px rgba(0,0,0,0.06);
        overflow: visible;
        position: relative;
        margin-bottom: 20px;
        border: 1px solid #f3f4f6;
    }

    .profile-banner {
        height: 170px;
        border-radius: 25px 25px 25px 25px;
        margin: 20px 20px 5px 20px;
        position: relative;
    }

    /* ── Classic ── */
    .profile-banner.rainbow { background: linear-gradient(135deg, #f8773c, #f5a623, #f9d423, #56ccf2, #2f80ed, #1d4ed8); }
    .profile-banner.blue-sea { background: linear-gradient(135deg, #006994, #003d5c, #001f33); }
    .profile-banner.sunset  { background: linear-gradient(135deg, #ff512f, #dd2476, #ff9a44); }
    .profile-banner.blue-night { background: linear-gradient(135deg, #0f172a, #1e3a8a, #3b82f6, #60a5fa); }

    /* ── Mesh Gradient Blobs ── */
    .profile-banner.mesh-blue {
        background:
            radial-gradient(ellipse at 15% 65%, rgba(96, 165, 250, 0.95) 0%, transparent 52%),
            radial-gradient(ellipse at 82% 12%, rgba(59, 130, 246, 0.9) 0%, transparent 48%),
            radial-gradient(ellipse at 68% 88%, rgba(29, 78, 216, 0.95) 0%, transparent 52%),
            radial-gradient(ellipse at 48% 48%, rgba(147, 197, 253, 0.5) 0%, transparent 65%),
            #0369a1;
    }
    .profile-banner.mesh-red {
        background:
            radial-gradient(ellipse at 18% 62%, rgba(252, 165, 165, 0.95) 0%, transparent 52%),
            radial-gradient(ellipse at 80% 15%, rgba(239, 68, 68, 0.9) 0%, transparent 48%),
            radial-gradient(ellipse at 65% 85%, rgba(185, 28, 28, 0.95) 0%, transparent 52%),
            radial-gradient(ellipse at 50% 45%, rgba(254, 202, 202, 0.45) 0%, transparent 65%),
            #9b1c1c;
    }
    .profile-banner.mesh-orange {
        background:
            radial-gradient(ellipse at 14% 60%, rgba(251, 191, 36, 0.9) 0%, transparent 52%),
            radial-gradient(ellipse at 83% 18%, rgba(249, 115, 22, 0.95) 0%, transparent 48%),
            radial-gradient(ellipse at 62% 84%, rgba(234, 88, 12, 0.9) 0%, transparent 52%),
            radial-gradient(ellipse at 46% 50%, rgba(253, 224, 71, 0.5) 0%, transparent 65%),
            #c2410c;
    }
    .profile-banner.mesh-green {
        background:
            radial-gradient(ellipse at 16% 64%, rgba(134, 239, 172, 0.95) 0%, transparent 52%),
            radial-gradient(ellipse at 81% 14%, rgba(34, 197, 94, 0.9) 0%, transparent 48%),
            radial-gradient(ellipse at 66% 86%, rgba(21, 128, 61, 0.95) 0%, transparent 52%),
            radial-gradient(ellipse at 49% 47%, rgba(187, 247, 208, 0.45) 0%, transparent 65%),
            #14532d;
    }
    .profile-banner.mesh-black {
        background:
            radial-gradient(ellipse at 17% 63%, rgba(107, 114, 128, 0.85) 0%, transparent 52%),
            radial-gradient(ellipse at 79% 16%, rgba(75, 85, 99, 0.9) 0%, transparent 48%),
            radial-gradient(ellipse at 64% 87%, rgba(55, 65, 81, 0.85) 0%, transparent 52%),
            radial-gradient(ellipse at 47% 49%, rgba(156, 163, 175, 0.3) 0%, transparent 65%),
            #030712;
    }

    /* ── Blob Blur Abstract ── */
    .profile-banner.blob-blue {
        background:
            radial-gradient(circle at 25% 35%, rgba(186, 230, 253, 0.95) 0%, rgba(96, 165, 250, 0.7) 28%, transparent 55%),
            radial-gradient(circle at 78% 20%, rgba(59, 130, 246, 0.9) 0%, rgba(37, 99, 235, 0.6) 25%, transparent 52%),
            radial-gradient(circle at 60% 80%, rgba(29, 78, 216, 1) 0%, rgba(30, 64, 175, 0.7) 30%, transparent 58%),
            radial-gradient(circle at 10% 85%, rgba(147, 197, 253, 0.8) 0%, transparent 45%),
            radial-gradient(circle at 88% 70%, rgba(224, 242, 254, 0.6) 0%, transparent 40%),
            #1e40af;
    }
    .profile-banner.blob-red {
        background:
            radial-gradient(circle at 22% 38%, rgba(254, 202, 202, 0.95) 0%, rgba(248, 113, 113, 0.7) 28%, transparent 55%),
            radial-gradient(circle at 76% 22%, rgba(239, 68, 68, 0.9) 0%, rgba(220, 38, 38, 0.6) 25%, transparent 52%),
            radial-gradient(circle at 58% 78%, rgba(185, 28, 28, 1) 0%, rgba(153, 27, 27, 0.7) 30%, transparent 58%),
            radial-gradient(circle at 12% 82%, rgba(252, 165, 165, 0.8) 0%, transparent 45%),
            radial-gradient(circle at 86% 68%, rgba(254, 226, 226, 0.6) 0%, transparent 40%),
            #7f1d1d;
    }
    .profile-banner.blob-orange {
        background:
            radial-gradient(circle at 24% 36%, rgba(254, 243, 199, 0.95) 0%, rgba(252, 211, 77, 0.75) 28%, transparent 55%),
            radial-gradient(circle at 77% 21%, rgba(249, 115, 22, 0.9) 0%, rgba(234, 88, 12, 0.65) 25%, transparent 52%),
            radial-gradient(circle at 59% 79%, rgba(194, 65, 12, 1) 0%, rgba(154, 52, 18, 0.7) 30%, transparent 58%),
            radial-gradient(circle at 11% 83%, rgba(253, 186, 116, 0.85) 0%, transparent 45%),
            radial-gradient(circle at 87% 69%, rgba(255, 237, 213, 0.6) 0%, transparent 40%),
            #7c2d12;
    }
    .profile-banner.blob-green {
        background:
            radial-gradient(circle at 23% 37%, rgba(209, 250, 229, 0.95) 0%, rgba(110, 231, 183, 0.75) 28%, transparent 55%),
            radial-gradient(circle at 75% 23%, rgba(34, 197, 94, 0.9) 0%, rgba(22, 163, 74, 0.65) 25%, transparent 52%),
            radial-gradient(circle at 57% 77%, rgba(21, 128, 61, 1) 0%, rgba(20, 83, 45, 0.7) 30%, transparent 58%),
            radial-gradient(circle at 13% 81%, rgba(134, 239, 172, 0.85) 0%, transparent 45%),
            radial-gradient(circle at 85% 67%, rgba(220, 252, 231, 0.6) 0%, transparent 40%),
            #14532d;
    }
    .profile-banner.blob-black {
        background:
            radial-gradient(circle at 26% 34%, rgba(148, 163, 184, 0.6) 0%, rgba(100, 116, 139, 0.4) 28%, transparent 55%),
            radial-gradient(circle at 74% 24%, rgba(71, 85, 105, 0.8) 0%, rgba(51, 65, 85, 0.55) 25%, transparent 52%),
            radial-gradient(circle at 56% 76%, rgba(30, 41, 59, 1) 0%, rgba(15, 23, 42, 0.85) 30%, transparent 58%),
            radial-gradient(circle at 14% 80%, rgba(120, 113, 108, 0.45) 0%, transparent 45%),
            radial-gradient(circle at 84% 66%, rgba(203, 213, 225, 0.2) 0%, transparent 40%),
            #020617;
    }

    .btn-edit-float {
        position: absolute;
        top: 14px;
        right: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px 7px 10px;
        background: rgba(255,255,255,0.22);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1.5px solid rgba(255,255,255,0.4);
        border-radius: 50px;
        color: #fff;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-decoration: none;
        transition: all .2s;
        z-index: 10;
        white-space: nowrap;
    }
    .btn-edit-float:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: scale(1.03);
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    }

    .avatar-wrap {
        position: absolute;
        bottom: -52px;
        left: 32px;
        width: 104px;
        height: 104px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 4px 24px rgba(0,0,0,0.12);
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
        letter-spacing: -1px;
    }

    .profile-body {
        padding: 72px 32px 28px;
    }
    .profile-name {
        font-size: 1.6rem;
        font-weight: 800;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.5px;
    }
    .profile-position {
        font-size: .94rem;
        color: #6b7280;
        margin: 0 0 4px;
        font-weight: 500;
    }
    .profile-location {
        font-size: .85rem;
        color: #9ca3af;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .profile-meta-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-top: 6px;
    }
    .btn-edit-profile {
        background: #111827;
        color: #fff;
        border: none;
        border-radius: 50px;
        padding: 10px 24px;
        font-size: .88rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }
    .btn-edit-profile:hover {
        background: #1f2937;
        color: #fff;
        transform: translateY(-1px);
    }
    .profile-right {
        text-align: right;
    }
    .label-sm {
        font-size: .72rem;
        font-weight: 700;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: .08em;
        display: flex;
        align-items: center;
        gap: 5px;
        justify-content: flex-end;
        margin-bottom: 6px;
    }
    .role-chip {
        display: inline-block;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px 14px;
        font-size: .84rem;
        font-weight: 600;
        color: #374151;
    }
    .role-chip.super {
        background: #fff7ed;
        border-color: #fed7aa;
        color: #ea580c;
    }
    .role-chip.unit {
        background: #eff6ff;
        border-color: #bfdbfe;
        color: #2563eb;
    }
    .profile-divider {
        border: none;
        border-top: 1.5px solid #f3f4f6;
        margin: 20px 32px;
    }
    .action-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        padding: 6px 32px 32px;
    }
    .action-card {
        background: #f9fafb;
        border: 1.5px solid #f3f4f6;
        border-radius: 16px;
        padding: 18px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        position: relative;
        text-decoration: none;
        color: inherit;
        transition: all .2s;
        cursor: pointer;
    }
    .action-card:hover {
        border-color: #e5e7eb;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        transform: translateY(-2px);
        color: inherit;
    }
    .action-card-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #f8773c;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 18px;
        right: 18px;
    }
    .action-card-icon svg { color: #fff; }
    .action-card-title {
        font-size: .9rem;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .action-card-desc {
        font-size: .78rem;
        color: #9ca3af;
        margin: 0;
        line-height: 1.45;
    }
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 20px;
    }
    .stat-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 24px;
        transition: all .2s;
        border: 1px solid #f3f4f6;
    }
    .stat-card:hover {
        box-shadow: 0 6px 24px rgba(0,0,0,0.08);
        border-color: #e5e7eb;
    }
    .stat-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1.5px solid #f3f4f6;
    }
    .stat-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(248, 119, 60, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .stat-card-title {
        font-size: .85rem;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: .03em;
    }
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-size: .88rem;
    }
    .stat-item .label { color: #6b7280; }
    .stat-item .value { font-weight: 600; color: #111827; }
    .stat-item .value.success { color: #16a34a; }
    .stat-item .value.muted { color: #9ca3af; }
    .stat-item .value.highlight { color: #f8773c; font-weight: 700; }

    .bio-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        padding: 24px;
        margin-top: 20px;
        border: 1px solid #f3f4f6;
    }
    .bio-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
    }
    .bio-card-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(248, 119, 60, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bio-card-title {
        font-size: .85rem;
        font-weight: 700;
        color: #374151;
        text-transform: uppercase;
        letter-spacing: .03em;
    }

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
</style>
@endpush

@section('admin-content')
<div class="container py-4 profile-container">
    @if(session('success'))
        <div class="alert-success-custom">
            <i class="ti ti-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert-error-custom">
            <i class="ti ti-alert-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="profile-card m-auto">
        @php
            $banner = Auth::guard('admin')->user()->getPreference('profile_banner', 'mesh-blue');
        @endphp
        <div class="profile-banner {{ $banner }}">
            <a href="{{ route('admin.profile.edit') }}" class="btn-edit-float" title="Edit Profile">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
                Edit Profile
            </a>
            <div class="avatar-wrap">
                @if(Auth::guard('admin')->user()->photo_url)
                    <img src="{{ Auth::guard('admin')->user()->photo_url }}" alt="{{ Auth::guard('admin')->user()->nama }}">
                @else
                    <div class="avatar-initials">{{ Auth::guard('admin')->user()->initials }}</div>
                @endif
            </div>
        </div>

        <div class="profile-body">
            <div class="profile-meta-row">
                <div>
                    <h1 class="profile-name">{{ Auth::guard('admin')->user()->nama }}</h1>
                    <p class="profile-position">{{ Auth::guard('admin')->user()->position ?? 'Administrator' }}</p>
                    @if(Auth::guard('admin')->user()->location)
                    <p class="profile-location">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        {{ Auth::guard('admin')->user()->location }}
                    </p>
                    @endif
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('admin.profile.edit') }}" class="btn-edit-profile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/>
                            </svg>
                            Edit Profile
                        </a>
                    </div>
                </div>
                <div class="profile-right">
                    <div class="label-sm">
                        Current role
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                    </div>
                    @php
                        $roleClass = match(Auth::guard('admin')->user()->role) {
                            'super_admin' => 'super',
                            'unit'        => 'unit',
                            default       => '',
                        };
                    @endphp
                    <span class="role-chip {{ $roleClass }}">
                        {{ Auth::guard('admin')->user()->role_label }}
                    </span>
                </div>
            </div>
        </div>

        <hr class="profile-divider">

        <div class="action-cards">
            <a href="{{ route('admin.profile.edit') }}" class="action-card">
                <div class="action-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <p class="action-card-title">Update Info</p>
                <p class="action-card-desc">Keep your profile up-to-date so others know you better.</p>
            </a>
            <a href="{{ route('admin.profile.edit') }}#security" class="action-card">
                <div class="action-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <p class="action-card-title">Security</p>
                <p class="action-card-desc">Change your password and manage account security settings.</p>
            </a>
            <a href="{{ route('admin.profile.edit') }}#preferences" class="action-card">
                <div class="action-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                </div>
                <p class="action-card-title">Preferences</p>
                <p class="action-card-desc">Set your theme, language and notification preferences.</p>
            </a>
        </div>
    </div>

    @if(Auth::guard('admin')->user()->bio)
    <div class="bio-card">
        <div class="bio-card-header">
            <div class="bio-card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f8773c" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <span class="bio-card-title">About Me</span>
        </div>
        <p class="mb-0" style="color:#4b5563; font-size:.9rem; line-height:1.7;">
            {{ Auth::guard('admin')->user()->bio }}
        </p>
    </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f8773c" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <span class="stat-card-title">Account Info</span>
            </div>
            <div class="stat-item">
                <span class="label">Member since</span>
                <span class="value">{{ Auth::guard('admin')->user()->created_at->format('M d, Y') }}</span>
            </div>
            <div class="stat-item">
                <span class="label">Email</span>
                <span class="value">{{ Auth::guard('admin')->user()->email }}</span>
            </div>
            <div class="stat-item">
                <span class="label">Phone</span>
                <span class="value {{ Auth::guard('admin')->user()->phone ? '' : 'muted' }}">
                    {{ Auth::guard('admin')->user()->phone ?? 'Not set' }}
                </span>
            </div>
            <div class="stat-item">
                <span class="label">Location</span>
                <span class="value {{ Auth::guard('admin')->user()->location ? '' : 'muted' }}">
                    {{ Auth::guard('admin')->user()->location ?? 'Not set' }}
                </span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f8773c" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <span class="stat-card-title">Security Status</span>
            </div>
            <div class="stat-item">
                <span class="label">Email verified</span>
                <span class="value {{ Auth::guard('admin')->user()->email_verified_at ? 'success' : 'muted' }}">
                    {{ Auth::guard('admin')->user()->email_verified_at ? '✓ Verified' : 'Pending' }}
                </span>
            </div>
            <div class="stat-item">
                <span class="label">Last login</span>
                <span class="value highlight">
                    {{ Auth::guard('admin')->user()->last_login_at ? Auth::guard('admin')->user()->last_login_at->diffForHumans() : 'Never' }}
                </span>
            </div>
            <div class="stat-item">
                <span class="label">Last login IP</span>
                <span class="value">{{ Auth::guard('admin')->user()->last_login_ip ?? 'N/A' }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card-header">
                <div class="stat-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f8773c" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                </div>
                <span class="stat-card-title">Preferences</span>
            </div>
            @php
                $preferences = Auth::guard('admin')->user()->preferences;
                if (is_string($preferences)) {
                    $decoded = json_decode($preferences, true);
                    $preferences = is_array($decoded) ? $decoded : [];
                } elseif (!is_array($preferences)) {
                    $preferences = [];
                }
            @endphp
            <div class="stat-item">
                <span class="label">Theme</span>
                <span class="value text-capitalize">{{ $preferences['theme'] ?? 'Light' }}</span>
            </div>
            <div class="stat-item">
                <span class="label">Language</span>
                <span class="value text-uppercase">{{ $preferences['language'] ?? 'ID' }}</span>
            </div>
            <div class="stat-item">
                <span class="label">Notifications</span>
                <span class="value {{ (isset($preferences['notifications']) && $preferences['notifications']) ? 'success' : 'muted' }}">
                    {{ (isset($preferences['notifications']) && $preferences['notifications']) ? 'Enabled' : 'Disabled' }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection