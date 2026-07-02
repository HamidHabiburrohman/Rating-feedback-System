@extends('layouts.admin.app')

@section('title', $unit->name . ' — Detail Unit')

@push('styles')
    <style>
        :root {
            --primary: #f8773c;
            --primary-light: rgba(248, 119, 60, 0.08);
            --primary-dark: #e56a2e;
            --bg-main: #f8fafc;
            --bg-surface: #ffffff;
            --surface: #f3f4f6;
            --border: rgba(15, 23, 42, 0.06);
            --border-medium: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-tertiary: #94a3b8;
            --text-inverse: #ffffff;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-2xl: 32px;
            --radius-full: 9999px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.04), 0 1px 2px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
            --font-display: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .show-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 32px 24px;
            font-family: var(--font-body);
            color: var(--text-primary);
        }

        .hero-section {
            position: relative;
            border-radius: var(--radius-2xl);
            overflow: hidden;
            height: 480px;
            margin-bottom: 32px;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            background: var(--surface);
        }

        .hero-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .hero-placeholder i {
            font-size: 80px;
            color: var(--text-tertiary);
            opacity: 0.3;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0) 40%, rgba(0, 0, 0, 0.6) 100%);
            pointer-events: none;
        }

        .hero-top-bar {
            position: absolute;
            top: 24px;
            left: 24px;
            right: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .glass-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 40px;
            padding: 0 16px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-full);
            color: var(--text-inverse);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }

        .glass-btn-icon {
            width: 40px;
            padding: 0;
        }

        .hero-actions {
            display: flex;
            gap: 8px;
        }

        .hero-content {
            position: absolute;
            bottom: 32px;
            left: 32px;
            right: 32px;
            z-index: 10;
            color: var(--text-inverse);
        }

        .hero-badges {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-full);
            font-size: 12px;
            font-weight: 600;
            color: var(--text-inverse);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .hero-badge.primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .hero-title {
            font-size: 40px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin: 0 0 12px 0;
            font-family: var(--font-display);
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .hero-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
        }

        .hero-code {
            font-family: 'SF Mono', 'Menlo', monospace;
            background: rgba(255, 255, 255, 0.1);
            padding: 4px 10px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-lg);
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            font-family: var(--font-display);
            line-height: 1.2;
        }

        .stat-sub {
            font-size: 12px;
            color: var(--text-tertiary);
            font-weight: 500;
        }

        .bento-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
            align-items: start;
        }

        .bento-col {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .bento-card {
            background: var(--bg-surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: 28px;
            box-shadow: var(--shadow-sm);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .card-header-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--radius);
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            letter-spacing: -0.01em;
            font-family: var(--font-display);
        }

        .card-text {
            font-size: 14px;
            line-height: 1.7;
            color: var(--text-secondary);
            margin: 0;
        }

        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .facility-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            background: var(--bg-main);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            transition: all 0.2s ease;
        }

        .facility-item:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .facility-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius);
            background: var(--bg-surface);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 18px;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .facility-item:hover .facility-icon {
            background: var(--primary);
            color: var(--text-inverse);
            border-color: var(--primary);
            transform: scale(1.05);
        }

        .facility-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.3;
        }

        .info-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .info-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius);
            background: var(--bg-main);
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
            min-width: 0;
        }

        .info-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-tertiary);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            word-break: break-word;
            text-decoration: none;
            transition: color 0.2s;
        }

        a.info-value:hover {
            color: var(--primary);
        }

        .schedule-list {
            display: flex;
            flex-direction: column;
        }

        .schedule-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
        }

        .schedule-row:last-child {
            border-bottom: none;
        }

        .schedule-day {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .schedule-time {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-primary);
            font-family: 'SF Mono', 'Menlo', monospace;
        }

        .schedule-closed {
            font-size: 12px;
            font-weight: 700;
            color: var(--danger);
            background: var(--danger-light);
            padding: 4px 10px;
            border-radius: var(--radius-full);
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 20px;
            padding: 14px 16px;
            background: var(--bg-main);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            position: relative;
        }

        .status-dot.open {
            background: var(--success);
        }

        .status-dot.open::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--success);
            opacity: 0.3;
            animation: pulse-ring 2s infinite;
        }

        .status-dot.closed {
            background: var(--danger);
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.8);
                opacity: 0.5;
            }

            100% {
                transform: scale(2);
                opacity: 0;
            }
        }

        .status-text {
            font-size: 13px;
            font-weight: 700;
        }

        .status-text.open {
            color: var(--success);
        }

        .status-text.closed {
            color: var(--danger);
        }

        .map-placeholder {
            width: 100%;
            height: 140px;
            background: linear-gradient(135deg, var(--bg-main) 0%, #e2e8f0 100%);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            border: 1px solid var(--border);
        }

        .map-placeholder i {
            font-size: 40px;
            color: var(--primary);
            opacity: 0.4;
        }

        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .bento-grid {
                grid-template-columns: 1fr;
            }

            .hero-section {
                height: 360px;
            }

            .hero-title {
                font-size: 32px;
            }
        }

        @media (max-width: 640px) {
            .show-container {
                padding: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .facilities-grid {
                grid-template-columns: 1fr;
            }

            .hero-section {
                height: 280px;
                border-radius: var(--radius-xl);
            }

            .hero-content {
                bottom: 20px;
                left: 20px;
                right: 20px;
            }

            .hero-title {
                font-size: 24px;
            }

            .hero-top-bar {
                top: 16px;
                left: 16px;
                right: 16px;
            }

            .bento-card {
                padding: 20px;
            }
        }
    </style>
@endpush

@section('admin-content')
    @php
        $imageUrl = asset('assets/images/UnitPlaceholder.png');
        $imageAlt = $unit->name;
        $hasPhoto = false;

        if ($unit->primaryPhoto && $unit->primaryPhoto->thumbnail_path) {
            $imageUrl = asset('storage/' . $unit->primaryPhoto->thumbnail_path);
            $imageAlt = $unit->primaryPhoto->alt_text ?? $unit->name;
            $hasPhoto = true;
        } elseif ($unit->photos->isNotEmpty() && $unit->photos->first()->thumbnail_path) {
            $imageUrl = asset('storage/' . $unit->photos->first()->thumbnail_path);
            $imageAlt = $unit->photos->first()->alt_text ?? $unit->name;
            $hasPhoto = true;
        }

        $schedule = $unit->operational_schedule ?? [];
        $isOpen = $unit->is_open ?? null;
    @endphp

    <div class="show-container">
        <div class="hero-section">
            @if($hasPhoto)
                <img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" class="hero-image">
            @else
                <div class="hero-placeholder">
                    <i class="ti ti-building"></i>
                </div>
            @endif
            <div class="hero-overlay"></div>

            <div class="hero-top-bar">
                <a href="{{ route('admin.units.index') }}" class="glass-btn glass-btn-icon">
                    <i class="ti ti-arrow-left"></i>
                </a>
                <div class="hero-actions">
                    <a href="{{ route('admin.units.edit', $unit->id) }}" class="glass-btn">
                        <i class="ti ti-edit"></i> Edit Unit
                    </a>
                </div>
            </div>

            <div class="hero-content">
                <div class="hero-badges">
                    <span class="hero-badge primary">
                        <i class="ti ti-tag"></i> {{ $unit->unitType->name ?? 'Unit' }}
                    </span>
                    @if($unit->unitDepartment)
                        <span class="hero-badge">
                            <i class="ti ti-sitemap"></i> {{ $unit->unitDepartment->name }}
                        </span>
                    @endif
                    <span class="hero-badge"
                        style="{{ !$unit->is_active ? 'background: rgba(239, 68, 68, 0.8); border-color: rgba(239, 68, 68, 0.8);' : '' }}">
                        {{ $unit->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <h1 class="hero-title">{{ $unit->name }}</h1>
                <div class="hero-meta">
                    @if($unit->code)
                        <span class="hero-code">{{ $unit->code }}</span>
                    @endif
                    @if($unit->operational_status)
                        <span style="display: flex; align-items: center; gap: 6px;">
                            <i class="ti ti-circle-dot"></i> {{ ucfirst($unit->operational_status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="ti ti-star"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Rating</span>
                    <span class="stat-value">{{ $unit->avg_rating > 0 ? number_format($unit->avg_rating, 1) : '—' }}</span>
                    <span
                        class="stat-sub">{{ $unit->total_ratings > 0 ? number_format($unit->total_ratings) . ' reviews' : 'No reviews yet' }}</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ti ti-users"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Capacity</span>
                    <span class="stat-value">{{ $unit->capacity ? number_format($unit->capacity) : '—' }}</span>
                    <span class="stat-sub">Max pax</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ti ti-alert-triangle"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Reports</span>
                    <span class="stat-value">{{ $unit->reports_count ?? $unit->reports()->count() }}</span>
                    <span class="stat-sub">Total submitted</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="ti ti-user-check"></i></div>
                <div class="stat-info">
                    <span class="stat-label">Employees</span>
                    <span class="stat-value">{{ $unit->employeeAssignments()->where('is_active', true)->count() }}</span>
                    <span class="stat-sub">Assigned staff</span>
                </div>
            </div>
        </div>

        <div class="bento-grid">
            <div class="bento-col">
                <div class="bento-card">
                    <div class="card-header">
                        <div class="card-header-icon"><i class="ti ti-align-left"></i></div>
                        <h3 class="card-title">Description</h3>
                    </div>
                    <p class="card-text">
                        {{ $unit->description ?: 'No description provided for this unit.' }}
                    </p>
                </div>

                @if($unit->facilities && $unit->facilities->isNotEmpty())
                    <div class="bento-card">
                        <div class="card-header">
                            <div class="card-header-icon"><i class="ti ti-apps"></i></div>
                            <h3 class="card-title">Facilities</h3>
                        </div>
                        <div class="facilities-grid">
                            @foreach($unit->facilities as $facility)
                                <div class="facility-item">
                                    <div class="facility-icon">
                                        @if(method_exists($facility, 'getIconSvg'))
                                            {!! $facility->getIconSvg() !!}
                                        @else
                                            <i class="ti ti-check"></i>
                                        @endif
                                    </div>
                                    <h4 class="facility-name">{{ $facility->name }}</h4>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="bento-col">
                @if($unit->email || $unit->phone)
                    <div class="bento-card">
                        <div class="card-header">
                            <div class="card-header-icon"><i class="ti ti-mail"></i></div>
                            <h3 class="card-title">Contact Info</h3>
                        </div>
                        <div class="info-list">
                            @if($unit->email)
                                <div class="info-item">
                                    <div class="info-icon"><i class="ti ti-mail"></i></div>
                                    <div class="info-content">
                                        <div class="info-label">Email Address</div>
                                        <a href="mailto:{{ $unit->email }}" class="info-value">{{ $unit->email }}</a>
                                    </div>
                                </div>
                            @endif
                            @if($unit->phone)
                                <div class="info-item">
                                    <div class="info-icon"><i class="ti ti-phone"></i></div>
                                    <div class="info-content">
                                        <div class="info-label">Phone Number</div>
                                        <a href="tel:{{ $unit->phone }}" class="info-value">{{ $unit->phone }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="bento-card">
                    <div class="card-header">
                        <div class="card-header-icon"><i class="ti ti-clock"></i></div>
                        <h3 class="card-title">Operating Hours</h3>
                    </div>

                    @if(!empty($schedule))
                        <div class="schedule-list">
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
                        <div class="schedule-list">
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
                        <p class="card-text" style="color: var(--text-tertiary);">Operating hours not configured.</p>
                    @endif

                    @if($isOpen !== null)
                        <div class="status-indicator">
                            <span class="status-dot {{ $isOpen ? 'open' : 'closed' }}"></span>
                            <span class="status-text {{ $isOpen ? 'open' : 'closed' }}">
                                {{ $isOpen ? 'Currently Open' : 'Currently Closed' }}
                            </span>
                        </div>
                    @endif
                </div>

                @if($unit->location || $unit->building || $unit->floor)
                    <div class="bento-card">
                        <div class="card-header">
                            <div class="card-header-icon"><i class="ti ti-map-pin"></i></div>
                            <h3 class="card-title">Location Details</h3>
                        </div>
                        <div class="map-placeholder">
                            <i class="ti ti-map-2"></i>
                        </div>
                        <div class="info-list">
                            @if($unit->location)
                                <div class="info-item">
                                    <div class="info-icon"><i class="ti ti-map-pin"></i></div>
                                    <div class="info-content">
                                        <div class="info-label">Address / Area</div>
                                        <div class="info-value">{{ $unit->location }}</div>
                                    </div>
                                </div>
                            @endif
                            @if($unit->building || $unit->floor)
                                <div class="info-item">
                                    <div class="info-icon"><i class="ti ti-building"></i></div>
                                    <div class="info-content">
                                        <div class="info-label">Building Details</div>
                                        <div class="info-value">
                                            @if($unit->building) Building {{ $unit->building }} @endif
                                            @if($unit->building && $unit->floor) • @endif
                                            @if($unit->floor) Floor {{ $unit->floor }} @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection