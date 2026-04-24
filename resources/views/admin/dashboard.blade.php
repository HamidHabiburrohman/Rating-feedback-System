@extends('layouts.admin.app')

@section('title', 'Dashboard')

@push('styles')
    <style>
        :root {
            --primary: #f8773c;
            --primary-light: #fff5f0;
            --primary-dark: #e56a2e;
            --primary-hover: #f0692d;
            --bg-main: #ffffff;
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
            --text-muted: #9ca3af;
            --text-inverse: #ffffff;
            --font-family: 'Open Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --space-1: 4px;
            --space-2: 8px;
            --space-3: 12px;
            --space-4: 16px;
            --space-5: 20px;
            --space-6: 24px;
            --space-8: 32px;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --radius-xl: 20px;
        }

        .dashboard-container {
            background-color: var(--bg-main);
            font-family: var(--font-family);
            color: var(--text-primary);
            padding: var(--space-6);
            min-height: 100vh;
            max-width: 1600px;
            margin: 0 auto;
        }

        .dashboard-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-6);
            padding-bottom: var(--space-4);
            border-bottom: 1px solid var(--border-light);
        }

        .dashboard-title {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        .dashboard-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            margin: var(--space-1) 0 0 0;
        }

        .time-filter-group {
            display: flex;
            gap: var(--space-1);
            background: var(--surface);
            padding: 4px;
            border-radius: var(--radius);
        }

        .time-filter {
            padding: var(--space-2) var(--space-4);
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius-sm);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .time-filter:hover {
            color: var(--text-primary);
        }

        .time-filter.active {
            background: var(--bg-surface);
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .kpi-section {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--space-4);
            margin-bottom: var(--space-6);
        }

        .kpi-card {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            padding: var(--space-5);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .kpi-card:hover {
            box-shadow: var(--shadow-md);
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
            width: 48px;
            height: 48px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: var(--space-3);
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
            color: var(--text-secondary);
            font-weight: 500;
            margin-bottom: var(--space-2);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-value-row {
            display: flex;
            align-items: baseline;
            gap: var(--space-3);
            margin-bottom: var(--space-1);
        }

        .kpi-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.02em;
        }

        .kpi-trend {
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 12px;
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
            color: var(--text-muted);
        }

        .alert-banner {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-4);
            border-radius: var(--radius);
            margin-bottom: var(--space-6);
            animation: slideIn 0.3s ease;
        }

        .alert-banner.warning {
            background: var(--warning-light);
            border: 1px solid #fcd34d;
            color: #92400e;
        }

        .alert-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .alert-content {
            flex: 1;
            font-size: 14px;
        }

        .alert-link {
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            margin-left: var(--space-2);
        }

        .alert-close {
            background: none;
            border: none;
            cursor: pointer;
            padding: var(--space-1);
            color: currentColor;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .alert-close:hover {
            opacity: 1;
        }

        .analytics-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-5);
            margin-bottom: var(--space-6);
        }

        .chart-container {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            padding: var(--space-5);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            width: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--space-5);
            flex-wrap: wrap;
            gap: var(--space-3);
        }

        .chart-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 var(--space-1) 0;
            color: var(--text-primary);
        }

        .chart-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 0;
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
            color: var(--text-secondary);
        }

        .legend-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .legend-dot.primary {
            background: var(--primary);
        }

        .legend-dot.secondary {
            background: var(--text-muted);
        }

        .chart-wrapper {
            width: 100%;
            min-height: 400px;
            position: relative;
        }

        .chart-wrapper canvas,
        .chart-wrapper svg {
            width: 100% !important;
            height: auto !important;
            min-height: 350px;
        }

        .monitoring-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-5);
            margin-bottom: var(--space-6);
        }

        .activity-panel,
        .insight-panel {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            padding: var(--space-5);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: var(--space-4);
            padding-bottom: var(--space-3);
            border-bottom: 1px solid var(--border-light);
        }

        .panel-title {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
        }

        .badge-live {
            background: var(--danger-light);
            color: var(--danger);
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .badge-live::before {
            content: '';
            width: 6px;
            height: 6px;
            background: var(--danger);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .badge-count {
            background: var(--primary-light);
            color: var(--primary);
            font-size: 12px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 12px;
        }

        .activity-list,
        .rated-list {
            display: flex;
            flex-direction: column;
            gap: var(--space-3);
            flex: 1;
            max-height: none;
            overflow-y: visible;
        }

        .activity-item,
        .rated-item {
            display: flex;
            align-items: center;
            gap: var(--space-3);
            padding: var(--space-3);
            border-radius: var(--radius);
            transition: background 0.2s;
        }

        .activity-item:hover,
        .rated-item:hover {
            background: var(--bg-main);
        }

        .activity-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
            flex-shrink: 0;
        }

        .activity-details,
        .rated-details {
            flex: 1;
            min-width: 0;
        }

        .activity-text,
        .rated-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }

        .activity-meta,
        .rated-date {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .activity-action {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 6px;
        }

        .activity-action.create {
            background: var(--success-light);
            color: var(--success);
        }

        .activity-action.update {
            background: var(--info-light);
            color: var(--info);
        }

        .activity-action.delete {
            background: var(--danger-light);
            color: var(--danger);
        }

        .activity-action.login {
            background: #f3e8ff;
            color: #9333ea;
        }

        .rated-icon {
            width: 40px;
            height: 40px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .rated-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 14px;
            font-weight: 700;
            color: var(--primary);
            background: var(--primary-light);
            padding: 4px 10px;
            border-radius: 20px;
        }

        .view-all-link {
            margin-top: var(--space-4);
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            padding: var(--space-3);
            border-radius: var(--radius);
            transition: all 0.2s;
        }

        .view-all-link:hover {
            background: var(--primary-light);
        }

        .insight-panel {
            background: linear-gradient(135deg, #fff 0%, var(--primary-light) 100%);
        }

        .insight-content {
            display: flex;
            flex-direction: column;
            gap: var(--space-4);
        }

        .insight-item {
            display: flex;
            gap: var(--space-3);
            align-items: flex-start;
        }

        .insight-icon {
            width: 32px;
            height: 32px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .insight-icon.positive {
            background: var(--success-light);
            color: var(--success);
        }

        .insight-icon.warning {
            background: var(--warning-light);
            color: var(--warning);
        }

        .insight-icon.info {
            background: var(--info-light);
            color: var(--info);
        }

        .insight-text {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.5;
        }

        .insight-text strong {
            color: var(--text-primary);
            display: block;
            margin-bottom: 2px;
        }

        .table-section {
            background: var(--bg-surface);
            border-radius: var(--radius-lg);
            padding: var(--space-5);
            box-shadow: var(--shadow);
            border: 1px solid var(--border-light);
        }

        .table-header-enhanced {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--space-5);
            gap: var(--space-4);
        }

        .table-header-left {
            flex: 1;
        }

        .table-title {
            font-size: 18px;
            font-weight: 700;
            margin: 0 0 var(--space-1) 0;
            color: var(--text-primary);
        }

        .table-subtitle {
            font-size: 13px;
            color: var(--text-secondary);
            margin: 0;
        }

        .table-controls {
            display: flex;
            flex-direction: column;
            gap: var(--space-3);
            align-items: flex-end;
        }

        .search-box {
            position: relative;
            width: 280px;
        }

        .search-box i {
            position: absolute;
            left: var(--space-3);
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 16px;
        }

        .search-box input {
            width: 100%;
            padding: var(--space-2) var(--space-3) var(--space-2) 40px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.1);
        }

        .filter-tabs {
            display: flex;
            gap: var(--space-1);
        }

        .filter-tab {
            padding: var(--space-2) var(--space-4);
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            border-radius: var(--radius);
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
        }

        .filter-tab:hover {
            color: var(--text-primary);
            background: var(--surface);
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
            padding: var(--space-3) var(--space-4);
            font-size: 11px;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--border);
            background: var(--bg-main);
            white-space: nowrap;
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
            margin-left: 4px;
            opacity: 0.5;
        }

        .data-table-enhanced tbody td {
            padding: var(--space-4);
            border-bottom: 1px solid var(--border-light);
            font-size: 14px;
            vertical-align: middle;
        }

        .data-table-enhanced tbody tr:hover td {
            background: var(--bg-main);
        }

        .data-table-enhanced tbody tr:last-child td {
            border-bottom: none;
        }

        .unit-cell {
            display: flex;
            align-items: center;
            gap: var(--space-3);
        }

        .unit-avatar {
            width: 40px;
            height: 40px;
            border-radius: var(--radius);
            background: var(--primary-light);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .unit-info {
            min-width: 0;
        }

        .unit-name {
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .unit-id {
            font-size: 12px;
            color: var(--text-muted);
            font-family: monospace;
        }

        .badge-category {
            background: var(--surface);
            color: var(--text-secondary);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .rating-cell {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .rating-cell i {
            color: #fbbf24;
        }

        .rating-value {
            font-weight: 700;
            color: var(--text-primary);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
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
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .table-actions-cell {
            text-align: right;
        }

        .btn-action {
            width: 32px;
            height: 32px;
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
            margin-top: var(--space-5);
            padding-top: var(--space-4);
            border-top: 1px solid var(--border-light);
        }

        .showing-text {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .pagination {
            display: flex;
            gap: var(--space-1);
        }

        .btn-page {
            width: 32px;
            height: 32px;
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
            gap: var(--space-3);
            color: var(--text-secondary);
            padding: var(--space-8);
        }

        .loading-state p {
            margin: 0;
            font-size: 14px;
        }

        .skeleton {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .skeleton-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--surface);
        }

        .skeleton-line {
            height: 12px;
            background: var(--surface);
            border-radius: 4px;
            margin-bottom: var(--space-2);
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .5;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 1200px) {
            .kpi-section {
                grid-template-columns: repeat(2, 1fr);
            }

            .analytics-section {
                grid-template-columns: 1fr;
            }

            .monitoring-section {
                grid-template-columns: 1fr;
            }

            .chart-wrapper {
                min-height: 350px;
            }

            .chart-wrapper canvas,
            .chart-wrapper svg {
                min-height: 300px;
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

            .time-filter-group {
                width: 100%;
                overflow-x: auto;
            }

            .monitoring-section {
                grid-template-columns: 1fr;
            }

            .table-header-enhanced {
                flex-direction: column;
            }

            .table-controls {
                width: 100%;
                align-items: stretch;
            }

            .search-box {
                width: 100%;
            }

            .filter-tabs {
                overflow-x: auto;
                padding-bottom: var(--space-2);
            }

            .chart-wrapper {
                min-height: 300px;
            }

            .chart-wrapper canvas,
            .chart-wrapper svg {
                min-height: 250px;
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

        <div class="kpi-section">
            <div class="kpi-card">
                <div class="kpi-icon accent">
                    <i class="ti ti-users"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Today Students</span>
                    <div class="kpi-value-row">
                        <span class="kpi-value" id="students-today">0</span>
                        <span class="kpi-trend up">
                            <i class="ti ti-trending-up"></i> +0%
                        </span>
                    </div>
                    <span class="kpi-comparison">vs yesterday</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon accent">
                    <i class="ti ti-star"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Today Ratings</span>
                    <div class="kpi-value-row">
                        <span class="kpi-value" id="ratings-today">0</span>
                        <span class="kpi-trend up">
                            <i class="ti ti-trending-up"></i> +0%
                        </span>
                    </div>
                    <span class="kpi-comparison">vs yesterday</span>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-icon accent">
                    <i class="ti ti-chart-bar"></i>
                </div>
                <div class="kpi-content">
                    <span class="kpi-label">Weekly Students</span>
                    <div class="kpi-value-row">
                        <span class="kpi-value" id="students-week">0</span>
                        <span class="kpi-trend down">
                            <i class="ti ti-trending-down"></i> -0%
                        </span>
                    </div>
                    <span class="kpi-comparison">vs last week</span>
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
                    <span class="kpi-comparison">vs last month</span>
                </div>
            </div>
        </div>

        <div class="analytics-section">
            <div class="chart-container">
                <div class="chart-header">
                    <div>
                        <h3 class="chart-title">Student Login Trends</h3>
                        <p class="chart-subtitle">Peak students: <span id="peak-students" class="highlight-text">0</span>
                        </p>
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
                        <h3 class="chart-title">Monthly Unit Growth</h3>
                        <p class="chart-subtitle">Total units: <span id="total-units-display"
                                class="highlight-text">0</span></p>
                    </div>
                    <div class="chart-legend">
                        <span class="legend-item">
                            <span class="legend-dot primary"></span> New Units
                        </span>
                        <span class="legend-item">
                            <span class="legend-dot secondary"></span> Cumulative
                        </span>
                    </div>
                </div>
                <div id="chart-units-monthly"></div>
            </div>
        </div>

        <div class="table-section">
            <div class="table-header-enhanced">
                <div class="table-header-left">
                    <h3 class="table-title">Top Rated Units</h3>
                    <span class="table-subtitle">Manage and monitor unit performance</span>
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
                                Unit <i class="ti ti-arrows-sort"></i>
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
                            <td colspan="6" class="py-5">
                                <div class="loading-state">
                                    <div class="spinner-border text-orange" role="status"></div>
                                    <p>Loading units...</p>
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
                    console.error('loadDashboardData function not found');
                }
            });
        </script>
    @endpush

@endsection
