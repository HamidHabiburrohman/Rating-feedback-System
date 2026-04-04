@extends('layouts.admin.app')

@section('title', 'Admin Replies')

@section('admin-content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 fw-bold mb-0" style="color: #1a1a1a;">Admin Replies</h1>
        </div>
    </div>

    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center gap-3">
            <x-admin.search-button-component placeholder="Search by reply message..." />
            <div class="d-flex align-items-center gap-2">
                <div class="dropdown">
                    <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
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

                <x-admin.sort-button 
                    :sortOptions="[
                        'created_at_desc' => 'Newest First',
                        'created_at_asc' => 'Oldest First',
                    ]"
                    defaultSort="created_at"
                    defaultOrder="desc"
                />
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
                        <th class="py-3 fw-semibold">Unit</th>
                        <th class="py-3 fw-semibold">Replied At</th>
                        <th class="pe-4 py-3 fw-semibold text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="repliesTable">
                    @include('admin.admin-replies.partials.rows', ['replies' => $replies])
                </tbody>
            </table>
        </div>

        <div class="px-4 py-3">
            @include('admin.admin-replies.partials.pagination', ['paginator' => $replies])
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/admin/js/admin-reply.js') }}"></script>
@endpush