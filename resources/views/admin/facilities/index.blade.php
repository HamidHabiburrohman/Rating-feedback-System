@extends('layouts.admin.app')

@section('title', 'Facilities Management')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Facilities Management</h1>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <x-admin.search-button-component placeholder="Search by name..." />
                <div class="d-flex align-items-center gap-2">
                    <x-admin.rows-per-page-component :paginator="$facilities" />

                    <div class="dropdown" id="sortDropdown">
                        @php
                            $currentSort = request('sort', 'name');
                            $currentOrder = request('order', 'asc');

                            $sortTexts = [
                                'name_asc' => 'Name A-Z',
                                'name_desc' => 'Name Z-A',
                                'created_at_desc' => 'Newest First',
                                'created_at_asc' => 'Oldest First',
                                'units_count_desc' => 'Most Used',
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
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ $currentSort == 'units_count' && $currentOrder == 'desc' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'units_count', 'order' => 'desc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M2 12h20M12 2v20" />
                                    </svg>
                                    Most Used
                                </a>
                            </li>
                        </ul>
                    </div>

                    <x-admin.button-create url="{{ route('admin.facilities.create') }}" tooltip="Add New Facility" size="md">
                        Add Facility
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
                            <th class="ps-7 py-3 fw-semibold">Name</th>
                            <th class="py-3 fw-semibold">Units Count</th>
                            <th class="py-3 fw-semibold">Status</th>
                            <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="facilitiesTable">
                        @include('admin.facilities.partials.rows', ['facilities' => $facilities])
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3">
                @include('admin.facilities.partials.pagination', ['paginator' => $facilities])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/facilities.js') }}"></script>
@endpush