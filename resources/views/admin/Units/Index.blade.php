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
         <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
             <x-admin.search-button placeholder="Search by name, code or location..." />

             <div class="d-flex align-items-center gap-2 flex-wrap">
                 <div class="dropdown" id="perPageDropdown">
                     <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
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
                     <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
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
                        $currentTypes = request('type') ? explode(',', request('type')) : [];
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
                                        data-value="open"
                                        style="{{ $currentStatus === 'open' ? $activeStyle : $inactiveStyle }}">Open</button>
                                     <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="closed"
                                        style="{{ $currentStatus === 'closed' ? $activeStyle : $inactiveStyle }}">Closed</button>
                                     <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                        data-value="maintenance"
                                        style="{{ $currentStatus === 'maintenance' ? $activeStyle : $inactiveStyle }}">Maintenance</button>
                                 </div>
                             </div>

                             <div class="mb-3">
                                 <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Unit Type</label>
                                 <div class="position-relative mb-2">
                                     <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="#9ca3af" stroke-width="2"
                                        style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);">
                                         <circle cx="11" cy="11" r="8"></circle>
                                         <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                     </svg>
                                     <input type="text" id="typeSearchInput" class="form-control form-control-sm"
                                        placeholder="Search type..."
                                        style="border-radius: 8px; border: 1px solid #d1d5db; font-size: 13px; padding: 8px 12px 8px 32px;">
                                 </div>
                                 <div style="max-height: 180px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; background: #fafafa;">
                                     <div class="d-flex flex-wrap gap-2" id="typeFilter">
                                         <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium filter-type type-btn {{ empty($currentTypes) ? 'filter-active' : '' }}"
                                            data-value="" data-name="all"
                                            style="{{ empty($currentTypes) ? $activeStyle : $inactiveStyle }}">All</button>

                                        @foreach ($unitTypes as $unitType)
                                             <button type="button"
                                                class="btn btn-sm rounded-pill px-3 fw-medium filter-type type-btn {{ in_array((string) $unitType->id, $currentTypes) ? 'filter-active' : '' }}"
                                                data-value="{{ $unitType->id }}"
                                                data-name="{{ strtolower($unitType->name) }}"
                                                style="{{ in_array((string) $unitType->id, $currentTypes) ? $activeStyle : $inactiveStyle }}">{{ $unitType->name }}</button>
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
                                style="height: 40px; background: #f8773c; border: none; color: white;">Apply Filter</button>
                         </div>
                     </div>
                 </div>

                 <x-admin.sort-button :sortOptions="[
                    'name_asc' => 'Name A-Z',
                    'name_desc' => 'Name Z-A',
                    'created_at_desc' => 'Newest First',
                    'created_at_asc' => 'Oldest First',
                ]" defaultSort="name" defaultOrder="asc" />

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

     <div class="card border rounded-5 mt-4">
         <div class="table-responsive">
             <table class="table align-middle mb-0">
                 <thead class="bg-transparent">
                     <tr class="text-muted" style="font-size: .75rem;">
                         <th class="ps-4 py-3 fw-semibold">Unit</th>
                         <th class="py-3 fw-semibold">Type</th>
                         <th class="py-3 fw-semibold">Operational Hours</th>
                         <th class="py-3 fw-semibold">Status</th>
                         <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                     </tr>
                 </thead>
                 <tbody id="unitsTable">
                    @include('admin.units.partials.rows', ['units' => $units ?? collect()])
                 </tbody>
             </table>
         </div>
         <div class="px-4 py-3" id="unitsPaginationContainer">
            @include('admin.units.partials.pagination', ['paginator' => $units ?? collect()])
         </div>
     </div>
 </div>
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
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
.time-range-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.375rem 0.75rem;
    background: var(--color-bg, #f8fafc);
    border: 1px solid var(--color-border, rgba(15, 23, 42, 0.06));
    border-radius: 9999px;
    font-size: 0.8125rem;
    font-weight: 500;
    color: #334155;
    font-variant-numeric: tabular-nums;
    font-family: 'SF Mono', ui-monospace, 'Plus Jakarta Sans', monospace;
    letter-spacing: 0.01em;
}
.time-icon { color: var(--color-primary, #f8773c); flex-shrink: 0; }
.time-text { display: inline-flex; align-items: center; gap: 0.375rem; }
.time-separator { color: #94a3b8; font-weight: 400; }
.time-single {
    display: inline-block;
    padding: 0.25rem 0.625rem;
    background: #fff5f0;
    color: var(--color-primary, #f8773c);
    border-radius: 9999px;
    font-size: 0.8125rem;
    font-weight: 500;
    font-variant-numeric: tabular-nums;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    initializeSearch();
    initializeFilters();
    initializePerPage();
    initializePagination();
    initializeSort();
    initializeAlerts();
    initializeDropdowns();
});

function generateSkeletonRows(count) {
    let html = '';
    for (let i = 0; i < count; i++) {
        html += `
         <tr>
             <td class="ps-4"><div class="d-flex flex-column gap-1"><div class="skeleton" style="width: 160px; height: 16px;"></div></div></td>
             <td><div class="skeleton" style="width: 100px; height: 24px; border-radius: 12px;"></div></td>
             <td><div class="skeleton" style="width: 120px; height: 24px; border-radius: 12px;"></div></td>
             <td><div class="skeleton" style="width: 70px; height: 24px; border-radius: 12px;"></div></td>
             <td class="text-center pe-4">
                 <div class="d-flex justify-content-center gap-2">
                     <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                     <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                     <div class="skeleton" style="width: 34px; height: 34px; border-radius: 8px;"></div>
                 </div>
             </td>
         </tr>`;
    }
    return html;
}

function generateSkeletonPagination() {
    return `<div class="d-flex justify-content-start py-3"><div class="skeleton" style="width:250px;height:40px;"></div></div>`;
}

function fetchData(url) {
    const tableBody = document.getElementById('unitsTable');
    const paginationContainer = document.getElementById('unitsPaginationContainer');
    if (!tableBody || !paginationContainer) return;

    tableBody.innerHTML = generateSkeletonRows(10);
    paginationContainer.innerHTML = generateSkeletonPagination();

    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
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
    let selectedTypes = params.get('type') ? params.get('type').split(',').filter(s => s !== '') : [];

    const activeStyle = 'background:#f8773c;border:none;color:white;';
    const inactiveStyle = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

    const typeBtns = document.querySelectorAll('.type-btn');
    const typeSearchInput = document.getElementById('typeSearchInput');

    function updateTypeVisibility() {
        if (!typeSearchInput) return;
        const searchTerm = typeSearchInput.value.toLowerCase();
        let visibleCount = 0;
        typeBtns.forEach(btn => {
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

        filterContainer.querySelectorAll('.filter-type').forEach(btn => {
            const value = btn.dataset.value;
            const isActive = (value === '' && selectedTypes.length === 0) || selectedTypes.includes(value);
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
            if (selectedTypes.length > 0) filterCount++;

            if (filterCount > 0) {
                const parts = [];
                if (selectedStatus) parts.push(selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1));
                if (selectedTypes.length > 0) parts.push('Type');
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

    if (typeSearchInput) {
        typeSearchInput.addEventListener('input', function (e) {
            e.stopPropagation();
            updateTypeVisibility();
        });
        updateTypeVisibility();
    }

    filterContainer.querySelectorAll('.filter-status').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            selectedStatus = this.dataset.value;
            updateUI();
        });
    });

    filterContainer.querySelectorAll('.filter-type').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const value = this.dataset.value;
            
            if (value === '') {
                selectedTypes = [];
                typeBtns.forEach(b => {
                    b.style.cssText = inactiveStyle;
                    b.classList.remove('filter-active');
                });
                this.style.cssText = activeStyle;
                this.classList.add('filter-active');
            } else {
                const allBtn = document.querySelector('.filter-type[data-value=""]');
                if (allBtn) {
                    allBtn.style.cssText = inactiveStyle;
                    allBtn.classList.remove('filter-active');
                }
                let selected = selectedTypes.length ? [...selectedTypes] : [];
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
                selectedTypes = selected;
            }
            updateUI();
            updateTypeVisibility();
        });
    });

    document.getElementById('applyFilter')?.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const url = new URL(window.location.href);

        if (selectedStatus) url.searchParams.set('status', selectedStatus);
        else url.searchParams.delete('status');
        
        if (selectedTypes.length > 0) url.searchParams.set('type', selectedTypes.join(','));
        else url.searchParams.delete('type');

        url.searchParams.set('page', '1');
        window.history.pushState({}, '', url);
        fetchData(url);

        const dropdownToggle = document.getElementById('filterDropdown');
        const dropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
        if (dropdown) dropdown.hide();
    });

    document.getElementById('resetFilter')?.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        selectedStatus = '';
        selectedTypes = [];
        updateUI();

        typeBtns.forEach(b => {
            b.style.cssText = inactiveStyle;
            b.classList.remove('filter-active');
        });
        const allTypeBtn = document.querySelector('.filter-type[data-value=""]');
        if (allTypeBtn) {
            allTypeBtn.style.cssText = activeStyle;
            allTypeBtn.classList.add('filter-active');
        }

        if (typeSearchInput) {
            typeSearchInput.value = '';
            updateTypeVisibility();
        }

        const url = new URL(window.location.href);
        url.searchParams.delete('status');
        url.searchParams.delete('type');
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
</script>
@endpush