@extends('layouts.admin.app')

@section('title', 'Department Management')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Unit Departments Management</h1>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <x-admin.search-button-component placeholder="Search by name, code or description..." />
                <div class="d-flex align-items-center gap-2">
                    <x-admin.rows-per-page-component :paginator="$departments" :hide="$hidePerPage ?? false" />

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

                        @php
                            $currentStatus = request('status') ? explode(',', request('status')) : [];
                        @endphp
                        <x-admin.status-filter-dropdown :currentStatus="$currentStatus" :options="['active' => 'Active', 'inactive' => 'Inactive']" />
                    </div>

                    <x-admin.sort-button :sortOptions="[
            'name_asc' => 'Name A-Z',
            'name_desc' => 'Name Z-A',
            'created_at_desc' => 'Newest First',
            'created_at_asc' => 'Oldest First',
        ]" defaultSort="name"
                        defaultOrder="asc" />

                    <x-admin.button-create url="{{ route('admin.unit-departments.create') }}" tooltip="Add New Department"
                        size="md">
                        Add Department
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
                            <th class="ps-4 py-3 fw-semibold">Name</th>
                            <th class="py-3 fw-semibold">Code</th>
                            <th class="py-3 fw-semibold">Units Count</th>
                            <th class="py-3 fw-semibold">Status</th>
                            <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="unitDepartmentsTable">
                        @include('admin.unit-departments.partials.rows', ['departments' => $departments])
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3">
                @include('admin.unit-departments.partials.pagination', ['paginator' => $departments])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/unit-department.js') }}"></script>
@endpush