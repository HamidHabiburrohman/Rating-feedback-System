@extends('layouts.admin.app')

@section('title', $unit->name . ' — Detail Unit')

@section('admin-content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --primary: #f8773c;
        --primary-light: rgba(248, 119, 60, 0.08);
        --primary-medium: rgba(248, 119, 60, 0.15);
        --primary-hover: #e5652a;
        
        --secondary: #2d3748;
        --secondary-light: #4a5568;
        --secondary-medium: #718096;
        
        --surface: #f8fafc;
        --surface-secondary: #ffffff;
        --surface-elevated: #ffffff;
        
        --text-primary: #1e293b;
        --text-secondary: #475569;
        --text-tertiary: #94a3b8;
        --text-inverse: #ffffff;
        
        --border-light: #e2e8f0;
        --border-medium: #cbd5e1;
        
        --success: #10b981;
        --success-light: rgba(16, 185, 129, 0.08);
        --danger: #ef4444;
        --danger-light: rgba(239, 68, 68, 0.08);
        
        --shadow-xs: 0 1px 2px rgba(0, 0, 0, 0.02);
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.03);
        --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.04);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.05);
        --shadow-inner-top: inset 0 1px 0 rgba(255, 255, 255, 0.2);
        
        --radius-sm: 10px;
        --radius-md: 16px;
        --radius-lg: 24px;
        --radius-xl: 32px;
        --radius-full: 9999px;
    }

    * {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        background: var(--surface);
    }

    .unit-show-container {
        max-width: 1280px;
        margin: 0 auto;
    }

    .bento-card {
        background: var(--surface-elevated);
        border: 1px solid var(--border-light);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-xs);
    }

    .bento-card:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--border-medium);
    }

    .hero-section {
        position: relative;
        border-radius: var(--radius-xl);
        overflow: hidden;
        height: 580px;
        margin-bottom: 1.75rem;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, 
            rgba(0, 0, 0, 0) 0%, 
            rgba(0, 0, 0, 0.1) 45%,
            rgba(0, 0, 0, 0.5) 100%);
    }

    .hero-back-btn {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: var(--radius-full);
        color: var(--text-inverse);
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 10;
    }

    .hero-back-btn:hover {
        background: rgba(255, 255, 255, 0.25);
        color: var(--text-inverse);
        transform: scale(1.05);
    }

    .hero-back-btn i {
        font-size: 1.25rem;
    }

    .hero-content {
        position: absolute;
        bottom: 1.75rem;
        left: 1.75rem;
        right: 1.75rem;
        color: var(--text-inverse);
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.875rem;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        color: var(--text-inverse);
        border-radius: var(--radius-full);
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: var(--surface-secondary);
        border: 1px solid var(--border-light);
    }

    .stat-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: var(--text-tertiary);
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1.2;
        color: var(--text-primary);
        letter-spacing: -0.02em;
    }

    .stat-unit {
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-tertiary);
        margin-left: 0.125rem;
    }

    .facility-item {
        transition: all 0.2s ease;
    }

    .facility-item:hover {
        background: var(--primary-light) !important;
        border-color: var(--primary) !important;
    }

    .facility-item:hover .facility-icon-wrapper {
        transform: scale(1.05);
    }

    .facility-icon-wrapper {
        transition: transform 0.2s ease;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-dot-pulse {
        position: relative;
    }

    .status-dot-pulse::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: inherit;
        animation: pulse 1.8s ease-out infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.5; }
        100% { transform: scale(2.8); opacity: 0; }
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        letter-spacing: -0.01em;
    }

    .section-title i {
        color: var(--primary);
        font-size: 1.25rem;
    }

    .schedule-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.625rem 0;
        border-bottom: 1px solid var(--border-light);
    }

    .schedule-row:last-child {
        border-bottom: none;
    }

    .schedule-day {
        font-weight: 500;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .schedule-time {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.875rem;
    }

    .schedule-closed {
        color: var(--danger);
        font-weight: 600;
        font-size: 0.875rem;
    }

    .map-placeholder {
        background: var(--surface-secondary);
        border-radius: var(--radius-md);
        padding: 1.25rem;
        text-align: center;
        border: 1px solid var(--border-light);
    }

    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius-full);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        letter-spacing: -0.01em;
    }

    .action-btn-primary {
        background: var(--primary);
        color: var(--text-inverse);
        border: none;
        box-shadow: var(--shadow-inner-top), 0 4px 12px rgba(248, 119, 60, 0.25);
    }

    .action-btn-primary:hover {
        background: var(--primary-hover);
        color: var(--text-inverse);
        box-shadow: var(--shadow-inner-top), 0 6px 16px rgba(248, 119, 60, 0.35);
        transform: translateY(-1px);
    }

    .action-btn-secondary {
        background: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-medium);
    }

    .action-btn-secondary:hover {
        background: var(--surface-secondary);
        border-color: var(--border-medium);
        color: var(--text-primary);
    }

    .action-btn i {
        font-size: 1.125rem;
    }

    .grid {
        display: grid;
    }

    .grid-cols-1 {
        grid-template-columns: repeat(1, 1fr);
    }

    .gap-4 {
        gap: 1rem;
    }

    .rounded-4 {
        border-radius: 1rem;
    }

    .tracking-tight { 
        letter-spacing: -0.02em; 
    }

    @media (min-width: 768px) {
        .md\:grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .hero-section {
            height: 300px;
            border-radius: var(--radius-lg);
        }
        
        .hero-content {
            bottom: 1.25rem;
            left: 1.25rem;
            right: 1.25rem;
        }
        
        .bento-card {
            padding: 1.25rem;
        }

        .hero-back-btn {
            top: 1rem;
            left: 1rem;
            width: 36px;
            height: 36px;
        }
    }
</style>

@php
    $imageUrl = asset('assets/images/UnitPlaceholder.png');

    if ($unit->primaryPhoto) {
        $photo = $unit->primaryPhoto;
        $url = $photo->thumbnail_url ?? $photo->original_url ?? null;
        if ($url) {
            $imageUrl = $url;
        }
    } elseif ($unit->photos->isNotEmpty()) {
        $photo = $unit->photos->first();
        $url = $photo->thumbnail_url ?? $photo->original_url ?? null;
        if ($url) {
            $imageUrl = $url;
        }
    }

    $schedule = $unit->operational_schedule ?? [];
    $isOpen = $unit->is_open ?? null;
@endphp

<div class="unit-show-container">
    
    <div class="hero-section">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="{{ $unit->name }}" style="width: 100%; height: 100%; object-fit: cover;">
        @else
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--surface), var(--surface-secondary)); display: flex; align-items: center; justify-content: center;">
                <i class="ti ti-building" style="font-size: 64px; color: var(--text-tertiary); opacity: 0.2;"></i>
            </div>
        @endif
        <div class="hero-overlay"></div>
        
        <a href="{{ route('admin.units.index') }}" class="hero-back-btn">
            <i class="ti ti-arrow-left"></i>
        </a>
        
        <div style="position: absolute; top: 1.25rem; right: 1.25rem; display: flex; gap: 0.5rem;">
            <span class="hero-badge" style="{{ !$unit->is_active ? 'background: rgba(100, 116, 139, 0.3);' : '' }}">
                {{ $unit->is_active ? 'Active' : 'Inactive' }}
            </span>
            @if($unit->operational_status)
                <span class="hero-badge">
                    {{ ucfirst($unit->operational_status) }}
                </span>
            @endif
        </div>
        
        <div class="hero-content">
            <div style="display: flex; align-items: center; gap: 0.625rem; margin-bottom: 0.75rem; flex-wrap: wrap;">
                <span class="hero-badge" style="background: var(--primary); border: none;">
                    {{ $unit->type->name ?? 'Unit' }}
                </span>
                @if($unit->department)
                    <span style="color: rgba(255,255,255,0.75); font-size: 0.8125rem; font-weight: 500;">
                        {{ $unit->department->name }}
                    </span>
                @endif
            </div>
            <h1 style="font-size: clamp(1.75rem, 4vw, 2.5rem); font-weight: 700; line-height: 1.2; margin-bottom: 0.5rem; color: var(--text-inverse); letter-spacing: -0.02em;">
                {{ $unit->name }}
            </h1>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <span style="font-family: 'SF Mono', 'Menlo', monospace; background: rgba(255,255,255,0.1); padding: 0.2rem 0.75rem; border-radius: var(--radius-full); font-size: 0.75rem; color: rgba(255,255,255,0.85); font-weight: 500; backdrop-filter: blur(4px);">
                    {{ $unit->code }}
                </span>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.5rem;">
        <div class="bento-card" style="padding: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon">
                    <i class="ti ti-users" style="font-size: 1.5rem; color: var(--primary);"></i>
                </div>
                <div>
                    <div class="stat-label">Capacity</div>
                    <div class="stat-value">{{ number_format($unit->capacity) }}<span class="stat-unit">pax</span></div>
                </div>
            </div>
        </div>
        
        <div class="bento-card" style="padding: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon">
                    <i class="ti ti-star" style="font-size: 1.5rem; color: var(--primary);"></i>
                </div>
                <div>
                    <div class="stat-label">Rating</div>
                    <div class="stat-value">
                        {{ $unit->avg_rating > 0 ? number_format($unit->avg_rating, 1) : '—' }}
                        <span class="stat-unit">{{ $unit->avg_rating > 0 ? '/5.0' : '' }}</span>
                    </div>
                    @if($unit->total_ratings > 0)
                        <div style="font-size: 0.7rem; color: var(--text-tertiary); margin-top: 0.125rem;">{{ number_format($unit->total_ratings) }} reviews</div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="bento-card" style="padding: 1.25rem;">
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div class="stat-icon">
                    <i class="ti ti-building" style="font-size: 1.5rem; color: var(--primary);"></i>
                </div>
                <div>
                    <div class="stat-label">Department</div>
                    <div class="stat-value" style="font-size: 1.1rem;">
                        {{ $unit->department->name ?? '—' }}
                    </div>
                    @if($unit->type)
                        <div style="font-size: 0.7rem; color: var(--text-tertiary); margin-top: 0.125rem;">{{ $unit->type->name }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem; align-items: start;">
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <div class="bento-card">
                <div class="section-title">
                    <i class="ti ti-align-left"></i>
                    <span>Description</span>
                </div>
                <p style="color: var(--text-secondary); line-height: 1.7; font-size: 0.9375rem; margin-bottom: 0;">
                    {{ $unit->description ?: 'No description provided for this unit.' }}
                </p>
            </div>

            @if($unit->facilities->isNotEmpty())
                <div class="bento-card">
                    <div class="section-title">
                        <i class="ti ti-apps"></i>
                        <span>Facilities</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($unit->facilities as $facility)
                            <div class="facility-item d-flex align-items-start gap-3 p-4 rounded-4 border">
                                <div class="facility-icon-wrapper d-flex align-items-center justify-content-center rounded-circle shrink-0" style="width: 48px; height: 48px; background: var(--surface-elevated); border: 1px solid var(--border-medium);">
                                    @if(method_exists($facility, 'getIconSvg'))
                                        <div style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                            {!! $facility->getIconSvg() !!}
                                        </div>
                                    @else
                                        <i class="ti ti-check" style="font-size: 1.5rem; color: var(--primary);"></i>
                                    @endif
                                </div>
                                <div class="d-flex align-items-center" style="min-height: 48px;">
                                    <h4 class="fw-bold mb-0" style="font-size: 1rem; color: var(--text-primary);">{{ $facility->name }}</h4>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            @if($unit->email || $unit->phone)
                <div class="bento-card">
                    <div class="section-title">
                        <i class="ti ti-mail"></i>
                        <span>Contact</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        @if($unit->email)
                            <div style="display: flex; align-items: center; gap: 0.875rem;">
                                <i class="ti ti-mail" style="color: var(--primary); font-size: 1.125rem;"></i>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--text-tertiary); letter-spacing: 0.3px;">Email</div>
                                    <a href="mailto:{{ $unit->email }}" style="color: var(--text-primary); text-decoration: none; font-weight: 500; font-size: 0.875rem; word-break: break-all;">{{ $unit->email }}</a>
                                </div>
                            </div>
                        @endif
                        @if($unit->phone)
                            <div style="display: flex; align-items: center; gap: 0.875rem;">
                                <i class="ti ti-phone" style="color: var(--primary); font-size: 1.125rem;"></i>
                                <div style="flex: 1; min-width: 0;">
                                    <div style="font-size: 0.65rem; font-weight: 600; text-transform: uppercase; color: var(--text-tertiary); letter-spacing: 0.3px;">Phone</div>
                                    <a href="tel:{{ $unit->phone }}" style="color: var(--text-primary); text-decoration: none; font-weight: 500; font-size: 0.875rem;">{{ $unit->phone }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bento-card">
                <div class="section-title">
                    <i class="ti ti-clock"></i>
                    <span>Hours</span>
                </div>
                
                @if(!empty($schedule))
                    <div style="margin-bottom: 0.75rem;">
                        @foreach($schedule as $item)
                            <div class="schedule-row">
                                <span class="schedule-day">{{ $item['days'] ?? '—' }}</span>
                                @if(isset($item['is_closed']) && $item['is_closed'])
                                    <span class="schedule-closed">Closed</span>
                                @else
                                    <span class="schedule-time">{{ $item['open'] ?? '—' }} — {{ $item['close'] ?? '—' }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @elseif($unit->open_time || $unit->close_time)
                    <div style="margin-bottom: 0.75rem;">
                        @if($unit->open_time)
                            <div class="schedule-row">
                                <span class="schedule-day">Open</span>
                                <span class="schedule-time">{{ \Carbon\Carbon::parse($unit->open_time)->format('H:i') }}</span>
                            </div>
                        @endif
                        @if($unit->close_time)
                            <div class="schedule-row">
                                <span class="schedule-day">Close</span>
                                <span class="schedule-time">{{ \Carbon\Carbon::parse($unit->close_time)->format('H:i') }}</span>
                            </div>
                        @endif
                    </div>
                @else
                    <p style="color: var(--text-tertiary); font-size: 0.875rem; margin-bottom: 0;">Hours not set.</p>
                @endif
                
                @if($isOpen !== null)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--border-light);">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span class="status-dot status-dot-pulse" style="background: {{ $isOpen ? 'var(--success)' : 'var(--danger)' }};"></span>
                            <span style="font-weight: 600; font-size: 0.8125rem; color: {{ $isOpen ? 'var(--success)' : 'var(--danger)' }};">
                                {{ $isOpen ? 'Currently open' : 'Currently closed' }}
                            </span>
                        </div>
                    </div>
                @endif
            </div>

            @if($unit->location || $unit->building || $unit->floor)
                <div class="bento-card">
                    <div class="section-title">
                        <i class="ti ti-map-pin"></i>
                        <span>Location</span>
                    </div>
                    
                    <div class="map-placeholder" style="margin-bottom: 1rem;">
                        <i class="ti ti-map-2" style="font-size: 2rem; color: var(--primary); opacity: 0.3;"></i>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.375rem;">
                        @if($unit->location)
                            <p style="font-weight: 600; color: var(--text-primary); font-size: 0.9375rem; margin-bottom: 0.125rem;">{{ $unit->location }}</p>
                        @endif
                        <p style="color: var(--text-secondary); display: flex; align-items: flex-start; gap: 0.5rem; font-size: 0.875rem;">
                            <i class="ti ti-building" style="color: var(--primary); margin-top: 0.125rem;"></i>
                            <span>
                                @if($unit->building)Building {{ $unit->building }}@endif
                                @if($unit->floor), Floor {{ $unit->floor }}@endif
                                @if(!$unit->building && !$unit->floor)Location details not specified
                                @endif
                            </span>
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            document.querySelectorAll('.toast').forEach(function(toast) {
                var bsToast = bootstrap.Toast.getInstance(toast);
                if (bsToast) bsToast.hide();
                toast.remove();
            });
        }, 5000);
    });
</script>
@endsection