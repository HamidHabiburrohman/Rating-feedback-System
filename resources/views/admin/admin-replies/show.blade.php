@extends('layouts.admin.app')

@section('title', 'Detail Balasan Admin')

@section('admin-content')
    <div class="toast-container" id="toastContainer">
        @if(session('success'))
            <div class="toast toast-success" role="alert">
                <div class="toast-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
                <div class="toast-content">
                    <p>{{ session('success') }}</p>
                </div>
                <button type="button" class="toast-close" aria-label="Close" onclick="this.closest('.toast').remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="toast toast-error" role="alert">
                <div class="toast-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>
                <div class="toast-content">
                    <p>{{ session('error') }}</p>
                </div>
                <button type="button" class="toast-close" aria-label="Close" onclick="this.closest('.toast').remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <main class="reply-detail-container">
        <div class="nav-wrapper">
            <a href="{{ route('admin.admin-replies.index') }}" class="btn-back-nav">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                <span>Kembali ke Daftar Balasan</span>
            </a>
        </div>

        <div class="row g-4 g-lg-5">
            <div class="col-lg-7 col-xl-8">
                <div class="card border-0 rounded-4 mb-4"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                    <div class="card-body p-4 p-xl-5">
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center shrink-0"
                                style="width: 56px; height: 56px; background: linear-gradient(135deg, #fff5f0, #fde8db); border: 1px solid #f1c3ae;">
                                <span class="fw-bold fs-5" style="color: #f8773c;">
                                    {{ substr($reply->admin->nama ?? 'A', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h2 class="h5 fw-semibold mb-0" style="color: #1e2937; letter-spacing: -0.01em;">
                                    {{ $reply->admin->nama ?? 'Admin' }}
                                </h2>
                                <div class="d-flex flex-wrap gap-3 text-muted small mt-1">
                                    <span class="d-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="#94a3b8" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        {{ $reply->replied_at->format('d M Y • H:i') }}
                                    </span>
                                    <span class="d-flex align-items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                            fill="none" stroke="#94a3b8" stroke-width="2">
                                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                        </svg>
                                        Balasan #{{ $reply->id }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <h6 class="small fw-semibold text-uppercase mb-3"
                                style="color: #64748b; letter-spacing: 0.02em;">Isi Balasan</h6>
                            <div class="p-4 rounded-4" style="background: #fff5f0; border: 1px solid #f1c3ae;">
                                <p class="mb-0 lh-lg" style="color: #1e2937; white-space: pre-wrap; font-size: 1rem;">
                                    {{ $reply->reply_message }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($reply->rating)
                    <div class="card border-0 rounded-4 mb-4"
                        style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                        <div class="card-body p-4 p-xl-5">
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <h6 class="small fw-semibold text-uppercase mb-0"
                                    style="color: #64748b; letter-spacing: 0.02em;">Rating Terkait</h6>
                                <a href="{{ route('admin.ratings.show', $reply->rating_id) }}"
                                    class="text-decoration-none d-flex align-items-center gap-1 small fw-medium px-3 py-1 rounded-pill"
                                    style="background: #f8fafc; color: #f8773c; border: 1px solid #f1c3ae; transition: all 0.2s ease;"
                                    onmouseover="this.style.background='#f8773c'; this.style.color='white'; this.style.borderColor='#f8773c'"
                                    onmouseout="this.style.background='#f8fafc'; this.style.color='#f8773c'; this.style.borderColor='#f1c3ae'">
                                    <span>Lihat Rating</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </a>
                            </div>

                            <div class="d-flex flex-column gap-3">
                                @if($reply->rating->tracking_code)
                                    <div class="d-flex justify-content-between align-items-center py-2"
                                        style="border-bottom: 1px solid #f0f0f0;">
                                        <span class="small text-muted">Tracking Code</span>
                                        <code class="small fw-medium"
                                            style="color: #1e2937;">{{ $reply->rating->tracking_code }}</code>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center py-2"
                                    style="border-bottom: 1px solid #f0f0f0;">
                                    <span class="small text-muted">Status Rating</span>
                                    @php
                                        $statusMap = ['active' => 'Aktif', 'inactive' => 'Nonaktif'];
                                        $statusColor = $reply->rating->status === 'active' ? '#10b981' : '#94a3b8';
                                        $statusBg = $reply->rating->status === 'active' ? '#ecfdf5' : '#f8fafc';
                                        $statusBorder = $reply->rating->status === 'active' ? '#a7f3d0' : '#e2e8f0';
                                    @endphp
                                    <span class="px-2 py-1 rounded-pill small fw-medium"
                                        style="background: {{ $statusBg }}; color: {{ $statusColor }}; border: 1px solid {{ $statusBorder }};">
                                        {{ $statusMap[$reply->rating->status] ?? ucfirst($reply->rating->status) }}
                                    </span>
                                </div>

                                @if($reply->rating->unit)
                                    <div class="d-flex justify-content-between align-items-center py-2"
                                        style="border-bottom: 1px solid #f0f0f0;">
                                        <span class="small text-muted">Unit</span>
                                        <span class="small fw-medium" style="color: #1e2937;">
                                            {{ $reply->rating->unit->name ?? '-' }}
                                        </span>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between align-items-center py-2"
                                    style="border-bottom: 1px solid #f0f0f0;">
                                    <span class="small text-muted">Dibuat</span>
                                    <span class="small fw-medium"
                                        style="color: #1e2937;">{{ $reply->rating->created_at->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-5 col-xl-4">
                <div class="card border-0 rounded-4 mb-4"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                    <div class="card-body p-4">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color: #64748b; letter-spacing: 0.02em;">
                            Detail Balasan</h6>

                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid #f0f0f0;">
                                <span class="small text-muted">ID Balasan</span>
                                <code class="small fw-medium" style="color: #1e2937;">#{{ $reply->id }}</code>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid #f0f0f0;">
                                <span class="small text-muted">ID Rating</span>
                                <code class="small fw-medium" style="color: #1e2937;">#{{ $reply->rating_id }}</code>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid #f0f0f0;">
                                <span class="small text-muted">Admin</span>
                                <span class="small fw-medium" style="color: #1e2937;">{{ $reply->admin->nama ?? '-' }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid #f0f0f0;">
                                <span class="small text-muted">Dibalas Pada</span>
                                <span class="small fw-medium"
                                    style="color: #1e2937;">{{ $reply->replied_at->format('d M Y') }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2"
                                style="border-bottom: 1px solid #f0f0f0;">
                                <span class="small text-muted">Dibuat</span>
                                <span class="small fw-medium"
                                    style="color: #1e2937;">{{ $reply->created_at->format('d M Y H:i') }}</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center py-2">
                                <span class="small text-muted">Diperbarui</span>
                                <span class="small fw-medium"
                                    style="color: #1e2937;">{{ $reply->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 rounded-4 mb-4"
                    style="background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02), 0 2px 6px rgba(0,0,0,0.03); border: 1px solid #e3e3e3;">
                    <div class="card-body p-4">
                        <h6 class="small fw-semibold text-uppercase mb-3" style="color: #64748b; letter-spacing: 0.02em;">
                            Aksi</h6>

                        <div class="d-flex flex-column gap-2">
                            <a href="{{ route('admin.admin-replies.edit', $reply->id) }}"
                                class="btn d-flex align-items-center justify-content-center gap-2 py-2 rounded-3 fw-medium"
                                style="background: #f8773c; color: white; border: none; font-size: 0.875rem; text-decoration: none; transition: all 0.2s;"
                                onmouseover="this.style.background='#e5621e'" onmouseout="this.style.background='#f8773c'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                Edit Balasan
                            </a>

                            <button type="button"
                                class="btn d-flex align-items-center justify-content-center gap-2 py-2 rounded-3 fw-medium"
                                style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-size: 0.875rem; transition: all 0.2s;"
                                onclick="openModal('deleteModalReply{{ $reply->id }}')"
                                onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='#fef2f2'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg>
                                Hapus Balasan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <x-admin.delete-modal-component id="deleteModalReply{{ $reply->id }}" title="Hapus Balasan"
        itemName="Balasan #{{ $reply->id }}"
        deleteRoute="{{ route('admin.admin-replies.destroy', $reply->id) }}" />

    <style>
        .reply-detail-container {
            padding: 2rem 2.5rem;
            max-width: 1200px;
        }

        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            margin-bottom: 0.75rem;
            min-width: 320px;
            border-left: 4px solid transparent;
            animation: slideIn 0.3s ease;
        }

        .toast-success {
            border-left-color: #10b981;
        }

        .toast-success .toast-icon {
            color: #10b981;
        }

        .toast-error {
            border-left-color: #ef4444;
        }

        .toast-error .toast-icon {
            color: #ef4444;
        }

        .toast-content {
            flex: 1;
        }

        .toast-content p {
            margin: 0;
            font-size: 0.875rem;
            color: #1e293b;
        }

        .toast-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 0.25rem;
            display: flex;
            border-radius: 6px;
            transition: all 0.2s;
        }

        .toast-close:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-back-nav {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 30px;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 2rem;
        }

        .btn-back-nav:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e293b;
            transform: translateX(-2px);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .reply-detail-container {
                padding: 1rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toasts = document.querySelectorAll('.toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                    toast.style.transform = 'translateX(100%)';
                    toast.style.opacity = '0';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            });
        });
    </script>
@endsection