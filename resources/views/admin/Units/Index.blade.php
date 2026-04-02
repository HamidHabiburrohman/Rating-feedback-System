@extends('layouts.admin.app')

@section('title', 'Units Management')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Units Management</h1>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <x-admin.search-button-component placeholder="Search by name, code or location..." />
                <div class="d-flex align-items-center gap-2">
                    @if(!($hidePerPage ?? false))
                        <div class="dropdown">
                            <button
                                class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
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
                            style="min-width: 350px; background-color: #ffffff;">
                            <div class="p-3">
                                <!-- Unit Type dengan button style -->
                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Unit Type</label>
                                    <div class="d-flex flex-wrap gap-2" id="typeFilter">
                                        @php
                                            $activeStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
                                            $inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
                                            $currentTypes = request('type') ? explode(',', request('type')) : [];
                                        @endphp

                                        <!-- All Types button -->
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-type"
                                            data-value=""
                                            style="{{ empty($currentTypes) ? $activeStyle : $inactiveStyle }}">
                                            All
                                        </button>

                                        <!-- Individual type buttons -->
                                        @foreach($typeNames as $type)
                                            <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-type"
                                                data-value="{{ $type }}"
                                                style="{{ in_array($type, $currentTypes) ? $activeStyle : $inactiveStyle }}">
                                                {{ $type }}
                                            </button>
                                        @endforeach
                                    </div>

                                    <!-- Hidden input untuk menyimpan nilai yang dipilih -->
                                    <input type="hidden" name="selected_types" id="selectedTypes"
                                        value="{{ request('type', '') }}">
                                </div>

                                <!-- Operational Status (tetap seperti sebelumnya) -->
                                <div class="mb-1">
                                    <label class="small fw-bold text-uppercase mb-2 mt-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Operational Status</label>
                                    <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                        @php
                                            $currentStatus = request('status') ? explode(',', request('status')) : [];
                                        @endphp
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value=""
                                            style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            All
                                        </button>
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value="open"
                                            style="{{ in_array('open', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Open
                                        </button>
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value="full"
                                            style="{{ in_array('full', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Full
                                        </button>
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value="maintenance"
                                            style="{{ in_array('maintenance', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Maintenance
                                        </button>
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value="closed"
                                            style="{{ in_array('closed', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Closed
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
                                <button type="button" id="applyFilter" class="btn btn-sm rounded-pill w-100 fw-semibold"
                                    style="height: 40px; {{ $applyStyle }} transition: all 0.2s;">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown" id="sortDropdown">
                        @php
                            $currentSort = request('sort', 'name');
                            $currentOrder = request('order', 'asc');

                            $sortTexts = [
                                'name_asc' => 'Name A-Z',
                                'name_desc' => 'Name Z-A',
                                'created_at_desc' => 'Newest First',
                                'created_at_asc' => 'Oldest First',
                                'avg_rating_desc' => 'Highest Rating',
                                'avg_rating_asc' => 'Lowest Rating',
                                'total_ratings_desc' => 'Most Rated',
                            ];

                            $currentSortKey = $currentSort . '_' . $currentOrder;
                            $buttonText = $sortTexts[$currentSortKey] ?? 'Sort';
                        @endphp

                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
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
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'avg_rating' && $currentOrder == 'desc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'avg_rating', 'order' => 'desc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2l2.5 5.5 6 .5-4.5 4 1.5 6-5.5-3.5-5.5 3.5 1.5-6-4.5-4 6-.5L12 2z" />
                                    </svg>
                                    Highest Rating
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'avg_rating' && $currentOrder == 'asc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'avg_rating', 'order' => 'asc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2l2.5 5.5 6 .5-4.5 4 1.5 6-5.5-3.5-5.5 3.5 1.5-6-4.5-4 6-.5L12 2z" />
                                    </svg>
                                    Lowest Rating
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'total_ratings' && $currentOrder == 'desc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'total_ratings', 'order' => 'desc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                    Most Rated
                                </a>
                            </li>
                        </ul>
                    </div>

                    <x-admin.button-create url="{{ route('admin.units.create') }}" tooltip="Add New Unit" size="md">
                        Add Unit
                    </x-admin.button-create>
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
                            <th class="ps-4 py-3 fw-semibold">Unit</th>
                            <th class="py-3 fw-semibold">Type</th>
                            <th class="py-3 fw-semibold">Status</th>
                            <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="unitsTable">
                        @include('admin.units.partials.rows', ['units' => $units])
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3">
                @include('admin.units.partials.pagination', ['paginator' => $units])
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/unit.js') }}"></script>
@endpush