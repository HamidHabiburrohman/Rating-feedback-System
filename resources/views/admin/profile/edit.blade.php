@extends('layouts.admin.app')
@section('title', 'Edit Profile')
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
* {
font-family: 'Plus Jakarta Sans', sans-serif;
}

.edit-page {
max-width: 860px;
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
margin-bottom: 1.25rem;
position: relative;
}

.profile-banner {
height: 160px;
border-radius: 20px 20px 20px 20px;
margin: 16px 16px 0;
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

.avatar-container {
display: flex;
justify-content: center;
margin-top: -50px;
margin-bottom: 16px;
position: relative;
z-index: 5;
}

.avatar-wrap {
width: 110px;
height: 110px;
border-radius: 50%;
border: 4px solid #fff;
overflow: hidden;
background: #fff;
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
font-size: 2.5rem;
font-weight: 800;
}

.hero-name-zone {
text-align: center;
padding: 0 24px 20px;
border-bottom: 1px solid #f1f5f9;
margin-bottom: 1.5rem;
}

.hero-name {
font-size: 1.3rem;
font-weight: 800;
color: #0F172A;
margin: 0 0 2px;
letter-spacing: -0.02em;
}

.hero-role {
display: inline-flex;
align-items: center;
gap: 5px;
padding: 3px 10px;
border-radius: 20px;
font-size: .73rem;
font-weight: 700;
text-transform: uppercase;
letter-spacing: .04em;
}

.hero-role.super-admin {
background: #fff7ed;
color: #ea580c;
border: 1px solid #fed7aa;
}

.hero-role.admin {
background: #f0fdf4;
color: #16a34a;
border: 1px solid #bbf7d0;
}

.tab-strip {
display: flex;
gap: 2px;
background: #f8fafc;
border-radius: 14px;
padding: 4px;
margin-bottom: 1.5rem;
}

.tab-btn {
flex: 1;
display: flex;
align-items: center;
justify-content: center;
gap: 6px;
padding: 9px 14px;
border: none;
border-radius: 11px;
background: transparent;
color: #94a3b8;
font-size: .83rem;
font-weight: 600;
cursor: pointer;
transition: all .18s;
font-family: 'Plus Jakarta Sans', sans-serif;
}

.tab-btn.active {
background: #fff;
color: #0F172A;
box-shadow: 0 1px 4px rgba(0, 0, 0, .08);
}

.tab-btn.active svg {
color: #F97316;
}

.tab-btn svg {
color: #cbd5e1;
transition: color .18s;
}

.tab-panel {
display: none;
}

.tab-panel.active {
display: block;
}

.form-card {
background: #fff;
border: 1px solid #f1f5f9;
border-radius: 20px;
padding: 24px;
box-shadow: 0 1px 3px rgba(0, 0, 0, .03), 0 4px 16px rgba(0, 0, 0, .04);
}

.form-card+.form-card {
margin-top: 1rem;
}

.form-card-title {
font-size: .78rem;
font-weight: 700;
text-transform: uppercase;
letter-spacing: .07em;
color: #94a3b8;
margin-bottom: 1.1rem;
display: flex;
align-items: center;
gap: 7px;
}

.form-card-title span {
width: 28px;
height: 28px;
border-radius: 8px;
background: rgba(249, 115, 22, .08);
display: inline-flex;
align-items: center;
justify-content: center;
}

.field-group {
margin-bottom: 1rem;
}

.field-group:last-child {
margin-bottom: 0;
}

.field-label {
display: block;
font-size: .82rem;
font-weight: 600;
color: #374151;
margin-bottom: 5px;
}

.field-hint {
font-size: .74rem;
color: #94a3b8;
font-weight: 400;
margin-left: 4px;
}

.form-input {
width: 100%;
padding: 9px 14px;
border: 1.5px solid #e2e8f0;
border-radius: 10px;
font-size: .88rem;
color: #0F172A;
background: #fafafa;
font-family: 'Plus Jakarta Sans', sans-serif;
transition: all .18s;
outline: none;
}

.form-input:focus {
border-color: #F97316;
background: #fff;
box-shadow: 0 0 0 3px rgba(249, 115, 22, .1);
}

.form-input::placeholder {
color: #cbd5e1;
}

.form-textarea {
resize: vertical;
min-height: 100px;
}

.field-error {
font-size: .76rem;
color: #ef4444;
margin-top: 4px;
}

.toggle-row {
display: flex;
align-items: center;
justify-content: space-between;
padding: 10px 0;
border-bottom: 1px solid #f8fafc;
}

.toggle-row:last-child {
border-bottom: none;
}

.toggle-label {
font-size: .88rem;
color: #374151;
font-weight: 500;
}

.toggle-desc {
font-size: .76rem;
color: #94a3b8;
margin-top: 1px;
}

.toggle-switch {
position: relative;
width: 40px;
height: 22px;
flex-shrink: 0;
cursor: pointer;
}

.toggle-switch input {
opacity: 0;
width: 0;
height: 0;
}

.toggle-thumb {
position: absolute;
inset: 0;
background: #e2e8f0;
border-radius: 11px;
transition: all .2s;
}

.toggle-thumb::before {
content: '';
position: absolute;
width: 16px;
height: 16px;
border-radius: 50%;
background: #fff;
top: 3px;
left: 3px;
transition: transform .2s;
box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
}

.toggle-switch input:checked+.toggle-thumb {
background: #F97316;
}

.toggle-switch input:checked+.toggle-thumb::before {
transform: translateX(18px);
}

.form-footer {
display: flex;
align-items: center;
justify-content: center;
gap: 12px;
padding-top: 1.5rem;
}

.btn-save,
.btn-cancel {
display: inline-flex;
align-items: center;
justify-content: center;
gap: 7px;
height: 42px;
min-width: 160px;
padding: 0 24px;
border-radius: 50px;
font-size: .88rem;
font-weight: 600;
font-family: 'Plus Jakarta Sans', sans-serif;
cursor: pointer;
transition: all .2s;
white-space: nowrap;
line-height: 1;
}

.btn-save {
background: #F97316;
color: #fff;
border: 1.5px solid #F97316;
}

.btn-save:hover {
background: #ea580c;
border-color: #ea580c;
box-shadow: 0 4px 16px rgba(249, 115, 22, .35);
transform: translateY(-1px);
}

.btn-cancel {
background: transparent;
color: #64748b;
border: 1.5px solid #e2e8f0;
text-decoration: none;
}

.btn-cancel:hover {
border-color: #cbd5e1;
color: #374151;
background: #f8fafc;
}

.csd-wrapper {
position: relative;
}

.csd-trigger {
display: flex;
align-items: center;
gap: 8px;
padding: 9px 14px;
border: 1.5px solid #e2e8f0;
border-radius: 10px;
font-size: .88rem;
color: #0F172A;
background: #fafafa;
cursor: pointer;
transition: border-color .18s, box-shadow .18s, background .18s;
user-select: none;
min-height: 40px;
}

.csd-trigger:hover,
.csd-trigger.open {
border-color: #F97316;
background: #fff;
box-shadow: 0 0 0 3px rgba(249, 115, 22, .1);
}

.csd-trigger-text {
flex: 1;
}

.csd-trigger-icon {
display: flex;
align-items: center;
justify-content: center;
width: 22px;
height: 22px;
flex-shrink: 0;
border-radius: 6px;
background: rgba(249, 115, 22, .08);
}

.csd-trigger-icon svg {
width: 13px;
height: 13px;
stroke: #F97316;
stroke-width: 2;
}

.csd-arrow {
margin-left: auto;
flex-shrink: 0;
transition: transform .2s;
}

.csd-trigger.open .csd-arrow {
transform: rotate(180deg);
}

.csd-dropdown {
position: absolute;
top: calc(100% + 5px);
left: 0;
right: 0;
background: #fff;
border: 1.5px solid #e2e8f0;
border-radius: 13px;
box-shadow: 0 12px 40px rgba(0, 0, 0, .12), 0 2px 8px rgba(0, 0, 0, .06);
z-index: 9999;
overflow: hidden;
opacity: 0;
transform: translateY(-6px) scale(.98);
pointer-events: none;
transition: opacity .16s, transform .16s;
display: flex;
flex-direction: column;
max-height: 260px;
}

.csd-dropdown.open {
opacity: 1;
transform: translateY(0) scale(1);
pointer-events: auto;
}

.csd-search-wrap {
padding: 8px 10px;
border-bottom: 1px solid #f1f5f9;
background: #fafafa;
display: flex;
align-items: center;
gap: 7px;
}

.csd-search-wrap svg {
width: 13px;
height: 13px;
stroke: #94a3b8;
flex-shrink: 0;
}

.csd-search {
flex: 1;
border: none;
outline: none;
background: transparent;
font-size: .84rem;
font-family: 'Plus Jakarta Sans', sans-serif;
color: #0F172A;
}

.csd-search::placeholder {
color: #cbd5e1;
}

.csd-list {
overflow-y: auto;
flex: 1;
scrollbar-width: thin;
scrollbar-color: #e2e8f0 transparent;
}

.csd-list::-webkit-scrollbar {
width: 4px;
}

.csd-list::-webkit-scrollbar-track {
background: transparent;
}

.csd-list::-webkit-scrollbar-thumb {
background: #e2e8f0;
border-radius: 4px;
}

.csd-opt {
display: flex;
align-items: center;
gap: 9px;
padding: 9px 14px;
font-size: .86rem;
cursor: pointer;
color: #374151;
transition: background .1s, color .1s;
}

.csd-opt:hover {
background: #fff7ed;
color: #F97316;
}

.csd-opt.selected {
background: #fff7ed;
color: #F97316;
font-weight: 600;
}

.csd-opt.hidden {
display: none;
}

.csd-opt .csd-check {
margin-left: auto;
width: 16px;
height: 16px;
border-radius: 50%;
background: #F97316;
display: none;
align-items: center;
justify-content: center;
flex-shrink: 0;
}

.csd-opt.selected .csd-check {
display: flex;
}

.csd-opt .csd-check svg {
width: 9px;
height: 9px;
stroke: #fff;
stroke-width: 3;
}

.row-2 {
display: grid;
grid-template-columns: 1fr 1fr;
gap: 1rem;
}

@media (max-width: 640px) {
.avatar-wrap {
width: 90px;
height: 90px;
}

.hero-name {
font-size: 1.1rem;
}

.row-2 {
grid-template-columns: 1fr;
gap: .75rem;
}

.form-footer {
flex-direction: column;
}

.btn-save,
.btn-cancel {
width: 100%;
min-width: unset;
}
}
</style>
@endpush
@section('admin-content')
<div class="edit-page">
@if (session('success'))
<div class="alert-custom success">
<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
<polyline points="22 4 12 14.01 9 11.01" />
</svg>
{{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert-custom error">
<svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
<circle cx="12" cy="12" r="10" />
<line x1="12" y1="8" x2="12" y2="12" />
<line x1="12" y1="16" x2="12.01" y2="16" />
</svg>
{{ session('error') }}
</div>
@endif

@php
$admin = Auth::guard('admin')->user();
$banner = $admin->getPreference('profile_banner', 'mesh-blue');
$roleClass = match ($admin->role) {
'super_admin' => 'super-admin',
default => 'admin',
};
$prefs = is_array($admin->preferences) ? $admin->preferences : json_decode($admin->preferences, true) ?? [];
@endphp

<div class="profile-hero">
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

<div class="hero-name-zone">
<h2 class="hero-name">{{ $admin->nama }}</h2>
<span class="hero-role {{ $roleClass }}">{{ $admin->role_label }}</span>
</div>
</div>

<div class="tab-strip" id="tabStrip">
<button class="tab-btn active" onclick="switchTab('profile', this)">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
<circle cx="12" cy="7" r="4" />
</svg>
Profile
</button>
<button class="tab-btn" onclick="switchTab('security', this)" id="tab-security">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<rect x="3" y="11" width="18" height="11" rx="2" />
<path d="M7 11V7a5 5 0 0 1 10 0v4" />
</svg>
Security
</button>
<button class="tab-btn" onclick="switchTab('preferences', this)" id="tab-preferences">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
<circle cx="12" cy="12" r="3" />
<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-2.82 1.18V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
</svg>
Preferences
</button>
</div>

<div class="tab-panel active" id="panel-profile">
<form action="{{ route('admin.profile.update') }}" method="POST">
@csrf @method('PUT')

<div class="form-card">
<div class="form-card-title">
<span>
<svg width="13" height="13" fill="none" stroke="#F97316" stroke-width="2" viewBox="0 0 24 24">
<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
<circle cx="12" cy="7" r="4" />
</svg>
</span>
Identity
</div>
<div class="row-2">
<div class="field-group">
<label class="field-label">Full Name <span class="field-hint">*</span></label>
<input type="text" name="nama" class="form-input" value="{{ old('nama', $admin->nama) }}" required>
@error('nama')
<div class="field-error">{{ $message }}</div>
@enderror
</div>
<div class="field-group">
<label class="field-label">Email Address <span class="field-hint">*</span></label>
<input type="email" name="email" class="form-input" value="{{ old('email', $admin->email) }}" required>
@error('email')
<div class="field-error">{{ $message }}</div>
@enderror
</div>
</div>
<div class="row-2">
<div class="field-group">
<label class="field-label">Phone Number</label>
<input type="text" name="phone" class="form-input" value="{{ old('phone', $admin->phone) }}">
</div>
<div class="field-group">
<label class="field-label">Position</label>
<input type="text" name="position" class="form-input" value="{{ old('position', $admin->position) }}">
</div>
</div>
<div class="row-2">
<div class="field-group">
<label class="field-label">Department</label>
<input type="text" name="department" class="form-input" value="{{ old('department', $admin->department) }}">
</div>
<div class="field-group">
<label class="field-label">Timezone</label>
<div class="csd-wrapper" id="csd-timezone">
<input type="hidden" name="timezone" class="csd-input" value="{{ old('timezone', $admin->timezone ?? 'Asia/Jakarta') }}">
<div class="csd-trigger" onclick="csdToggle('csd-timezone')">
<div class="csd-trigger-icon">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
<circle cx="12" cy="12" r="10" />
<polyline points="12 6 12 12 16 14" />
</svg>
</div>
<span class="csd-trigger-text">{{ old('timezone', $admin->timezone ?? 'Asia/Jakarta') }}</span>
<svg class="csd-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5">
<polyline points="6 9 12 15 18 9" />
</svg>
</div>
<div class="csd-dropdown" id="csd-timezone-drop">
<div class="csd-search-wrap">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
<circle cx="11" cy="11" r="8" />
<line x1="21" y1="21" x2="16.65" y2="16.65" />
</svg>
<input type="text" class="csd-search" placeholder="Search timezone…" oninput="csdSearch('csd-timezone', this.value)">
</div>
<div class="csd-list">
@php $currentTz = old('timezone', $admin->timezone ?? 'Asia/Jakarta'); @endphp
@foreach (timezone_identifiers_list() as $tz)
<div class="csd-opt {{ $currentTz === $tz ? 'selected' : '' }}" data-value="{{ $tz }}" onclick="csdSelect('csd-timezone', '{{ $tz }}', '{{ $tz }}')">
{{ $tz }}
<span class="csd-check">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
<polyline points="20 6 9 17 4 12" />
</svg>
</span>
</div>
@endforeach
</div>
</div>
</div>
</div>
</div>

<div class="field-group">
<label class="field-label">Bio</label>
<textarea name="bio" class="form-input form-textarea" placeholder="A short bio…">{{ old('bio', $admin->bio) }}</textarea>
</div>
</div>

<div class="form-footer">
<a href="{{ route('admin.profile.show') }}" class="btn-cancel">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
<line x1="18" y1="6" x2="6" y2="18" />
<line x1="6" y1="6" x2="18" y2="18" />
</svg>
Cancel
</a>
<button type="submit" class="btn-save">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
<polyline points="17 21 17 13 7 13 7 21" />
<polyline points="7 3 7 8 15 8" />
</svg>
Save Changes
</button>
</div>
</form>
</div>

<div class="tab-panel" id="panel-security">
<form action="{{ route('admin.profile.update-password') }}" method="POST">
@csrf @method('PUT')

<div class="form-card">
<div class="form-card-title">
<span>
<svg width="13" height="13" fill="none" stroke="#F97316" stroke-width="2" viewBox="0 0 24 24">
<rect x="3" y="11" width="18" height="11" rx="2" />
<path d="M7 11V7a5 5 0 0 1 10 0v4" />
</svg>
</span>
Change Password
</div>
<div class="field-group">
<label class="field-label">Current Password</label>
<input type="password" name="current_password" class="form-input">
@error('current_password')
<div class="field-error">{{ $message }}</div>
@enderror
</div>
<div class="row-2">
<div class="field-group">
<label class="field-label">New Password</label>
<input type="password" name="password" class="form-input">
@error('password')
<div class="field-error">{{ $message }}</div>
@enderror
</div>
<div class="field-group">
<label class="field-label">Confirm New Password</label>
<input type="password" name="password_confirmation" class="form-input">
</div>
</div>
</div>

<div class="form-footer">
<a href="{{ route('admin.profile.show') }}" class="btn-cancel">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
<line x1="18" y1="6" x2="6" y2="18" />
<line x1="6" y1="6" x2="18" y2="18" />
</svg>
Cancel
</a>
<button type="submit" class="btn-save">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
</svg>
Update Security
</button>
</div>
</form>
</div>

<div class="tab-panel" id="panel-preferences">
<form action="{{ route('admin.profile.update-preferences') }}" method="POST">
@csrf @method('PUT')

<div class="form-card">
<div class="form-card-title">
<span>
<svg width="13" height="13" fill="none" stroke="#F97316" stroke-width="2" viewBox="0 0 24 24">
<circle cx="12" cy="12" r="3" />
<path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-2.82 1.18V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
</svg>
</span>
Display Preferences
</div>

<div class="row-2">
@php
$currentTheme = old('theme', $prefs['theme'] ?? 'light');
$themeLabels = ['light' => '☀️ Light', 'dark' => '🌙 Dark', 'system' => '💻 System'];
@endphp
<div class="field-group">
<label class="field-label">Theme</label>
<div class="csd-wrapper" id="csd-theme">
<input type="hidden" name="preferences[theme]" class="csd-input" value="{{ $currentTheme }}">
<div class="csd-trigger" onclick="csdToggle('csd-theme')">
<div class="csd-trigger-icon">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
<circle cx="12" cy="12" r="5" />
<line x1="12" y1="1" x2="12" y2="3" />
<line x1="12" y1="21" x2="12" y2="23" />
</svg>
</div>
<span class="csd-trigger-text">{{ $themeLabels[$currentTheme] ?? ucfirst($currentTheme) }}</span>
<svg class="csd-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5">
<polyline points="6 9 12 15 18 9" />
</svg>
</div>
<div class="csd-dropdown" id="csd-theme-drop">
<div class="csd-list">
<div class="csd-opt {{ $currentTheme === 'light' ? 'selected' : '' }}" data-value="light" onclick="csdSelect('csd-theme', 'light', '☀️ Light')">
☀️ Light
<span class="csd-check">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
<polyline points="20 6 9 17 4 12" />
</svg>
</span>
</div>
<div class="csd-opt {{ $currentTheme === 'dark' ? 'selected' : '' }}" data-value="dark" onclick="csdSelect('csd-theme', 'dark', '🌙 Dark')">
🌙 Dark
<span class="csd-check">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
<polyline points="20 6 9 17 4 12" />
</svg>
</span>
</div>
<div class="csd-opt {{ $currentTheme === 'system' ? 'selected' : '' }}" data-value="system" onclick="csdSelect('csd-theme', 'system', '💻 System')">
💻 System
<span class="csd-check">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
<polyline points="20 6 9 17 4 12" />
</svg>
</span>
</div>
</div>
</div>
</div>
</div>

@php
$currentLang = old('language', $prefs['language'] ?? 'id');
$langLabels = ['id' => '🇮🇩 Indonesia (ID)', 'en' => '🇬🇧 English (EN)'];
@endphp
<div class="field-group">
<label class="field-label">Language</label>
<div class="csd-wrapper" id="csd-language">
<input type="hidden" name="preferences[language]" class="csd-input" value="{{ $currentLang }}">
<div class="csd-trigger" onclick="csdToggle('csd-language')">
<div class="csd-trigger-icon">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
<circle cx="12" cy="12" r="10" />
<line x1="2" y1="12" x2="22" y2="12" />
<path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
</svg>
</div>
<span class="csd-trigger-text">{{ $langLabels[$currentLang] ?? strtoupper($currentLang) }}</span>
<svg class="csd-arrow" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2.5">
<polyline points="6 9 12 15 18 9" />
</svg>
</div>
<div class="csd-dropdown" id="csd-language-drop">
<div class="csd-list">
<div class="csd-opt {{ $currentLang === 'id' ? 'selected' : '' }}" data-value="id" onclick="csdSelect('csd-language', 'id', '🇮🇩 Indonesia (ID)')">
🇮🇩 Indonesia (ID)
<span class="csd-check">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
<polyline points="20 6 9 17 4 12" />
</svg>
</span>
</div>
<div class="csd-opt {{ $currentLang === 'en' ? 'selected' : '' }}" data-value="en" onclick="csdSelect('csd-language', 'en', '🇬🇧 English (EN)')">
🇬🇧 English (EN)
<span class="csd-check">
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
<polyline points="20 6 9 17 4 12" />
</svg>
</span>
</div>
</div>
</div>
</div>
</div>
</div>

<div class="toggle-row">
<div>
<div class="toggle-label">Email Notifications</div>
<div class="toggle-desc">Receive email updates and alerts</div>
</div>
<label class="toggle-switch">
<input type="checkbox" name="preferences[notifications]" value="1" {{ $prefs['notifications'] ?? false ? 'checked' : '' }}>
<span class="toggle-thumb"></span>
</label>
</div>
</div>

<div class="form-footer">
<a href="{{ route('admin.profile.show') }}" class="btn-cancel">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
<line x1="18" y1="6" x2="6" y2="18" />
<line x1="6" y1="6" x2="18" y2="18" />
</svg>
Cancel
</a>
<button type="submit" class="btn-save">
<svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
<path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
<polyline points="17 21 17 13 7 13 7 21" />
<polyline points="7 3 7 8 15 8" />
</svg>
Save Preferences
</button>
</div>
</form>
</div>
</div>

@push('scripts')
<script>
function switchTab(tab, btn) {
document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
btn.classList.add('active');
document.getElementById('panel-' + tab).classList.add('active');
window.location.hash = tab === 'profile' ? '' : tab;
document.querySelectorAll('.csd-dropdown.open').forEach(d => d.classList.remove('open'));
document.querySelectorAll('.csd-trigger.open').forEach(t => t.classList.remove('open'));
}

document.addEventListener('DOMContentLoaded', () => {
const hash = window.location.hash.replace('#', '');
if (['security', 'preferences'].includes(hash)) {
const btn = document.getElementById('tab-' + hash);
if (btn) switchTab(hash, btn);
}
});

function csdToggle(wrapperId) {
const wrapper = document.getElementById(wrapperId);
const trigger = wrapper.querySelector('.csd-trigger');
const dropdown = wrapper.querySelector('.csd-dropdown');
const isOpen = dropdown.classList.contains('open');

document.querySelectorAll('.csd-dropdown.open').forEach(d => d.classList.remove('open'));
document.querySelectorAll('.csd-trigger.open').forEach(t => t.classList.remove('open'));

if (!isOpen) {
dropdown.classList.add('open');
trigger.classList.add('open');
const search = dropdown.querySelector('.csd-search');
if (search) {
search.value = '';
search.focus();
csdSearch(wrapperId, '');
}
}
}

function csdSelect(wrapperId, value, label) {
const wrapper = document.getElementById(wrapperId);
const input = wrapper.querySelector('.csd-input');
const text = wrapper.querySelector('.csd-trigger-text');
const dropdown = wrapper.querySelector('.csd-dropdown');
const trigger = wrapper.querySelector('.csd-trigger');

input.value = value;
text.textContent = label;

wrapper.querySelectorAll('.csd-opt').forEach(o => o.classList.remove('selected'));
wrapper.querySelector(`.csd-opt[data-value="${value}"]`)?.classList.add('selected');

dropdown.classList.remove('open');
trigger.classList.remove('open');
}

function csdSearch(wrapperId, query) {
const wrapper = document.getElementById(wrapperId);
const q = query.trim().toLowerCase();
wrapper.querySelectorAll('.csd-opt').forEach(opt => {
const text = opt.dataset.value ? opt.dataset.value.toLowerCase() : opt.textContent.toLowerCase();
opt.classList.toggle('hidden', q !== '' && !text.includes(q));
});
}

document.addEventListener('click', function(e) {
if (!e.target.closest('.csd-wrapper')) {
document.querySelectorAll('.csd-dropdown.open').forEach(d => d.classList.remove('open'));
document.querySelectorAll('.csd-trigger.open').forEach(t => t.classList.remove('open'));
}
});

document.querySelectorAll('.csd-dropdown').forEach(d => {
d.addEventListener('click', e => e.stopPropagation());
});
</script>
@endpush
@endsection