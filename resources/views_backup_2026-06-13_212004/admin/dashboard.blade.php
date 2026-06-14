@extends('layouts.admin.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
        :root {
            --primary: #f8773c;
            --primary-light: #fff5f0;
            --primary-dark: #e56a2e;
            --primary-hover: #f0692d;
            --bg-main: ##f8fafc;
            --bg-surface: #ffffff;
            --bg-elevated: #ffffff;
            --surface: #f3f4f6;
            --surface-hover: #e5e7eb;
            --border: #e5e7eb;
            --border-light: #f3f4f6;
            --accent: #f8773c;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --text-primary: #111827;
            --text-secondary: #6b7280;
            --text-tertiary: #9ca3af;
            --text-inverse: #ffffff;

            --font-display: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;

            --space-1: 4px;
            --space-2: 8px;
            --space-3: 12px;
            --space-4: 16px;
            --space-5: 20px;
            --space-6: 24px;
            --space-8: 32px;
            --space-10: 40px;
            --space-12: 48px;

            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
            --radius-2xl: 24px;
        }

        * {
            font-family: var(--font-body);
        }

        .dashboard-container {
            background-color: var(--bg-main);
            padding: var(--space-6);
            min-height: 100vh;
            max-width: 1600px;
            margin: 0 auto;
        }

        .dashboard-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-8);
            padding-bottom: var(--space-4);
            border-bottom: 1px solid var(--border-light);
        }

        .dashboard-title {
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            font-family: var(--font-display);
            line-height: 1.2;
        }

        .dashboard-subtitle {
            font-size: 14px;
            font-weight: 400;
            color: var(--text-secondary);
            margin: var(--space-2) 0 0 0;
            font-family: var(--font-body);
        }

        .time-filter-group {
            display: flex;
            gap: var(--space-1);
            background: var(--surface);
            padding: 7px;
            border-radius: var(--radius-xl);
        }

        .time-filter {
            padding: var(--space-2) var(--space-4);
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: var(--font-body);
        }

        .time-filter:hover {
            color: var(--text-primary);
            background: var(--bg-surface);
        }

        .time-filter.active {
            background: var(--bg-surface);
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .kpi-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-5);
            margin-bottom: var(--space-8);
        }

        .kpi-card {
            background: var(--bg-surface);
            border-radius: var(--radius-xl);
            padding: var(--space-6);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .kpi-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }

        .kpi-card.highlight {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: var(--text-inverse);
        }

        .kpi-card.highlight .kpi-label,
        .kpi-card.highlight .kpi-comparison {
            color: rgba(255, 255, 255, 0.8);
        }

        .kpi-card.highlight .kpi-value {
            color: var(--text-inverse);
        }

        .kpi-icon {
            width: 52px;
            height: 52px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: var(--space-4);
            background: var(--primary-light);
            color: var(--primary);
        }

        .kpi-icon.accent {
            background: #f3f4f6;
            color: var(--text-secondary);
        }

        .kpi-icon.solid {
            background: rgba(255, 255, 255, 0.2);
            color: var(--text-inverse);
        }

        .kpi-content {
            display: flex;
            flex-direction: column;
        }

        .kpi-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: var(--space-3);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            font-family: var(--font-body);
        }

        .kpi-value-row {
            display: flex;
            align-items: baseline;
            gap: var(--space-3);
            margin-bottom: var(--space-2);
        }

        .kpi-value {
            font-size: 36px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1.1;
            letter-spacing: -0.02em;
            font-family: var(--font-display);
        }

        .kpi-trend {
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 20px;
            font-family: var(--font-body);
        }

        .kpi-trend.up {
            color: var(--success);
            background: var(--success-light);
        }

        .kpi-trend.down {
            color: var(--danger);
            background: var(--danger-light);
        }

        .kpi-card.highlight .kpi-trend.up {
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .kpi-comparison {
            font-size: 12px;
            font-weight: 400;
            color: var(--text-tertiary);
            font-family: var(--font-body);
        }

        .analytics-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-6);
            margin-bottom: var(--space-8);
        }

        .chart-container {
            background: var(--bg-surface);
            border-radius: var(--radius-xl);
            padding: var(--space-6);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            width: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--space-6);
            flex-wrap: wrap;
            gap: var(--space-3);
        }

        .chart-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 var(--space-1) 0;
            color: var(--text-primary);
            font-family: var(--font-display);
            letter-spacing: -0.01em;
        }

        .chart-subtitle {
            font-size: 13px;
            font-weight: 400;
            color: var(--text-secondary);
            margin: 0;
            font-family: var(--font-body);
        }

        .highlight-text {
            color: var(--primary);
            font-weight: 600;
        }

        .chart-legend {
            display: flex;
            gap: var(--space-4);
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            font-size: 12px;
            font-weight: 500;
            color: var(--text-secondary);
            font-family: var(--font-body);
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .legend-dot.primary {
            background: var(--primary);
        }

        .legend-dot.secondary {
            background: var(--text-tertiary);
        }

        .chart-wrapper {
            width: 100%;
            min-height: 400px;
            position: relative;
        }

        .table-section {
            background: var(--bg-surface);
            border-radius: var(--radius-xl);
            padding: var(--space-6);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
        }

        .table-header-enhanced {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--space-6);
            gap: var(--space-4);
        }

        .table-header-left {
            flex: 1;
        }

        .table-title {
            font-size: 20px;
            font-weight: 700;
            margin: 0 0 var(--space-1) 0;
            color: var(--text-primary);
            font-family: var(--font-display);
            letter-spacing: -0.01em;
        }

        .table-subtitle {
            font-size: 13px;
            font-weight: 400;
            color: var(--text-secondary);
            margin: 0;
            font-family: var(--font-body);
        }

        .table-controls {
            display: flex;
            flex-direction: column;
            gap: var(--space-3);
            align-items: flex-end;
        }

        .filter-tabs {
            display: flex;
            gap: var(--space-1);
            background: var(--surface);
            padding: 8px;
            border-radius: var(--radius-2xl);
            border: 1px solid #eaeaea;
        }

        .filter-tab {
            padding: var(--space-2) var(--space-4);
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius-lg);
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            font-family: var(--font-body);
        }

        .filter-tab:hover {
            color: var(--text-primary);
            background: var(--bg-surface);
        }

        .filter-tab.active {
            background: var(--primary);
            color: var(--text-inverse);
        }

        .data-table-enhanced {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .data-table-enhanced thead th {
            text-align: left;
            padding: var(--space-4) var(--space-4);
            font-size: 12px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 2px solid var(--border);
            background: var(--bg-main);
            white-space: nowrap;
            font-family: var(--font-body);
        }

        .data-table-enhanced thead th:first-child {
            border-radius: var(--radius-sm) 0 0 0;
        }

        .data-table-enhanced thead th:last-child {
            border-radius: 0 var(--radius-sm) 0 0;
        }

        .data-table-enhanced thead th.sortable {
            cursor: pointer;
            user-select: none;
            transition: color 0.2s;
        }

        .data-table-enhanced thead th.sortable:hover {
            color: var(--text-primary);
        }

        .data-table-enhanced thead th.sortable i {
            font-size: 14px;
            margin-left: 6px;
            opacity: 0.5;
        }

        .data-table-enhanced tbody td {
            padding: var(--space-4);
            border-bottom: 1px solid var(--border-light);
            font-size: 14px;
            font-weight: 500;
            vertical-align: middle;
            font-family: var(--font-body);
        }

        .data-table-enhanced tbody tr:hover td {
            background: var(--bg-main);
        }

        .unit-cell {
            display: flex;
            align-items: center;
            gap: var(--space-3);
        }

        .unit-avatar {
            width: 44px;
            height: 44px;
            border-radius: var(--radius);
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .unit-info {
            min-width: 0;
        }

        .unit-name {
            font-weight: 700;
            color: var(--text-primary);
            font-family: var(--font-display);
            font-size: 14px;
            letter-spacing: -0.01em;
        }

        .unit-id {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-tertiary);
            font-family: monospace;
            margin-top: 2px;
        }

        .badge-category {
            background: var(--surface);
            color: var(--text-secondary);
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            font-family: var(--font-body);
        }

        .rating-cell {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rating-cell i {
            color: #fbbf24;
            font-size: 16px;
        }

        .rating-value {
            font-weight: 700;
            color: var(--text-primary);
            font-family: var(--font-display);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            font-family: var(--font-body);
        }

        .status-badge.active {
            background: var(--success-light);
            color: var(--success);
        }

        .status-badge.inactive {
            background: var(--danger-light);
            color: var(--danger);
        }

        .status-badge.warning {
            background: var(--warning-light);
            color: var(--warning);
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
        }

        .table-actions-cell {
            text-align: right;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            border-radius: var(--radius);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-action:hover {
            background: var(--surface);
            color: var(--text-primary);
        }

        .btn-action.primary:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: var(--space-6);
            padding-top: var(--space-4);
            border-top: 1px solid var(--border-light);
        }

        .showing-text {
            font-size: 13px;
            font-weight: 400;
            color: var(--text-secondary);
            font-family: var(--font-body);
        }

        .pagination {
            display: flex;
            gap: var(--space-1);
        }

        .btn-page {
            width: 36px;
            height: 36px;
            border: 1px solid var(--border);
            background: var(--bg-surface);
            color: var(--text-secondary);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s;
            font-family: var(--font-body);
        }

        .btn-page:hover:not(.disabled) {
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-page.active {
            background: var(--primary);
            border-color: var(--primary);
            color: var(--text-inverse);
        }

        .btn-page.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .loading-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: var(--space-4);
            color: var(--text-secondary);
            padding: var(--space-12);
        }

        .loading-state p {
            margin: 0;
            font-size: 14px;
            font-weight: 500;
            font-family: var(--font-body);
        }

        @media (max-width: 1200px) {
            .kpi-section {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--space-4);
            }

            .analytics-section {
                grid-template-columns: 1fr;
                gap: var(--space-4);
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: var(--space-4);
            }

            .kpi-section {
                grid-template-columns: 1fr;
            }

            .dashboard-header-row {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--space-4);
            }

            .dashboard-title {
                font-size: 28px;
            }

            .time-filter-group {
                width: 100%;
                overflow-x: auto;
            }

            .table-header-enhanced {
                flex-direction: column;
            }

            .table-controls {
                width: 100%;
                align-items: stretch;
            }

            .filter-tabs {
                overflow-x: auto;
                padding-bottom: var(--space-2);
            }
        }
    </style>
@endpush

@push('admin-scripts')
    <script src="{{ asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script defer src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script defer src="{{ asset('assets/admin/js/dashboard.js') }}"></script>
@endpush

@section('admin-content')
    <div class="dashboard-container" data-dashboard-overview="{{ route('admin.dashboard.overview') }}"
        data-dashboard-stats="{{ route('admin.dashboard.stats') }}"
        data-dashboard-charts="{{ route('admin.dashboard.charts') }}"
        data-audit-logs="{{ route('admin.dashboard.audit-logs') }}"
        data-recent-rated="{{ route('admin.dashboard.recent-rated') }}"
        data-top-units="{{ route('admin.dashboard.top-units', ['type' => 'all']) }}">

        <div class="dashboard-header-row">
            <div>
                <h1 class="dashboard-title">Dashboard</h1>
                <p class="dashboard-subtitle">Welcome back! Here's what's happening with your units today.</p>
            </div>
            <div class="time-filter-group">
                <button class="time-filter active" data-period="today">Today</button>
                <button class="time-filter" data-period="week">This Week</button>
                <button class="time-filter" data-period="month">This Month</button>
                <button class="time-filter" data-period="year">This Year</button>
            </div>
        </div>

        <div class="kpi-section">
            <div class="kpi-card">
                <div class="kpi-icon accent">
                    <i class="ti ti-users"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Active Students</span>
                    <div class="kpi-value-row">
                        <span class="kpi-value" id="students-today">0</span>
                        <span class="kpi-trend up">
                            <i class="ti ti-trending-up"></i> +0%
                        </span>
                    </div>
                    <span class="kpi-comparison">vs previous period</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon accent">
                    <i class="ti ti-star"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Total Ratings</span>
                    <div class="kpi-value-row">
                        <span class="kpi-value" id="ratings-today">0</span>
                        <span class="kpi-trend up">
                            <i class="ti ti-trending-up"></i> +0%
                        </span>
                    </div>
                    <span class="kpi-comparison">vs previous period</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon accent">
                    <i class="ti ti-chart-bar"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Average Rating</span>
                    <div class="kpi-value-row">
                        <span class="kpi-value" id="avg-rating">0.0</span>
                        <span class="kpi-trend up">
                            <i class="ti ti-trending-up"></i> +0%
                        </span>
                    </div>
                    <span class="kpi-comparison">across all units</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon accent">
                    <i class="ti ti-building"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Active Units</span>
                    <div class="kpi-value-row">
                        <span class="kpi-value" id="active-units">0</span>
                        <span class="kpi-trend up">
                            <i class="ti ti-trending-up"></i> +0%
                        </span>
                    </div>
                    <span class="kpi-comparison">total available units</span>
                </div>
            </div>
        </div>

        <div class="analytics-section">
            <div class="chart-container">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Student Activity Trends</h3>
                        <p class="chart-subtitle">Peak activity: <span id="peak-students" class="highlight-text">0</span>
                            students</p>
                    </div>
                    <div class="chart-legend">
                        <span class="legend-item">
                            <span class="legend-dot primary"></span> Daily Active Students
                        </span>
                    </div>
                </div>
                <div id="chart-students"></div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Unit Growth Overview</h3>
                        <p class="chart-subtitle">Total units: <span id="total-units-display"
                                class="highlight-text">0</span> units</p>
                    </div>
                    <div class="chart-legend">
                        <span class="legend-item">
                            <span class="legend-dot primary"></span> New Units
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot secondary"></span> Cumulative Total
                        </span>
                    </div>
                </div>
                <div id="chart-units-monthly"></div>
            </div>
        </div>

        <div class="table-section">
            <div class="table-header-enhanced">
                <div class="table-header-left">
                    <h3 class="table-title">Top Performing Units</h3>
                    <p class="table-subtitle">Monitor and manage unit performance metrics</p>
                </div>
                <div class="table-controls">
                    <div class="filter-tabs">
                        <button class="filter-tab active" data-filter="all">All Units</button>
                        <button class="filter-tab" data-filter="popularity">Most Popular</button>
                        <button class="filter-tab" data-filter="quality">Top Rated</button>
                        <button class="filter-tab" data-filter="attention">Needs Attention</button>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table-enhanced">
                    <thead>
                        <tr>
                            <th class="sortable" data-sort="name">
                                Unit Name <i class="ti ti-arrows-sort"></i>
                            </th>
                            <th class="sortable" data-sort="category">
                                Category <i class="ti ti-arrows-sort"></i>
                            </th>
                            <th class="sortable" data-sort="rating">
                                Rating <i class="ti ti-arrows-sort"></i>
                            </th>
                            <th class="sortable" data-sort="reviews">
                                Reviews <i class="ti ti-arrows-sort"></i>
                            </th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="units-table-body">
                        <tr>
                            <td colspan="6">
                                <div class="loading-state">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p>Loading units data...</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-footer">
                <span class="showing-text">Showing <span id="showing-count">0</span> of <span id="total-count">0</span>
                    units</span>
                <div class="pagination" id="pagination-container"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.querySelector('.dashboard-container');
                if (!container) {
                    console.error('Dashboard container not found');
                    return;
                }

                const routes = {
                    overview: container.dataset.dashboardOverview,
                    stats: container.dataset.dashboardStats,
                    charts: container.dataset.dashboardCharts,
                    auditLogs: container.dataset.auditLogs,
                    recentRated: container.dataset.recentRated,
                    topUnits: container.dataset.topUnits
                };

                document.querySelectorAll('.time-filter').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.querySelectorAll('.time-filter').forEach(b => b.classList.remove(
                            'active'));
                        this.classList.add('active');
                        if (typeof window.loadDashboardData === 'function') {
                            window.loadDashboardData(routes);
                        }
                    });
                });

                if (typeof window.loadDashboardData === 'function') {
                    window.loadDashboardData(routes);
                } else {
                    console.warn('loadDashboardData function not found - waiting for dashboard.js');
                }
            });
        </script>
    @endpush
@endsection
