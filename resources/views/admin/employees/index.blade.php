@extends('layouts.admin.app')

@section('title', 'Employees Management')

@push('styles')
    <style>
        .skeleton-avatar {
            background: #e5e7eb;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        .skeleton-text {
            background: #e5e7eb;
            border-radius: 4px;
            height: 14px;
            animation: pulse 1.5s infinite;
        }

        .skeleton-btn {
            background: #e5e7eb;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }
    </style>
@endpush

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Employees Management</h1>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <x-admin.search-button placeholder="Search employees..." />

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <div class="dropdown" id="perPageDropdown">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown"
                            style="height: 44px; background-color: white; border-color: #d1d5db;">
                            <span class="fw-medium" id="perPageText">{{ request('per_page', 10) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" class="dropdown-icon"
                                style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3">
                            @foreach ([10, 25, 50, 100] as $size)
                                <li>
                                    <a class="dropdown-item ajax-per-page {{ request('per_page', 10) == $size ? 'active fw-bold' : '' }}"
                                        href="javascript:void(0)" data-per-page="{{ $size }}"
                                        style="{{ request('per_page', 10) == $size ? 'background: #f8773c; color: white;' : '' }}">
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

                        <div class="dropdown-menu border-0 shadow-lg rounded-4 mt-2"
                            style="min-width: 380px; background-color: #ffffff;">
                            <div class="p-3">
                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Assigned Unit</label>
                                    <div class="position-relative mb-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"
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
                                            @php
                                                $activeStyle =
                                                    'background: #f8773c !important; border: none !important; color: white !important;';
                                                $inactiveStyle =
                                                    'background: white !important; border: 1px solid #d1d5db !important; color: #6b7280 !important;';
                                                $currentUnits = request('unit_id')
                                                    ? explode(',', request('unit_id'))
                                                    : [];
                                            @endphp
                                            <button type="button"
                                                class="btn btn-sm rounded-pill px-3 fw-medium filter-unit unit-btn {{ empty($currentUnits) ? 'filter-active' : '' }}"
                                                data-value="" data-name="all"
                                                style="{{ empty($currentUnits) ? $activeStyle : $inactiveStyle }}">All</button>

                                            @foreach ($units as $unit)
                                                <button type="button"
                                                    class="btn btn-sm rounded-pill px-3 fw-medium filter-unit unit-btn {{ in_array($unit->id, $currentUnits) ? 'filter-active' : '' }}"
                                                    data-value="{{ $unit->id }}"
                                                    data-name="{{ strtolower($unit->name) }}"
                                                    style="{{ in_array($unit->id, $currentUnits) ? $activeStyle : $inactiveStyle }}">{{ $unit->name }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-1">
                                    <label class="small fw-bold text-uppercase mb-2 mt-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Status</label>
                                    <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                        @php $currentStatus = request('status') ? explode(',', request('status')) : []; @endphp
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-status {{ empty($currentStatus) ? 'filter-active' : '' }}"
                                            data-value=""
                                            style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">All</button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-status {{ in_array('active', $currentStatus) ? 'filter-active' : '' }}"
                                            data-value="active"
                                            style="{{ in_array('active', $currentStatus) ? $activeStyle : $inactiveStyle }}">Active</button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-status {{ in_array('inactive', $currentStatus) ? 'filter-active' : '' }}"
                                            data-value="inactive"
                                            style="{{ in_array('inactive', $currentStatus) ? $activeStyle : $inactiveStyle }}">Inactive</button>
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

                    <div class="dropdown" id="sortDropdown">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown"
                            style="height: 44px; background: white; border-color: #d1d5db;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M6 12h12M9 18h6" />
                            </svg>
                            <span class="fw-medium"
                                id="sortText">{{ request('sort', 'Name A-Z') == 'name_asc' ? 'Name A-Z' : (request('sort') == 'name_desc' ? 'Name Z-A' : (request('sort') == 'created_at_desc' ? 'Newest First' : (request('sort') == 'created_at_asc' ? 'Oldest First' : 'Name A-Z'))) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" class="dropdown-icon"
                                style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3">
                            <li><a class="dropdown-item sort-option" data-sort="name_asc" href="javascript:void(0)">Name
                                    A-Z</a></li>
                            <li><a class="dropdown-item sort-option" data-sort="name_desc" href="javascript:void(0)">Name
                                    Z-A</a></li>
                            <li><a class="dropdown-item sort-option" data-sort="created_at_desc"
                                    href="javascript:void(0)">Newest First</a></li>
                            <li><a class="dropdown-item sort-option" data-sort="created_at_asc"
                                    href="javascript:void(0)">Oldest First</a></li>
                        </ul>
                    </div>

                    <x-admin.button-create url="{{ route('admin.employees.create') }}" tooltip="Add New Employee"
                        size="md">Add Employee</x-admin.button-create>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 d-flex align-items-center"
                role="alert">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" class="me-2">
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
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2" class="me-2">
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
                            <th class="ps-4 py-3 fw-light">Employee</th>
                            <th class="py-3 fw-light">Email</th>
                            <th class="py-3 fw-light">Assigned Units</th>
                            <th class="py-3 fw-light">Status</th>
                            <th class="pe-4 py-3 fw-light text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeesTable">
                        @include('admin.employees.partials.rows', ['employees' => $employees])
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-3" id="paginationWrapper">
                @include('admin.employees.partials.pagination', ['paginator' => $employees])
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterContainer = document.getElementById('filterContainer');
            const filterDropdownBtn = document.getElementById('filterDropdown');
            const unitBtns = document.querySelectorAll('.unit-btn');
            const unitSearchInput = document.getElementById('unitSearchInput');
            const applyFilterBtn = document.getElementById('applyFilter');
            const resetFilterBtn = document.getElementById('resetFilter');
            const searchInput = document.getElementById('searchInput');
            const tbody = document.getElementById('employeesTable');
            const paginationWrapper = document.getElementById('paginationWrapper');

            const activeStyle = 'background: #f8773c !important; border: none !important; color: white !important;';
            const inactiveStyle =
                'background: white !important; border: 1px solid #d1d5db !important; color: #6b7280 !important;';

            let filters = {
                search: new URLSearchParams(window.location.search).get('search') || '',
                unit_id: '{{ request('unit_id', '') }}',
                status: '{{ request('status', '') }}',
                sort: '{{ request('sort', 'name_asc') }}',
                per_page: '{{ request('per_page', 10) }}',
                page: 1
            };

            let searchTimeout;

            function showSkeleton() {
                let skeleton = '';
                const rows = parseInt(filters.per_page) || 10;
                for (let i = 0; i < rows; i++) {
                    skeleton += `
                <tr>
                    <td class="ps-4"><div class="d-flex align-items-center gap-3"><div class="skeleton-avatar" style="width:40px;height:40px;"></div><div class="skeleton-text" style="width: 120px;"></div></div></td>
                    <td><div class="skeleton-text" style="width: 150px;"></div></td>
                    <td><div class="skeleton-text" style="width: 100px;"></div></td>
                    <td><div class="skeleton-text" style="width: 80px;"></div></td>
                    <td class="text-center pe-4"><div class="d-flex justify-content-center gap-1"><div class="skeleton-btn"></div><div class="skeleton-btn"></div><div class="skeleton-btn"></div></div></td>
                </tr>`;
                }
                tbody.innerHTML = skeleton;
            }

            function fetchEmployees() {
                showSkeleton();
                const params = new URLSearchParams();
                Object.keys(filters).forEach(key => {
                    if (filters[key] !== '' && filters[key] !== null) {
                        params.append(key, filters[key]);
                    }
                });

                fetch(`{{ route('admin.employees.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        tbody.innerHTML = data.html;
                        paginationWrapper.innerHTML = data.pagination;
                        bindPaginationEvents();
                    })
                    .catch(err => {
                        console.error('Error fetching employees:', err);
                        tbody.innerHTML =
                            `<tr><td colspan="5" class="text-center py-5 text-danger">Failed to load data</td></tr>`;
                    });
            }

            function bindPaginationEvents() {
                document.querySelectorAll('.ajax-page-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = this.dataset.page;
                        if (page && page != filters.page) {
                            filters.page = page;
                            fetchEmployees();
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        }
                    });
                });

                document.querySelectorAll('.ajax-per-page').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        filters.per_page = this.dataset.perPage;
                        filters.page = 1;
                        document.getElementById('perPageText').textContent = filters.per_page;
                        fetchEmployees();
                    });
                });
            }

            function updateUnitVisibility() {
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

            function setupDropdownIconRotation(dropdownId) {
                const dropdown = document.getElementById(dropdownId);
                if (!dropdown) return;
                const btn = dropdown.querySelector('.dropdown-toggle-btn');
                const icon = dropdown.querySelector('.dropdown-icon');
                if (!btn || !icon) return;

                dropdown.addEventListener('show.bs.dropdown', () => {
                    icon.style.transform = 'rotate(180deg)';
                });
                dropdown.addEventListener('hide.bs.dropdown', () => {
                    icon.style.transform = 'rotate(0)';
                });
            }

            setupDropdownIconRotation('perPageDropdown');
            setupDropdownIconRotation('filterContainer');
            setupDropdownIconRotation('sortDropdown');

            if (filterContainer) {
                filterContainer.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            if (unitSearchInput) {
                unitSearchInput.addEventListener('input', function(e) {
                    e.stopPropagation();
                    updateUnitVisibility();
                });
                updateUnitVisibility();
            }

            document.querySelectorAll('.filter-unit, .filter-status').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const value = this.dataset.value;
                    const isUnit = this.classList.contains('unit-btn');

                    if (isUnit) {
                        if (value === '') {
                            filters.unit_id = '';
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
                            let selected = filters.unit_id ? filters.unit_id.split(',').filter(
                                Boolean) : [];
                            const idx = selected.indexOf(value);
                            if (idx > -1) {
                                selected.splice(idx, 1);
                                this.style.cssText = inactiveStyle;
                                this.classList.remove('filter-active');
                            } else {
                                selected.push(value);
                                this.style.cssText = activeStyle;
                                this.classList.add('filter-active');
                            }
                            filters.unit_id = selected.join(',');
                        }
                    } else {
                        filters.status = value;
                        document.querySelectorAll('.filter-status').forEach(b => {
                            b.style.cssText = inactiveStyle;
                            b.classList.remove('filter-active');
                        });
                        this.style.cssText = activeStyle;
                        this.classList.add('filter-active');
                    }
                });
            });

            if (applyFilterBtn) {
                applyFilterBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    filters.page = 1;
                    fetchEmployees();
                    const dropdownInstance = bootstrap.Dropdown.getInstance(filterDropdownBtn);
                    if (dropdownInstance) dropdownInstance.hide();
                });
            }

            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    filters.unit_id = '';
                    filters.status = '';
                    filters.page = 1;

                    document.querySelectorAll('.filter-unit, .filter-status').forEach(b => {
                        b.style.cssText = inactiveStyle;
                        b.classList.remove('filter-active');
                    });
                    const allUnitBtn = document.querySelector('.filter-unit[data-value=""]');
                    if (allUnitBtn) {
                        allUnitBtn.style.cssText = activeStyle;
                        allUnitBtn.classList.add('filter-active');
                    }
                    const allStatusBtn = document.querySelector('.filter-status[data-value=""]');
                    if (allStatusBtn) {
                        allStatusBtn.style.cssText = activeStyle;
                        allStatusBtn.classList.add('filter-active');
                    }

                    if (unitSearchInput) {
                        unitSearchInput.value = '';
                        updateUnitVisibility();
                    }

                    fetchEmployees();
                    const dropdownInstance = bootstrap.Dropdown.getInstance(filterDropdownBtn);
                    if (dropdownInstance) dropdownInstance.hide();
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        filters.search = this.value;
                        filters.page = 1;
                        fetchEmployees();
                    }, 500);
                });
            }

            document.querySelectorAll('.sort-option').forEach(opt => {
                opt.addEventListener('click', function(e) {
                    e.preventDefault();
                    filters.sort = this.dataset.sort;
                    filters.page = 1;
                    document.getElementById('sortText').textContent = this.textContent;
                    fetchEmployees();
                });
            });

            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert.classList.contains('show')) {
                        const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                        if (bsAlert) bsAlert.close();
                    }
                }, 5000);
            });

            bindPaginationEvents();
        });
    </script>
@endpush
