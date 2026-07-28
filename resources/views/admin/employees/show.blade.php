@extends('layouts.admin.app')

@section('title', 'Employee Detail')

@push('styles')
    <style>
        .employee-show-wrapper {
            background: #ffffff;
            min-height: 100vh;
            padding: 32px 0;
        }

        .employee-header-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            margin-bottom: 24px;
            border: 1px solid #aeaeaf42
        }

        .employee-avatar-section {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 24px;
        }

        .employee-avatar {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            background: linear-gradient(135deg, #F8773C, #E55A2B);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 36px;
            font-weight: 700;
            flex-shrink: 0;
            overflow: hidden;
        }

        .employee-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .employee-header-info {
            flex: 1;
        }

        .employee-name {
            font-size: 28px;
            font-weight: 700;
            color: #1A1C20;
            margin: 0 0 8px 0;
            line-height: 1.2;
        }

        .employee-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .employee-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
            color: #64748B;
        }

        .employee-meta-item i {
            font-size: 16px;
        }

        .employee-actions {
            display: flex;
            gap: 12px;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-action-primary {
            background: #F8773C;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(248, 119, 60, 0.25);
        }

        .btn-action-primary:hover {
            background: #E55A2B;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(248, 119, 60, 0.35);
            color: #ffffff;
        }

        .btn-action-secondary {
            background: #ffffff;
            color: #374151;
            border: 1.5px solid #E5E7EB;
        }

        .btn-action-secondary:hover {
            background: #F8FAFC;
            border-color: #CBD5E1;
            color: #374151;
        }

        .btn-action-danger {
            background: #ffffff;
            color: #EF4444;
            border: 1.5px solid #FEE2E2;
        }

        .btn-action-danger:hover {
            background: #FEF2F2;
            border-color: #FCA5A5;
            color: #EF4444;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .info-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #aeaeaf42
        }

        .info-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .info-card-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .info-card-icon.primary {
            background: #FFF5F0;
            color: #F8773C;
        }

        .info-card-icon.success {
            background: #D1FAE5;
            color: #10B981;
        }

        .info-card-icon.info {
            background: #DBEAFE;
            color: #3B82F6;
        }

        .info-card-icon.warning {
            background: #FEF3C7;
            color: #F59E0B;
        }

        .info-card-title {
            font-size: 16px;
            font-weight: 700;
            color: #1A1C20;
            margin: 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 12px 0;
            border-bottom: 1px solid #F1F5F9;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row:first-child {
            padding-top: 0;
        }

        .info-label {
            font-size: 13px;
            font-weight: 500;
            color: #64748B;
            flex-shrink: 0;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1A1C20;
            text-align: right;
            word-break: break-word;
            max-width: 60%;
        }

        .assignments-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
                border: 1px solid #aeaeaf42

        }

        .assignments-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .assignments-title {
            font-size: 20px;
            font-weight: 700;
            color: #1A1C20;
            margin: 0;
        }

        .assignments-count {
            background: #FFF5F0;
            color: #F8773C;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 600;
        }

        .assignments-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .assignments-table thead th {
            background: #F8FAFC;
            padding: 14px 16px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #E5E7EB;
        }

        .assignments-table thead th:first-child {
            border-top-left-radius: 12px;
        }

        .assignments-table thead th:last-child {
            border-top-right-radius: 12px;
        }

        .assignments-table tbody td {
            padding: 16px;
            font-size: 14px;
            color: #1A1C20;
            border-bottom: 1px solid #F1F5F9;
        }

        .assignments-table tbody tr:last-child td {
            border-bottom: none;
        }

        .assignment-unit-name {
            font-weight: 600;
            color: #1A1C20;
        }

        .assignment-unit-code {
            font-size: 12px;
            color: #64748B;
            font-family: 'SF Mono', 'Menlo', monospace;
        }

        .status-badge-custom {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge-assigned {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .status-badge-accepted {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-badge-in_progress {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-badge-completed {
            background: #E5E7EB;
            color: #374151;
        }

        .status-badge-cancelled {
            background: #FEE2E2;
            color: #991B1B;
        }

        .empty-assignments {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-assignments-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            color: #94A3B8;
            font-size: 28px;
        }

        .empty-assignments-title {
            font-size: 16px;
            font-weight: 600;
            color: #1A1C20;
            margin: 0 0 8px 0;
        }

        .empty-assignments-desc {
            font-size: 14px;
            color: #64748B;
            margin: 0;
        }

        @media (max-width: 768px) {
            .employee-header-card {
                padding: 24px;
            }

            .employee-avatar-section {
                flex-direction: column;
                text-align: center;
            }

            .employee-meta {
                justify-content: center;
            }

            .employee-actions {
                width: 100%;
                flex-direction: column;
            }

            .btn-action {
                width: 100%;
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .assignments-card {
                padding: 20px;
            }

            .assignments-table {
                font-size: 13px;
            }

            .assignments-table thead th,
            .assignments-table tbody td {
                padding: 12px 10px;
            }
        }
    </style>
@endpush

@section('admin-content')
    <div class="employee-show-wrapper">
        <div class="container-fluid px-4">
            {{-- <x-shared.breadcrumb :items="[
                ['label' => 'Employees', 'url' => route('admin.employees.index')],
                ['label' => $employee->name, 'url' => null]
            ]" /> --}}

            <div class="employee-header-card">
                <div class="employee-avatar-section">
                    <div class="employee-avatar">
                        @if($employee->photo)
                            <img src="{{ asset('storage/' . $employee->photo) }}" alt="{{ $employee->name }}">
                        @else
                            {{ strtoupper(substr($employee->name, 0, 1)) }}
                        @endif
                    </div>

                    <div class="employee-header-info">
                        <h1 class="employee-name">{{ $employee->name }}</h1>
                        <div class="employee-meta">
                            <div class="employee-meta-item">
                                <i class="ti ti-id"></i>
                                <span>{{ $employee->employee_id }}</span>
                            </div>
                            <div class="employee-meta-item">
                                <i class="ti ti-mail"></i>
                                <span>{{ $employee->email }}</span>
                            </div>
                            <div class="employee-meta-item">
                                <x-shared.status-badge :status="$employee->is_active ? 'active' : 'inactive'" />
                            </div>
                        </div>
                    </div>

                    <div class="employee-actions">
                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn-action btn-action-primary">
                            <i class="ti ti-edit"></i>
                            <span>Edit</span>
                        </a>
                        <button type="button" class="btn-action btn-action-danger"
                            onclick="openModal('deleteModal{{ $employee->id }}')">
                            <i class="ti ti-trash"></i>
                            <span>Delete</span>
                        </button>
                        <a href="{{ route('admin.employees.index') }}" class="btn-action btn-action-secondary">
                            <i class="ti ti-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon primary">
                            <i class="ti ti-user"></i>
                        </div>
                        <h3 class="info-card-title">Personal Information</h3>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Full Name</span>
                        <span class="info-value">{{ $employee->name }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Employee ID</span>
                        <span class="info-value">{{ $employee->employee_id }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $employee->email }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Phone</span>
                        <span class="info-value">{{ $employee->phone ?? '-' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Position</span>
                        <span class="info-value">{{ $employee->position ?? '-' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Department</span>
                        <span class="info-value">{{ $employee->department ?? '-' }}</span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon info">
                            <i class="ti ti-settings"></i>
                        </div>
                        <h3 class="info-card-title">Account Settings</h3>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Timezone</span>
                        <span class="info-value">{{ $employee->timezone ?? 'Asia/Jakarta' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <x-shared.status-badge :status="$employee->is_active ? 'active' : 'inactive'" />
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Login Count</span>
                        <span class="info-value">{{ $employee->login_count ?? 0 }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Last Login</span>
                        <span class="info-value">
                            @if($employee->last_login_at)
                                {{ $employee->last_login_at->format('d M Y, H:i') }}
                            @else
                                Never
                            @endif
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Last Login IP</span>
                        <span class="info-value">{{ $employee->last_login_ip ?? '-' }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Joined</span>
                        <span class="info-value">{{ $employee->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                <div class="info-card">
                    <div class="info-card-header">
                        <div class="info-card-icon success">
                            <i class="ti ti-building"></i>
                        </div>
                        <h3 class="info-card-title">Assignment Statistics</h3>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Total Assignments</span>
                        <span class="info-value">{{ $stats['total_units'] ?? 0 }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Active Units</span>
                        <span class="info-value">{{ count($active_assignments) }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Account Status</span>
                        <span class="info-value">
                            @if($employee->is_active)
                                <span style="color: #10B981; font-weight: 600;">Active</span>
                            @else
                                <span style="color: #EF4444; font-weight: 600;">Inactive</span>
                            @endif
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Email Verified</span>
                        <span class="info-value">
                            @if($employee->email_verified_at)
                                <span style="color: #10B981; font-weight: 600;">Verified</span>
                            @else
                                <span style="color: #F59E0B; font-weight: 600;">Pending</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="assignments-card">
                <div class="assignments-header">
                    <h2 class="assignments-title">Unit Assignments</h2>
                    <span class="assignments-count">{{ count($active_assignments) }} Active</span>
                </div>

                @if(count($active_assignments) > 0)
                    <div class="table-responsive">
                        <table class="assignments-table">
                            <thead>
                                <tr>
                                    <th>Unit Name</th>
                                    <th>Unit Code</th>
                                    <th>Status</th>
                                    <th>Assigned At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($active_assignments as $assignment)
                                    <tr>
                                        <td>
                                            <div class="assignment-unit-name">
                                                {{ $assignment->unit->name ?? 'Unknown' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="assignment-unit-code">
                                                {{ $assignment->unit->code ?? '-' }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge-custom status-badge-{{ $assignment->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $assignment->status)) }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $assignment->assigned_at ? $assignment->assigned_at->format('d M Y') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-assignments">
                        <div class="empty-assignments-icon">
                            <i class="ti ti-building"></i>
                        </div>
                        <h3 class="empty-assignments-title">No Active Assignments</h3>
                        <p class="empty-assignments-desc">This employee is not currently assigned to any units.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <x-shared.delete-modal :id="'deleteModal' . $employee->id" title="Delete Employee" :item-name="$employee->name"
        itemType="employee" :delete-route="route('admin.employees.destroy', $employee->id)" deleteMethod="DELETE" />
@endsection