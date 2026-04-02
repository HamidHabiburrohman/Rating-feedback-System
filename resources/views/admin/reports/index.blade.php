@extends('layouts.admin.app')

@section('title', 'Reports Management')

@section('admin-content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Reports Management</h1>
        </div>
    </div>

    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <x-admin.search-button-component placeholder="Search by title, tracking code..." />
            <div class="d-flex align-items-center gap-2">
                @if(!($hidePerPage ?? false))
                    <div class="dropdown">
                        <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown"
                            style="height: 44px; background-color: white; border-color: #d1d5db;">
                            <span class="fw-medium">{{ request('per_page', 10) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3">
                            @foreach ([10, 25, 50, 100] as $size)
                                <li>
                                    <a class="dropdown-item py-2 px-3 {{ request('per_page', 10) == $size ? 'active bg-light text-primary fw-bold' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}">
                                        {{ $size }} Rows
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="dropdown" id="filterContainer">
                    <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                        type="button" data-bs-toggle="dropdown" id="filterDropdown"
                        style="height:44px;background:white;border-color:#d1d5db;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                        </svg>
                        <span class="fw-medium" id="filterText">Filter</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>
                    <div class="dropdown-menu p-0 border-0 shadow-lg rounded-4 overflow-hidden mt-2"
                        style="min-width: 350px; background-color: #ffffff;">
                        <div class="p-3">
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Status</label>
                                <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                    @php
                                        $activeStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
                                        $inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
                                        $currentStatus = request('status') ? explode(',', request('status')) : [];
                                    @endphp
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value=""
                                        style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">
                                        All
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="new"
                                        style="{{ in_array('new', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                        New
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="in_progress"
                                        style="{{ in_array('in_progress', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                        In Progress
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="resolved"
                                        style="{{ in_array('resolved', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                        Resolved
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="rejected"
                                        style="{{ in_array('rejected', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                        Rejected
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Priority</label>
                                <div class="d-flex flex-wrap gap-2" id="priorityFilter">
                                    @php
                                        $currentPriority = request('priority') ? explode(',', request('priority')) : [];
                                    @endphp
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-priority"
                                        data-value=""
                                        style="{{ empty($currentPriority) ? $activeStyle : $inactiveStyle }}">
                                        All
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-priority"
                                        data-value="low"
                                        style="{{ in_array('low', $currentPriority) ? $activeStyle : $inactiveStyle }}">
                                        Low
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-priority"
                                        data-value="medium"
                                        style="{{ in_array('medium', $currentPriority) ? $activeStyle : $inactiveStyle }}">
                                        Medium
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-priority"
                                        data-value="high"
                                        style="{{ in_array('high', $currentPriority) ? $activeStyle : $inactiveStyle }}">
                                        High
                                    </button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-priority"
                                        data-value="critical"
                                        style="{{ in_array('critical', $currentPriority) ? $activeStyle : $inactiveStyle }}">
                                        Critical
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Unit</label>
                                <select class="form-select" id="unitFilter">
                                    <option value="">All Units</option>
                                    @foreach($filterData['units'] ?? [] as $id => $name)
                                        <option value="{{ $id }}" {{ request('unit_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="p-3 border-top d-flex gap-2 bg-white">
                            <button type="button" id="resetFilter"
                                class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center"
                                style="height: 40px; background: white; border: 1px solid #d1d5db; color: #4b5563;">
                                Reset
                            </button>
                            <button type="button" id="applyFilter" 
                                class="btn btn-sm rounded-pill w-100 fw-semibold"
                                style="height: 40px; background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;">
                                Apply Filter
                            </button>
                        </div>
                    </div>
                </div>

                <div class="dropdown" id="sortDropdown">
                    @php
                        $currentSort = request('sort', 'created_at');
                        $currentOrder = request('order', 'desc');

                        $sortTexts = [
                            'created_at_desc' => 'Newest First',
                            'created_at_asc' => 'Oldest First',
                            'priority_desc' => 'Priority (High to Low)',
                            'priority_asc' => 'Priority (Low to High)',
                        ];

                        $currentSortKey = $currentSort . '_' . $currentOrder;
                        $buttonText = $sortTexts[$currentSortKey] ?? 'Sort';
                    @endphp

                    <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                        type="button" data-bs-toggle="dropdown"
                        style="height: 44px; background-color: white; border-color: #d1d5db;">
                        <span class="fw-medium">{{ $buttonText }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
                        <li>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'created_at' && $currentOrder == 'desc' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc', 'page' => 1]) }}">
                                Newest First
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'created_at' && $currentOrder == 'asc' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'asc', 'page' => 1]) }}">
                                Oldest First
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'priority' && $currentOrder == 'desc' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'priority', 'order' => 'desc', 'page' => 1]) }}">
                                Priority (High to Low)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'priority' && $currentOrder == 'asc' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['sort' => 'priority', 'order' => 'asc', 'page' => 1]) }}">
                                Priority (Low to High)
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" class="me-2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" class="me-2">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border rounded-4 mt-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-transparent">
                    <tr class="text-muted text-uppercase" style="font-size: .75rem;">
                        <th class="ps-4 py-3 fw-semibold">Title</th>
                        <th class="py-3 fw-semibold">Unit</th>
                        <th class="py-3 fw-semibold">Priority</th>
                        <th class="py-3 fw-semibold">Status</th>
                        <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="reportsTable">
                    @include('admin.reports.partials.rows', ['reports' => $reports])
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3">
            @include('admin.reports.partials.pagination', ['paginator' => $reports])
        </div>
    </div>
</div>
@endsection

@push('admin-scripts')
<script src="{{ asset('assets/admin/js/reports.js') }}"></script>
@endpush