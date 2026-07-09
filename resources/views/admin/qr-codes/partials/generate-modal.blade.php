{{-- Generate QR Code Modal --}}
<div class="modal fade" id="generateQrModal" tabindex="-1" aria-labelledby="generateQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content generate-modal-content">
            <div class="modal-header generate-modal-header">
                <div class="modal-header-content">
                    <div class="modal-icon-wrapper">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <div>
                        <h5 class="modal-title" id="generateQrModalLabel">Generate QR Code</h5>
                        <p class="modal-subtitle">Pilih unit untuk membuat QR Code baru</p>
                    </div>
                </div>
                <button type="button" class="modal-close-btn" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="modal-body generate-modal-body">
                <div x-data="generateModal()" x-init="init()">
                    {{-- Error Alert --}}
                    <template x-if="error">
                        <div class="alert-generate alert-error" x-show="error" x-transition>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <span x-text="error"></span>
                        </div>
                    </template>

                    {{-- Unit Selection --}}
                    <div class="form-group-generate">
                        <label class="label-generate">
                            Pilih Unit <span class="required-mark">*</span>
                        </label>

                        <div class="searchable-select-wrapper">
                            <button type="button" class="search-trigger" @click="toggleSearch()"
                                :class="{ 'active': isSearchOpen }">
                                <template x-if="!selectedUnit">
                                    <span class="placeholder-text">Cari dan pilih unit...</span>
                                </template>
                                <template x-if="selectedUnit">
                                    <div class="selected-unit-preview">
                                        <div class="unit-icon-mini">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                <polyline points="9 22 9 12 15 12 15 22" />
                                            </svg>
                                        </div>
                                        <div class="selected-info">
                                            <span class="selected-name" x-text="selectedUnit.name"></span>
                                            <span class="selected-meta" x-text="selectedUnit.code"></span>
                                        </div>
                                    </div>
                                </template>
                                <svg class="chevron-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6" />
                                </svg>
                            </button>

                            <div class="search-dropdown" x-show="isSearchOpen" @click.away="closeSearch()" x-transition>
                                <div class="search-input-wrapper">
                                    <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8" />
                                        <path d="m21 21-4.35-4.35" />
                                    </svg>
                                    <input type="text" class="search-input" placeholder="Ketik nama atau kode unit..."
                                        x-model="searchQuery" @input="searchUnits()" @click.stop x-ref="searchInput">
                                </div>

                                <div class="search-results">
                                    <template x-if="isLoading">
                                        <div class="loading-state">
                                            <svg class="spinner-small" width="20" height="20" viewBox="0 0 24 24">
                                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"
                                                    fill="none" stroke-dasharray="60" stroke-dashoffset="20" />
                                            </svg>
                                            <span>Mencari unit...</span>
                                        </div>
                                    </template>

                                    <template x-if="!isLoading && searchResults.length === 0 && searchQuery">
                                        <div class="no-results">
                                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="1.5">
                                                <circle cx="11" cy="11" r="8" />
                                                <path d="m21 21-4.35-4.35" />
                                            </svg>
                                            <span>Tidak ada unit ditemukan</span>
                                            <span class="no-results-hint">Coba kata kunci lain</span>
                                        </div>
                                    </template>

                                    <template x-if="!isLoading && searchResults.length > 0">
                                        <div class="results-list">
                                            <template x-for="unit in searchResults" :key="unit.id">
                                                <button type="button" class="unit-result-card"
                                                    :class="{ 'selected': selectedUnit && selectedUnit.id === unit.id }"
                                                    @click="selectUnit(unit)">
                                                    <div class="result-icon">
                                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2">
                                                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                            <polyline points="9 22 9 12 15 12 15 22" />
                                                        </svg>
                                                    </div>
                                                    <div class="result-info">
                                                        <div class="result-name" x-text="unit.name"></div>
                                                        <div class="result-meta">
                                                            <span class="meta-code" x-text="unit.code"></span>
                                                            <span class="meta-separator">•</span>
                                                            <span class="meta-type" x-text="unit.type"></span>
                                                        </div>
                                                        <div class="result-department">
                                                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2">
                                                                <rect x="2" y="7" width="20" height="14" rx="2"
                                                                    ry="2" />
                                                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                                            </svg>
                                                            <span x-text="unit.department"></span>
                                                        </div>
                                                    </div>
                                                    <svg class="check-icon" width="18" height="18" viewBox="0 0 24 24"
                                                        fill="none" stroke="currentColor" stroke-width="2.5">
                                                        <polyline points="20 6 9 17 4 12" />
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Warning Notice --}}
                    <div class="warning-notice">
                        <div class="warning-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="warning-content">
                            <div class="warning-title">Perhatian</div>
                            <p class="warning-text">
                                Jika unit sudah memiliki QR Code aktif, sistem akan menolak pembuatan baru. Gunakan
                                tombol Regenerate pada tabel QR Codes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer generate-modal-footer">
                <button type="button" class="btn-cancel-generate" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" class="btn-generate" x-data="generateModal()" @click="generate()"
                    :disabled="!selectedUnit || isLoading">
                    <template x-if="isLoading">
                        <svg class="spinner-small" width="16" height="16" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none"
                                stroke-dasharray="60" stroke-dashoffset="20" />
                        </svg>
                    </template>
                    <span x-text="isLoading ? 'Generating...' : 'Generate QR Code'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .generate-modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .generate-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1.5rem 1.5rem 1.25rem;
            border-bottom: 1px solid #e5e7eb;
            background: linear-gradient(to bottom, #ffffff, #fafbfc);
        }

        .modal-header-content {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
        }

        .modal-icon-wrapper {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #fff5f0, #ffe8d6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8773c;
            flex-shrink: 0;
        }

        .modal-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.125rem 0;
            letter-spacing: -0.02em;
        }

        .modal-subtitle {
            font-size: 0.8125rem;
            color: #64748b;
            margin: 0;
            font-weight: 400;
        }

        .modal-close-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #f1f5f9;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .modal-close-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        .generate-modal-body {
            padding: 1.5rem;
            background: #ffffff;
        }

        .form-group-generate {
            margin-bottom: 1.25rem;
        }

        .label-generate {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            letter-spacing: 0.01em;
        }

        .required-mark {
            color: #ef4444;
            margin-left: 0.125rem;
        }

        .searchable-select-wrapper {
            position: relative;
        }

        .search-trigger {
            width: 100%;
            padding: 0.75rem 1rem;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            font-family: inherit;
        }

        .search-trigger:hover {
            border-color: #cbd5e1;
            background: #fafbfc;
        }

        .search-trigger.active {
            border-color: #f8773c;
            box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.1);
        }

        .placeholder-text {
            flex: 1;
            color: #9ca3af;
            font-size: 0.875rem;
        }

        .selected-unit-preview {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .unit-icon-mini {
            width: 28px;
            height: 28px;
            background: #fff5f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8773c;
            flex-shrink: 0;
        }

        .selected-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.125rem;
        }

        .selected-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.2;
        }

        .selected-meta {
            font-size: 0.75rem;
            color: #64748b;
            font-family: 'SF Mono', 'Menlo', monospace;
        }

        .chevron-icon {
            color: #9ca3af;
            transition: transform 0.2s;
            flex-shrink: 0;
        }

        .search-trigger.active .chevron-icon {
            transform: rotate(180deg);
            color: #f8773c;
        }

        .search-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            left: 0;
            right: 0;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            overflow: hidden;
        }

        .search-input-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 1rem;
            border-bottom: 1px solid #e5e7eb;
            background: #fafbfc;
        }

        .search-icon {
            color: #9ca3af;
            flex-shrink: 0;
        }

        .search-input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 0.875rem;
            color: #0f172a;
            outline: none;
            font-family: inherit;
        }

        .search-input::placeholder {
            color: #9ca3af;
        }

        .search-results {
            max-height: 320px;
            overflow-y: auto;
            padding: 0.5rem;
        }

        .loading-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 2rem 1rem;
            color: #64748b;
            font-size: 0.875rem;
        }

        .spinner-small {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .no-results {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 2rem 1rem;
            color: #9ca3af;
        }

        .no-results svg {
            opacity: 0.3;
        }

        .no-results span:first-of-type {
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
        }

        .no-results-hint {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .results-list {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .unit-result-card {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.15s;
            text-align: left;
            font-family: inherit;
        }

        .unit-result-card:hover {
            border-color: #f8773c;
            background: #fff5f0;
        }

        .unit-result-card.selected {
            border-color: #f8773c;
            background: #fff5f0;
            box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.1);
        }

        .result-icon {
            width: 36px;
            height: 36px;
            background: #fff5f0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f8773c;
            flex-shrink: 0;
        }

        .unit-result-card.selected .result-icon {
            background: #f8773c;
            color: #ffffff;
        }

        .result-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .result-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            line-height: 1.2;
        }

        .result-meta {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: #64748b;
        }

        .meta-code {
            font-family: 'SF Mono', 'Menlo', monospace;
            font-weight: 500;
        }

        .meta-separator {
            color: #cbd5e1;
        }

        .meta-type {
            color: #64748b;
        }

        .result-department {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            color: #64748b;
            margin-top: 0.125rem;
        }

        .result-department svg {
            opacity: 0.6;
        }

        .check-icon {
            color: #f8773c;
            flex-shrink: 0;
            opacity: 0;
            transition: opacity 0.15s;
        }

        .unit-result-card.selected .check-icon {
            opacity: 1;
        }

        .warning-notice {
            display: flex;
            gap: 0.875rem;
            padding: 1rem 1.125rem;
            background: #fef3c7;
            border: 1px solid #fde68a;
            border-radius: 12px;
            margin-top: 1.25rem;
        }

        .warning-icon {
            flex-shrink: 0;
            color: #f59e0b;
            margin-top: 0.125rem;
        }

        .warning-content {
            flex: 1;
        }

        .warning-title {
            font-size: 0.8125rem;
            font-weight: 700;
            color: #92400e;
            margin-bottom: 0.25rem;
        }

        .warning-text {
            font-size: 0.8125rem;
            color: #78350f;
            margin: 0;
            line-height: 1.5;
        }

        .generate-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding: 1.25rem 1.5rem;
            background: #fafbfc;
            border-top: 1px solid #e5e7eb;
        }

        .btn-cancel-generate {
            padding: 0.625rem 1.25rem;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-cancel-generate:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        .btn-generate {
            padding: 0.625rem 1.5rem;
            background: #f8773c;
            border: none;
            border-radius: 10px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-family: inherit;
            box-shadow: 0 4px 12px rgba(248, 119, 60, 0.25);
        }

        .btn-generate:hover:not(:disabled) {
            background: #e55a2b;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(248, 119, 60, 0.35);
        }

        .btn-generate:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .alert-generate {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            padding: 0.875rem 1rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            font-size: 0.8125rem;
            font-weight: 500;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #b91c1c;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('generateModal', () => ({
                selectedUnit: null,
                searchQuery: '',
                searchResults: [],
                isLoading: false,
                isSearchOpen: false,
                error: null,
                searchTimeout: null,

                init() {
                    this.loadInitialUnits();
                },

                async loadInitialUnits() {
                    this.isLoading = true;
                    try {
                        const response = await fetch('{{ route("admin.qr-codes.search") }}?limit=5', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.searchResults = data.units;
                        }
                    } catch (err) {
                        console.error('Error loading units:', err);
                    } finally {
                        this.isLoading = false;
                    }
                },

                toggleSearch() {
                    this.isSearchOpen = !this.isSearchOpen;
                    if (this.isSearchOpen) {
                        setTimeout(() => {
                            this.$refs.searchInput?.focus();
                        }, 100);
                    }
                },

                closeSearch() {
                    setTimeout(() => {
                        this.isSearchOpen = false;
                    }, 200);
                },

                async searchUnits() {
                    clearTimeout(this.searchTimeout);

                    if (!this.searchQuery.trim()) {
                        this.loadInitialUnits();
                        return;
                    }

                    this.searchTimeout = setTimeout(async () => {
                        this.isLoading = true;
                        try {
                            const response = await fetch(`{{ route("admin.qr-codes.search") }}?q=${encodeURIComponent(this.searchQuery)}&limit=5`, {
                                headers: {
                                    'Accept': 'application/json',
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.searchResults = data.units;
                            }
                        } catch (err) {
                            console.error('Error searching units:', err);
                            this.searchResults = [];
                        } finally {
                            this.isLoading = false;
                        }
                    }, 300);
                },

                selectUnit(unit) {
                    this.selectedUnit = unit;
                    this.isSearchOpen = false;
                    this.error = null;
                },

                async generate() {
                    if (!this.selectedUnit) return;

                    this.isLoading = true;
                    this.error = null;

                    try {
                        const response = await fetch('{{ route("admin.qr-codes.generate") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ unit_id: this.selectedUnit.id })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            window.location.reload();
                        } else {
                            this.error = data.message || 'Gagal generate QR Code';
                        }
                    } catch (err) {
                        this.error = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                    } finally {
                        this.isLoading = false;
                    }
                }
            }));
        });
    </script>
@endpush