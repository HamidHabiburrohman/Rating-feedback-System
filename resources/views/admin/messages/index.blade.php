@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Messages</h1>
                <p class="text-muted mb-0">Manage communications between admin and units</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#3b82f6" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-muted small">Total Messages</div>
                                <div class="h4 fw-bold mb-0">{{ $messages->total() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#f59e0b" stroke-width="2">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-muted small">Unread</div>
                                <div class="h4 fw-bold mb-0">{{ $unreadCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#dc2626" stroke-width="2">
                                    <path
                                        d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                                    <line x1="12" y1="9" x2="12" y2="13" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-muted small">Action Required</div>
                                <div class="h4 fw-bold mb-0">{{ $actionCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 d-flex align-items-center justify-content-center me-3"
                                style="width: 48px; height: 48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="#10b981" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-muted small">Responded</div>
                                <div class="h4 fw-bold mb-0">
                                    @php
                                        $respondedCount = 0;
                                        if (isset($messages) && $messages->count() > 0) {
                                            foreach ($messages as $msg) {
                                                if (in_array($msg->status, ['ditanggapi', 'selesai'])) {
                                                    $respondedCount++;
                                                }
                                            }
                                        }
                                    @endphp
                                    {{ $respondedCount }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                        placeholder="Search messages by title or content..." value="{{ request('search') }}"
                        style="height: 44px; background-color: white; border-color: #d1d5db !important;">
                </div>

                <div class="d-flex align-items-center gap-2">
                    <div class="dropdown">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown"
                            style="height:44px;background:white;border-color:#d1d5db;">
                            <span class="fw-medium">
                                {{ request('per_page', 20) }}
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
                            @foreach ([10, 20, 50, 100] as $size)
                                <li>
                                    <a class="dropdown-item py-2 px-3 {{ request('per_page', 20) == $size ? 'active' : '' }}"
                                        href="{{ request()->fullUrlWithQuery(['per_page' => $size, 'page' => 1]) }}">
                                        {{ $size }} Rows
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="btn-group" role="group">
                        @php
                            $activeTabStyle = 'background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; color: white;';
                            $inactiveTabStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
                        @endphp

                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'inbox']) }}"
                            class="btn rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm"
                            style="{{ $tab === 'inbox' ? $activeTabStyle : $inactiveTabStyle }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                                <polyline points="22,6 12,13 2,6" />
                            </svg>
                            <span class="fw-medium">Inbox</span>
                            @if($tab === 'inbox' && $unreadCount > 0)
                                <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                            @endif
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'sent']) }}"
                            class="btn rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm"
                            style="{{ $tab === 'sent' ? $activeTabStyle : $inactiveTabStyle }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                            <span class="fw-medium">Sent</span>
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'action']) }}"
                            class="btn rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm"
                            style="{{ $tab === 'action' ? $activeTabStyle : $inactiveTabStyle }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <span class="fw-medium">Action</span>
                            @if($tab === 'action' && $actionCount > 0)
                                <span class="badge bg-danger rounded-pill">{{ $actionCount }}</span>
                            @endif
                        </a>

                        <a href="{{ request()->fullUrlWithQuery(['tab' => 'unit']) }}"
                            class="btn rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm"
                            style="{{ $tab === 'unit' ? $activeTabStyle : $inactiveTabStyle }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            <span class="fw-medium">From Units</span>
                        </a>
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
                        <div class="dropdown-menu p-3 border-0 shadow-lg rounded-4 mt-2" style="min-width: 250px;">
                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Category</label>
                                <div class="d-flex flex-wrap gap-2" id="categoryFilter">
                                    @php
                                        $activeStyle = 'background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; color: white;';
                                        $inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
                                        $currentKategori = request('kategori') ? explode(',', request('kategori')) : [];
                                    @endphp
                                    <button type="button"
                                        class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-category"
                                        data-value="" style="{{ empty($currentKategori) ? $activeStyle : $inactiveStyle }}">
                                        All
                                    </button>
                                    @foreach($kategoriOptions as $value => $label)
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-category"
                                            data-value="{{ $value }}"
                                            style="{{ in_array($value, $currentKategori) ? $activeStyle : $inactiveStyle }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Priority</label>
                                <div class="d-flex flex-wrap gap-2" id="priorityFilter">
                                    @php
                                        $currentPrioritas = request('prioritas') ? explode(',', request('prioritas')) : [];
                                    @endphp
                                    <button type="button"
                                        class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-priority"
                                        data-value=""
                                        style="{{ empty($currentPrioritas) ? $activeStyle : $inactiveStyle }}">
                                        All
                                    </button>
                                    @foreach($prioritasOptions as $value => $label)
                                        <button type="button"
                                            class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-priority"
                                            data-value="{{ $value }}"
                                            style="{{ in_array($value, $currentPrioritas) ? $activeStyle : $inactiveStyle }}">
                                            {{ $label }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="small fw-bold text-uppercase mb-2 d-block"
                                    style="color: #6b7280; letter-spacing: 0.05em;">Status</label>
                                <div class="d-flex flex-wrap gap-2" id="statusFilter">
                                    @php
                                        $currentStatus = request('status') ? explode(',', request('status')) : [];
                                    @endphp
                                    <button type="button"
                                        class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status" data-value=""
                                        style="{{ empty($currentStatus) ? $activeStyle : $inactiveStyle }}">
                                        All
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                        data-value="terkirim,diterima"
                                        style="{{ count(array_intersect(['terkirim', 'diterima'], $currentStatus)) === 2 ? $activeStyle : $inactiveStyle }}">
                                        Unread
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                        data-value="dibaca"
                                        style="{{ in_array('dibaca', $currentStatus) ? $activeStyle : $inactiveStyle }}">
                                        Read
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm rounded-pill px-3 fw-medium shadow-sm filter-status"
                                        data-value="ditanggapi,selesai"
                                        style="{{ count(array_intersect(['ditanggapi', 'selesai'], $currentStatus)) === 2 ? $activeStyle : $inactiveStyle }}">
                                        Responded
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="button" id="resetFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center shadow-sm"
                                    style="height: 36px; background: white; border: 1px solid #d1d5db; color: #4b5563;">
                                    Reset
                                </button>
                                <button type="button" id="applyFilter"
                                    class="btn btn-sm rounded-pill w-100 fw-semibold shadow-sm"
                                    style="height: 36px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; color: white;">
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button
                            class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 shadow-sm dropdown-toggle-btn"
                            type="button" data-bs-toggle="dropdown"
                            style="height:44px;background:white;border-color:#d1d5db;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M7 12h10M10 18h4" />
                            </svg>
                            <span class="fw-medium">Sort</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
                                <path d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ request('sort') == 'created_at' && request('order') == 'desc' ? 'active bg-light text-primary fw-bold' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'desc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 16 4 4 4-4M7 20V4M21 16H10M21 10H10M21 4H10" />
                                    </svg>
                                    Newest First
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ request('sort') == 'created_at' && request('order') == 'asc' ? 'active bg-light text-primary fw-bold' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => 'asc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="m3 8 4-4 4 4M7 4v16M21 4H10M21 10H10M21 16H10" />
                                    </svg>
                                    Oldest First
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 {{ request('sort') == 'prioritas' && request('order') == 'desc' ? 'active bg-light text-primary fw-bold' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['sort' => 'prioritas', 'order' => 'desc', 'page' => 1]) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18M7 12h10M10 18h4" />
                                    </svg>
                                    Priority High-Low
                                </a>
                            </li>
                        </ul>
                    </div>

                    <a href="{{ route('admin.messages.create') }}"
                        class="btn btn-primary rounded-pill px-4 d-flex align-items-center gap-2 shadow-sm"
                        style="height: 44px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="white" stroke-width="2.5">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                        <span class="fw-medium">Compose</span>
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

        @if(isset($messages) && $messages->count() > 0)
            <div class="card border-1 rounded-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="bg-transparent">
                            <tr class="text-muted text-uppercase" style="font-size: .75rem;">
                                <th class="ps-4 py-3 fw-semibold">Subject</th>
                                <th class="py-3 fw-semibold">From/To</th>
                                <th class="py-3 fw-semibold">Unit</th>
                                <th class="py-3 fw-semibold">Category</th>
                                <th class="py-3 fw-semibold">Priority</th>
                                <th class="py-3 fw-semibold">Status</th>
                                <th class="py-3 fw-semibold">Date</th>
                                <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($messages as $message)
                                <tr class="{{ !in_array($message->status, ['dibaca', 'ditanggapi', 'selesai']) ? 'table-primary bg-opacity-10' : '' }}"
                                    style="{{ !in_array($message->status, ['dibaca', 'ditanggapi', 'selesai']) ? 'background-color: rgba(59, 130, 246, 0.05) !important;' : '' }}">
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            @if(!in_array($message->status, ['dibaca', 'ditanggapi', 'selesai']))
                                                <div class="rounded-circle bg-primary me-2" style="width: 8px; height: 8px;"></div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold mb-1">{{ $message->judul }}</div>
                                                <div class="text-muted small text-truncate" style="max-width: 200px;">
                                                    {{ Str::limit($message->pesan, 60) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center gap-2">
                                            @if($message->pengirim_tipe === 'admin')
                                                <span class="badge bg-primary rounded-pill">Admin</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">Unit</span>
                                            @endif
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="#6b7280" stroke-width="2">
                                                <path d="M5 12h14" />
                                                <path d="M12 5l7 7-7 7" />
                                            </svg>
                                            @if($message->penerima_tipe === 'admin')
                                                <span class="badge bg-primary rounded-pill">Admin</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill">Unit</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if($message->unit)
                                            <div class="fw-medium">{{ $message->unit->nama_unit }}</div>
                                            <div class="text-muted small">{{ $message->unit->kode_unit }}</div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $categoryColors = [
                                                'technical' => 'badge-technical',
                                                'status_request' => 'badge-status-request',
                                                'rating_feedback' => 'badge-rating-feedback',
                                                'maintenance' => 'badge-maintenance',
                                                'announcement' => 'badge-announcement',
                                                'instruction' => 'badge-instruction',
                                                'question' => 'badge-question',
                                                'emergency' => 'badge-emergency'
                                            ];
                                            $categoryLabels = [
                                                'technical' => 'Technical',
                                                'status_request' => 'Status Request',
                                                'rating_feedback' => 'Feedback',
                                                'maintenance' => 'Maintenance',
                                                'announcement' => 'Announcement',
                                                'instruction' => 'Instruction',
                                                'question' => 'Question',
                                                'emergency' => 'Emergency'
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $categoryColors[$message->kategori] ?? 'badge-secondary' }} rounded-pill">
                                            {{ $categoryLabels[$message->kategori] ?? $message->kategori }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $priorityColors = [
                                                'biasa' => 'badge-biasa',
                                                'penting' => 'badge-penting',
                                                'sangat_penting' => 'badge-sangat-penting'
                                            ];
                                        @endphp
                                        <span class="badge {{ $priorityColors[$message->prioritas] }} rounded-pill">
                                            {{ ucfirst(str_replace('_', ' ', $message->prioritas)) }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        @php
                                            $statusColors = [
                                                'terkirim' => 'badge-status-terkirim',
                                                'diterima' => 'badge-status-diterima',
                                                'dibaca' => 'badge-status-dibaca',
                                                'ditanggapi' => 'badge-status-ditanggapi',
                                                'selesai' => 'badge-status-selesai'
                                            ];
                                            $statusLabels = [
                                                'terkirim' => 'Sent',
                                                'diterima' => 'Received',
                                                'dibaca' => 'Read',
                                                'ditanggapi' => 'Responded',
                                                'selesai' => 'Completed'
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusColors[$message->status] }} rounded-pill">
                                            {{ $statusLabels[$message->status] ?? $message->status }}
                                        </span>
                                        @if($message->perlu_tindakan && !$message->tindakan_diambil_pada)
                                            <span class="badge bg-danger rounded-pill mt-1">Action Required</span>
                                        @endif
                                    </td>
                                    <td class="py-3">
                                        <div class="small text-muted">{{ $message->created_at->format('M d, Y') }}</div>
                                        <div class="small text-muted">{{ $message->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="pe-4 py-3 text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('admin.messages.show', $message->id) }}"
                                                class="btn btn-sm btn-outline-primary rounded-circle d-flex align-items-center justify-content-center"
                                                style="width: 32px; height: 32px;" title="View Message">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                    fill="none" stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>
                                            @if($message->pengirim_tipe !== 'admin')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-success rounded-circle d-flex align-items-center justify-content-center reply-btn"
                                                    style="width: 32px; height: 32px;" data-id="{{ $message->id }}" title="Reply">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="9 10 4 15 9 20" />
                                                        <path d="M20 4v7a4 4 0 0 1-4 4H4" />
                                                    </svg>
                                                </button>
                                            @endif
                                            <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST"
                                                class="d-inline delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center delete-btn"
                                                    style="width: 32px; height: 32px;" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="3 6 5 6 21 6" />
                                                        <path
                                                            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                                        <line x1="10" y1="11" x2="10" y2="17" />
                                                        <line x1="14" y1="11" x2="14" y2="17" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="px-4 py-3">
                    @include('admin.messages.partials.pagination')
                </div>
            </div>
        @else
            <div class="card border-1 rounded-4">
                <div class="card-body text-center py-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="#9ca3af" stroke-width="1.5" class="mb-3">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    <div class="h5 text-muted mb-2">No messages found</div>
                    <p class="text-muted mb-4">Start by composing a new message or check your filters.</p>
                    <a href="{{ route('admin.messages.create') }}" class="btn btn-primary">
                        Compose New Message
                    </a>
                </div>
            </div>
        @endif
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

        .dropdown-toggle::after {
            color: #6b7280;
        }

        .btn-filter:hover .dropdown-toggle::after,
        .btn-export:hover .dropdown-toggle::after,
        .btn-per-page:hover .dropdown-toggle::after {
            color: #374151;
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

        .filter-badge {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .filter-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-striped-bg: rgba(59, 130, 246, 0.02);
            --bs-table-hover-bg: rgba(59, 130, 246, 0.04);
        }

        .table> :not(:first-child) {
            border-top: 2px solid #e5e7eb;
        }

        .btn-reset {
            background-color: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
            transition: all 0.2s ease;
        }

        .btn-reset:hover {
            background-color: #fecaca;
            color: #b91c1c;
            border-color: #fca5a5;
            transform: translateY(-1px);
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

        @media (max-width: 768px) {
            .d-flex.justify-content-between.align-items-center.gap-3 {
                flex-direction: column;
                gap: 1rem !important;
            }

            .position-relative {
                max-width: 100% !important;
                width: 100%;
            }

            .d-flex.align-items-center.gap-2 {
                width: 100%;
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .btn span {
                display: inline-block;
            }
        }

        .badge-technical {
            background-color: rgba(79, 70, 229, 0.1);
            color: #4f46e5;
            border: 1px solid rgba(79, 70, 229, 0.2);
        }

        .badge-status-request {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-rating-feedback {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-maintenance {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .badge-announcement {
            background-color: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .badge-instruction {
            background-color: rgba(236, 72, 153, 0.1);
            color: #ec4899;
            border: 1px solid rgba(236, 72, 153, 0.2);
        }

        .badge-question {
            background-color: rgba(14, 165, 233, 0.1);
            color: #0ea5e9;
            border: 1px solid rgba(14, 165, 233, 0.2);
        }

        .badge-emergency {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-biasa {
            background-color: rgba(156, 163, 175, 0.1);
            color: #6b7280;
            border: 1px solid rgba(156, 163, 175, 0.2);
        }

        .badge-penting {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-sangat-penting {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-status-terkirim {
            background-color: rgba(59, 130, 246, 0.1);
            color: #3b82f6;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .badge-status-diterima {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-status-dibaca {
            background-color: rgba(139, 92, 246, 0.1);
            color: #8b5cf6;
            border: 1px solid rgba(139, 92, 246, 0.2);
        }

        .badge-status-ditanggapi {
            background-color: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-status-selesai {
            background-color: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .rotate-180 {
            transform: rotate(180deg);
        }

        .table-primary {
            --bs-table-bg: rgba(59, 130, 246, 0.05);
        }

        .badge {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 0.25rem 0.5rem;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let category = new Set();
            let priority = new Set();
            let status = new Set();
            let unit = new Set();

            const params = new URLSearchParams(location.search);

            initState();
            initIcons();
            bind();
            updateLabel();

            function initState() {
                const c = params.get('kategori');
                const p = params.get('prioritas');
                const s = params.get('status');
                const u = params.get('unit');

                if (c) c.split(',').forEach(v => v && category.add(v));
                if (p) p.split(',').forEach(v => v && priority.add(v));
                if (s) s.split(',').forEach(v => v && status.add(v));
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
                document.querySelectorAll('.filter-category')
                    .forEach(b => b.addEventListener('click', e => toggle(e, category)));

                document.querySelectorAll('.filter-priority')
                    .forEach(b => b.addEventListener('click', e => toggle(e, priority)));

                document.querySelectorAll('.filter-status')
                    .forEach(b => b.addEventListener('click', e => toggle(e, status)));

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

                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        const form = this.closest('form');
                        if (confirm('Are you sure you want to delete this message?')) {
                            form.submit();
                        }
                    });
                });

                document.querySelectorAll('.reply-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const messageId = this.getAttribute('data-id');
                        window.location.href = `/admin/messages/${messageId}?reply=true`;
                    });
                });
            }

            function toggle(e, set) {
                e.stopPropagation();
                const v = e.currentTarget.dataset.value;

                if (v.includes(',')) {
                    const values = v.split(',');
                    const allSelected = values.every(val => set.has(val));

                    if (allSelected) {
                        values.forEach(val => set.delete(val));
                    } else {
                        values.forEach(val => set.add(val));
                    }
                } else {
                    v === '' ? set.clear() :
                        set.has(v) ? set.delete(v) :
                            set.add(v);
                }

                paint();
            }

            function paint() {
                const on = 'background:linear-gradient(135deg,#3b82f6,#1d4ed8);border:none;color:white;';
                const off = 'background:white;border:1px solid #d1d5db;color:#6b7280;';

                document.querySelectorAll('.filter-category').forEach(b => {
                    const v = b.dataset.value;
                    b.style = v === '' ? (category.size ? off : on) : (category.has(v) ? on : off);
                });

                document.querySelectorAll('.filter-priority').forEach(b => {
                    const v = b.dataset.value;
                    b.style = v === '' ? (priority.size ? off : on) : (priority.has(v) ? on : off);
                });

                document.querySelectorAll('.filter-status').forEach(b => {
                    const v = b.dataset.value;
                    if (v === '') {
                        b.style = status.size ? off : on;
                    } else if (v.includes(',')) {
                        const values = v.split(',');
                        const allSelected = values.every(val => status.has(val));
                        b.style = allSelected ? on : off;
                    } else {
                        b.style = status.has(v) ? on : off;
                    }
                });

                document.querySelectorAll('.filter-unit').forEach(b => {
                    const v = b.dataset.value;
                    b.style = v === '' ? (unit.size ? off : on) : (unit.has(v) ? on : off);
                });
            }

            function apply() {
                const url = new URL(location.href);

                category.size
                    ? url.searchParams.set('kategori', [...category].join(','))
                    : url.searchParams.delete('kategori');

                priority.size
                    ? url.searchParams.set('prioritas', [...priority].join(','))
                    : url.searchParams.delete('prioritas');

                status.size
                    ? url.searchParams.set('status', [...status].join(','))
                    : url.searchParams.delete('status');

                unit.size
                    ? url.searchParams.set('unit', [...unit].join(','))
                    : url.searchParams.delete('unit');

                url.searchParams.set('page', 1);
                location.href = url;
            }

            function reset() {
                const url = new URL(location.href);

                category.clear();
                priority.clear();
                status.clear();
                unit.clear();

                url.searchParams.delete('kategori');
                url.searchParams.delete('prioritas');
                url.searchParams.delete('status');
                url.searchParams.delete('unit');
                url.searchParams.delete('search');
                url.searchParams.set('page', 1);

                document.getElementById('searchInput').value = '';
                location.href = url;
            }

            function searchNow() {
                const v = document.getElementById('searchInput').value;
                const url = new URL(location.href);

                v ? url.searchParams.set('search', v)
                    : url.searchParams.delete('search');

                url.searchParams.set('page', 1);
                location.href = url;
            }

            function updateLabel() {
                const btn = document.getElementById('filterDropdown');
                const text = document.getElementById('filterText');

                if (!btn || !text) return;

                let badge = btn.querySelector('.badge');
                const c = params.get('kategori');
                const p = params.get('prioritas');
                const s = params.get('status');
                const u = params.get('unit');

                if (c || p || s || u) {
                    let v = 'Filter';
                    const parts = [];

                    if (c) parts.push('Category: ' + getCategoryLabels(c));
                    if (p) parts.push('Priority: ' + getPriorityLabels(p));
                    if (s) parts.push('Status: ' + getStatusLabels(s));
                    if (u) parts.push('Unit');

                    v += ': ' + parts.join(', ');
                    text.textContent = v.length > 30 ? 'Filter: Multiple' : v;

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

            function getCategoryLabels(categories) {
                const labels = {
                    'technical': 'Technical',
                    'status_request': 'Status Request',
                    'rating_feedback': 'Feedback',
                    'maintenance': 'Maintenance',
                    'announcement': 'Announcement',
                    'instruction': 'Instruction',
                    'question': 'Question',
                    'emergency': 'Emergency'
                };

                return categories.split(',').map(c => labels[c] || c).join(', ');
            }

            function getPriorityLabels(priorities) {
                const labels = {
                    'biasa': 'Normal',
                    'penting': 'Important',
                    'sangat_penting': 'Urgent'
                };

                return priorities.split(',').map(p => labels[p] || p).join(', ');
            }

            function getStatusLabels(statuses) {
                const labels = {
                    'terkirim': 'Sent',
                    'diterima': 'Received',
                    'dibaca': 'Read',
                    'ditanggapi': 'Responded',
                    'selesai': 'Completed'
                };

                return statuses.split(',').map(s => labels[s] || s).join(', ');
            }
        });
    </script>
@endsection