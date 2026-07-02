@extends('layouts.admin.app')
@section('title', 'QR Codes Management')
@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">QR Codes Management</h1>
                <p class="text-muted mb-0 mt-1">Monitor and manage all QR codes across your units</p>
            </div>
        </div>

        {{-- <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="ti ti-qrcode"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Total QR Codes</div>
                        <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon active">
                        <i class="ti ti-circle-check"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Active</div>
                        <div class="stat-value">{{ $stats['active'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon inactive">
                        <i class="ti ti-circle-x"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Inactive</div>
                        <div class="stat-value">{{ $stats['inactive'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon expired">
                        <i class="ti ti-clock-x"></i>
                    </div>
                    <div class="stat-info">
                        <div class="stat-label">Expired</div>
                        <div class="stat-value">{{ $stats['expired'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div> --}}

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
                            $inactiveStyle = 'background: white !important; border: 1px solid #d1d5db !important; color: #6b7280 !important;';
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
                                                    style="{{ $currentUnitId == $unit->id ? $activeStyle : $inactiveStyle }}">{{ $unit->name }}</button>
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
        ]" defaultSort="created_at"
                        defaultOrder="desc" />

                    <button type="button" class="qr-btn qr-btn-primary open-generate-modal-btn">
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
@endsection

@push('styles')
    <style>
        .skeleton {
            background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
            background-size: 200% 100%;
            animation: skeleton-shimmer 1.5s infinite ease-in-out;
            border-radius: 6px;
        }

        @keyframes skeleton-shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }

        .stat-card {
            background: white;
            border: 1px solid rgba(15, 23, 42, 0.06);
            border-radius: 16px;
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.2s ease;
        }

        .stat-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .stat-icon.total {
            background: #fff5f0;
            color: #f8773c;
        }

        .stat-icon.active {
            background: #d1fae5;
            color: #10b981;
        }

        .stat-icon.inactive {
            background: #f3f4f6;
            color: #6b7280;
        }

        .stat-icon.expired {
            background: #fef3c7;
            color: #f59e0b;
        }

        .stat-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        /* Modal Styles */
        .modal-content {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid #f1f5f9;
            padding: 1.5rem;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #f1f5f9;
            padding: 1.25rem 1.5rem;
            background: #fafbfc;
            border-radius: 0 0 20px 20px;
        }

        /* Custom Select Dropdown */
        .custom-select-wrapper {
            position: relative;
        }

        .custom-select-trigger {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: white;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.875rem;
            color: #111827;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: left;
        }

        .custom-select-trigger:hover {
            border-color: #cbd5e1;
        }

        .custom-select-wrapper.open .custom-select-trigger {
            outline: none;
            border-color: #f8773c;
            box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.1);
        }

        .selected-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            color: #64748b;
            flex-shrink: 0;
        }

        .selected-text {
            flex: 1;
            color: #64748b;
        }

        .custom-select-wrapper.open .selected-text {
            color: #111827;
        }

        .chevron {
            color: #64748b;
            transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            flex-shrink: 0;
        }

        .custom-select-wrapper.open .chevron {
            transform: rotate(180deg);
            color: #f8773c;
        }

        .custom-select-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.12), 0 4px 8px rgba(15, 23, 42, 0.04);
            max-height: 280px;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(0.96);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .custom-select-wrapper.open .custom-select-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }

        .custom-select-search {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            background: #fafafa;
        }

        .custom-select-search svg {
            color: #94a3b8;
            flex-shrink: 0;
        }

        .custom-select-search input {
            flex: 1;
            border: none;
            outline: none;
            background: transparent;
            font-size: 0.875rem;
            color: #111827;
        }

        .custom-select-search input::placeholder {
            color: #94a3b8;
        }

        .custom-select-options {
            max-height: 200px;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .custom-select-options::-webkit-scrollbar {
            width: 6px;
        }

        .custom-select-options::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .custom-select-options::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .custom-select-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 0.875rem;
            cursor: pointer;
            transition: all 0.15s ease;
            border-radius: 8px;
            font-size: 0.875rem;
            color: #111827;
        }

        .custom-select-option:hover {
            background: #f8fafc;
        }

        .custom-select-option.selected {
            background: #fff5f0;
            color: #f8773c;
        }

        .custom-select-option svg {
            flex-shrink: 0;
            color: #64748b;
        }

        .custom-select-option.selected svg {
            color: #f8773c;
        }

        .custom-select-option .option-text {
            flex: 1;
            font-weight: 500;
        }

        .custom-select-option .option-code {
            font-size: 0.75rem;
            color: #94a3b8;
            font-family: 'SF Mono', 'Menlo', monospace;
        }

        .loading-state,
        .no-results {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            justify-content: center;
            color: #94a3b8;
            font-size: 0.875rem;
        }

        .loading-state svg,
        .no-results svg {
            color: #94a3b8;
        }

        /* Button Styles */
        .btn-secondary {
            background: white;
            color: #374151;
            border: 1px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-primary {
            background: #f8773c;
            color: white;
            border: none;
            box-shadow: 0 4px 14px rgba(248, 119, 60, 0.25);
        }

        .btn-primary:hover:not(:disabled) {
            background: #e55a2b;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(248, 119, 60, 0.35);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Alert Styles */
        .alert-danger {
            background: #fef2f2;
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #b91c1c;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('generateQrModal');
            const unitSelectWrapper = document.getElementById('unitSelectWrapper');
            const unitSelectTrigger = document.getElementById('unitSelectTrigger');
            const unitSelectDropdown = document.getElementById('unitSelectDropdown');
            const unitSelectInput = document.getElementById('generateUnitSelect');
            const unitSelectText = document.getElementById('unitSelectText');
            const unitSearchInput = document.getElementById('unitSearchInput');
            const unitSelectOptions = document.getElementById('unitSelectOptions');
            const btnGenerate = document.getElementById('btnGenerateQr');
            const errorBox = document.getElementById('generateModalError');
            const spinner = document.getElementById('generateSpinner');
            const btnText = document.getElementById('generateBtnText');

            let selectedUnitId = null;
            let searchTimeout = null;

            // Toggle dropdown
            unitSelectTrigger.addEventListener('click', function (e) {
                e.stopPropagation();
                unitSelectWrapper.classList.toggle('open');
                if (unitSelectWrapper.classList.contains('open')) {
                    unitSearchInput.focus();
                }
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!unitSelectWrapper.contains(e.target)) {
                    unitSelectWrapper.classList.remove('open');
                }
            });

            // Search functionality with AJAX
            unitSearchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                searchTimeout = setTimeout(() => {
                    fetchUnits(query);
                }, 300);
            });

            // Fetch units from server
            function fetchUnits(query = '') {
                const loadingState = unitSelectOptions.querySelector('.loading-state');
                const noResults = unitSelectOptions.querySelector('.no-results');

                // Show loading
                loadingState.classList.remove('d-none');
                noResults.classList.add('d-none');

                // Remove existing options
                unitSelectOptions.querySelectorAll('.custom-select-option').forEach(opt => opt.remove());

                // Fetch from server
                fetch(`{{ route('admin.units.search') }}?q=${encodeURIComponent(query)}&limit=5`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        loadingState.classList.add('d-none');

                        if (data.units && data.units.length > 0) {
                            noResults.classList.add('d-none');
                            data.units.forEach(unit => {
                                const option = document.createElement('div');
                                option.className = 'custom-select-option';
                                option.dataset.value = unit.id;
                                option.innerHTML = `
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                                        <polyline points="9 22 9 12 15 12 15 22"/>
                                    </svg>
                                    <span class="option-text">${unit.name}</span>
                                    <span class="option-code">${unit.code || 'No Code'}</span>
                                `;

                                option.addEventListener('click', function () {
                                    selectUnit(unit.id, unit.name);
                                });

                                unitSelectOptions.appendChild(option);
                            });
                        } else {
                            noResults.classList.remove('d-none');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching units:', error);
                        loadingState.classList.add('d-none');
                        noResults.classList.remove('d-none');
                    });
            }

            // Select unit
            function selectUnit(id, name) {
                selectedUnitId = id;
                unitSelectInput.value = id;
                unitSelectText.textContent = name;
                unitSelectText.style.color = '#111827';
                unitSelectWrapper.classList.remove('open');

                // Mark as selected
                unitSelectOptions.querySelectorAll('.custom-select-option').forEach(opt => {
                    opt.classList.toggle('selected', opt.dataset.value === id);
                });
            }

            // Generate QR Code
            btnGenerate.addEventListener('click', async function () {
                errorBox.classList.add('d-none');

                if (!selectedUnitId) {
                    errorBox.textContent = 'Harap pilih unit terlebih dahulu.';
                    errorBox.classList.remove('d-none');
                    return;
                }

                btnGenerate.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = 'Generating...';

                try {
                    const response = await fetch('{{ route("admin.qr-codes.generate", ":unit") }}'.replace(':unit', selectedUnitId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        window.location.reload();
                    } else {
                        errorBox.textContent = data.message || 'Gagal generate QR Code.';
                        errorBox.classList.remove('d-none');
                    }
                } catch (err) {
                    errorBox.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                    errorBox.classList.remove('d-none');
                } finally {
                    btnGenerate.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Generate QR Code';
                }
            });

            // Reset modal when closed
            modal.addEventListener('hidden.bs.modal', function () {
                selectedUnitId = null;
                unitSelectInput.value = '';
                unitSelectText.textContent = 'Pilih unit...';
                unitSelectText.style.color = '#64748b';
                unitSearchInput.value = '';
                errorBox.classList.add('d-none');
                unitSelectOptions.querySelectorAll('.custom-select-option').forEach(opt => opt.remove());
            });

            // Load initial units when modal opens
            modal.addEventListener('shown.bs.modal', function () {
                fetchUnits();
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            initializeSearch();
            initializeFilters();
            initializePerPage();
            initializePagination();
            initializeSort();
            initializeAlerts();
            initializeDropdowns();
            initializeGenerateModal();
        });

        function generateSkeletonRows(count) {
            let html = '';
            for (let i = 0; i < count; i++) {
                html += `
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="skeleton" style="width: 40px; height: 40px; border-radius: 8px;"></div>
                                    <div class="skeleton" style="width: 120px; height: 16px;"></div>
                                </div>
                            </td>
                            <td><div class="skeleton" style="width: 140px; height: 16px;"></div></td>
                            <td><div class="skeleton" style="width: 80px; height: 24px; border-radius: 12px;"></div></td>
                            <td><div class="skeleton" style="width: 100px; height: 16px;"></div></td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-2">
                                    <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                                    <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                                </div>
                            </td>
                        </tr>
                    `;
            }
            return html;
        }

        function generateSkeletonPagination() {
            return `<div class="d-flex justify-content-start py-3"><div class="skeleton" style="width:250px;height:40px;"></div></div>`;
        }

        function fetchData(url) {
            const tableBody = document.getElementById('qrCodesTable');
            const paginationContainer = document.getElementById('qrCodesPaginationContainer');
            if (!tableBody || !paginationContainer) return;

            tableBody.innerHTML = generateSkeletonRows(10);
            paginationContainer.innerHTML = generateSkeletonPagination();

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.html !== undefined) tableBody.innerHTML = data.html;
                    if (data.pagination !== undefined) paginationContainer.innerHTML = data.pagination;
                    initializeTooltips();
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    tableBody.innerHTML = '<tr><td colspan="5" class="text-center py-5 text-danger">Failed to load data.</td></tr>';
                });
        }

        function initializeTooltips() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                if (!bootstrap.Tooltip.getInstance(el)) new bootstrap.Tooltip(el);
            });
        }

        function initializeSearch() {
            const searchInput = document.getElementById('searchInput') || document.querySelector('input[name="search"]');
            if (!searchInput) return;

            let searchTimeout;
            const performSearch = () => {
                const url = new URL(window.location.href);
                if (searchInput.value.trim()) {
                    url.searchParams.set('search', searchInput.value.trim());
                } else {
                    url.searchParams.delete('search');
                }
                url.searchParams.set('page', '1');
                window.history.pushState({}, '', url);
                fetchData(url);
            };

            searchInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    performSearch();
                }
            });

            searchInput.addEventListener('input', function () {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 800);
            });
        }

        function initializePerPage() {
            document.addEventListener('click', function (e) {
                const perPageLink = e.target.closest('.per-page-link');
                if (perPageLink) {
                    e.preventDefault();
                    const perPage = perPageLink.dataset.perPage;
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', perPage);
                    url.searchParams.set('page', '1');
                    window.history.pushState({}, '', url);

                    const perPageText = document.getElementById('perPageText');
                    if (perPageText) perPageText.textContent = perPage + ' Rows';

                    fetchData(url);
                }
            });
        }

        function initializeFilters() {
            const filterContainer = document.getElementById('filterContainer');
            if (!filterContainer) return;

            const params = new URLSearchParams(window.location.search);
            let selectedStatus = params.get('status') || '';
            let selectedUnitId = params.get('unit_id') || '';

            const activeStyle = 'background:#f8773c;border:none;color:white;';
            const inactiveStyle = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

            const unitBtns = document.querySelectorAll('.unit-btn');
            const unitSearchInput = document.getElementById('unitSearchInput');

            function updateUnitVisibility() {
                if (!unitSearchInput) return;
                const searchTerm = unitSearchInput.value.toLowerCase();
                let visibleCount = 0;
                unitBtns.forEach(btn => {
                    const isAllBtn = btn.dataset.value === '';
                    const name = btn.dataset.name || '';
                    const isActive = btn.classList.contains('filter-active');
                    if (searchTerm !== '') {
                        btn.classList.toggle('d-none', !(name.includes(searchTerm) || isAllBtn));
                    } else {
                        if (isAllBtn || isActive) {
                            btn.classList.remove('d-none');
                            if (!isAllBtn) visibleCount++;
                        } else if (visibleCount < 5) {
                            btn.classList.remove('d-none');
                            visibleCount++;
                        } else {
                            btn.classList.add('d-none');
                        }
                    }
                });
            }

            function updateUI() {
                filterContainer.querySelectorAll('.filter-status').forEach(btn => {
                    btn.style.cssText = btn.dataset.value === selectedStatus ? activeStyle : inactiveStyle;
                });

                filterContainer.querySelectorAll('.filter-unit').forEach(btn => {
                    const value = btn.dataset.value;
                    const isActive = (value === '' && selectedUnitId === '') || selectedUnitId === value;
                    btn.style.cssText = isActive ? activeStyle : inactiveStyle;
                    if (isActive) btn.classList.add('filter-active');
                    else btn.classList.remove('filter-active');
                });

                const filterText = document.getElementById('filterText');
                const filterBtn = document.getElementById('filterDropdown');

                if (filterBtn && filterText) {
                    filterBtn.querySelector('.filter-badge')?.remove();
                    let filterCount = 0;
                    if (selectedStatus) filterCount++;
                    if (selectedUnitId) filterCount++;

                    if (filterCount > 0) {
                        const parts = [];
                        if (selectedStatus) parts.push(selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1));
                        if (selectedUnitId) parts.push('Unit');
                        filterText.textContent = `Filter: ${parts.join(', ')}`;
                        const badge = document.createElement('span');
                        badge.className = 'filter-badge';
                        badge.style.cssText = 'width:8px;height:8px;background:#f8773c;border-radius:50%;margin-left:6px;display:inline-block;';
                        filterBtn.appendChild(badge);
                    } else {
                        filterText.textContent = 'Filter';
                    }
                }
            }

            if (unitSearchInput) {
                unitSearchInput.addEventListener('input', function (e) {
                    e.stopPropagation();
                    updateUnitVisibility();
                });
                updateUnitVisibility();
            }

            filterContainer.querySelectorAll('.filter-status').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    selectedStatus = this.dataset.value;
                    updateUI();
                });
            });

            filterContainer.querySelectorAll('.filter-unit').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.stopPropagation();
                    const value = this.dataset.value;

                    if (value === '') {
                        selectedUnitId = '';
                        unitBtns.forEach(b => {
                            b.style.cssText = inactiveStyle;
                            b.classList.remove('filter-active');
                        });
                        this.style.cssText = activeStyle;
                        this.classList.add('filter-active');
                    } else {
                        const allBtn = document.querySelector('.filter-unit[data-value=""]');
                        if (allBtn) {
                            allBtn.style.cssText = inactiveStyle;
                            allBtn.classList.remove('filter-active');
                        }
                        selectedUnitId = value;
                        unitBtns.forEach(b => {
                            if (b.dataset.value === value) {
                                b.style.cssText = activeStyle;
                                b.classList.add('filter-active');
                            } else {
                                b.style.cssText = inactiveStyle;
                                b.classList.remove('filter-active');
                            }
                        });
                    }
                    updateUI();
                    updateUnitVisibility();
                });
            });

            document.getElementById('applyFilter')?.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const url = new URL(window.location.href);

                if (selectedStatus) url.searchParams.set('status', selectedStatus);
                else url.searchParams.delete('status');

                if (selectedUnitId) url.searchParams.set('unit_id', selectedUnitId);
                else url.searchParams.delete('unit_id');

                url.searchParams.set('page', '1');
                window.history.pushState({}, '', url);
                fetchData(url);

                const dropdownToggle = document.getElementById('filterDropdown');
                const dropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (dropdown) dropdown.hide();
            });

            document.getElementById('resetFilter')?.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                selectedStatus = '';
                selectedUnitId = '';
                updateUI();

                unitBtns.forEach(b => {
                    b.style.cssText = inactiveStyle;
                    b.classList.remove('filter-active');
                });
                const allUnitBtn = document.querySelector('.filter-unit[data-value=""]');
                if (allUnitBtn) {
                    allUnitBtn.style.cssText = activeStyle;
                    allUnitBtn.classList.add('filter-active');
                }

                if (unitSearchInput) {
                    unitSearchInput.value = '';
                    updateUnitVisibility();
                }

                const url = new URL(window.location.href);
                url.searchParams.delete('status');
                url.searchParams.delete('unit_id');
                url.searchParams.set('page', '1');
                window.history.pushState({}, '', url);
                fetchData(url);
            });

            filterContainer.querySelector('.dropdown-menu')?.addEventListener('click', e => e.stopPropagation());
            updateUI();
        }

        function initializePagination() {
            document.addEventListener('click', function (e) {
                const paginationLink = e.target.closest('.pagination .page-link');
                if (paginationLink) {
                    e.preventDefault();
                    const url = new URL(paginationLink.href);
                    window.history.pushState({}, '', url);
                    fetchData(url);
                }
            });
        }

        function initializeSort() {
            document.addEventListener('click', function (e) {
                const sortLink = e.target.closest('#sortDropdown .dropdown-item');
                if (sortLink) {
                    e.preventDefault();
                    const url = new URL(sortLink.href);
                    window.history.pushState({}, '', url);
                    fetchData(url);

                    const dropdown = sortLink.closest('.dropdown');
                    const buttonTextSpan = dropdown.querySelector('button .fw-medium');
                    if (buttonTextSpan) {
                        buttonTextSpan.textContent = 'Sort: ' + sortLink.textContent.trim();
                    }

                    dropdown.querySelectorAll('.dropdown-item').forEach(function (item) {
                        item.classList.remove('active');
                    });
                    sortLink.classList.add('active');

                    const dropdownToggle = dropdown.querySelector('[data-bs-toggle="dropdown"]');
                    const bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                    if (bsDropdown) bsDropdown.hide();
                }
            });
        }

        function initializeDropdowns() {
            document.querySelectorAll('.dropdown-toggle-btn').forEach(btn => {
                const icon = btn.querySelector('.dropdown-icon');
                if (!icon) return;
                btn.addEventListener('show.bs.dropdown', () => icon.style.transform = 'rotate(180deg)');
                btn.addEventListener('hide.bs.dropdown', () => icon.style.transform = 'rotate(0)');
            });
        }

        function initializeAlerts() {
            document.querySelectorAll('.alert').forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        bootstrap.Alert.getOrCreateInstance(alert)?.close();
                    }
                }, 5000);
            });
        }

        function initializeGenerateModal() {
            const btnGenerate = document.getElementById('btnGenerateQr');
            const unitSelect = document.getElementById('generateUnitSelect');
            const errorBox = document.getElementById('generateModalError');
            const spinner = document.getElementById('generateSpinner');
            const btnText = document.getElementById('generateBtnText');
            const modalEl = document.getElementById('generateQrModal');

            if (!btnGenerate) return;

            btnGenerate.addEventListener('click', async function () {
                const unitId = unitSelect.value;
                errorBox.classList.add('d-none');

                if (!unitId) {
                    errorBox.textContent = 'Harap pilih unit terlebih dahulu.';
                    errorBox.classList.remove('d-none');
                    return;
                }

                btnGenerate.disabled = true;
                spinner.classList.remove('d-none');
                btnText.textContent = 'Generating...';

                try {
                    const response = await fetch(`{{ route("admin.qr-codes.generate") }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ unit_id: unitId })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        window.location.reload();
                    } else {
                        errorBox.textContent = data.message || 'Gagal generate QR Code.';
                        errorBox.classList.remove('d-none');
                    }
                } catch (err) {
                    errorBox.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                    errorBox.classList.remove('d-none');
                } finally {
                    btnGenerate.disabled = false;
                    spinner.classList.add('d-none');
                    btnText.textContent = 'Generate QR Code';
                }
            });

            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', function () {
                    unitSelect.value = '';
                    errorBox.classList.add('d-none');
                });
            }
        }
    </script>
@endpush