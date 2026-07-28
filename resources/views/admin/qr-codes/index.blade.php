@extends('layouts.admin.app')
@section('title', 'QR Codes Management')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/admin/css/qr-codes/styles.css') }}">
@endpush
@section('admin-content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">QR Codes Management</h1>
            <p class="text-muted mb-0 mt-1">Monitor and manage all QR codes across your units</p>
        </div>
    </div>
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
            <x-admin.search-button placeholder="Search by code or unit name..." />
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="dropdown" id="perPageDropdown">
                    <button
                        class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                        type="button" data-bs-toggle="dropdown"
                        style="height: 44px; background-color: white; border-color: #d1d5db;">
                        <span class="fw-medium" id="perPageText">{{ request('per_page', 10) }} Rows</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-3">
                        @foreach ([10, 25, 50, 100] as $size)
                        <li>
                            <a class="dropdown-item per-page-link py-2 px-3 {{ request('per_page', 10) == $size ? 'active fw-bold' : '' }}"
                                href="#" data-per-page="{{ $size }}">{{ $size }} Rows</a>
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
                    @php
                    $activeStyle = 'background: #f8773c !important; border: none !important; color: white !important;';
                    $inactiveStyle = 'background: white !important; border: 1px solid #d1d5db !important; color: #6b7280
                    !important;';
                    $currentStatus = request('status', '');
                    $currentUnitId = request('unit_id', '');
                    @endphp
                    <div class="dropdown-menu border-0 shadow-lg rounded-4 mt-2"
                        style="min-width: 340px; background-color: #ffffff;">
                        <div class="p-3">
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Status</label>
                                <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value=""
                                        style="{{ $currentStatus === '' ? $activeStyle : $inactiveStyle }}">All</button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="active"
                                        style="{{ $currentStatus === 'active' ? $activeStyle : $inactiveStyle }}">Active</button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="inactive"
                                        style="{{ $currentStatus === 'inactive' ? $activeStyle : $inactiveStyle }}">Inactive</button>
                                    <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="expired"
                                        style="{{ $currentStatus === 'expired' ? $activeStyle : $inactiveStyle }}">Expired</button>
                                </div>
                            </div>
                            <div class="mb-1">
                                <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Unit</label>
                                <div class="position-relative mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="#9ca3af" stroke-width="2"
                                        style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">
                                        <circle cx="11" cy="11" r="8"></circle>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                    </svg>
                                    <input type="text" id="unitSearchInput" class="form-control form-control-sm"
                                        placeholder="Search unit..."
                                        style="border-radius: 8px; border: 1px solid #d1d5db; font-size: 13px; padding: 8px 12px 8px 32px;">
                                </div>
                                <div
                                    style="max-height: 180px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; background: #fafafa;">
                                    <div class="d-flex flex-wrap gap-2" id="unitFilter">
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-unit unit-btn {{ empty($currentUnitId) ? 'filter-active' : '' }}"
                                            data-value="" data-name="all"
                                            style="{{ empty($currentUnitId) ? $activeStyle : $inactiveStyle }}">All</button>
                                        @foreach ($units as $unit)
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-unit unit-btn {{ $currentUnitId == $unit->id ? 'filter-active' : '' }}"
                                            data-value="{{ $unit->id }}" data-name="{{ strtolower($unit->name) }}"
                                            style="{{ $currentUnitId == $unit->id ? $activeStyle : $inactiveStyle }}">{{
                                            $unit->name }}</button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 border-top d-flex gap-2 bg-white">
                            <button type="button" id="resetFilter"
                                class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center"
                                style="height: 40px; background: white; border: 1px solid #d1d5db; color: #4b5563;">Reset</button>
                            <button type="button" id="applyFilter" class="btn btn-sm rounded-pill w-100 fw-semibold"
                                style="height: 40px; background: #f8773c; border: none; color: white;">Apply
                                Filter</button>
                        </div>
                    </div>
                </div>
                <x-admin.sort-button :sortOptions="[
                    'created_at_desc' => 'Newest First',
                    'created_at_asc' => 'Oldest First',
                    'code_asc' => 'Code A-Z',
                    'expires_at_asc' => 'Expiring Soon',
                ]" defaultSort="created_at" defaultOrder="desc" />
                <button type="button" class="qr-btn qr-btn-primary" data-bs-toggle="modal"
                    data-bs-target="#generateQrModal"
                    style="background: #f8773c; border: none; color: white; padding: 0.625rem 1.5rem; border-radius: 333px; font-size: 0.875rem; font-weight: 600; box-shadow: 0 4px 14px rgba(248, 119, 60, 0.25); display: inline-flex; align-items: center; gap: 0.5rem;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Generate QR Code</span>
                </button>
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
    <div class="card border rounded-5 mt-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-transparent">
                    <tr class="text-muted" style="font-size: .75rem;">
                        <th class="ps-4 py-3 fw-semibold">QR Code</th>
                        <th class="py-3 fw-semibold">Unit</th>
                        <th class="py-3 fw-semibold">Status</th>
                        <th class="py-3 fw-semibold">Expires At</th>
                        <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="qrCodesTable">
                    @include('admin.qr-codes.partials.rows', ['qrCodes' => $qrCodes ?? collect()])
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3" id="qrCodesPaginationContainer">
            @include('admin.qr-codes.partials.pagination', ['paginator' => $qrCodes ?? collect()])
        </div>
    </div>
</div>

@include('admin.qr-codes.partials.generate-modal', ['units' => $units])
@include('admin.qr-codes.partials.preview-modal')
@include('admin.qr-codes.partials.qr-action-modal')

@endsection
@push('scripts')
<script>
    window.QrCodeConfig = {
        searchUrl: "{{ route('admin.qr-codes.search') }}"
        , generateUrl: "{{ route('admin.qr-codes.generate') }}"
    };

</script>
<script src="{{ asset('assets/admin/js/qr-codes/qr-codes.js') }}" defer></script>
@endpush