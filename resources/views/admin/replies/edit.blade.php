@extends('layouts.admin.app')

@section('title', 'Edit Balasan Admin')

@section('admin-content')
    <div class="page-container">
        <header class="page-header">
            <div>
                <h1>Edit Balasan</h1>
                <p class="subtitle">Perbarui balasan untuk Rating #{{ $reply->rating_id }}</p>
            </div>
            <a href="{{ route('admin.admin-replies.show', $reply->id) }}" class="btn-ghost">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </header>

        @if($errors->any())
            <div class="alert alert-error" role="alert">
                <svg class="alert-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div>
                    <strong>Terjadi kesalahan:</strong>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button class="alert-close" onclick="this.parentElement.remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif

        <form action="{{ route('admin.admin-replies.update', $reply->id) }}" method="POST" id="replyForm"
            class="form-layout">
            @csrf
            @method('PUT')

            <div class="edit-grid">
                <!-- Left Column -->
                <div class="edit-main">
                    <!-- Card: Isi Balasan -->
                    <section class="form-card">
                        <h2 class="section-title">
                            <span class="section-number">1</span>
                            Isi Balasan
                        </h2>

                        <div class="form-group">
                            <label for="reply_message" class="form-label">
                                Pesan Balasan <span class="required">*</span>
                            </label>
                            <div class="textarea-wrapper">
                                <textarea id="reply_message" name="reply_message" rows="8"
                                    class="form-textarea @error('reply_message') is-invalid @enderror"
                                    placeholder="Tulis balasan untuk rating ini..." required
                                    maxlength="5000">{{ old('reply_message', $reply->reply_message) }}</textarea>
                                <div class="textarea-footer">
                                    <span class="char-counter" id="charCount">0/5000</span>
                                </div>
                            </div>
                            @error('reply_message')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <span class="help-text">Maksimal 5000 karakter. Tulis balasan yang jelas dan informatif.</span>
                        </div>

                        <div class="quick-responses-section mt-4">
                            <p class="small fw-semibold mb-3" style="color: #64748b;">Respons Cepat:</p>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="quick-response-btn"
                                    data-response="Terima kasih atas masukan Anda. Kami akan segera menindaklanjuti laporan ini.">
                                    Terima kasih
                                </button>
                                <button type="button" class="quick-response-btn"
                                    data-response="Masukan Anda telah kami terima dan sedang dalam proses penanganan oleh tim terkait.">
                                    Sedang diproses
                                </button>
                                <button type="button" class="quick-response-btn"
                                    data-response="Permasalahan yang Anda laporkan telah berhasil kami selesaikan. Terima kasih atas laporan Anda.">
                                    Sudah diselesaikan
                                </button>
                            </div>
                        </div>
                    </section>

                    <!-- Card: Pesan Sebelumnya -->
                    <section class="form-card">
                        <h2 class="section-title-sm">Pesan Sebelumnya</h2>
                        <div class="prev-message-box">
                            <p class="mb-0 small lh-lg" style="color: #475569; white-space: pre-wrap;">
                                {{ $reply->reply_message }}
                            </p>
                        </div>
                    </section>
                </div>

                <!-- Right Column -->
                <div class="edit-sidebar">
                    <!-- Card: Info Rating -->
                    <section class="form-card">
                        <h2 class="section-title-sm">Info Rating</h2>

                        <div class="info-row">
                            <span class="info-label">ID Balasan</span>
                            <code class="info-value-code">#{{ $reply->id }}</code>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Rating</span>
                            <a href="{{ route('admin.ratings.show', $reply->rating_id) }}"
                                class="info-value-link">#{{ $reply->rating_id }} →</a>
                        </div>
                        @if($reply->rating && $reply->rating->tracking_code)
                            <div class="info-row">
                                <span class="info-label">Tracking</span>
                                <code class="info-value-code">{{ $reply->rating->tracking_code }}</code>
                            </div>
                        @endif
                        @if($reply->rating && $reply->rating->unit)
                            <div class="info-row">
                                <span class="info-label">Unit</span>
                                <span class="info-value">{{ Str::limit($reply->rating->unit->name ?? '-', 20) }}</span>
                            </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">Admin</span>
                            <span class="info-value">{{ $reply->admin->nama ?? '-' }}</span>
                        </div>
                        <div class="info-row" style="border-bottom: none;">
                            <span class="info-label">Dibalas</span>
                            <span class="info-value">{{ $reply->replied_at->format('d M Y') }}</span>
                        </div>
                    </section>

                    <!-- Card: Aksi -->
                    <section class="form-card">
                        <h2 class="section-title-sm">Aksi</h2>

                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn-submit" id="submitBtn">
                                <span class="btn-text">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v14a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                    Simpan Perubahan
                                </span>
                                <span class="btn-loader" style="display: none;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" class="spin">
                                        <path d="M21 12a9 9 0 1 1-6.219-8.56" />
                                    </svg>
                                    Menyimpan...
                                </span>
                            </button>

                            <a href="{{ route('admin.admin-replies.show', $reply->id) }}" class="btn-cancel">
                                Batal
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </form>
    </div>

    <style>
        .page-container {
            padding: 2rem 2.5rem;
            max-width: 1200px;
        }

        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 2rem;
            gap: 1rem;
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e2937;
            margin: 0 0 0.25rem 0;
            letter-spacing: -0.02em;
        }

        .page-header .subtitle {
            color: #64748b;
            font-size: 0.9rem;
            margin: 0;
        }

        .btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #475569;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
            white-space: nowrap;
            background: white;
        }

        .btn-ghost:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e2937;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        .alert-icon {
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .alert div {
            flex: 1;
            font-size: 0.875rem;
        }

        .alert ul {
            margin: 0.5rem 0 0;
            padding-left: 1.25rem;
        }

        .alert-close {
            background: none;
            border: none;
            cursor: pointer;
            color: inherit;
            opacity: 0.6;
            padding: 0.125rem;
            display: flex;
            border-radius: 4px;
            transition: opacity 0.15s;
            flex-shrink: 0;
        }

        .alert-close:hover {
            opacity: 1;
        }

        .form-layout {
            display: flex;
            flex-direction: column;
        }

        .edit-grid {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 1.5rem;
            align-items: start;
        }

        .edit-main,
        .edit-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .form-card {
            background: white;
            border-radius: 14px;
            padding: 1.75rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02), 0 2px 6px rgba(0, 0, 0, 0.03);
            border: 1px solid #e3e3e3;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #1e2937;
            margin: 0 0 1.5rem 0;
        }

        .section-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 26px;
            height: 26px;
            background: linear-gradient(135deg, #f8773c, #e5621e);
            color: white;
            border-radius: 50%;
            font-size: 0.75rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .section-title-sm {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #64748b;
            margin: 0 0 1rem 0;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .form-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        .required {
            color: #ef4444;
        }

        .textarea-wrapper {
            display: flex;
            flex-direction: column;
        }

        .form-textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 0.9rem;
            color: #1e2937;
            background: #fafafa;
            transition: all 0.15s ease;
            resize: vertical;
            min-height: 200px;
            font-family: inherit;
            line-height: 1.6;
        }

        .form-textarea:focus {
            outline: none;
            border-color: #f8773c;
            background: white;
            box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.1);
        }

        .form-textarea.is-invalid {
            border-color: #ef4444;
            background: #fef2f2;
        }

        .form-textarea.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .textarea-footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 0.375rem;
        }

        .char-counter {
            font-size: 0.75rem;
            color: #94a3b8;
        }

        .error-message {
            font-size: 0.8rem;
            color: #ef4444;
        }

        .help-text {
            font-size: 0.78rem;
            color: #94a3b8;
        }

        .quick-responses-section {
            padding-top: 1rem;
            border-top: 1px solid #f0f0f0;
        }

        .quick-response-btn {
            padding: 0.35rem 0.85rem;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            background: white;
            color: #475569;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .quick-response-btn:hover {
            border-color: #f8773c;
            color: #f8773c;
            background: #fff5f0;
        }

        .prev-message-box {
            padding: 0.875rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f0f0f0;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 0.8rem;
            color: #94a3b8;
            flex-shrink: 0;
        }

        .info-value {
            font-size: 0.8rem;
            font-weight: 500;
            color: #1e2937;
            text-align: right;
        }

        .info-value-code {
            font-size: 0.75rem;
            font-weight: 500;
            color: #1e2937;
            background: #f8fafc;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
        }

        .info-value-link {
            font-size: 0.8rem;
            font-weight: 500;
            color: #f8773c;
            text-decoration: none;
        }

        .info-value-link:hover {
            text-decoration: underline;
        }

        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.65rem 1.25rem;
            background: #f8773c;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-submit:hover:not(:disabled) {
            background: #e5621e;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(248, 119, 60, 0.3);
        }

        .btn-submit:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .btn-cancel {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.65rem 1.25rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            background: white;
            transition: all 0.15s ease;
            text-align: center;
        }

        .btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #1e2937;
        }

        .btn-text,
        .btn-loader {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .spin {
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @media (max-width: 900px) {
            .edit-grid {
                grid-template-columns: 1fr;
            }

            .edit-sidebar {
                order: -1;
            }
        }

        @media (max-width: 640px) {
            .page-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                initCharCounter();
                initQuickResponses();
                initFormValidation();
            });

            function initCharCounter() {
                const textarea = document.getElementById('reply_message');
                const counter = document.getElementById('charCount');

                if (!textarea || !counter) return;

                const update = () => {
                    const len = textarea.value.length;
                    counter.textContent = `${len}/5000`;
                    counter.style.color = len > 4800 ? '#ef4444' : len > 4500 ? '#eab308' : '#94a3b8';
                };

                update();
                textarea.addEventListener('input', update);
            }

            function initQuickResponses() {
                const textarea = document.getElementById('reply_message');
                if (!textarea) return;

                document.querySelectorAll('.quick-response-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        textarea.value = this.dataset.response;
                        textarea.dispatchEvent(new Event('input'));
                        textarea.focus();
                    });
                });
            }

            function initFormValidation() {
                const form = document.getElementById('replyForm');
                const submitBtn = document.getElementById('submitBtn');

                if (!form) return;

                form.addEventListener('submit', function (e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let isValid = true;
                    let firstError = null;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            field.classList.add('is-invalid');
                            isValid = false;
                            if (!firstError) firstError = field;
                        } else {
                            field.classList.remove('is-invalid');
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        if (firstError) {
                            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            firstError.focus();
                        }
                        return;
                    }

                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.querySelector('.btn-text').hidden = true;
                        submitBtn.querySelector('.btn-loader').hidden = false;
                    }
                });

                form.querySelectorAll('.form-textarea').forEach(field => {
                    field.addEventListener('input', function () {
                        this.classList.remove('is-invalid');
                    });
                });
            }
        </script>
    @endpush
@endsection