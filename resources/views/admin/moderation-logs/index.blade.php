@extends('layouts.admin.app')

@section('title', 'Moderation Logs')

@section('admin-content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Moderation Logs</h1>
            </div>
        </div>

        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center gap-3">
                <x-admin.search-button-component placeholder="Search by action or reason..." />
                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown"
                            style="height: 44px; background-color: white; border-color: #d1d5db;">
                            <span class="fw-medium">{{ request('per_page', 10) }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
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

                        <div class="dropdown-menu p-0 border-0 shadow-lg rounded-4 overflow-hidden mt-2"
                            style="min-width: 350px; background-color: #ffffff;">
                            <div class="p-3">
                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Action</label>
                                    <select class="form-select" id="actionFilter">
                                        <option value="">All Actions</option>
                                        @foreach($filterData['actions'] ?? [] as $action)
                                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Target Type</label>
                                    <select class="form-select" id="targetTypeFilter">
                                        <option value="">All Types</option>
                                        @foreach($filterData['target_types'] ?? [] as $type)
                                            <option value="{{ $type }}" {{ request('target_type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="small fw-bold text-uppercase mb-2 d-block"
                                        style="color: #6b7280; letter-spacing: 0.05em;">Admin</label>
                                    <select class="form-select" id="adminFilter">
                                        <option value="">All Admins</option>
                                        @foreach($filterData['admins'] ?? [] as $admin)
                                            <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->nama }}
                                            </option>
                                        @endforeach
                                    </select>
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
            'created_at_desc' => 'Newest First',
            'created_at_asc' => 'Oldest First'
        ]" defaultSort="created_at" defaultOrder="desc" />

                    <button type="button"
                        class="btn btn-outline-secondary rounded-pill px-3 d-flex align-items-center gap-2"
                        onclick="cleanupLogs()" style="height: 44px; border: 1px solid #d1d5db;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span class="fw-medium">Cleanup</span>
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

        <div class="card border rounded-4 mt-4">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="bg-transparent">
                        <tr class="text-muted text-uppercase" style="font-size: .75rem;">
                            <th class="ps-4 py-3 fw-semibold">Admin</th>
                            <th class="py-3 fw-semibold">Action</th>
                            <th class="py-3 fw-semibold">Target</th>
                            <th class="py-3 fw-semibold">Date</th>
                            <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="logsTable">
                        @include('admin.moderation-logs.partials.rows', ['logs' => $logs])
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3">
                @include('admin.moderation-logs.partials.pagination', ['paginator' => $logs])
            </div>
        </div>
    </div>

    <div class="modal fade" id="cleanupModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Cleanup Old Logs</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-3">This will permanently delete logs older than the specified days.</p>
                    <div class="mb-3">
                        <label class="form-label fw-medium mb-2">Days to keep</label>
                        <input type="number" class="form-control rounded-3" id="cleanupDays" value="90" min="30" max="365">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger rounded-pill px-4" id="confirmCleanup">Cleanup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 pt-0 text-center">
                    <div class="rounded-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 64px; height: 64px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none"
                            stroke="#dc2626" stroke-width="2">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                            <line x1="10" y1="11" x2="10" y2="17" />
                            <line x1="14" y1="11" x2="14" y2="17" />
                        </svg>
                    </div>
                    <h5 class="fw-bold mb-2">Delete Log</h5>
                    <p class="text-muted mb-4" id="deleteModalText">Are you sure you want to delete this log?</p>
                    <div class="d-flex justify-content-center gap-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form id="deleteForm" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger rounded-pill px-4">
                                Yes, Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/admin/js/moderation-logs.js') }}"></script>
@endpush