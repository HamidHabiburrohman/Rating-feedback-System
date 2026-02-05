@extends('layouts.admin.app')

@section('title','Type management')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Unit Types Management</h1>
                <p class="text-muted mb-0">Manage unit types/categories</p>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <div class="position-relative" style="flex: 1; max-width: 500px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="#6b7280" stroke-width="2" class="position-absolute top-50 translate-middle-y ms-3"
                        style="left: 0; z-index: 10;">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" id="searchInput" class="form-control ps-5 rounded-pill border shadow-sm"
                        placeholder="Search unit types by name or description..." value="{{ request('search') }}"
                        style="height: 44px; background-color: white; border-color: #d1d5db !important;">
                </div>

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
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
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
                            style="min-width: 300px; background-color: #ffffff;">
                            <div class="p-3">
                                <!-- STATUS FILTER -->
                                <div class="mb-1">
                                    <label class="small fw-bold text-uppercase mb-2 mt-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Status</label>
                                    <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                        @php
                                        $activeStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
                                        $inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
                                        $currentStatus = request('status') ? explode(',', request('status')) : [];
                                        @endphp
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value=""
                                            style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            All
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value="active"
                                            style="{{ in_array('active', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Active
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value="inactive"
                                            style="{{ in_array('inactive', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Inactive
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border-top d-flex gap-2 bg-white">
                                @php
                                $resetStyle = 'background: white; border: 1px solid #d1d5db; color: #4b5563;';
                                $applyStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
                                @endphp
                                <button type="button" id="resetFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center"
                                    style="height: 40px; {{ $resetStyle }} transition: all 0.2s;">
                                    Reset
                                </button>
                                <button type="button" id="applyFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold"
                                    style="height: 40px; {{ $applyStyle }} transition: all 0.2s;">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- SORT BUTTON - UPDATE STYLE -->
                    <div class="dropdown" id="sortDropdown">
                        @php
                            $currentSort = request('sort', 'sort_order');
                            $currentOrder = request('order', 'asc');
                            
                            $sortTexts = [
                                'name_asc' => 'Name A-Z',
                                'name_desc' => 'Name Z-A',
                                'created_at_desc' => 'Newest First',
                                'created_at_asc' => 'Oldest First',
                                'sort_order_asc' => 'Sort Order'
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
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'name' && $currentOrder == 'asc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'asc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 16 4 4 4-4M7 20V4M14 8h7M14 12h7M14 16h7" />
                                    </svg>
                                    Name A-Z
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'name' && $currentOrder == 'desc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => 'desc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 8 4-4 4 4M7 4v16M14 8h7M14 12h7M14 16h7" />
                                    </svg>
                                    Name Z-A
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'created_at' && $currentOrder == 'desc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 16 4 4 4-4M7 20V4M21 16H10M21 10H10M21 4H10" />
                                    </svg>
                                    Newest First
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'created_at' && $currentOrder == 'asc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'asc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 8 4-4 4 4M7 4v16M21 4H10M21 10H10M21 16H10" />
                                    </svg>
                                    Oldest First
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'sort_order' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'sort_order', 'order' => 'asc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 3h5v5M4 20L21 3M21 16v5h-5" />
                                    </svg>
                                    Sort Order
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- CREATE BUTTON - UPDATE STYLE SAMA DENGAN UNIT -->
                    <a href="{{ route('admin.unit-types.create') }}"
                        class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2 shadow-sm"
                        style="height: 44px; background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="white" stroke-width="2.5">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        <span class="fw-medium">Add Type</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" class="me-2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center" role="alert">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" class="me-2">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-1 rounded-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-transparent">
                    <tr class="text-muted text-uppercase" style="font-size: .75rem;">
                        <th class="ps-4 py-3 fw-semibold">Name</th>
                        <th class="py-3 fw-semibold">Units Count</th>
                        <th class="py-3 fw-semibold">Status</th>
                        <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="unitTypesTable">
                    @include('admin.unit-type.partials.unit-types_rows', ['unitTypes' => $unitTypes])
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3">
            @include('admin.unit-type.partials.pagination', ['unitTypes' => $unitTypes])
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0 text-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 64px; height: 64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="#dc2626" stroke-width="2">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                        </svg>
                    </div>
                    <h5 class="fw-bold mb-2">Delete Unit Type</h5>
                    <p class="text-muted mb-4" id="deleteModalText">Are you sure you want to delete this unit type?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="deleteForm" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4">
                                Yes, Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script src="{{ asset("assets/js/unit-type.js") }}"></script>
@endsection