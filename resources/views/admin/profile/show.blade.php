@extends('layouts.admin.app')
@section('title', 'My Profile')
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* {
font-family: 'Plus Jakarta Sans', sans-serif;
}

.profile-page {
max-width: 1100px;
margin: 0 auto;
padding: 2rem 1.5rem;
}

.alert-custom {
display: flex;
align-items: center;
gap: .75rem;
padding: .85rem 1.1rem;
border-radius: 12px;
font-size: .88rem;
font-weight: 500;
margin-bottom: 1.25rem;
}

.alert-custom.success {
background: #f0fdf4;
border: 1px solid #bbf7d0;
color: #166534;
}

.alert-custom.error {
background: #fff1f2;
border: 1px solid #fecdd3;
color: #9f1239;
}

.profile-hero {
background: #fff;
border: 1px solid #f1f5f9;
border-radius: 24px;
overflow: visible;
box-shadow: 0 1px 3px rgba(0, 0, 0, .04), 0 8px 32px rgba(0, 0, 0, .04);
margin-bottom: 1.5rem;
position: relative;
}

.profile-banner {
height: 185px;
border-radius: 20px 20px 20px 20px;
margin: 16px 16px 0 16px;
position: relative;
overflow: hidden;
}

.profile-banner.mesh-blue {
background:
radial-gradient(ellipse at 15% 65%, rgba(96, 165, 250, .95) 0%, transparent 52%),
radial-gradient(ellipse at 82% 12%, rgba(59, 130, 246, .9) 0%, transparent 48%),
radial-gradient(ellipse at 68% 88%, rgba(29, 78, 216, .95) 0%, transparent 52%),
radial-gradient(ellipse at 48% 48%, rgba(147, 197, 253, .5) 0%, transparent 65%),
#0369a1;
}

.profile-banner.mesh-orange {
background:
radial-gradient(ellipse at 14% 60%, rgba(251, 191, 36, .9) 0%, transparent 52%),
radial-gradient(ellipse at 83% 18%, rgba(249, 115, 22, .95) 0%, transparent 48%),
radial-gradient(ellipse at 62% 84%, rgba(234, 88, 12, .9) 0%, transparent 52%),
radial-gradient(ellipse at 46% 50%, rgba(253, 224, 71, .5) 0%, transparent 65%),
#c2410c;
}

.profile-banner.mesh-green {
background:
radial-gradient(ellipse at 16% 64%, rgba(134, 239, 172, .95) 0%, transparent 52%),
radial-gradient(ellipse at 81% 14%, rgba(34, 197, 94, .9) 0%, transparent 48%),
radial-gradient(ellipse at 66% 86%, rgba(21, 128, 61, .95) 0%, transparent 52%),
radial-gradient(ellipse at 49% 47%, rgba(187, 247, 208, .45) 0%, transparent 65%),
#14532d;
}

.avatar-container {
position: relative;
margin-top: -48px;
margin-bottom: 15px;
padding-left: 28px;
z-index: 20;
}

.avatar-wrap {
width: 96px;
height: 96px;
border-radius: 50%;
border: 5px solid #fff;
overflow: hidden;
background: #fff;
position: relative;
}

.avatar-wrap img {
width: 100%;
height: 100%;
object-fit: cover;
}

.avatar-initials {
width: 100%;
height: 100%;
background: linear-gradient(135deg, #F97316, #ea580c);
display: flex;
align-items: center;
justify-content: center;
color: #fff;
font-size: 2.2rem;
font-weight: 800;
}

.profile-body {
padding: 16px 28px 24px;
}

.profile-name {
font-size: 1.55rem;
font-weight: 800;
color: #0F172A;
margin: 0 0 3px;
letter-spacing: -0.02em;
}

.profile-sub {
font-size: .88rem;
color: #64748b;
font-weight: 500;
margin-bottom: 2px;
}

.role-badge {
display: inline-flex;
align-items: center;
gap: 5px;
padding: 4px 12px;
border-radius: 20px;
font-size: .75rem;
font-weight: 700;
text-transform: uppercase;
letter-spacing: 0.04em;
}

.role-badge.super-admin {
background: #fff7ed;
color: #ea580c;
border: 1px solid #fed7aa;
}

.role-badge.admin {
background: #f0fdf4;
color: #16a34a;
border: 1px solid #bbf7d0;
}

.role-badge .dot {
width: 6px;
height: 6px;
border-radius: 50%;
background: currentColor;
}

.meta-row {
display: flex;
align-items: flex-end;
justify-content: space-between;
flex-wrap: wrap;
gap: 1rem;
}

.meta-right {
display: flex;
flex-direction: column;
align-items: flex-end;
gap: 8px;
}

.btn-edit-profile {
display: inline-flex;
align-items: center;
gap: 6px;
padding: 8px 20px;
background: #F97316;
color: #fff;
border-radius: 50px;
font-size: .83rem;
font-weight: 600;
text-decoration: none;
transition: all .2s;
}

.btn-edit-profile:hover {
background: #ea580c;
color: #fff;
transform: translateY(-1px);
box-shadow: 0 4px 16px rgba(249, 115, 22, .35);
}

.info-grid {
display: grid;
grid-template-columns: repeat(2, 1fr);
gap: 1rem;
margin-top: 1.25rem;
}

.info-card {
background: #fff;
border: 1px solid #f1f5f9;
border-radius: 18px;
padding: 20px;
transition: all .18s;
}

.info-card:hover {
border-color: #fed7aa;
box-shadow: 0 4px 12px rgba(0, 0, 0, .04);
}

.info-card-head {
display: flex;
align-items: center;
gap: 9px;
padding-bottom: 12px;
margin-bottom: 14px;
border-bottom: 1px solid #f8fafc;
}

.info-card-icon {
width: 32px;
height: 32px;
border-radius: 9px;
background: rgba(249, 115, 22, .08);
display: flex;
align-items: center;
justify-content: center;
}

.info-card-icon svg {
width: 16px;
height: 16px;
stroke: #F97316;
stroke-width: 2;
}

.info-card-title {
font-size: .78rem;
font-weight: 700;
color: #64748b;
text-transform: uppercase;
letter-spacing: .06em;
}

.info-row {
display: flex;
justify-content: space-between;
align-items: center;
padding: 7px 0;
font-size: .85rem;
border-bottom: 1px solid #f8fafc;
}

.info-row:last-child {
border-bottom: none;
}

.info-row .lbl {
color: #94a3b8;
font-weight: 400;
}

.info-row .val {
font-weight: 600;
color: #0F172A;
max-width: 60%;
text-align: right;
word-break: break-word;
}

.info-row .val.success {
color: #16a34a;
}

.info-row .val.muted {
color: #cbd5e1;
}

.info-row .val.accent {
color: #F97316;
}

@media (max-width: 768px) {
.info-grid {
grid-template-columns: 1fr;
}

.meta-row {
flex-direction: column;
}

.meta-right {
align-items: flex-start;
}
}
</style>
@endpush
@section('admin-content')
<div class="profile-page">
@if (session('success'))
<div class="alert-custom success">
<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
<polyline points="22 4 12 14.01 9 11.01" />
</svg>
{{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert-custom error">
<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5">
<circle cx="12" cy="12" r="10" />
<line x1="12" y1="8" x2="12" y2="12" />
<line x1="12" y1="16" x2="12.01" y2="16" />
</svg>
{{ session('error') }}
</div>
@endif

<div class="profile-hero">
@php
$admin = Auth::guard('admin')->user();
$banner = $admin->getPreference('profile_banner', 'mesh-blue');
$roleClass = match ($admin->role) {
'super_admin' => 'super-admin',
default => 'admin',
};
@endphp

<div class="profile-banner {{ $banner }}"></div>

<div class="avatar-container">
<div class="avatar-wrap">
@if ($admin->photo_url)
<img src="{{ $admin->photo_url }}" alt="{{ $admin->nama }}">
@else
<div class="avatar-initials">{{ $admin->initials }}</div>
@endif
</div>
</div>

<div class="profile-body">
<div class="meta-row">
<div>
<h1 class="profile-name">{{ $admin->nama }}</h1>
<p class="profile-sub">{{ $admin->position ?? 'Administrator' }}
@if ($admin->department)
· {{ $admin->department }}
@endif
</p>
<span class="role-badge {{ $roleClass }}">
<span class="dot"></span>
{{ $admin->role_label }}
</span>
</div>
<div class="meta-right">
<a href="{{ route('admin.profile.edit') }}" class="btn-edit-profile">
<svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5">
<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
<path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z" />
</svg>
Edit Profile
</a>
</div>
</div>
</div>
</div>

<div class="info-grid">
<div class="info-card">
<div class="info-card-head">
<div class="info-card-icon">
<svg viewBox="0 0 24 24" fill="none" stroke="#F97316" stroke-width="2">
<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
<circle cx="12" cy="7" r="4" />
</svg>
</div>
<span class="info-card-title">Contact Info</span>
</div>
<div class="info-row">
<span class="lbl">Email</span>
<span class="val">{{ $admin->email }}</span>
</div>
<div class="info-row">
<span class="lbl">Phone</span>
<span class="val {{ $admin->phone ? '' : 'muted' }}">{{ $admin->phone ?? 'Not set' }}</span>
</div>
<div class="info-row">
<span class="lbl">Department</span>
<span class="val {{ $admin->department ? '' : 'muted' }}">{{ $admin->department ?? 'Not set' }}</span>
</div>
</div>

<div class="info-card">
<div class="info-card-head">
<div class="info-card-icon">
<svg viewBox="0 0 24 24" fill="none" stroke="#F97316" stroke-width="2">
<rect x="3" y="11" width="18" height="11" rx="2" />
<path d="M7 11V7a5 5 0 0 1 10 0v4" />
</svg>
</div>
<span class="info-card-title">Account Info</span>
</div>
<div class="info-row">
<span class="lbl">Member since</span>
<span class="val">{{ $admin->created_at->format('M d, Y') }}</span>
</div>
<div class="info-row">
<span class="lbl">Last login</span>
<span class="val accent">{{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Never' }}</span>
</div>
<div class="info-row">
<span class="lbl">Account status</span>
<span class="val {{ $admin->is_active ? 'success' : 'muted' }}">{{ $admin->is_active ? '● Active' : '● Inactive' }}</span>
</div>
<div class="info-row">
<span class="lbl">Email verified</span>
<span class="val {{ $admin->email_verified_at ? 'success' : 'muted' }}">{{ $admin->email_verified_at ? '✓ Verified' : 'Pending' }}</span>
</div>
</div>
</div>
</div>
@endsection