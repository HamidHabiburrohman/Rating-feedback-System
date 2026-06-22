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
                    <x-admin.sort-button :sortOptions="[
                        'name_asc' => 'Name A-Z',
                        'name_desc' => 'Name Z-A',
                        'created_at_desc' => 'Newest First',
                        'created_at_asc' => 'Oldest First',
                        'units_count_desc' => 'Most Used',
                    ]" defaultSort="name" defaultOrder="asc" />

                    <x-admin.button-create url="{{ route('admin.facilities.create') }}" tooltip="Add New Facility"
                        size="md">
                        Add Facility
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

            <div class="px-4 py-3" id="facilitiesPaginationContainer">
                @include('admin.facilities.partials.pagination', ['paginator' => $facilities])
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

        .skeleton-circle {
            border-radius: 50%;
        }

        @keyframes skeleton-shimmer {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            initializeSearch();
            initializePagination();
            initializeSort();
            initializeDropdownToggles();
            initializeAlerts();
        });

        function generateSkeletonRows(count) {
            let html = '';
            for (let i = 0; i < count; i++) {
                html += `
            <tr>
                <td class="ps-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="skeleton skeleton-circle" style="width: 40px; height: 40px;"></div>
                        <div class="skeleton skeleton-text" style="width: 160px; height: 16px;"></div>
                    </div>
                </td>
                <td>
                    <div class="skeleton skeleton-badge" style="width: 80px; height: 26px;"></div>
                </td>
                <td>
                    <div class="skeleton skeleton-badge" style="width: 90px; height: 26px;"></div>
                </td>
                <td class="text-center pe-4">
                    <div class="d-flex justify-content-center gap-2">
                        <div class="skeleton skeleton-btn" style="width: 34px; height: 34px;"></div>
                        <div class="skeleton skeleton-btn" style="width: 34px; height: 34px;"></div>
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
            const tableBody = document.getElementById('facilitiesTable');
            const paginationContainer = document.getElementById('facilitiesPaginationContainer');

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
                    tableBody.innerHTML =
                        '<tr><td colspan="4" class="text-center py-5 text-danger">Failed to load data.</td></tr>';
                });
        }

        function initializeTooltips() {
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                if (!bootstrap.Tooltip.getInstance(el)) new bootstrap.Tooltip(el);
            });
        }

        function initializeSearch() {
            const searchInput = document.getElementById('searchInput');
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

        function initializeDropdownToggles() {
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
