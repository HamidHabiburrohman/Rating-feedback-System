@extends('layouts.admin.app')

@section('title', $unit->name . ' — Detail Unit')

@section('admin-content')

    {{-- ════════════════════════════════════════
    TOAST NOTIFICATIONS
    ════════════════════════════════════════ --}}
    <div class="ud-toast-wrap" id="toastContainer">
        @if(session('success'))
            <div class="ud-toast ud-toast--success" role="alert">
                <svg class="ud-toast__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                <p class="ud-toast__msg">{{ session('success') }}</p>
                <button class="ud-toast__close" onclick="this.closest('.ud-toast').remove()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
        @endif
        @if(session('error'))
            <div class="ud-toast ud-toast--error" role="alert">
                <svg class="ud-toast__icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2.5">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <p class="ud-toast__msg">{{ session('error') }}</p>
                <button class="ud-toast__close" onclick="this.closest('.ud-toast').remove()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    @php
        $now = now();
        $open = $unit->open_time ? \Carbon\Carbon::parse($unit->open_time) : null;
        $close = $unit->close_time ? \Carbon\Carbon::parse($unit->close_time) : null;
        $isOpen = ($open && $close) ? $now->between($open, $close) : null;
        $unitType = $unit->unitType ?? $unit->type ?? null;
        $iconSvg = $unitType?->icon_svg ?? null;
    @endphp

    <main class="ud-page">

        {{-- ─── Back Nav ─── --}}
        <a href="{{ route('admin.units.index') }}" class="ud-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Unit
        </a>

        <div class="row g-4 g-xl-5">

            {{-- ════════════════════════════
            LEFT COLUMN
            ════════════════════════════ --}}
            <div class="col-lg-7 col-xl-8">

                {{-- ── Hero Card ── --}}
                <div class="ud-card ud-card--hero mb-4">

                    {{-- Hero Image --}}
                    {{-- Hero Image --}}
                    @php
                        $primaryPhoto = $unit->primaryPhoto ?? $unit->photos->first();
                        if ($primaryPhoto) {
                            if ($primaryPhoto->thumbnail_url) {
                                $heroImageUrl = $primaryPhoto->thumbnail_url;
                            } elseif ($primaryPhoto->thumbnail_path) {
                                $heroImageUrl = asset($primaryPhoto->thumbnail_path);
                            } elseif ($primaryPhoto->original_path) {
                                $heroImageUrl = asset($primaryPhoto->original_path);
                            } else {
                                $heroImageUrl = asset('assets/images/UnitPlaceholder.png');
                            }
                        } else {
                            $heroImageUrl = asset('assets/images/UnitPlaceholder.png');
                        }
                    @endphp

                    @if($primaryPhoto)
                        <div class="ud-hero">
                            <img src="{{ $heroImageUrl }}" alt="{{ $unit->name }}" class="ud-hero__img">
                            <div class="ud-hero__overlay"></div>
                            <div class="ud-hero__chips">
                                <span class="ud-status-chip ud-status-chip--{{ $unit->is_active ? 'active' : 'inactive' }}">
                                    <span class="ud-status-chip__dot"></span>
                                    {{ $unit->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                @if($unitType)
                                    <span class="ud-type-chip">{{ $unitType->name }}</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="ud-hero ud-hero--empty">
                            <div class="ud-hero__placeholder">
                                @if($iconSvg)
                                    {!! str_replace('currentColor', 'var(--ud-primary)', $iconSvg) !!}
                                @else
                                    <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="var(--ud-primary)"
                                        stroke-width="1.25">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <path d="M3 9h18M9 21V9" />
                                    </svg>
                                @endif
                            </div>
                            <p class="ud-hero__empty-label">Belum ada foto unit</p>
                            <div class="ud-hero__chips ud-hero__chips--static">
                                <span class="ud-status-chip ud-status-chip--{{ $unit->is_active ? 'active' : 'inactive' }}">
                                    <span class="ud-status-chip__dot"></span>
                                    {{ $unit->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                                @if($unitType)
                                    <span class="ud-type-chip">{{ $unitType->name }}</span>
                                @endif
                            </div>
                        </div>
                    @endif
                    {{-- Unit Identity — HIERARCHY LEVEL 1 --}}
                    <div class="ud-identity">
                        <div class="ud-identity__top">
                            <div class="ud-identity__meta">

                                {{-- Scale: title is dominant element on the page --}}
                                <h1 class="ud-identity__name">{{ $unit->name }}</h1>

                                <div class="ud-identity__attrs">
                                    <span class="ud-attr">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <path d="M3 9h18" />
                                        </svg>
                                        <code>{{ $unit->code }}</code>
                                    </span>
                                    @if($unit->building)
                                        <span class="ud-attr__sep">·</span>
                                        <span class="ud-attr">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                                <circle cx="12" cy="10" r="3" />
                                            </svg>
                                            Gd. {{ $unit->building }}@if($unit->floor), Lt. {{ $unit->floor }}@endif
                                        </span>
                                    @endif
                                    @if($unit->avg_rating > 0)
                                        <span class="ud-attr__sep">·</span>
                                        <span class="ud-attr ud-attr--star">
                                            <svg width="13" height="13" viewBox="0 0 24 24" fill="#f59e0b" stroke="none">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                            </svg>
                                            {{ number_format($unit->avg_rating, 1) }}
                                            <span class="ud-attr__sub">/ 5 ({{ $unit->total_ratings }})</span>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($isOpen !== null)
                                <span class="ud-openpill ud-openpill--{{ $isOpen ? 'open' : 'closed' }}">
                                    <span class="ud-openpill__dot"></span>
                                    {{ $isOpen ? 'Buka' : 'Tutup' }}
                                </span>
                            @endif
                        </div>

                        @if($unit->description)
                            {{-- HIERARCHY LEVEL 2: section heading --}}
                            <div class="ud-section-divider"></div>
                            <h2 class="ud-section-heading">Deskripsi Unit</h2>
                            <p class="ud-description">{{ $unit->description }}</p>
                        @endif
                    </div>
                </div>

                {{-- ── Facilities Card ── --}}
                @if($unit->facilities->isNotEmpty())
                    <div class="ud-card mb-4">
                        <div class="ud-card__body">
                            {{-- Similarity: ALL section headings use the same pattern --}}
                            <div class="ud-card__header">
                                <h2 class="ud-section-heading mb-0">Fasilitas</h2>
                                <span class="ud-count-tag">{{ $unit->facilities->count() }}</span>
                            </div>
                            {{-- Similarity: ALL facility chips share identical shape, padding, hover --}}
                            <div class="ud-chips-wrap">
                                @foreach($unit->facilities as $facility)
                                    <span class="ud-facility-chip">
                                        @if($facility->icon_key)
                                            <svg class="ud-facility-chip__icon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                {!! $facility->icon_key !!}
                                            </svg>
                                        @endif
                                        {{ $facility->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── Info Blocks Card ── --}}
                @if($unit->location || $unit->building || $unit->floor || $unit->phone || $unit->email || $unit->open_time || $unit->close_time || $unit->type || $unit->department || $unit->capacity)
                    <div class="ud-card">
                        <div class="ud-card__body">
                            <h2 class="ud-section-heading">Informasi Lengkap</h2>

                            {{-- Color + Similarity: ALL info-blocks use identical structure,
                            same background tint, same border — unified not rainbow --}}
                            <div class="row g-3">

                                @if($unit->location || $unit->building || $unit->floor)
                                    <div class="col-md-6">
                                        <div class="ud-infoblock">
                                            <div class="ud-infoblock__head">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                                    <circle cx="12" cy="10" r="3" />
                                                </svg>
                                                Lokasi
                                            </div>
                                            <div class="ud-infoblock__rows">
                                                @if($unit->location)
                                                    <div class="ud-row"><span>Alamat</span><span>{{ $unit->location }}</span></div>
                                                @endif
                                                @if($unit->building)
                                                    <div class="ud-row"><span>Gedung</span><span>{{ $unit->building }}</span></div>
                                                @endif
                                                @if($unit->floor)
                                                    <div class="ud-row"><span>Lantai</span><span>{{ $unit->floor }}</span></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($unit->phone || $unit->email)
                                    <div class="col-md-6">
                                        <div class="ud-infoblock">
                                            <div class="ud-infoblock__head">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path
                                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                                </svg>
                                                Kontak
                                            </div>
                                            <div class="ud-infoblock__rows">
                                                @if($unit->phone)
                                                    <div class="ud-row"><span>Telepon</span><a
                                                            href="tel:{{ $unit->phone }}">{{ $unit->phone }}</a></div>
                                                @endif
                                                @if($unit->email)
                                                    <div class="ud-row"><span>Email</span><a
                                                            href="mailto:{{ $unit->email }}">{{ $unit->email }}</a></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($unit->open_time || $unit->close_time)
                                    <div class="col-md-6">
                                        <div class="ud-infoblock">
                                            <div class="ud-infoblock__head">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <circle cx="12" cy="12" r="10" />
                                                    <polyline points="12 6 12 12 16 14" />
                                                </svg>
                                                Jam Operasional
                                            </div>
                                            <div class="ud-infoblock__rows">
                                                @if($unit->open_time)
                                                    <div class="ud-row">
                                                        <span>Buka</span><span>{{ \Carbon\Carbon::parse($unit->open_time)->format('H:i') }}</span>
                                                    </div>
                                                @endif
                                                @if($unit->close_time)
                                                    <div class="ud-row">
                                                        <span>Tutup</span><span>{{ \Carbon\Carbon::parse($unit->close_time)->format('H:i') }}</span>
                                                    </div>
                                                @endif
                                                @if($isOpen !== null)
                                                    <div class="ud-row">
                                                        <span>Status</span>
                                                        <span class="ud-openpill ud-openpill--{{ $isOpen ? 'open' : 'closed' }}"
                                                            style="font-size:0.72rem;padding:2px 10px;">
                                                            <span class="ud-openpill__dot"></span>
                                                            {{ $isOpen ? 'Buka' : 'Tutup' }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($unit->type || $unit->department || $unit->capacity)
                                    <div class="col-md-6">
                                        <div class="ud-infoblock">
                                            <div class="ud-infoblock__head">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                    <line x1="3" y1="9" x2="21" y2="9" />
                                                    <line x1="9" y1="21" x2="9" y2="9" />
                                                </svg>
                                                Spesifikasi
                                            </div>
                                            <div class="ud-infoblock__rows">
                                                @if($unit->type)
                                                    <div class="ud-row"><span>Tipe Unit</span><span>{{ $unit->type->name }}</span></div>
                                                @endif
                                                @if($unit->department)
                                                    <div class="ud-row">
                                                        <span>Departemen</span><span>{{ $unit->department->name }}</span></div>
                                                @endif
                                                @if($unit->capacity)
                                                    <div class="ud-row"><span>Kapasitas</span><span>{{ number_format($unit->capacity) }}
                                                            orang</span></div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                @endif

            </div>

            {{-- ════════════════════════════
            RIGHT COLUMN (SIDEBAR)
            ════════════════════════════ --}}
            <div class="col-lg-5 col-xl-4">

                {{-- ── Primary CTA — HIERARCHY: most prominent action ── --}}
                <a href="{{ route('admin.units.edit', $unit->id) }}" class="ud-cta-edit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit Unit
                </a>

                {{-- ── Stats Strip — Scale: numbers dominate ── --}}
                @if($unit->capacity || $unit->avg_rating > 0)
                    <div class="ud-card mb-4">
                        <div class="ud-card__body ud-stats-strip">
                            @if($unit->capacity)
                                <div class="ud-stat">
                                    <span class="ud-stat__num">{{ number_format($unit->capacity) }}</span>
                                    <span class="ud-stat__label">Kapasitas</span>
                                    <span class="ud-stat__unit">orang</span>
                                </div>
                            @endif
                            @if($unit->capacity && $unit->avg_rating > 0)
                                <div class="ud-stat-divider"></div>
                            @endif
                            @if($unit->avg_rating > 0)
                                <div class="ud-stat">
                                    <span class="ud-stat__num ud-stat__num--star">{{ number_format($unit->avg_rating, 1) }}</span>
                                    <span class="ud-stat__label">Rating</span>
                                    <div class="ud-stat__stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg width="11" height="11" viewBox="0 0 24 24">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                                                    fill="{{ $i <= round($unit->avg_rating) ? '#f59e0b' : '#e2e8f0' }}" stroke="none" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ── Technical Details ── --}}
                <div class="ud-card mb-4">
                    <div class="ud-card__body">
                        <h2 class="ud-section-heading">Detail Teknis</h2>
                        {{-- Similarity: ALL tech rows identical treatment --}}
                        <div class="ud-techlist">
                            <div class="ud-techrow">
                                <span class="ud-techrow__key">ID</span>
                                <code class="ud-techrow__val ud-techrow__val--code">#{{ $unit->id }}</code>
                            </div>
                            <div class="ud-techrow">
                                <span class="ud-techrow__key">Kode</span>
                                <code class="ud-techrow__val ud-techrow__val--mono">{{ $unit->code }}</code>
                            </div>
                            <div class="ud-techrow">
                                <span class="ud-techrow__key">Tipe</span>
                                <span class="ud-techrow__val">{{ $unitType?->name ?? '—' }}</span>
                            </div>
                            <div class="ud-techrow">
                                <span class="ud-techrow__key">Status</span>
                                <span class="ud-status-chip ud-status-chip--{{ $unit->is_active ? 'active' : 'inactive' }}"
                                    style="font-size:0.7rem;padding:2px 10px;">
                                    <span class="ud-status-chip__dot"></span>
                                    {{ $unit->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <div class="ud-techrow">
                                <span class="ud-techrow__key">Dibuat</span>
                                <span class="ud-techrow__val">{{ $unit->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="ud-techrow ud-techrow--last">
                                <span class="ud-techrow__key">Diperbarui</span>
                                <span class="ud-techrow__val">{{ $unit->updated_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Operating Hours — Color: ONE intentional dark element ── --}}
                @if($unit->open_time || $unit->close_time)
                    <div class="ud-card ud-card--dark mb-4">
                        <div class="ud-card__body">
                            <h2 class="ud-section-heading ud-section-heading--light">Jam Operasional</h2>
                            <div class="ud-techlist ud-techlist--dark">
                                @if($unit->open_time)
                                    <div class="ud-techrow">
                                        <span class="ud-techrow__key">Buka</span>
                                        <span
                                            class="ud-techrow__val">{{ \Carbon\Carbon::parse($unit->open_time)->format('H:i') }}</span>
                                    </div>
                                @endif
                                @if($unit->close_time)
                                    <div class="ud-techrow">
                                        <span class="ud-techrow__key">Tutup</span>
                                        <span
                                            class="ud-techrow__val">{{ \Carbon\Carbon::parse($unit->close_time)->format('H:i') }}</span>
                                    </div>
                                @endif
                                @if($isOpen !== null)
                                    <div class="ud-techrow ud-techrow--last">
                                        <span class="ud-techrow__key">Sekarang</span>
                                        <span class="ud-openpill ud-openpill--{{ $isOpen ? 'open' : 'closed' }}"
                                            style="font-size:0.72rem;padding:2px 10px;">
                                            <span class="ud-openpill__dot {{ $isOpen ? 'ud-openpill__dot--pulse' : '' }}"></span>
                                            {{ $isOpen ? 'Buka' : 'Tutup' }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ── Quick Nav — Similarity: ALL nav links identical ── --}}
                <div class="ud-card">
                    <div class="ud-card__body">
                        <h2 class="ud-section-heading">Navigasi Cepat</h2>
                        <div class="ud-navlist">
                            <a href="{{ route('admin.units.index') }}" class="ud-navlink">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="8" y1="6" x2="21" y2="6" />
                                    <line x1="8" y1="12" x2="21" y2="12" />
                                    <line x1="8" y1="18" x2="21" y2="18" />
                                    <line x1="3" y1="6" x2="3.01" y2="6" />
                                    <line x1="3" y1="12" x2="3.01" y2="12" />
                                    <line x1="3" y1="18" x2="3.01" y2="18" />
                                </svg>
                                Semua Unit
                                <svg class="ud-navlink__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M5 12h14M12 5l7 7-7 7" />
                                </svg>
                            </a>
                            @if($unitType)
                                <a href="{{ route('admin.units.index', ['type' => $unitType->id]) }}" class="ud-navlink">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                                    </svg>
                                    Tipe: {{ $unitType->name }}
                                    <svg class="ud-navlink__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                            @if(\Route::has('admin.moderation-logs.index'))
                                <a href="{{ route('admin.moderation-logs.index', ['target_type' => 'Unit', 'target_id' => $unit->id]) }}"
                                    class="ud-navlink">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z" />
                                    </svg>
                                    Log Moderasi Unit
                                    <svg class="ud-navlink__arrow" width="14" height="14" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M5 12h14M12 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <style>
        /* ═══════════════════════════════════════════════
               DESIGN TOKENS
               Single source of truth — Color principle:
               one primary, one dark, all neutrals derived
            ═══════════════════════════════════════════════ */
        :root {
            --ud-primary: #f8773c;
            --ud-primary-dim: #fef5f0;
            --ud-primary-mid: #fde8d8;
            --ud-primary-text: #c1440e;

            --ud-dark: #18212e;
            --ud-dark-surface: #222e3c;
            --ud-dark-border: #2e3d4f;
            --ud-dark-muted: #7a909c;

            --ud-surface: #ffffff;
            --ud-bg: #f8f9fb;
            --ud-border: #eaedf0;
            --ud-border-subtle: #f3f4f6;

            --ud-text-1: #111827;
            --ud-text-2: #374151;
            --ud-text-3: #6b7280;
            --ud-text-4: #9ca3af;

            --ud-green: #16a34a;
            --ud-green-bg: #f0fdf4;
            --ud-green-border: #bbf7d0;

            --ud-radius-sm: 8px;
            --ud-radius-md: 12px;
            --ud-radius-lg: 18px;
            --ud-radius-xl: 24px;
            --ud-radius-pill: 999px;

            --ud-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 4px 16px rgba(0, 0, 0, 0.04);
            --ud-shadow-lift: 0 4px 12px rgba(0, 0, 0, 0.06), 0 12px 32px rgba(0, 0, 0, 0.07);
        }

        /* ═══════════════════════════════════════════════
               PAGE SHELL
            ═══════════════════════════════════════════════ */
        .ud-page {
            padding: 1.75rem 1.5rem 3rem;
            max-width: 1280px;
        }

        /* ═══════════════════════════════════════════════
               BACK NAV
            ═══════════════════════════════════════════════ */
        .ud-back {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            margin-bottom: 2rem;
            padding: 0.45rem 1rem 0.45rem 0.75rem;
            border: 1px solid var(--ud-border);
            border-radius: var(--ud-radius-pill);
            color: var(--ud-text-3);
            font-size: 0.8rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.15s, border-color 0.15s, background 0.15s, transform 0.15s;
        }

        .ud-back:hover {
            color: var(--ud-text-1);
            border-color: var(--ud-text-4);
            background: var(--ud-bg);
            transform: translateX(-2px);
        }

        /* ═══════════════════════════════════════════════
               CARD — Similarity: every card same treatment
            ═══════════════════════════════════════════════ */
        .ud-card {
            background: var(--ud-surface);
            border: 1px solid var(--ud-border);
            border-radius: var(--ud-radius-xl);
            box-shadow: var(--ud-shadow);
            overflow: hidden;
        }

        .ud-card--hero {}

        .ud-card--dark {
            background: var(--ud-dark);
            border-color: var(--ud-dark-border);
        }

        .ud-card__body {
            padding: 1.5rem;
        }

        @media (min-width: 1200px) {
            .ud-card__body {
                padding: 1.75rem 2rem;
            }
        }

        .ud-card__header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        /* ═══════════════════════════════════════════════
               HERO IMAGE
               Scale: dominant visual element, 400px height
            ═══════════════════════════════════════════════ */
        .ud-hero {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
        }

        .ud-hero--empty {
            background: linear-gradient(140deg, var(--ud-primary-dim) 0%, #fff8f4 50%, #fffaf8 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            height: 240px;
            border-bottom: 1px solid var(--ud-border-subtle);
        }

        .ud-hero__img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.6s cubic-bezier(0.25, 0.1, 0.25, 1);
        }

        .ud-card--hero:hover .ud-hero__img {
            transform: scale(1.03);
        }

        .ud-hero__overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(10, 15, 25, 0.55) 0%, rgba(10, 15, 25, 0.1) 45%, transparent 100%);
        }

        .ud-hero__chips {
            position: absolute;
            bottom: 1rem;
            left: 1.125rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
        }

        .ud-hero__chips--static {
            position: static;
            margin-top: 0.75rem;
        }

        .ud-hero__placeholder {
            width: 88px;
            height: 88px;
            background: var(--ud-surface);
            border: 2px solid var(--ud-primary-mid);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(248, 119, 60, 0.12);
        }

        .ud-hero__empty-label {
            font-size: 0.8rem;
            color: var(--ud-text-4);
            margin: 0;
        }

        /* ═══════════════════════════════════════════════
               STATUS + TYPE CHIPS
               Similarity: consistent pill anatomy
            ═══════════════════════════════════════════════ */
        .ud-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: var(--ud-radius-pill);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.01em;
            border: 1px solid transparent;
        }

        .ud-status-chip--active {
            background: rgba(240, 253, 244, 0.92);
            color: var(--ud-green);
            border-color: var(--ud-green-border);
        }

        .ud-status-chip--inactive {
            background: rgba(248, 250, 252, 0.92);
            color: var(--ud-text-3);
            border-color: var(--ud-border);
        }

        .ud-status-chip__dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .ud-type-chip {
            display: inline-flex;
            align-items: center;
            padding: 0.3rem 0.75rem;
            border-radius: var(--ud-radius-pill);
            font-size: 0.75rem;
            font-weight: 600;
            background: rgba(255, 245, 240, 0.92);
            color: var(--ud-primary-text);
            border: 1px solid var(--ud-primary-mid);
        }

        /* ═══════════════════════════════════════════════
               UNIT IDENTITY
               Hierarchy: name > attrs > description
            ═══════════════════════════════════════════════ */
        .ud-identity {
            padding: 1.375rem 1.5rem 1.5rem;
        }

        @media (min-width: 1200px) {
            .ud-identity {
                padding: 1.5rem 2rem 2rem;
            }
        }

        .ud-identity__top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.25rem;
        }

        /* Scale Level 1: dominant name */
        .ud-identity__name {
            font-size: 2rem;
            font-weight: 800;
            color: var(--ud-text-1);
            letter-spacing: -0.025em;
            line-height: 1.15;
            margin: 0 0 0.625rem;
        }

        @media (min-width: 1200px) {
            .ud-identity__name {
                font-size: 2.25rem;
            }
        }

        .ud-identity__attrs {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.125rem;
        }

        .ud-attr {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.8rem;
            color: var(--ud-text-3);
            font-weight: 500;
        }

        .ud-attr code {
            font-family: 'Fira Code', 'Cascadia Code', monospace;
            font-size: 0.78rem;
            background: var(--ud-bg);
            padding: 1px 6px;
            border-radius: 5px;
            color: var(--ud-text-2);
            border: 1px solid var(--ud-border);
        }

        .ud-attr__sep {
            margin: 0 0.3rem;
            color: var(--ud-border);
            font-size: 1rem;
        }

        .ud-attr--star {
            color: #b45309;
        }

        .ud-attr__sub {
            color: var(--ud-text-4);
            font-weight: 400;
        }

        /* Scale Level 1b: open/closed status pill — balanced against the name */
        .ud-openpill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.875rem;
            border-radius: var(--ud-radius-pill);
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            border: 1px solid transparent;
        }

        .ud-openpill--open {
            background: var(--ud-green-bg);
            color: var(--ud-green);
            border-color: var(--ud-green-border);
        }

        .ud-openpill--closed {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .ud-openpill__dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            flex-shrink: 0;
        }

        .ud-openpill__dot--pulse {
            animation: ud-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes ud-pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .ud-section-divider {
            height: 1px;
            background: var(--ud-border-subtle);
            margin: 1.25rem 0;
        }

        /* Scale Level 2: section heading — clearly subordinate to name */
        .ud-section-heading {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            color: var(--ud-text-4);
            margin: 0 0 1.125rem;
        }

        .ud-section-heading--light {
            color: var(--ud-dark-muted);
        }

        /* Scale Level 3: body / description */
        .ud-description {
            font-size: 0.95rem;
            color: var(--ud-text-2);
            line-height: 1.8;
            margin: 0;
        }

        /* ═══════════════════════════════════════════════
               FACILITY CHIPS
               Similarity: all chips identical anatomy
            ═══════════════════════════════════════════════ */
        .ud-chips-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .ud-facility-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.4rem 0.875rem;
            background: var(--ud-bg);
            border: 1px solid var(--ud-border);
            border-radius: var(--ud-radius-sm);
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--ud-text-2);
            transition: background 0.15s, border-color 0.15s, color 0.15s;
            cursor: default;
        }

        .ud-facility-chip:hover {
            background: var(--ud-primary-dim);
            border-color: var(--ud-primary-mid);
            color: var(--ud-primary-text);
        }

        .ud-facility-chip__icon {
            color: var(--ud-primary);
            flex-shrink: 0;
        }

        .ud-count-tag {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--ud-text-4);
            background: var(--ud-bg);
            border: 1px solid var(--ud-border);
            border-radius: var(--ud-radius-pill);
            padding: 2px 10px;
        }

        /* ═══════════════════════════════════════════════
               INFO BLOCKS
               Color + Similarity: ALL blocks same warm-tint
               scheme — no more rainbow headers
            ═══════════════════════════════════════════════ */
        .ud-infoblock {
            border: 1px solid var(--ud-border);
            border-radius: var(--ud-radius-md);
            overflow: hidden;
            height: 100%;
        }

        .ud-infoblock__head {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.625rem 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--ud-primary-text);
            background: var(--ud-primary-dim);
            border-bottom: 1px solid var(--ud-primary-mid);
        }

        .ud-infoblock__rows {
            padding: 0.25rem 0;
        }

        /* ═══════════════════════════════════════════════
               SHARED ROW — used inside infoblock + techlist
               Similarity: identical left/right alignment
            ═══════════════════════════════════════════════ */
        .ud-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.5rem 1rem;
            gap: 0.75rem;
            border-bottom: 1px solid var(--ud-border-subtle);
        }

        .ud-row:last-child {
            border-bottom: none;
        }

        .ud-row>span:first-child,
        .ud-row>a:first-child {
            font-size: 0.78rem;
            color: var(--ud-text-4);
            flex-shrink: 0;
        }

        .ud-row>span:last-child {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--ud-text-1);
            text-align: right;
        }

        .ud-row>a:last-child {
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--ud-primary);
            text-align: right;
            text-decoration: none;
        }

        .ud-row>a:last-child:hover {
            text-decoration: underline;
        }

        /* ═══════════════════════════════════════════════
               STATS STRIP
               Scale: numbers are the hero of this card
            ═══════════════════════════════════════════════ */
        .ud-stats-strip {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 1.25rem 1.5rem;
        }

        .ud-stat {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.2rem;
        }

        /* Scale: number dominates the card — 2.75rem */
        .ud-stat__num {
            font-size: 2.75rem;
            font-weight: 800;
            color: var(--ud-text-1);
            line-height: 1;
            letter-spacing: -0.03em;
        }

        .ud-stat__num--star {
            color: #b45309;
        }

        .ud-stat__label {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--ud-text-4);
        }

        .ud-stat__unit {
            font-size: 0.7rem;
            color: var(--ud-text-4);
        }

        .ud-stat__stars {
            display: flex;
            gap: 1px;
            margin-top: 0.125rem;
        }

        .ud-stat-divider {
            width: 1px;
            height: 52px;
            background: var(--ud-border);
            margin: 0 0.25rem;
            flex-shrink: 0;
        }

        /* ═══════════════════════════════════════════════
               TECH LIST
               Similarity: identical to info-block rows
            ═══════════════════════════════════════════════ */
        .ud-techlist {}

        .ud-techrow {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid var(--ud-border-subtle);
        }

        .ud-techrow--last {
            border-bottom: none;
        }

        .ud-techlist--dark .ud-techrow {
            border-bottom-color: var(--ud-dark-border);
        }

        .ud-techrow__key {
            font-size: 0.78rem;
            color: var(--ud-text-4);
        }

        .ud-techlist--dark .ud-techrow__key {
            color: var(--ud-dark-muted);
        }

        .ud-techrow__val {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--ud-text-1);
            text-align: right;
        }

        .ud-techlist--dark .ud-techrow__val {
            color: #f1f5f9;
        }

        .ud-techrow__val--code {
            background: var(--ud-bg);
            border: 1px solid var(--ud-border);
            padding: 2px 8px;
            border-radius: 5px;
            font-family: 'Fira Code', 'Cascadia Code', monospace;
            font-size: 0.78rem;
        }

        .ud-techrow__val--mono {
            background: #f5f3ff;
            color: #6d28d9;
            border: 1px solid #ede9fe;
            padding: 2px 8px;
            border-radius: 5px;
            font-family: 'Fira Code', 'Cascadia Code', monospace;
            font-size: 0.78rem;
        }

        /* ═══════════════════════════════════════════════
               PRIMARY CTA
               Hierarchy: single most important action,
               placed at top of sidebar, full-width
            ═══════════════════════════════════════════════ */
        .ud-cta-edit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.875rem 1.5rem;
            margin-bottom: 1rem;
            border-radius: var(--ud-radius-lg);
            background: linear-gradient(135deg, #f9a07a 0%, var(--ud-primary) 60%, #e55a2b 100%);
            color: white;
            font-size: 0.9rem;
            font-weight: 700;
            letter-spacing: 0.01em;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(248, 119, 60, 0.3), 0 1px 3px rgba(248, 119, 60, 0.2);
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
        }

        .ud-cta-edit:hover {
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(248, 119, 60, 0.35), 0 2px 6px rgba(248, 119, 60, 0.25);
        }

        .ud-cta-edit:active {
            transform: translateY(0);
            opacity: 0.9;
        }

        /* ═══════════════════════════════════════════════
               QUICK NAV
               Similarity: all links same shape + hover
            ═══════════════════════════════════════════════ */
        .ud-navlist {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .ud-navlink {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 0.875rem;
            background: var(--ud-bg);
            border: 1px solid var(--ud-border);
            border-radius: var(--ud-radius-sm);
            color: var(--ud-text-3);
            font-size: 0.82rem;
            font-weight: 500;
            text-decoration: none;
            transition: background 0.15s, border-color 0.15s, color 0.15s;
        }

        .ud-navlink:hover {
            background: var(--ud-primary-dim);
            border-color: var(--ud-primary-mid);
            color: var(--ud-primary-text);
        }

        .ud-navlink__arrow {
            margin-left: auto;
            opacity: 0;
            transform: translateX(-4px);
            transition: opacity 0.15s, transform 0.15s;
            flex-shrink: 0;
        }

        .ud-navlink:hover .ud-navlink__arrow {
            opacity: 1;
            transform: translateX(0);
        }

        /* ═══════════════════════════════════════════════
               TOAST
            ═══════════════════════════════════════════════ */
        .ud-toast-wrap {
            position: fixed;
            top: 1.25rem;
            right: 1.25rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.625rem;
        }

        .ud-toast {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.125rem;
            background: var(--ud-surface);
            border-radius: var(--ud-radius-md);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1), 0 2px 6px rgba(0, 0, 0, 0.06);
            min-width: 280px;
            border-left: 3px solid transparent;
            animation: ud-toast-in 0.35s cubic-bezier(0.34, 1.4, 0.64, 1) both;
        }

        .ud-toast--success {
            border-left-color: #10b981;
        }

        .ud-toast--success .ud-toast__icon {
            color: #10b981;
        }

        .ud-toast--error {
            border-left-color: #ef4444;
        }

        .ud-toast--error .ud-toast__icon {
            color: #ef4444;
        }

        .ud-toast__msg {
            flex: 1;
            font-size: 0.82rem;
            font-weight: 500;
            color: var(--ud-text-1);
            margin: 0;
        }

        .ud-toast__close {
            background: none;
            border: none;
            color: var(--ud-text-4);
            cursor: pointer;
            padding: 0.2rem;
            border-radius: 4px;
            display: flex;
            transition: color 0.15s, background 0.15s;
        }

        .ud-toast__close:hover {
            color: var(--ud-text-2);
            background: var(--ud-bg);
        }

        @keyframes ud-toast-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* ═══════════════════════════════════════════════
               RESPONSIVE
            ═══════════════════════════════════════════════ */
        @media (max-width: 768px) {
            .ud-page {
                padding: 1rem 1rem 2.5rem;
            }

            .ud-identity__name {
                font-size: 1.625rem;
            }

            .ud-hero {
                height: 260px;
            }

            .ud-stat__num {
                font-size: 2rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.ud-toast').forEach(function (t) {
                setTimeout(function () {
                    t.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
                    t.style.transform = 'translateX(100%)';
                    t.style.opacity = '0';
                    setTimeout(function () { t.remove(); }, 300);
                }, 5000);
            });
        });
    </script>

@endsection