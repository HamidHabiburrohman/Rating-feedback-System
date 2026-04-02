@extends('layouts.admin.app')

@section('title', 'Ratings Management')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: var(--color-gray-900);">Ratings Management</h1>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">

                <!-- Search Component -->
                <x-admin.search-button-component placeholder="Search by title, tracking code..." />


                <div class="d-flex align-items-center gap-2">
                    <!-- Per Page Dropdown -->
                    <div class="dropdown">
                        <button class="dropdown-toggle-btn" type="button" data-bs-toggle="dropdown">
                            <span class="fw-medium">{{ request('per_page', 10) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach ([10, 25, 50, 100] as $size)
                                <li>
                                    <a class="dropdown-item {{ request('per_page', 10) == $size ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}">
                                        {{ $size }} Rows
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Filter Dropdown -->
                    <div class="dropdown">
                        <button class="dropdown-toggle-btn" type="button" data-bs-toggle="dropdown" id="filterDropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                            </svg>
                            <span class="fw-medium" id="filterText">Filter</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <div class="dropdown-menu p-3" style="min-width: 320px;">
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block text-muted">Unit</label>
                                <select class="form-select" id="unitFilter">
                                    <option value="">All Units</option>
                                    @foreach($filterData['units'] ?? [] as $id => $name)
                                        <option value="{{ $id }}" {{ request('unit_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block text-muted">Status</label>
                                <select class="form-select" id="statusFilter">
                                    @foreach($filterData['statuses'] ?? [] as $value => $label)
                                        <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block text-muted">Score Range</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" id="minScoreFilter" placeholder="Min"
                                            value="{{ request('min_score') }}" min="1" max="5" step="0.1">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" id="maxScoreFilter" placeholder="Max"
                                            value="{{ request('max_score') }}" min="1" max="5" step="0.1">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-1">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="hasReportsFilter" {{ request('has_reports') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hasReportsFilter">
                                        Has Active Reports
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="censoredFilter" {{ request('is_comment_censored') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="censoredFilter">
                                        Censored Comments
                                    </label>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex gap-2">
                                <button type="button" id="resetFilter" class="btn btn-secondary w-100">Reset</button>
                                <button type="button" id="applyFilter" class="btn btn-primary w-100">Apply Filter</button>
                            </div>
                        </div>
                    </div>

                    <!-- Sort Dropdown -->
                    <div class="dropdown">
                        @php
                            $currentSort = request('sort', 'created_at');
                            $currentOrder = request('order', 'desc');
                            $sortTexts = [
                                'created_at_desc' => 'Newest First',
                                'created_at_asc' => 'Oldest First',
                                'overall_score_desc' => 'Highest Score',
                                'overall_score_asc' => 'Lowest Score',
                            ];
                            $currentSortKey = $currentSort . '_' . $currentOrder;
                        @endphp
                        <button class="dropdown-toggle-btn" type="button" data-bs-toggle="dropdown">
                            <span class="fw-medium">{{ $sortTexts[$currentSortKey] ?? 'Sort' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ $currentSort == 'created_at' && $currentOrder == 'desc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc', 'page' => 1]) }}">Newest
                                    First</a></li>
                            <li><a class="dropdown-item {{ $currentSort == 'created_at' && $currentOrder == 'asc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'asc', 'page' => 1]) }}">Oldest
                                    First</a></li>
                            <li><a class="dropdown-item {{ $currentSort == 'overall_score' && $currentOrder == 'desc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'overall_score', 'order' => 'desc', 'page' => 1]) }}">Highest
                                    Score</a></li>
                            <li><a class="dropdown-item {{ $currentSort == 'overall_score' && $currentOrder == 'asc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'overall_score', 'order' => 'asc', 'page' => 1]) }}">Lowest
                                    Score</a></li>
                        </ul>
                    </div>

                    <!-- Export Button -->
                    <a href="{{ route('admin.ratings.export') }}" class="btn btn-outline-secondary btn-icon-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Export
                    </a>

                    <!-- Bulk Actions Button -->
                    <button type="button" id="bulkActionsBtn" class="btn btn-primary btn-icon-left d-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Bulk Actions
                        <span class="badge bg-white text-dark rounded-pill ms-1">0</span>
                    </button>
                </div>
            </div>
        </div>


        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
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
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                            <th class="ps-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th class="py-3 fw-semibold">Unit</th>
                            <th class="py-3 fw-semibold">Student</th>
                            <th class="py-3 fw-semibold">Score</th>
                            <th class="py-3 fw-semibold text-center">Status</th>
                            <th class="pe-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ratingsTable">
                        @include('admin.ratings.partials.rows', ['ratings' => $ratings])
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3 border-top border-soft">
                @include('admin.ratings.partials.pagination', ['paginator' => $ratings])
            </div>
        </div>


        <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bulk Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">Apply action to <span id="selectedCount" class="fw-bold text-primary">0</span>
                            selected ratings</p>
                        <select class="form-select mb-3" id="bulkActionSelect">
                            <option value="">Select Action</option>
                            <option value="archive">Archive</option>
                            <option value="restore">Restore</option>
                            <option value="delete">Delete</option>
                        </select>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="applyBulkAction">Apply</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/ratings.js') }}"></script>
@endpush