@extends('layouts.admin.app')

@section('title', 'Rating Details')

@section('admin-content')
    <div class="container-fluid px-4 py-5"
        style="max-width: 1400px; font-family: 'Inter', 'Plus Jakarta Sans', system-ui, sans-serif;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.ratings.index') }}"
                    class="d-flex align-items-center gap-2 text-decoration-none py-2 px-3"
                    style="color: #64748b; font-size: 0.95rem; transition: color 0.2s; border: 1px solid #e2e8f0; border-radius: 20px;"
                    onmouseover="this.style.color='#f8773c'" onmouseout="this.style.color='#64748b'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7" />
                    </svg>
                    <span>Back to ratings</span>
                </a>
            </div>

            <div class="d-flex align-items-center gap-3 mt-3 mt-md-0">
                <span class="px-3 py-1 rounded-pill small fw-medium"
                    style="background-color: {{ $rating->status === 'active' ? '#e8f5e9' : ($rating->status === 'edited' ? '#fff8e1' : '#eceff1') }}; 
                               color: {{ $rating->status === 'active' ? '#2e7d32' : ($rating->status === 'edited' ? '#b85c00' : '#546e7a') }};
                               border: 1px solid {{ $rating->status === 'active' ? '#c8e6c9' : ($rating->status === 'edited' ? '#ffe0b2' : '#d0d7dd') }};">
                    {{ ucfirst($rating->status) }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-5 border-0" role="alert"
                style="background-color: #e8f5e9; color: #2e7d32; padding: 1rem 1.5rem; border-left: 4px solid #2e7d32;">
                <div class="d-flex align-items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                        style="filter: brightness(0.5);"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-5 border-0" role="alert"
                style="background-color: #ffebee; color: #c62828; padding: 1rem 1.5rem; border-left: 4px solid #c62828;">
                <div class="d-flex align-items-center gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                        style="filter: brightness(0.5);"></button>
                </div>
            </div>
        @endif

        <div class="row g-4 g-lg-5">
            <div class="col-lg-7 col-xl-8">
                <div class="card rounded-4 mb-4 border"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border-color: #e2e8f0;">
                    <div class="card-body p-4 p-xl-5">
                        <div class="row g-4 align-items-start">
                            <div class="col-md-auto">
                                <div class="rounded-4 overflow-hidden"
                                    style="width: 96px; height: 96px; background: #f8fafc; border: 1px solid #e2e8f0;">
                                    @if($rating->unit && $rating->unit->primaryPhoto)
                                        <img src="{{ $rating->unit->primaryPhoto->thumbnail_url }}"
                                            alt="{{ $rating->unit->name }}" class="w-100 h-100 object-fit-cover">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                                fill="none" stroke="#cbd5e1" stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5" fill="#cbd5e1"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col">
                                <div class="d-flex flex-column gap-2">
                                    <h2 class="h4 fw-semibold mb-0" style="color: #1e2937; letter-spacing: -0.01em;">
                                        {{ $rating->unit->name ?? '—' }}
                                    </h2>
                                    <div class="d-flex flex-wrap gap-3 gap-lg-4 text-muted small">
                                        <span class="d-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                                                <line x1="8" y1="10" x2="16" y2="10"></line>
                                            </svg>
                                            {{ $rating->unit->code ?? '—' }}
                                        </span>
                                        <span class="d-flex align-items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                            {{ $rating->unit->type->name ?? '—' }}
                                        </span>
                                        @if($rating->unit && $rating->unit->location)
                                            <span class="d-flex align-items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                    viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                                    <circle cx="12" cy="10" r="3"></circle>
                                                </svg>
                                                {{ $rating->unit->location }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-auto text-md-end mt-3 mt-md-0">
                                <div class="d-flex flex-column align-items-md-end">
                                    <span class="h2 fw-bold mb-0"
                                        style="color: #1e2937; line-height: 1;">{{ number_format($rating->overall_score, 1) }}</span>
                                    <span class="small text-muted">overall rating</span>
                                    <div class="d-flex gap-1 mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                                                    fill="{{ $i <= round($rating->overall_score) ? '#f8773c' : '#e2e8f0' }}"
                                                    stroke="none" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($rating->scores && $rating->scores->count() > 0)
                            <div class="mt-5 pt-4" style="border-top: 1px solid #e2e8f0;">
                                <h6 class="small fw-semibold text-uppercase mb-3"
                                    style="color: #64748b; letter-spacing: 0.02em;">Category Scores</h6>
                                <div class="row g-4">
                                    @foreach($rating->scores as $score)
                                        @php
                                            $scoreValue = $score->score;
                                            $scoreColor = $scoreValue >= 4.0 ? '#10b981' : ($scoreValue >= 3.0 ? '#f59e0b' : '#ef4444');
                                        @endphp
                                        <div class="col-6 col-md-4">
                                            <div class="d-flex flex-column gap-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="small text-muted">{{ $score->category->name ?? 'Category' }}</span>
                                                    <span class="fw-semibold"
                                                        style="color: {{ $scoreColor }};">{{ number_format($scoreValue, 1) }}</span>
                                                </div>
                                                <div class="progress"
                                                    style="height: 6px; background-color: #f1f5f9; border-radius: 3px;">
                                                    <div class="progress-bar rounded-pill"
                                                        style="width: {{ ($scoreValue / 5) * 100 }}%; background-color: {{ $scoreColor }}; transition: width 0.3s;">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="card rounded-4 mb-4 border"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border-color: #e2e8f0;">
                    <div class="card-body p-4 p-xl-5">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color: #64748b; letter-spacing: 0.02em;">
                            Visitor Comment</h6>
                        <div class="p-4 rounded-4" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                            @if($rating->comment)
                                <p class="mb-0 lh-lg" style="color: #334155; white-space: pre-wrap; font-size: 1rem;">
                                    {{ $rating->comment }}
                                </p>
                            @else
                                <p class="mb-0 text-muted fst-italic small">— No comment provided —</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card rounded-4 border"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border-color: #e2e8f0;">
                    <div class="card-body p-4 p-xl-5">
                        <h6 class="small fw-semibold text-uppercase mb-4" style="color: #64748b; letter-spacing: 0.02em;">
                            Timeline</h6>

                        <div class="position-relative" style="padding-left: 32px;">
                            <div
                                style="position: absolute; left: 15px; top: 12px; bottom: 12px; width: 2px; background: linear-gradient(to bottom, #f8773c 0%, #f8773c 30%, #e2e8f0 70%, #e2e8f0 100%);">
                            </div>

                            <div class="position-relative mb-4" style="z-index: 2;">
                                <div
                                    style="position: absolute; left: -32px; top: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <div
                                        style="width: 16px; height: 16px; background-color: #f8773c; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 0 2px rgba(248,119,60,0.2), 0 2px 4px rgba(0,0,0,0.1);">
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-medium" style="color: #1e2937;">Rating Created</div>
                                    <div class="small text-muted">{{ $rating->created_at->format('d M Y • H:i') }}</div>
                                </div>
                            </div>

                            @if($rating->last_replied_at)
                                <div class="position-relative mb-4" style="z-index: 2;">
                                    <div
                                        style="position: absolute; left: -32px; top: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <div
                                            style="width: 16px; height: 16px; background-color: #f8773c; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 0 2px rgba(248,119,60,0.2), 0 2px 4px rgba(0,0,0,0.1);">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-medium" style="color: #1e2937;">Admin Replied</div>
                                        <div class="small text-muted">{{ $rating->last_replied_at->format('d M Y • H:i') }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($rating->last_edited_at)
                                <div class="position-relative" style="z-index: 2;">
                                    <div
                                        style="position: absolute; left: -32px; top: 0; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        <div
                                            style="width: 16px; height: 16px; background-color: #94a3b8; border: 2px solid white; border-radius: 50%; box-shadow: 0 0 0 2px rgba(148,163,184,0.2), 0 2px 4px rgba(0,0,0,0.1);">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-medium" style="color: #1e2937;">Last Edited</div>
                                        <div class="small text-muted">{{ $rating->last_edited_at->format('d M Y • H:i') }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-xl-4">
                <div class="card rounded-4 mb-4 border"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border-color: #e2e8f0;">
                    <div class="card-body p-4">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color: #64748b; letter-spacing: 0.02em;">
                            Student</h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px; background: #f8fafc; border: 1px solid #e2e8f0;">
                                    <span class="fw-semibold"
                                        style="color: #f8773c;">{{ substr($rating->student->name ?? 'A', 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="fw-medium" style="color: #1e2937;">
                                        {{ $rating->student->name ?? 'Anonymous' }}
                                    </div>
                                    @if($rating->student && $rating->student->student_identifier)
                                        <div class="small text-muted">{{ $rating->student->student_identifier }}</div>
                                    @endif
                                </div>
                            </div>
                            @if($rating->student && $rating->student->email)
                                <div class="d-flex align-items-center gap-2 small">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="#94a3b8" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                        </path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <span class="text-muted">{{ $rating->student->email }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card rounded-4 border"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border-color: #e2e8f0;">
                    <div class="card-body p-4">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color: #64748b; letter-spacing: 0.02em;">
                            Actions</h6>
                        <div class="d-flex flex-column gap-2">
                            @if(!$rating->adminReply)
                                {{-- Menggunakan button reply dengan style full (ada teksnya) --}}
                                <x-admin.button type="reply-full" modalId="replyModal{{ $rating->id }}"
                                    tooltip="Reply to Rating" />
                            @endif

                            <div class="d-flex flex-column gap-2 mt-2">
                                @if($rating->status === 'active' || $rating->status === 'edited')
                                    <form action="{{ route('admin.ratings.update-status', $rating->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="archived">
                                        <button type="submit"
                                            class="btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-3"
                                            style="background: white; border: 1px solid #e2e8f0; color: #64748b; font-size: 0.95rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path
                                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                </path>
                                            </svg>
                                            <span>Archive</span>
                                        </button>
                                    </form>
                                @endif

                                @if($rating->status === 'archived')
                                    <form action="{{ route('admin.ratings.update-status', $rating->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="active">
                                        <button type="submit"
                                            class="btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-3"
                                            style="background: white; border: 1px solid #e2e8f0; color: #64748b; font-size: 0.95rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span>Restore</span>
                                        </button>
                                    </form>
                                @endif

                                @if(!$rating->is_comment_censored)
                                    <form action="{{ route('admin.ratings.moderate', $rating->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="censor">
                                        <button type="submit"
                                            class="btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-3"
                                            style="background: white; border: 1px solid #e2e8f0; color: #64748b; font-size: 0.95rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                            </svg>
                                            <span>Censor</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.ratings.moderate', $rating->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="action" value="restore">
                                        <button type="submit"
                                            class="btn d-flex align-items-center justify-content-center gap-2 w-100 py-2 rounded-3"
                                            style="background: white; border: 1px solid #e2e8f0; color: #64748b; font-size: 0.95rem;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            <span>Uncensor</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <x-admin.reply-rating-modal :rating="$rating" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Modal - Redesigned dengan Gradient Orange dan Icon Circle Putih -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"
                style="border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">

                <!-- Header - Gradient Orange dengan Icon Circle Putih -->
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #f1c3ae, #f8773c); padding: 1.5rem 2rem; border: none; position: relative;">
                    <!-- Icon Circle Putih -->
                    <div
                        style="position: absolute; left: 2rem; top: 50%; transform: translateY(-50%); width: 48px; height: 48px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="#f8773c" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>

                    <!-- Title -->
                    <div style="margin-left: 60px;">
                        <h2 class="modal-title fw-bold text-white" style="letter-spacing: -0.01em;">
                            Balas Rating
                        </h2>
                        <p class="fs-2 text-white mb-0 small" style="opacity: 5; margin-top: 0.25rem;">
                            {{ $rating->unit->name ?? 'Unit' }}
                        </p>
                    </div>

                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body" style="padding: 2rem; background: #f8fafc;">
                    <!-- Rating Summary Card -->
                    <div
                        style="background: white; border-radius: 16px; padding: 1.25rem; margin-bottom: 1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                        <div class="d-flex gap-3">
                            <!-- Student Avatar -->
                            <div
                                style="width: 48px; height: 48px; background: #fff5f0; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #f8773c; font-weight: 600;">
                                {{ substr($rating->student->name ?? 'A', 0, 1) }}
                            </div>

                            <!-- Rating Info -->
                            <div style="flex: 1;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-medium">{{ $rating->student->name ?? 'Anonymous' }}</span>
                                    <span class="badge"
                                        style="background: #f1f5f9; color: #475569; font-weight: 500; padding: 0.25rem 0.75rem; border-radius: 30px; font-size: 0.7rem;">
                                        {{ $rating->created_at->format('d M Y') }}
                                    </span>
                                </div>

                                <!-- Star Rating -->
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <div class="d-flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                style="margin-right: 1px;">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                                                    fill="{{ $i <= round($rating->overall_score) ? '#f8773c' : '#e2e8f0' }}"
                                                    stroke="none" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="fw-bold"
                                        style="color: #f8773c;">{{ number_format($rating->overall_score, 1) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Comment Preview -->
                        @if($rating->comment)
                            <div class="mt-3 pt-3" style="border-top: 1px dashed #e2e8f0;">
                                <p class="small text-muted mb-1">Komentar:</p>
                                <p class="mb-0" style="color: #334155; font-size: 0.9rem; line-height: 1.6;">
                                    {{ $rating->comment }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Reply Form -->
                    <form action="{{ route('admin.admin-replies.store', $rating->id) }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 1.5rem;">
                            <label for="reply_message"
                                style="display: block; font-size: 0.85rem; font-weight: 600; color: #334155; margin-bottom: 0.5rem; letter-spacing: 0.02em;">
                                TULIS TANGGAPAN
                            </label>
                            <textarea id="reply_message" name="reply_message" rows="4" required
                                style="width: 100%; padding: 1rem; border: 2px solid #e2e8f0; border-radius: 14px; font-size: 0.95rem; line-height: 1.6; color: #0f172a; background: white; transition: all 0.2s; resize: vertical;"
                                placeholder="Terima kasih atas rating dan masukannya..."></textarea>
                        </div>

                        <!-- Quick Responses -->
                        <div style="margin-bottom: 1rem;">
                            <label
                                style="display: block; font-size: 0.8rem; font-weight: 500; color: #64748b; margin-bottom: 0.5rem;">
                                Quick Responses
                            </label>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                <button type="button" class="quick-response"
                                    data-response="Terima kasih atas rating positifnya. Kami senang Anda puas dengan pelayanan kami."
                                    style="background: white; border: 1px solid #e2e8f0; border-radius: 30px; padding: 0.3rem 0.8rem; font-size: 0.75rem; color: #475569; transition: all 0.2s; cursor: pointer;">
                                    Terima kasih
                                </button>
                                <button type="button" class="quick-response"
                                    data-response="Terima kasih atas masukannya. Kami akan segera menindaklanjuti untuk perbaikan."
                                    style="background: white; border: 1px solid #e2e8f0; border-radius: 30px; padding: 0.3rem 0.8rem; font-size: 0.75rem; color: #475569; transition: all 0.2s; cursor: pointer;">
                                    Akan ditindaklanjuti
                                </button>
                                <button type="button" class="quick-response"
                                    data-response="Mohon maaf atas ketidaknyamanannya. Kami akan berusaha meningkatkan kualitas pelayanan."
                                    style="background: white; border: 1px solid #e2e8f0; border-radius: 30px; padding: 0.3rem 0.8rem; font-size: 0.75rem; color: #475569; transition: all 0.2s; cursor: pointer;">
                                    Permintaan maaf
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <button type="button" class="btn px-4 py-2" data-bs-dismiss="modal"
                                style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; color: #475569; font-weight: 500; font-size: 0.9rem;">
                                Batal
                            </button>
                            <button type="submit" class="btn px-4 py-2"
                                style="background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; border-radius: 12px; color: white; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 6px -1px rgba(248, 119, 60, 0.2);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 0.5rem;">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                                Kirim Tanggapan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .quick-response:hover {
            background: #f8773c !important;
            border-color: #f8773c !important;
            color: white !important;
            transform: translateY(-1px);
        }

        textarea:focus {
            outline: none;
            border-color: #f8773c !important;
            box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1);
        }

        .card {
            transition: all 0.2s ease;
        }

        .card:hover {
            border-color: #cbd5e1 !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.quick-response').forEach(btn => {
                btn.addEventListener('click', function () {
                    const response = this.dataset.response;
                    const textarea = document.getElementById('reply_message');
                    if (textarea) {
                        textarea.value = response;
                    }
                });
            });
        });
    </script>
@endsection