@extends('layouts.admin.app')

@section('title', 'Ratings management')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Ratings & Feedback</h1>
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
                        placeholder="Search by unit name or comment..." value="{{ request('search') }}"
                        style="height: 44px; background-color: white; border-color: #d1d5db !important;">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown"
                            style="height:44px;background:white;border-color:#d1d5db;">
                            <span class="fw-medium">
                                {{ request('per_page', 10) }}
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
                            @foreach ([10, 25, 50, 100] as $size)
                                <li>
                                    <a class="dropdown-item py-2 px-3 {{ (request('per_page', 10) == $size) ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}">
                                        {{ $size }} Rows
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

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
                                <div class="mb-4">
                                    <label class="small fw-bold text-uppercase mb-2 mt-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Status Rating</label>
                                    <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                        @php
                                            $activeStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
                                            $inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
                                            $currentStatus = request('status') ? explode(',', request('status')) : [];
                                        @endphp
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value=""
                                            style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            All
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value="pending"
                                            style="{{ in_array('pending', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Pending
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value="dibalas"
                                            style="{{ in_array('dibalas', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Responded
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value="selesai"
                                            style="{{ in_array('selesai', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Completed
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Date Range</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" id="dateFrom" class="form-control form-control-sm rounded"
                                                value="{{ request('date_from') }}" placeholder="From">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" id="dateTo" class="form-control form-control-sm rounded"
                                                value="{{ request('date_to') }}" placeholder="To">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border-top d-flex gap-2 bg-white">
                                @php
                                    $resetStyle = 'background: white; border: 1px solid #d1d5db; color: #4b5563;';
                                    $applyStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
                                @endphp
                                <button type="button" id="resetFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center shadow-sm"
                                    style="height: 40px; {{ $resetStyle }} transition: all 0.2s;">
                                    Reset
                                </button>
                                <button type="button" id="applyFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold shadow-sm"
                                    style="height: 40px; {{ $applyStyle }} transition: all 0.2s;">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        @include('layouts.components.button', [
                            'formats' => ['excel', 'pdf'],
                            'filters' => [
                                'search' => request('search'),
                                'status' => request('status'),
                                'date_from' => request('date_from'),
                                'date_to' => request('date_to'),
                                'per_page' => request('per_page')
                            ],
                            'exportRoute' => route('admin.ratings.export')
                        ])
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

        <div class="card border-1 rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-transparent">
                        <tr class="text-muted text-uppercase" style="font-size: .75rem;">
                            <th class="ps-4 py-3 fw-semibold">Unit</th>
                            <th class="py-3 fw-semibold">Comment</th>
                            <th class="py-3 fw-semibold">Status</th>
                            <th class="pe-4 py-3 fw-semibold text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @include('admin.ratings.partials.rows')
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3">
                @include('admin.ratings.partials.pagination')
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/ratings.js') }}"></script>

@endsection