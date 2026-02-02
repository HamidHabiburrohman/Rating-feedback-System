@extends('layouts.admin.app')

@section('title','Reports management')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Reports</h1>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <div class="position-relative" style="flex: 1; max-width: 500px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="#6b7280" stroke-width="2" class="position-absolute top-50 translate-middle-y ms-3"
                        style="left: 0; z-index: 10;">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input type="text" id="searchInput" class="form-control ps-5 rounded-pill border shadow-sm"
                        placeholder="Search by title or description..." value="{{ request('search') }}"
                        style="height: 44px; background-color: white; border-color: #d1d5db !important;">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown"
                            style="height:44px;background:white;border-color:#d1d5db;">
                            <span class="fw-medium">
                                {{ request('per_page', 10) }}
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
                            @foreach ([10, 25, 50, 100] as $size)
                                <li>
                                    <a class="dropdown-item py-2 px-3 {{ request('per_page', 10) == $size ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}">
                                        {{ $size }} Rows
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="dropdown" id="filterContainer">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm dropdown-toggle-btn"
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
                            style="min-width: 300px; background-color: #ffffff;">
                            <div class="p-3">
                                <div class="mb-4">
                                    <label class="small fw-bold text-uppercase mb-2 mt-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Status Report</label>
                                    <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                        @php
                                            $activeStyle = 'background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; color: white;';
                                            $inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
                                            $currentStatus = request('status') ? explode(',', request('status')) : [];
                                        @endphp
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value=""
                                            style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            All
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value="baru"
                                            style="{{ in_array('baru', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            New
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value="diproses"
                                            style="{{ in_array('diproses', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Processing
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value="selesai"
                                            style="{{ in_array('selesai', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Completed
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                            data-value="ditolak"
                                            style="{{ in_array('ditolak', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                            Rejected
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Type</label>
                                    <div class="d-flex flex-wrap gap-2" id="typeFilter">
                                        @php
                                            $currentType = request('tipe') ? explode(',', request('tipe')) : [];
                                        @endphp
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-tipe"
                                            data-value="" style="{{ empty($currentType) ? $activeStyle : $inactiveStyle }}">
                                            All
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-tipe"
                                            data-value="masalah"
                                            style="{{ in_array('masalah', $currentType) ? $activeStyle : $inactiveStyle }}">
                                            Problem
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-tipe"
                                            data-value="saran"
                                            style="{{ in_array('saran', $currentType) ? $activeStyle : $inactiveStyle }}">
                                            Suggestion
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-tipe"
                                            data-value="keluhan"
                                            style="{{ in_array('keluhan', $currentType) ? $activeStyle : $inactiveStyle }}">
                                            Complaint
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-tipe"
                                            data-value="pujian"
                                            style="{{ in_array('pujian', $currentType) ? $activeStyle : $inactiveStyle }}">
                                            Praise
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-tipe"
                                            data-value="lainnya"
                                            style="{{ in_array('lainnya', $currentType) ? $activeStyle : $inactiveStyle }}">
                                            Other
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Priority</label>
                                    <div class="d-flex flex-wrap gap-2" id="priorityFilter">
                                        @php
                                            $currentPriority = request('prioritas') ? explode(',', request('prioritas')) : [];
                                        @endphp
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-prioritas"
                                            data-value=""
                                            style="{{ empty($currentPriority) ? $activeStyle : $inactiveStyle }}">
                                            All
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-prioritas"
                                            data-value="rendah"
                                            style="{{ in_array('rendah', $currentPriority) ? $activeStyle : $inactiveStyle }}">
                                            Low
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-prioritas"
                                            data-value="sedang"
                                            style="{{ in_array('sedang', $currentPriority) ? $activeStyle : $inactiveStyle }}">
                                            Medium
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-prioritas"
                                            data-value="tinggi"
                                            style="{{ in_array('tinggi', $currentPriority) ? $activeStyle : $inactiveStyle }}">
                                            High
                                        </button>
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-prioritas"
                                            data-value="kritis"
                                            style="{{ in_array('kritis', $currentPriority) ? $activeStyle : $inactiveStyle }}">
                                            Critical
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Date Range</label>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <input type="date" id="dateFrom" class="form-control form-control-sm rounded"
                                                value="{{ request('date_from') }}" placeholder="From">
                                        </div>
                                        <div class="col-6">
                                            <input type="date" id="dateTo" class="form-control form-control-sm rounded"
                                                value="{{ request('date_to') }}" placeholder="To">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 border-top d-flex gap-2 bg-white">
                                @php
                                    $resetStyle = 'background: white; border: 1px solid #d1d5db; color: #4b5563;';
                                    $applyStyle = 'background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; color: white;';
                                @endphp
                                <button type="button" id="resetFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center shadow-sm"
                                    style="height: 40px; {{ $resetStyle }} transition: all 0.2s;">
                                    Reset
                                </button>
                                <button type="button" id="applyFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold shadow-sm"
                                    style="height: 40px; {{ $applyStyle }} transition: all 0.2s;">
                                    Apply Filter
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false" id="filterDropdown"
                            style="height: 44px; background-color: white; border-color: #d1d5db;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                <polyline points="7 10 12 15 17 10" />
                                <line x1="12" y1="15" x2="12" y2="3" />
                            </svg>
                            <span class="fw-medium">Export</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark" href="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10 9 9 9 8 9" />
                                    </svg>
                                    PDF Report
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark" href="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14 2 14 8 20 8" />
                                        <path d="M16 13H8" />
                                        <path d="M16 17H8" />
                                        <path d="M10 9H9H8" />
                                    </svg>
                                    Excel Sheet
                                </a>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('admin.reports.create') }}"
                        class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2 shadow-sm"
                        style="height: 44px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="white" stroke-width="2.5">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        <span class="fw-medium">Create Report</span>
                    </a>
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

        <div class="card border-1 rounded-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-transparent">
                        <tr class="text-muted text-uppercase" style="font-size: .75rem;">
                            <th class="ps-4 py-3 fw-semibold">Title & Unit</th>
                            <th class="py-3 fw-semibold">Description</th>
                            <th class="py-3 fw-semibold">Status</th>
                            <th class="pe-4 py-3 fw-semibold text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $reports = $reports ?? collect();
                        @endphp

                        @if($reports->count())
                            @include('admin.reports.partials.rows', ['reports' => $reports])
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    No reports found
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3">
                @include('admin.reports.partials.pagination', ['reports' => $reports])
            </div>
        </div>
    </div>

    <style>
        .btn {
            transition: all 0.2s ease;
            border-radius: 50px;
            font-weight: 500;
        }

        .btn-filter,
        .btn-export,
        .btn-per-page {
            color: #374151 !important;
            background-color: white;
            border: 1px solid #d1d5db;
        }

        .btn-filter:hover,
        .btn-export:hover,
        .btn-per-page:hover,
        .btn-filter:active,
        .btn-export:active,
        .btn-per-page:active,
        .btn-filter:focus,
        .btn-export:focus,
        .btn-per-page:focus,
        .btn-filter.show,
        .btn-export.show,
        .btn-per-page.show {
            color: #374151 !important;
            background-color: #f9fafb !important;
            border-color: #9ca3af !important;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.3);
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        #searchInput {
            border-color: #d1d5db !important;
            transition: all 0.2s ease;
        }

        #searchInput:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
            outline: none;
        }

        .dropdown-menu {
            border: 1px solid #e5e7eb;
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
            opacity: 0;
            transform: translateY(-10px);
            transition: opacity 0.2s ease, transform 0.2s ease;
            display: block;
            pointer-events: none;
        }

        .dropdown-menu.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(59, 130, 246, 0.02);
            --bs-table-hover-bg: rgba(59, 130, 246, 0.04);
        }

        .table> :not(:first-child) {
            border-top: 2px solid #e5e7eb;
        }

        .card {
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .dropdown-item {
            color: #374151 !important;
            transition: all 0.15s ease;
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            color: #1f2937 !important;
            background-color: #f9fafb;
            transform: translateX(2px);
        }

        .dropdown-item.active {
            background-color: #f3f4f6 !important;
            color: #2563eb !important;
        }

        .btn-filter.show,
        .btn-export.show,
        .btn-per-page.show {
            background-color: #f3f4f6 !important;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let status = new Set();
            let tipe = new Set();
            let prioritas = new Set();
            let unit = new Set();
            const params = new URLSearchParams(location.search);

            initState();
            initIcons();
            bind();
            updateLabel();

            function initState() {
                const s = params.get('status');
                const t = params.get('tipe');
                const p = params.get('prioritas');
                const u = params.get('unit');
                if (s) s.split(',').forEach(v => v && status.add(v));
                if (t) t.split(',').forEach(v => v && tipe.add(v));
                if (p) p.split(',').forEach(v => v && prioritas.add(v));
                if (u) u.split(',').forEach(v => v && unit.add(v));
                paint();
            }

            function initIcons() {
                document.querySelectorAll('.dropdown-toggle-btn').forEach(btn => {
                    const icon = btn.querySelector('.dropdown-icon');
                    if (!icon) return;
                    btn.addEventListener('show.bs.dropdown', () => {
                        icon.style.transform = 'rotate(180deg)';
                    });
                    btn.addEventListener('hide.bs.dropdown', () => {
                        icon.style.transform = 'rotate(0)';
                    });
                });
            }

            function bind() {
                document.querySelectorAll('.filter-status')
                    .forEach(b => b.addEventListener('click', e => toggle(e, status)));
                document.querySelectorAll('.filter-tipe')
                    .forEach(b => b.addEventListener('click', e => toggle(e, tipe)));
                document.querySelectorAll('.filter-prioritas')
                    .forEach(b => b.addEventListener('click', e => toggle(e, prioritas)));
                document.querySelectorAll('.filter-unit')
                    .forEach(b => b.addEventListener('click', e => toggle(e, unit)));
                document.getElementById('applyFilter')?.addEventListener('click', apply);
                document.getElementById('resetFilter')?.addEventListener('click', reset);

                const search = document.getElementById('searchInput');
                if (search) {
                    let t;
                    search.addEventListener('input', () => {
                        clearTimeout(t);
                        t = setTimeout(searchNow, 500);
                    });
                }
            }

            function toggle(e, set) {
                e.stopPropagation();
                const v = e.currentTarget.dataset.value;
                v === '' ? set.clear() :
                    set.has(v) ? set.delete(v) :
                        set.add(v);
                paint();
            }

            function paint() {
                const on = 'background:linear-gradient(135deg,#3b82f6,#1d4ed8);border:none;color:white;';
                const off = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

                document.querySelectorAll('.filter-status').forEach(b => {
                    const v = b.dataset.value;
                    b.style = v === '' ? (status.size ? off : on) : (status.has(v) ? on : off);
                });

                document.querySelectorAll('.filter-tipe').forEach(b => {
                    const v = b.dataset.value;
                    b.style = v === '' ? (tipe.size ? off : on) : (tipe.has(v) ? on : off);
                });

                document.querySelectorAll('.filter-prioritas').forEach(b => {
                    const v = b.dataset.value;
                    b.style = v === '' ? (prioritas.size ? off : on) : (prioritas.has(v) ? on : off);
                });

                document.querySelectorAll('.filter-unit').forEach(b => {
                    const v = b.dataset.value;
                    b.style = v === '' ? (unit.size ? off : on) : (unit.has(v) ? on : off);
                });
            }

            function apply() {
                const url = new URL(location.href);
                status.size ? url.searchParams.set('status', [...status].join(',')) : url.searchParams.delete('status');
                tipe.size ? url.searchParams.set('tipe', [...tipe].join(',')) : url.searchParams.delete('tipe');
                prioritas.size ? url.searchParams.set('prioritas', [...prioritas].join(',')) : url.searchParams.delete('prioritas');
                unit.size ? url.searchParams.set('unit', [...unit].join(',')) : url.searchParams.delete('unit');

                const df = document.getElementById('dateFrom').value;
                const dt = document.getElementById('dateTo').value;
                df ? url.searchParams.set('date_from', df) : url.searchParams.delete('date_from');
                dt ? url.searchParams.set('date_to', dt) : url.searchParams.delete('date_to');

                url.searchParams.set('page', 1);
                location.href = url;
            }

            function reset() {
                const url = new URL(location.href);
                status.clear();
                tipe.clear();
                prioritas.clear();
                unit.clear();
                url.searchParams.delete('status');
                url.searchParams.delete('tipe');
                url.searchParams.delete('prioritas');
                url.searchParams.delete('unit');
                url.searchParams.delete('date_from');
                url.searchParams.delete('date_to');
                url.searchParams.delete('search');
                url.searchParams.set('page', 1);
                location.href = url;
            }

            function searchNow() {
                const v = document.getElementById('searchInput').value;
                const url = new URL(location.href);
                v ? url.searchParams.set('search', v) : url.searchParams.delete('search');
                url.searchParams.set('page', 1);
                location.href = url;
            }

            function updateLabel() {
                const btn = document.getElementById('filterDropdown');
                const text = document.getElementById('filterText');
                if (!btn || !text) return;
                let badge = btn.querySelector('.badge');

                const s = params.get('status');
                const t = params.get('tipe');
                const p = params.get('prioritas');
                const u = params.get('unit');
                const df = params.get('date_from');
                const dt = params.get('date_to');

                if (s || t || p || u || df || dt) {
                    let v = 'Filter';
                    if (s) v += ': ' + s.split(',').map(labelStatus).join(', ');
                    if (t) v += s ? ', type' : ': type';
                    if (p) v += (s || t) ? ', priority' : ': priority';
                    if (u) v += (s || t || p) ? ', unit' : ': unit';
                    if (df || dt) v += (s || t || p || u) ? ', date' : ': date';
                    text.textContent = v;

                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'badge bg-primary rounded-circle ms-1';
                        badge.style = 'width:6px;height:6px;';
                        btn.appendChild(badge);
                    }
                } else {
                    text.textContent = 'Filter';
                    badge && badge.remove();
                }
            }

            function labelStatus(v) {
                const labels = {
                    'baru': 'New',
                    'diproses': 'Processing',
                    'selesai': 'Completed',
                    'ditolak': 'Rejected'
                };
                return labels[v] || v;
            }
        });
    </script>
@endsection