<div class="modal fade" id="generateQrModal" tabindex="-1" aria-labelledby="generateQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
        <div class="modal-content qr-modal" x-data="generateModal()" x-init="init()" x-cloak>

            {{-- Header --}}
            <div class="qr-header">
                <div class="qr-header-left">
                    <div class="qr-header-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1.5"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1.5"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1.5"></rect>
                        </svg>
                    </div>
                    <div>
                        <h5 class="qr-title" id="generateQrModalLabel">Generate QR Code</h5>
                        <p class="qr-subtitle">Pilih unit yang belum memiliki QR Code aktif</p>
                    </div>
                </div>
                <button type="button" class="qr-close" data-bs-dismiss="modal" aria-label="Close" @click="reset()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M18 6L6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="qr-body">
                {{-- Error Alert --}}
                <template x-if="error">
                    <div class="qr-alert qr-alert-error" x-show="error" x-transition.opacity>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span x-text="error"></span>
                    </div>
                </template>

                {{-- Selected Unit Preview --}}
                <div x-show="selectedUnit" class="qr-selected-bar" x-transition>
                    <div class="qr-selected-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                            <polyline points="9 22 9 12 15 12 15 22" />
                        </svg>
                    </div>
                    <div class="qr-selected-info">
                        <span class="qr-selected-name" x-text="selectedUnit ? selectedUnit.name : ''"></span>
                        <span class="qr-selected-meta" x-text="selectedUnit ? (selectedUnit.code + ' • ' + selectedUnit.type) : ''"></span>
                    </div>
                    <button type="button" class="qr-selected-clear" @click="clearSelection()" aria-label="Hapus pilihan">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                            <path d="M18 6L6 18M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Search --}}
                <div class="qr-search-wrap">
                    <label class="qr-label">Cari Unit</label>
                    <div class="qr-search-box" x-ref="searchBox">
                        <div class="qr-search-input-wrap">
                            <svg class="qr-search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round">
                                <circle cx="11" cy="11" r="8" />
                                <path d="m21 21-4.35-4.35" />
                            </svg>
                            <input
                                type="text"
                                class="qr-search-input"
                                placeholder="Ketik nama atau kode unit..."
                                x-model="searchQuery"
                                @input="searchUnits()"
                                @focus="openSearch()"
                                @keydown.escape="closeSearch()"
                                x-ref="searchInput">
                            <button type="button" class="qr-search-clear" x-show="searchQuery" @click="clearSearch()" aria-label="Hapus pencarian">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                                    <path d="M18 6L6 18M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        {{-- Dropdown Results: Max 2 --}}
                        <div class="qr-search-dropdown" x-show="isSearchOpen && (searchResults.length > 0 || isSearching)" x-transition:enter="dd-enter" x-transition:enter-start="dd-enter-start" x-transition:enter-end="dd-enter-end" x-transition:leave="dd-leave" x-transition:leave-start="dd-leave-start" x-transition:leave-end="dd-leave-end" @click.away="closeSearch()">
                            <template x-if="isSearching">
                                <div class="qr-search-loading">
                                    <div class="qr-skeleton-sm"></div>
                                    <div class="qr-skeleton-sm" style="width: 80%"></div>
                                </div>
                            </template>
                            <template x-if="!isSearching && searchResults.length > 0">
                                <div>
                                    <template x-for="unit in searchResults" :key="unit.id">
                                        <button type="button" class="qr-search-item" @click="selectUnit(unit)">
                                            <div class="qr-search-item-icon">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                                    <polyline points="9 22 9 12 15 12 15 22" />
                                                </svg>
                                            </div>
                                            <div class="qr-search-item-info">
                                                <span class="qr-search-item-name" x-text="unit.name"></span>
                                                <span class="qr-search-item-meta" x-text="unit.code + ' • ' + unit.type"></span>
                                            </div>
                                        </button>
                                    </template>
                                    <div class="qr-search-footer">Maksimal 2 hasil pencarian</div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Section Label --}}
                <div class="qr-section-header">
                    <span class="qr-section-title">Unit Tersedia</span>
                    <span class="qr-section-badge" x-show="!isLoadingGrid && gridUnits.length > 0" x-text="gridUnits.length + ' unit'"></span>
                </div>

                {{-- Grid 2x2 --}}
                <div class="qr-grid">
                    <template x-if="isLoadingGrid">
                        <template x-for="n in 4" :key="n">
                            <div class="qr-grid-card qr-grid-skeleton">
                                <div class="qr-skeleton-icon"></div>
                                <div class="qr-skeleton-lines">
                                    <div class="qr-skeleton-line"></div>
                                    <div class="qr-skeleton-line" style="width: 60%"></div>
                                </div>
                            </div>
                        </template>
                    </template>

                    <template x-if="!isLoadingGrid && gridUnits.length === 0">
                        <div class="qr-grid-empty">
                            <div class="qr-empty-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                            </div>
                            <span class="qr-empty-title">Tidak ada unit tersedia</span>
                            <span class="qr-empty-hint">Semua unit sudah memiliki QR Code aktif</span>
                        </div>
                    </template>

                    <template x-if="!isLoadingGrid && gridUnits.length > 0">
                        <template x-for="unit in gridUnits" :key="unit.id">
                            <button
                                type="button"
                                class="qr-grid-card"
                                :class="{ 'selected': selectedUnit && selectedUnit.id === unit.id }"
                                @click="selectUnit(unit)">
                                <div class="qr-grid-icon">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                                        <polyline points="9 22 9 12 15 12 15 22" />
                                    </svg>
                                </div>
                                <div class="qr-grid-info">
                                    <div class="qr-grid-name" x-text="unit.name"></div>
                                    <div class="qr-grid-meta">
                                        <span x-text="unit.code"></span>
                                        <span class="qr-grid-sep">&bull;</span>
                                        <span x-text="unit.type"></span>
                                    </div>
                                    <div class="qr-grid-dept" x-show="unit.department">
                                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2" />
                                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                        </svg>
                                        <span x-text="unit.department"></span>
                                    </div>
                                </div>
                                <div class="qr-grid-check">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </div>
                            </button>
                        </template>
                    </template>
                </div>

                {{-- Info Banner --}}
                <div class="qr-info">
                    <div class="qr-info-icon">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <path d="M12 16v-4"></path>
                            <path d="M12 8h.01"></path>
                        </svg>
                    </div>
                    <p class="qr-info-text">Unit yang sudah memiliki QR Code aktif tidak ditampilkan. Gunakan tombol <strong>Regenerate</strong> pada tabel untuk memperbarui QR Code yang sudah ada.</p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="qr-footer">
                <button type="button" class="qr-btn qr-btn-cancel" data-bs-dismiss="modal" @click="reset()">Batal</button>
                <button
                    type="button"
                    class="qr-btn qr-btn-generate"
                    @click="generate()"
                    :disabled="!selectedUnit || isLoading"
                    :class="{ 'is-loading': isLoading }">
                    <template x-if="isLoading">
                        <svg class="qr-spinner" width="14" height="14" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="60" stroke-dashoffset="20" stroke-linecap="round" />
                        </svg>
                    </template>
                    <template x-if="!isLoading">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                            <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                            <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                            <rect x="3" y="14" width="7" height="7" rx="1"></rect>
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
[x-cloak] { display: none !important; }

/* =========================================================
   PREMIUM SAAS — Generate QR Modal (Solid Colors Only)
   Accent: #f8773c
   ========================================================= */

.qr-modal {
    border: none;
    border-radius: 20px;
    background: #ffffff;
    box-shadow:
        0 24px 48px -12px rgba(15, 23, 42, 0.18),
        0 0 0 1px rgba(15, 23, 42, 0.05);
    font-family: 'Plus Jakarta Sans', 'Inter', system-ui, -apple-system, sans-serif;
    overflow: hidden;
}

/* ---------- Header ---------- */
.qr-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
    background: #ffffff;
}

.qr-header-left {
    display: flex;
    align-items: center;
    gap: 14px;
}

.qr-header-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: #fff7ed;
    border: 1px solid #ffedd5;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #f8773c;
    flex-shrink: 0;
}

.qr-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 2px 0;
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.qr-subtitle {
    font-size: 12px;
    color: #64748b;
    margin: 0;
    font-weight: 450;
}

.qr-close {
    width: 32px;
    height: 32px;
    border-radius: 10px;
    background: #f1f5f9;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}

.qr-close:hover {
    background: #e2e8f0;
    color: #0f172a;
}

/* ---------- Body ---------- */
.qr-body {
    padding: 22px 24px 10px;
    background: #ffffff;
}

/* Selected Bar */
.qr-selected-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #fff7ed;
    border: 1px solid #ffedd5;
    border-radius: 10px;
    margin-bottom: 14px;
}

.qr-selected-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #f8773c;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.qr-selected-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
}

.qr-selected-name {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.qr-selected-meta {
    font-size: 11px;
    color: #64748b;
    font-family: 'SF Mono', 'JetBrains Mono', monospace;
}

.qr-selected-clear {
    width: 22px;
    height: 22px;
    border-radius: 6px;
    border: none;
    background: #ffedd5;
    color: #9a3412;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.15s ease;
    flex-shrink: 0;
}

.qr-selected-clear:hover {
    background: #fecaca;
    color: #dc2626;
}

/* Search */
.qr-search-wrap { margin-bottom: 16px; }

.qr-label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
    letter-spacing: 0.01em;
}

.qr-search-box {
    position: relative;
}

.qr-search-input-wrap {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.2s ease;
}

.qr-search-input-wrap:focus-within {
    background: #ffffff;
    border-color: #f8773c;
    box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.12);
}

.qr-search-icon {
    color: #94a3b8;
    flex-shrink: 0;
}

.qr-search-input {
    flex: 1;
    border: none;
    background: transparent;
    font-size: 13.5px;
    color: #0f172a;
    outline: none;
    font-family: inherit;
    min-width: 0;
    font-weight: 450;
}

.qr-search-input::placeholder {
    color: #94a3b8;
}

.qr-search-clear {
    width: 20px;
    height: 20px;
    border-radius: 5px;
    border: none;
    background: #e2e8f0;
    color: #64748b;
    cursor: pointer;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.15s ease;
}

.qr-search-clear:hover {
    background: #fee2e2;
    color: #dc2626;
}

/* Dropdown */
.qr-search-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 12px 24px -4px rgba(15, 23, 42, 0.1);
    z-index: 60;
    overflow: hidden;
    transform-origin: top center;
}

.dd-enter { transition: opacity 0.18s ease, transform 0.18s cubic-bezier(0.16, 1, 0.3, 1); }
.dd-enter-start { opacity: 0; transform: translateY(-4px) scale(0.98); }
.dd-enter-end { opacity: 1; transform: translateY(0) scale(1); }
.dd-leave { transition: opacity 0.12s ease, transform 0.12s ease; }
.dd-leave-start { opacity: 1; transform: translateY(0) scale(1); }
.dd-leave-end { opacity: 0; transform: translateY(-2px) scale(0.99); }

.qr-search-loading {
    padding: 14px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.qr-skeleton-sm {
    height: 10px;
    border-radius: 4px;
    background: #f1f5f9;
    width: 100%;
}

.qr-search-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 14px;
    background: #ffffff;
    border: none;
    border-bottom: 1px solid #f8fafc;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
    transition: background 0.15s ease;
}

.qr-search-item:last-child {
    border-bottom: none;
}

.qr-search-item:hover {
    background: #fff7ed;
}

.qr-search-item-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: #fff7ed;
    border: 1px solid #ffedd5;
    color: #f8773c;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.qr-search-item-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 1px;
    min-width: 0;
}

.qr-search-item-name {
    font-size: 13px;
    font-weight: 600;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.qr-search-item-meta {
    font-size: 11px;
    color: #64748b;
    font-family: 'SF Mono', 'JetBrains Mono', monospace;
}

.qr-search-footer {
    padding: 8px 14px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
    font-size: 11px;
    color: #94a3b8;
    font-weight: 500;
    text-align: center;
}

/* Section Header */
.qr-section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.qr-section-title {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.qr-section-badge {
    font-size: 11px;
    font-weight: 600;
    color: #f8773c;
    background: #fff7ed;
    padding: 2px 8px;
    border-radius: 999px;
    border: 1px solid #ffedd5;
}

/* Grid 2x2 */
.qr-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
    margin-bottom: 4px;
}

.qr-grid-card {
    position: relative;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 13px;
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    text-align: left;
    font-family: inherit;
    transition: all 0.15s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}

.qr-grid-card:hover {
    border-color: #f8773c;
    background: #fffaf5;
    box-shadow: 0 4px 12px -2px rgba(248, 119, 60, 0.1);
}

.qr-grid-card.selected {
    border-color: #f8773c;
    background: #fff7ed;
    box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.1);
}

.qr-grid-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #fff7ed;
    border: 1px solid #ffedd5;
    color: #f8773c;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.15s ease;
}

.qr-grid-card.selected .qr-grid-icon {
    background: #f8773c;
    color: #ffffff;
    border-color: #f8773c;
}

.qr-grid-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 3px;
    min-width: 0;
}

.qr-grid-name {
    font-size: 12px;
    font-weight: 600;
    color: #0f172a;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    letter-spacing: -0.01em;
}

.qr-grid-meta {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 10.5px;
    color: #64748b;
    font-family: 'SF Mono', 'JetBrains Mono', monospace;
}

.qr-grid-sep { color: #cbd5e1; }

.qr-grid-dept {
    display: flex;
    align-items: center;
    gap: 3px;
    font-size: 10px;
    color: #94a3b8;
    margin-top: 1px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.qr-grid-dept svg { opacity: 0.6; flex-shrink: 0; }

.qr-grid-check {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #f8773c;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transform: scale(0.6);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}

.qr-grid-card.selected .qr-grid-check {
    opacity: 1;
    transform: scale(1);
}

/* Skeleton Grid Card */
.qr-grid-skeleton {
    cursor: default;
    pointer-events: none;
}

.qr-skeleton-icon {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #f1f5f9;
    flex-shrink: 0;
}

.qr-skeleton-lines {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.qr-skeleton-line {
    height: 9px;
    border-radius: 4px;
    background: #f1f5f9;
    width: 100%;
}

/* Empty State inside Grid */
.qr-grid-empty {
    grid-column: 1 / -1;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 28px 16px;
    text-align: center;
}

.qr-empty-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #f1f5f9;
    color: #94a3b8;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 2px;
}

.qr-empty-title {
    font-size: 13px;
    font-weight: 600;
    color: #475569;
}

.qr-empty-hint {
    font-size: 11.5px;
    color: #94a3b8;
    font-weight: 450;
}

.qr-info {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 11px 13px;
    background: #fefce8;
    border: 1px solid #fef08a;
    border-radius: 10px;
    margin-top: 14px;
}

.qr-info-icon {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #fef08a;
    color: #854d0e;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-info-text {
    font-size: 11.5px;
    color: #a16207;
    margin: 0;
    line-height: 1.5;
    font-weight: 450;
}

.qr-info-text strong {
    font-weight: 600;
    color: #713f12;
}

/* ---------- Footer ---------- */
.qr-footer {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding: 16px 24px 20px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
}

.qr-btn {
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    font-family: inherit;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    letter-spacing: -0.01em;
}

.qr-btn-cancel {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    color: #475569;
}

.qr-btn-cancel:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
    color: #0f172a;
}

.qr-btn-generate {
    background: #f8773c;
    border: none;
    color: #ffffff;
    box-shadow: 0 4px 12px -2px rgba(248, 119, 60, 0.3);
}

.qr-btn-generate:hover:not(:disabled) {
    background: #ea580c;
    box-shadow: 0 6px 16px -2px rgba(248, 119, 60, 0.4);
}

.qr-btn-generate:active:not(:disabled) {
    transform: scale(0.98);
}

.qr-btn-generate:disabled {
    background: #e2e8f0;
    color: #94a3b8;
    cursor: not-allowed;
    box-shadow: none;
}

.qr-btn-generate.is-loading {
    cursor: progress;
    opacity: 0.85;
}

/* Spinner */
.qr-spinner {
    animation: qr-spin 0.8s linear infinite;
}

@keyframes qr-spin {
    to { transform: rotate(360deg); }
}

/* Alert */
.qr-alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 13px;
    border-radius: 10px;
    margin-bottom: 14px;
    font-size: 12.5px;
    font-weight: 500;
}

.qr-alert-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
}

.qr-alert svg { flex-shrink: 0; }

/* Responsive */
@media (max-width: 576px) {
    .qr-grid { grid-template-columns: 1fr; }
    .qr-header, .qr-body, .qr-footer { padding-left: 18px; padding-right: 18px; }
    .qr-title { font-size: 15px; }
    .qr-subtitle { font-size: 11.5px; }
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
        gridUnits: [],
        isLoading: false,
        isLoadingGrid: false,
        isSearching: false,
        isSearchOpen: false,
        error: null,
        searchTimeout: null,

        init() {
            this.loadGridUnits();

            const modalEl = this.$el.closest('.modal');
            if (modalEl) {
                modalEl.addEventListener('hidden.bs.modal', () => this.reset());
            }
        },

        reset() {
            this.closeSearch();
            this.selectedUnit = null;
            this.searchQuery = '';
            this.error = null;
            this.loadGridUnits();
        },

        clearSelection() {
            this.selectedUnit = null;
        },

        openSearch() {
            if (this.searchResults.length > 0 || this.searchQuery.trim()) {
                this.isSearchOpen = true;
            }
        },

        closeSearch() {
            this.isSearchOpen = false;
        },

        clearSearch() {
            this.searchQuery = '';
            this.searchResults = [];
            this.closeSearch();
            this.$nextTick(() => this.$refs.searchInput?.focus());
        },

        async loadGridUnits() {
            this.isLoadingGrid = true;
            try {
                const url = '{{ route("admin.qr-codes.search") }}?limit=4&without_qr=1';
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const data = await response.json();
                if (data.success) {
                    this.gridUnits = this.filterUnits(data.units).slice(0, 4);
                }
            } catch (err) {
                console.error('Error loading grid units:', err);
                this.gridUnits = [];
            } finally {
                this.isLoadingGrid = false;
            }
        },

        async searchUnits() {
            clearTimeout(this.searchTimeout);
            const query = this.searchQuery.trim();

            if (!query) {
                this.searchResults = [];
                this.isSearchOpen = false;
                return;
            }

            this.isSearchOpen = true;
            this.isSearching = true;

            this.searchTimeout = setTimeout(async () => {
                try {
                    const q = encodeURIComponent(query);
                    const url = `{{ route("admin.qr-codes.search") }}?q=${q}&limit=2&without_qr=1`;
                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.searchResults = this.filterUnits(data.units).slice(0, 2);
                    }
                } catch (err) {
                    console.error('Error searching units:', err);
                    this.searchResults = [];
                } finally {
                    this.isSearching = false;
                }
            }, 280);
        },

        /**
         * Safety filter — drops units that already have an active QR Code.
         */
        filterUnits(units) {
            if (!Array.isArray(units)) return [];
            return units.filter(u => {
                if (u.has_qr_code === true) return false;
                if (u.qr_code_id) return false;
                if (Array.isArray(u.qr_codes) && u.qr_codes.length > 0) return false;
                if (u.qr_code && typeof u.qr_code === 'object' && u.qr_code.id) return false;
                if (u.qr_code && typeof u.qr_code === 'string') return false;
                return true;
            });
        },

        selectUnit(unit) {
            this.selectedUnit = unit;
            this.closeSearch();
            this.error = null;
        },

        async generate() {
            if (!this.selectedUnit || this.isLoading) return;
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