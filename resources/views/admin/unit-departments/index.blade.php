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
            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                <x-admin.search-button placeholder="Search by name, code or description..." />

                <div class="d-flex align-items-center gap-2 flex-wrap">
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
                            $activeStyle =
                                'background: #f8773c !important; border: none !important; color: white !important;';
                            $inactiveStyle =
                                'background: white !important; border: 1px solid #d1d5db !important; color: #6b7280 !important;';
                            $currentStatus = request('status') ? explode(',', request('status')) : [];
                        @endphp
                        <div class="dropdown-menu border-0 shadow-lg rounded-4 mt-2"
                            style="min-width: 300px; background-color: #ffffff;">
                            <div class="p-3">
                                <div class="mb-1">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Status</label>
                                    <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value=""
                                            style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            All
                                        </button>
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value="active"
                                            style="{{ in_array('active', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Active
                                        </button>
                                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                                            data-value="inactive"
                                            style="{{ in_array('inactive', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Inactive
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="p-3 border-top d-flex gap-2 bg-white">
                                <button type="button" id="resetFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center"
                                    style="height: 40px; background: white; border: 1px solid #d1d5db; color: #4b5563;">
                                    Reset
                                </button>
                                <button type="button" id="applyFilter" class="btn btn-sm rounded-pill w-100 fw-semibold"
                                    style="height: 40px; background: #f8773c; border: none; color: white;">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <x-admin.sort-button :sortOptions="[
                        'name_asc' => 'Name A-Z',
                        'name_desc' => 'Name Z-A',
                        'created_at_desc' => 'Newest First',
                        'created_at_asc' => 'Oldest First',
                    ]" defaultSort="name_asc" defaultOrder="asc" />

                    <x-admin.button-create url="{{ route('admin.unit-departments.create') }}" tooltip="Add New Department"
                        size="md">
                        Add Department
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
                        <tr class="text-muted" style="font-size: .75rem;">
                            <th class="ps-4 py-3 fw-semibold">Name</th>
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

            <div class="px-4 py-3" id="unitDepartmentsPaginationContainer">
                @include('admin.unit-departments.partials.pagination', ['paginator' => $departments])
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
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    initializeSearch();
    initializeFilters();
    initializePagination();
    initializeSort();
    initializeAlerts();
    initializeDropdowns();
    initializeStatusToggle();
});

function generateSkeletonRows(count) {
    let html = '';
    for (let i = 0; i < count; i++) {
        html += `
            <tr>
                <td class="ps-4"><div class="skeleton" style="width: 160px; height: 16px;"></div></td>
                <td><div class="skeleton" style="width: 60px; height: 24px; border-radius: 12px;"></div></td>
                <td><div class="skeleton" style="width: 70px; height: 24px; border-radius: 12px;"></div></td>
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
    return `<div class="d-flex justify-content-center py-3"><div class="skeleton" style="width:250px;height:40px;"></div></div>`;
}

function fetchData(url) {
    const tableBody = document.getElementById('unitTypesTable') || document.getElementById('unitDepartmentsTable') || document.getElementById('facilitiesTable');
    const paginationContainer = document.getElementById('unitTypesPaginationContainer') || document.getElementById('unitDepartmentsPaginationContainer') || document.getElementById('facilitiesPaginationContainer');

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

    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            clearTimeout(searchTimeout);
            performSearch();
        }
    });

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(performSearch, 800);
    });
}

function initializeFilters() {
    const filterContainer = document.getElementById('filterContainer');
    if (!filterContainer) return;

    const params = new URLSearchParams(window.location.search);
    let selectedStatus = params.get('status') || '';

    const activeStyle = 'background:#f8773c;border:none;color:white;';
    const inactiveStyle = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

    function updateUI() {
        filterContainer.querySelectorAll('.filter-status').forEach(btn => {
            const value = btn.dataset.value;
            btn.style.cssText = value === selectedStatus ? activeStyle : inactiveStyle;
        });

        const filterText = document.getElementById('filterText');
        const filterBtn = document.getElementById('filterDropdown');

        if (filterBtn && filterText) {
            filterBtn.querySelector('.filter-badge')?.remove();
            if (selectedStatus) {
                const label = { 'active': 'Active', 'inactive': 'Inactive' }[selectedStatus] || selectedStatus;
                filterText.textContent = `Filter: ${label}`;
                const badge = document.createElement('span');
                badge.className = 'filter-badge';
                badge.style.cssText = 'width:8px;height:8px;background:#f8773c;border-radius:50%;margin-left:6px;display:inline-block;';
                filterBtn.appendChild(badge);
            } else {
                filterText.textContent = 'Filter';
            }
        }
    }

    filterContainer.querySelectorAll('.filter-status').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            selectedStatus = this.dataset.value;
            updateUI();
        });
    });

    document.getElementById('applyFilter')?.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const url = new URL(window.location.href);
        if (selectedStatus) {
            url.searchParams.set('status', selectedStatus);
        } else {
            url.searchParams.delete('status');
        }
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
        updateUI();
        const url = new URL(window.location.href);
        url.searchParams.delete('status');
        url.searchParams.set('page', '1');
        window.history.pushState({}, '', url);
        fetchData(url);
    });

    filterContainer.querySelector('.dropdown-menu')?.addEventListener('click', e => e.stopPropagation());
    updateUI();
}

function initializePagination() {
    document.addEventListener('click', function(e) {
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
    document.addEventListener('click', function(e) {
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

            dropdown.querySelectorAll('.dropdown-item').forEach(function(item) {
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

function initializeStatusToggle() {
    document.querySelectorAll('.status-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const isActive = this.dataset.active === 'true';
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            let baseUrl = window.location.pathname;
            if (baseUrl.endsWith('/')) baseUrl = baseUrl.slice(0, -1);
            const endpoint = `${baseUrl}/${id}/toggle-status`;

            fetch(endpoint, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                return res.json();
            })
            .then(data => {
                if (!data.success) return;
                const newActive = !isActive;
                this.dataset.active = String(newActive);
                const badge = document.getElementById(`status_badge_${id}`);

                if (newActive) {
                    this.classList.replace('btn-outline-secondary', 'btn-success');
                    this.textContent = 'Active';
                    if (badge) {
                        badge.className = 'badge rounded-pill px-3 ms-2 bg-success-subtle text-success';
                        badge.textContent = 'Active';
                    }
                } else {
                    this.classList.replace('btn-success', 'btn-outline-secondary');
                    this.textContent = 'Inactive';
                    if (badge) {
                        badge.className = 'badge rounded-pill px-3 ms-2 bg-danger-subtle text-danger';
                        badge.textContent = 'Inactive';
                    }
                }
            })
            .catch(err => console.error('Toggle status error:', err));
        });
    });
}
</script>
@endpush
