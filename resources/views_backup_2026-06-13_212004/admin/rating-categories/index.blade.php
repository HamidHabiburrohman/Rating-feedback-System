@extends('layouts.admin.app')

@section('title', 'Rating Categories Management')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Rating Categories Management</h1>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <x-admin.search-button-component placeholder="Search by name or slug..." />
                <div class="d-flex align-items-center gap-2">
                    @if (!($hidePerPage ?? false))
                        <div class="dropdown">
                            <button
                                class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                                type="button" data-bs-toggle="dropdown"
                                style="height: 44px; background-color: white; border-color: #d1d5db;">
                                <span class="fw-medium">{{ request('per_page', 10) }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" class="dropdown-icon"
                                    style="transition:.3s">
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
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                            </svg>
                            <span class="fw-medium" id="filterText">Filter</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" class="dropdown-icon"
                                style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>

                        @php
                            $currentStatus = request('is_active') !== null ? [request('is_active')] : [];
                        @endphp
                        <x-admin.status-filter-dropdown :currentStatus="$currentStatus" filterKey="is_active" />
                    </div>

                    <x-admin.sort-button :sortOptions="[
                        'sort_order_asc' => 'Sort Order',
                        'created_at_desc' => 'Newest First',
                        'name_asc' => 'Name A-Z',
                        'name_desc' => 'Name Z-A',
                        'created_at_asc' => 'Oldest First',
                    ]" defaultSort="sort_order" defaultOrder="asc" />

                    <x-admin.button-create url="{{ route('admin.rating-categories.create') }}"
                        tooltip="Add New Rating Category" size="md">
                        Add Category
                    </x-admin.button-create>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center"
                role="alert">
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
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center"
                role="alert">
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

        <div class="card border rounded-5 mt-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-transparent">
                        <tr class="text-muted text-uppercase" style="font-size: .75rem;">
                            <th class="ps-4 py-3 fw-semibold">Name</th>
                            <th class="py-3 fw-semibold">Sort Order</th>
                            <th class="py-3 fw-semibold">Usage Count</th>
                            <th class="py-3 fw-semibold">Status</th>
                            <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="categoriesTable">
                        @include('admin.rating-categories.partials.rows', ['categories' => $categories])
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3">
                @include('admin.rating-categories.partials.pagination', ['paginator' => $categories])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/rating-category.js') }}"></script>
@endpush
