@extends('layouts.admin.app')

@section('title', 'My Profile')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    * { font-family: 'Plus Jakarta Sans', sans-serif; }



    .profile-container {
        max-width: 860px;
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
        background: linear-gradient(135deg,
            #f8773c 0%,
            #f5a623 20%,
            #f9d423 40%,
            #56ccf2 65%,
            #2f80ed 85%,
            #1d4ed8 100%);
        position: relative;
        border-radius: 25px 25px 25px 25px;
        margin: 20px 20px 5px 20px;
    }
    
    .profile-banner::after {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='170'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3CfeColorMatrix type='saturate' values='0'/%3E%3C/filter%3E%3Crect width='400' height='170' filter='url(%23n)' opacity='0.08'/%3E%3C/svg%3E");
        mix-blend-mode: overlay;
        border-radius: 28px 28px 0 0;
    }

    .btn-edit-float {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 38px;
        height: 38px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(8px);
        border: 1.5px solid rgba(255,255,255,0.3);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        text-decoration: none;
        transition: all .2s;
        z-index: 10;
    }
    
    .btn-edit-float:hover {
        background: rgba(255,255,255,0.35);
        color: #fff;
        transform: scale(1.02);
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
    
    .btn-settings {
        background: transparent;
        color: #374151;
        border: 1.5px solid #e5e7eb;
        border-radius: 50px;
        padding: 9px 22px;
        font-size: .88rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all .2s;
    }
    
    .btn-settings:hover {
        border-color: #9ca3af;
        background: #f9fafb;
        color: #374151;
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

    .skills-row {
        padding: 0 32px 12px;
    }
    
    .skills-row .label-sm {
        justify-content: flex-start;
        margin-bottom: 10px;
    }
    
    .perm-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: .82rem;
        font-weight: 600;
        background: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
        transition: all .15s;
    }
    
    .perm-badge.full-access {
        background: linear-gradient(135deg, #f8773c, #ea580c);
        color: #fff;
        border: none;
    }
    
    .perm-badge:hover {
        background: #e5e7eb;
    }
    
    .perm-badge.full-access:hover {
        background: linear-gradient(135deg, #f97316, #dc2626);
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
    
    .action-card-icon svg {
        color: #fff;
    }
    
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
    
    .stat-item .label {
        color: #6b7280;
    }
    
    .stat-item .value {
        font-weight: 600;
        color: #111827;
    }
    
    .stat-item .value.success {
        color: #16a34a;
    }
    
    .stat-item .value.muted {
        color: #9ca3af;
    }
    
    .stat-item .value.highlight {
        color: #f8773c;
        font-weight: 700;
    }

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

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .action-cards {
            grid-template-columns: 1fr;
        }
        .profile-meta-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }
        .profile-right {
            text-align: left;
        }
        .label-sm {
            justify-content: flex-start;
        }
        .profile-wrapper {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('admin-content')

        <div class="profile-card m-auto">
            <div class="profile-banner">
                <a href="{{ route('admin.profile.edit') }}" class="btn-edit-float" title="Edit Profile">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/>
                    </svg>
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
                            <a href="{{ route('admin.settings.index') }}" class="btn-settings">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                                Settings
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

            <div class="skills-row">
                <div class="label-sm">
                    Permissions
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    @php
                        $permissions = Auth::guard('admin')->user()->permissions;
                        if (is_string($permissions)) {
                            $decoded = json_decode($permissions, true);
                            $permissions = is_array($decoded) ? $decoded : [];
                        } elseif (!is_array($permissions)) {
                            $permissions = [];
                        }
                    @endphp

                    @if(in_array('*', $permissions) || Auth::guard('admin')->user()->isSuperAdmin())
                        <span class="perm-badge full-access">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Full Access
                        </span>
                    @elseif(!empty($permissions))
                        @foreach($permissions as $permission)
                            <span class="perm-badge">{{ ucwords(str_replace('_', ' ', $permission)) }}</span>
                        @endforeach
                    @else
                        <span class="text-muted" style="font-size:.85rem;">No specific permissions assigned</span>
                    @endif
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
@endsection