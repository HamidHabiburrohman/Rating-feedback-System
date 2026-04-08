@extends('layouts.student.app')

@section('title', 'Dashboard')

@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400&display=swap');

        :root {
            --primary: #f8773c;
            --primary-dark: #e0622a;
            --primary-light: rgba(248, 119, 60, 0.08);
            --primary-glow: rgba(248, 119, 60, 0.28);
            --bg: #F8F7F5;
            --surface: rgba(255, 255, 255, 0.85);
            --surface-solid: #FFFFFF;
            --border: rgba(17, 17, 17, 0.07);
            --border-accent: rgba(248, 119, 60, 0.25);
            --text: #1A1A1A;
            --text-soft: #555555;
            --text-muted: #999999;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        .db {
            background: var(--bg);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            padding-bottom: 60px;
        }

        /* ── Blobs ── */
        .db-blobs {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .db-blobs::before {
            content: '';
            position: absolute;
            top: -140px;
            right: -140px;
            width: 480px;
            height: 480px;
            background: radial-gradient(circle, rgba(248, 119, 60, 0.08) 0%, transparent 65%);
            border-radius: 50%;
            animation: blobA 22s ease-in-out infinite;
        }

        .db-blobs::after {
            content: '';
            position: absolute;
            bottom: 5%;
            left: -100px;
            width: 380px;
            height: 380px;
            background: radial-gradient(circle, rgba(248, 119, 60, 0.05) 0%, transparent 65%);
            border-radius: 50%;
            animation: blobB 28s ease-in-out infinite;
        }

        @keyframes blobA {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            40% {
                transform: translate(-30px, 40px) scale(1.06);
            }

            70% {
                transform: translate(20px, -20px) scale(0.97);
            }
        }

        @keyframes blobB {

            0%,
            100% {
                transform: translate(0, 0);
            }

            50% {
                transform: translate(40px, -50px) scale(1.08);
            }
        }

        /* ── Layout ── */
        .db-inner {
            position: relative;
            z-index: 1;
            max-width: 1080px;
            margin: 0 auto;
            padding: 28px 16px 0;
        }

        @media(min-width:640px) {
            .db-inner {
                padding: 36px 24px 0;
            }
        }

        /* ── Glass ── */
        .glass {
            background: var(--surface);
            backdrop-filter: blur(20px) saturate(160%);
            -webkit-backdrop-filter: blur(20px) saturate(160%);
            border: 1px solid rgba(255, 255, 255, 0.80);
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.04), 0 0 0 1px var(--border);
        }

        .card {
            background: var(--surface-solid);
            border: 1px solid var(--border);
        }

        /* ── Section tag ── */
        .stag {
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--primary-dark);
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        .stag::before {
            content: '';
            width: 16px;
            height: 2px;
            background: var(--primary);
            border-radius: 1px;
        }

        /* ── Greeting ── */
        .greet-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            margin-bottom: 28px;
            animation: fadeUp .7s ease both;
        }

        .greet-name {
            font-size: clamp(1.4rem, 3.5vw, 2rem);
            font-weight: 800;
            color: var(--text);
            line-height: 1.15;
        }

        .greet-name span {
            color: var(--primary);
        }

        .greet-sub {
            font-size: 0.875rem;
            font-weight: 400;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .btn-browse {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 99px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            color: white;
            border: none;
            background: linear-gradient(135deg, #f8773c 0%, #e0622a 100%);
            box-shadow: 0 4px 18px rgba(248, 119, 60, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.18);
            cursor: pointer;
            text-decoration: none;
            position: relative;
            overflow: hidden;
            transition: all .3s ease;
            white-space: nowrap;
        }

        .btn-browse::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -60%;
            width: 40%;
            height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .2), transparent);
            transform: skewX(-20deg);
            transition: left .5s ease;
        }

        .btn-browse:hover::after {
            left: 130%;
        }

        .btn-browse:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(248, 119, 60, .4);
        }

        /* ── Stats grid ── */
        .stats-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(2, 1fr);
            margin-bottom: 24px;
        }

        @media(min-width:520px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media(min-width:768px) {
            .stats-grid {
                grid-template-columns: repeat(5, 1fr);
            }
        }

        .stat-card {
            border-radius: 20px;
            padding: 18px 16px;
            transition: all .32s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: var(--border-accent);
            box-shadow: 0 8px 28px rgba(248, 119, 60, .08);
        }

        .stat-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(248, 119, 60, .09);
            border: 1px solid rgba(248, 119, 60, .16);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            transition: all .32s ease;
        }

        .stat-card:hover .stat-icon {
            background: rgba(248, 119, 60, .16);
            border-color: rgba(248, 119, 60, .32);
            box-shadow: 0 0 12px rgba(248, 119, 60, .14);
        }

        .stat-val {
            font-size: 1.7rem;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #1A1A1A 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 4px;
        }

        .stat-lbl {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 0.68rem;
            font-weight: 600;
            margin-top: 6px;
            padding: 2px 7px;
            border-radius: 99px;
        }

        .t-up {
            background: rgba(34, 197, 94, .1);
            color: #16a34a;
        }

        .t-mid {
            background: rgba(248, 119, 60, .1);
            color: var(--primary-dark);
        }

        .t-zero {
            background: rgba(17, 17, 17, .06);
            color: var(--text-muted);
        }

        /* ── Columns ── */
        .main-cols {
            display: grid;
            gap: 16px;
            grid-template-columns: 1fr;
        }

        @media(min-width:768px) {
            .main-cols {
                grid-template-columns: 1fr 340px;
            }
        }

        /* ── Chart card ── */
        .chart-card {
            border-radius: 24px;
            padding: 22px 22px 16px;
            margin-bottom: 16px;
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .chart-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }

        .period-tabs {
            display: flex;
            gap: 2px;
            padding: 3px;
            background: rgba(17, 17, 17, .05);
            border: 1px solid var(--border);
            border-radius: 99px;
        }

        .period-btn {
            padding: 5px 13px;
            border-radius: 99px;
            border: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all .25s ease;
        }

        .period-btn.active {
            background: white;
            color: var(--text);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }

        .chart-canvas-wrap {
            position: relative;
            height: 170px;
        }

        /* ── Act tabs ── */
        .act-tabs {
            display: flex;
            gap: 2px;
            padding: 3px;
            background: rgba(17, 17, 17, .05);
            border: 1px solid var(--border);
            border-radius: 99px;
            width: fit-content;
            margin-bottom: 14px;
        }

        .act-tab {
            padding: 6px 16px;
            border-radius: 99px;
            border: none;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.78rem;
            font-weight: 600;
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all .25s ease;
        }

        .act-tab.active {
            background: white;
            color: var(--text);
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }

        /* ── Activity item ── */
        .act-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .act-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px 15px;
            border-radius: 16px;
            transition: all .28s ease;
            text-decoration: none;
            color: inherit;
        }

        .act-item:hover {
            transform: translateX(3px);
            border-color: var(--border-accent);
            box-shadow: 0 4px 16px rgba(248, 119, 60, .06);
        }

        .act-ico {
            width: 36px;
            height: 36px;
            border-radius: 11px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ico-rating {
            background: rgba(248, 119, 60, .1);
            border: 1px solid rgba(248, 119, 60, .2);
        }

        .ico-report {
            background: rgba(239, 68, 68, .08);
            border: 1px solid rgba(239, 68, 68, .18);
        }

        .act-title {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.3;
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 240px;
        }

        .act-sub {
            font-size: 0.72rem;
            font-weight: 400;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .act-right {
            margin-left: auto;
            flex-shrink: 0;
            text-align: right;
        }

        .act-score {
            font-size: 0.9rem;
            font-weight: 800;
            background: linear-gradient(135deg, #1A1A1A 0%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .act-code {
            font-size: 0.65rem;
            font-family: monospace;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ── Badges ── */
        .sbadge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: .06em;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 99px;
        }

        .sb-active {
            background: rgba(34, 197, 94, .1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, .2);
        }

        .sb-edited {
            background: rgba(248, 119, 60, .1);
            color: var(--primary-dark);
            border: 1px solid rgba(248, 119, 60, .2);
        }

        .sb-archived {
            background: rgba(17, 17, 17, .06);
            color: var(--text-muted);
            border: 1px solid var(--border);
        }

        .sb-new {
            background: rgba(59, 130, 246, .1);
            color: #2563eb;
            border: 1px solid rgba(59, 130, 246, .2);
        }

        .sb-in_progress {
            background: rgba(248, 119, 60, .1);
            color: var(--primary-dark);
            border: 1px solid rgba(248, 119, 60, .2);
        }

        .sb-replied {
            background: rgba(139, 92, 246, .1);
            color: #7c3aed;
            border: 1px solid rgba(139, 92, 246, .2);
        }

        .sb-resolved {
            background: rgba(34, 197, 94, .1);
            color: #16a34a;
            border: 1px solid rgba(34, 197, 94, .2);
        }

        .sb-rejected {
            background: rgba(239, 68, 68, .08);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, .18);
        }

        .pbadge {
            display: inline-flex;
            align-items: center;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 99px;
        }

        .pb-low {
            background: rgba(34, 197, 94, .1);
            color: #16a34a;
        }

        .pb-medium {
            background: rgba(248, 119, 60, .1);
            color: var(--primary-dark);
        }

        .pb-high {
            background: rgba(239, 68, 68, .08);
            color: #dc2626;
        }

        .pb-critical {
            background: rgba(220, 38, 38, .15);
            color: #991b1b;
        }

        /* ── Card header ── */
        .card-hdr {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-hdr-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--text);
        }

        .view-all {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: opacity .2s;
        }

        .view-all:hover {
            opacity: .7;
        }

        .view-all svg {
            transition: transform .2s;
        }

        .view-all:hover svg {
            transform: translateX(3px);
        }

        /* ── Sidebar ── */
        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ── Ring ── */
        .ring-wrap {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            border-radius: 18px;
            background: rgba(248, 119, 60, .05);
            border: 1px solid rgba(248, 119, 60, .12);
            margin-bottom: 16px;
        }

        .ring-bg {
            fill: none;
            stroke: rgba(248, 119, 60, .12);
            stroke-width: 7;
        }

        .ring-fg {
            fill: none;
            stroke: var(--primary);
            stroke-width: 7;
            stroke-linecap: round;
            stroke-dasharray: 151;
            stroke-dashoffset: 151;
            transform: rotate(-90deg);
            transform-origin: 50% 50%;
            transition: stroke-dashoffset 1.2s cubic-bezier(.34, 1.3, .64, 1);
        }

        .ring-val {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text);
        }

        .ring-lbl {
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        /* ── Rec items ── */
        .rec-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: 14px;
            text-decoration: none;
            color: inherit;
            margin-bottom: 8px;
            transition: all .28s ease;
            border: 1px solid transparent;
        }

        .rec-item:last-child {
            margin-bottom: 0;
        }

        .rec-item:hover {
            background: rgba(248, 119, 60, .05);
            border-color: rgba(248, 119, 60, .18);
            transform: translateX(3px);
        }

        .rec-rank {
            width: 28px;
            height: 28px;
            border-radius: 9px;
            flex-shrink: 0;
            background: rgba(248, 119, 60, .09);
            border: 1px solid rgba(248, 119, 60, .16);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 800;
            color: var(--primary-dark);
            transition: all .28s ease;
        }

        .rec-item:hover .rec-rank {
            background: rgba(248, 119, 60, .16);
            border-color: rgba(248, 119, 60, .32);
        }

        .rec-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--text);
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rec-type {
            font-size: 0.68rem;
            font-weight: 500;
            color: var(--text-muted);
        }

        .rec-score {
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--primary);
            flex-shrink: 0;
        }

        /* ── Quick actions ── */
        .qa-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
        }

        .qa-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 7px;
            padding: 14px 10px;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: white;
            text-decoration: none;
            color: var(--text-soft);
            font-size: 0.72rem;
            font-weight: 600;
            text-align: center;
            transition: all .28s ease;
            cursor: pointer;
        }

        .qa-btn:hover {
            border-color: var(--border-accent);
            background: rgba(248, 119, 60, .04);
            color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 16px rgba(248, 119, 60, .08);
        }

        .qa-btn svg {
            color: var(--primary);
        }

        /* ── Empty ── */
        .empty-state {
            text-align: center;
            padding: 32px 16px;
            color: var(--text-muted);
            font-size: 0.84rem;
        }

        .empty-state svg {
            margin: 0 auto 10px;
            display: block;
        }

        /* ── Reveal ── */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal {
            opacity: 0;
            transform: translateY(26px);
            transition: opacity .55s ease, transform .55s ease;
        }

        .reveal.in {
            opacity: 1;
            transform: translateY(0);
        }

        .d1 {
            transition-delay: .05s;
        }

        .d2 {
            transition-delay: .12s;
        }

        .d3 {
            transition-delay: .19s;
        }

        .d4 {
            transition-delay: .26s;
        }

        .d5 {
            transition-delay: .33s;
        }

        .d6 {
            transition-delay: .40s;
        }
    </style>

    <div class="db">
        <div class="db-blobs"></div>
        <div class="db-inner">

            {{-- ── GREETING ── --}}
            <div class="greet-row">
                <div>
                    <div class="stag" style="margin-bottom:10px">Dashboard</div>
                    <h1 class="greet-name">
                        Halo, <span>{{ session('student_name', 'Mahasiswa') }}</span> 👋
                    </h1>
                    <p class="greet-sub">
                        {{ now()->translatedFormat('l, d F Y') }}
                        &nbsp;·&nbsp; Semester Genap 2025/2026
                    </p>
                </div>
                <a href="" class="btn-browse">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    Jelajahi Unit
                </a>
            </div>

            {{-- ── STATS ── --}}
            @php
                $cards = [
                    ['val' => $stats['total_ratings'], 'lbl' => 'Total Penilaian', 't' => 'mid', 'tv' => 'penilaian', 'fill' => true, 'path' => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>'],
                    ['val' => $stats['unique_units_rated'], 'lbl' => 'Unit Dinilai', 't' => 'up', 'tv' => 'unit', 'fill' => false, 'path' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>'],
                    ['val' => number_format($stats['average_rating'], 1), 'lbl' => 'Rata-rata Skor', 't' => 'mid', 'tv' => '/5.0', 'fill' => false, 'path' => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/>'],
                    ['val' => $stats['total_reports'], 'lbl' => 'Total Laporan', 't' => 'zero', 'tv' => 'laporan', 'fill' => false, 'path' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>'],
                    ['val' => $stats['active_reports'], 'lbl' => 'Laporan Aktif', 't' => $stats['active_reports'] > 0 ? 'mid' : 'zero', 'tv' => 'berjalan', 'fill' => false, 'path' => '<circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/>'],
                ];
            @endphp

            <div class="stats-grid">
                @foreach($cards as $i => $c)
                    <div class="stat-card glass reveal d{{ $i + 1 }}">
                        <div class="stat-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $c['fill'] ? 'currentColor' : 'none' }}"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"
                                style="color:var(--primary)">{!! $c['path'] !!}</svg>
                        </div>
                        <div class="stat-val">{{ $c['val'] }}</div>
                        <div class="stat-lbl">{{ $c['lbl'] }}</div>
                        <div class="stat-trend {{ $c['t'] === 'up' ? 't-up' : ($c['t'] === 'mid' ? 't-mid' : 't-zero') }}">
                            @if($c['t'] === 'up') ↑ @elseif($c['t'] === 'mid') ● @else — @endif
                            {{ $c['tv'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ── MAIN COLS ── --}}
            <div class="main-cols">

                {{-- ── LEFT ── --}}
                <div>

                    {{-- Chart ── --}}
                    <div class="chart-card glass reveal d1">
                        <div class="chart-header">
                            <span class="chart-title">Aktivitas Penilaian</span>
                            <div class="period-tabs">
                                <button class="period-btn active" data-period="week">Minggu</button>
                                <button class="period-btn" data-period="month">Bulan</button>
                            </div>
                        </div>
                        <div class="chart-canvas-wrap">
                            <canvas id="actChart"></canvas>
                        </div>
                    </div>

                    {{-- Activity ── --}}
                    <div class="card reveal d2" style="border-radius:24px;padding:20px">
                        <div class="card-hdr">
                            <span class="card-hdr-title">Aktivitas Terbaru</span>
                            <div class="act-tabs">
                                <button class="act-tab active" data-act="ratings">
                                    Penilaian
                                    @if(count($recentRatings) > 0)
                                        <span
                                            style="font-size:.62rem;background:var(--primary-light);color:var(--primary-dark);border-radius:99px;padding:1px 6px;margin-left:3px">{{ count($recentRatings) }}</span>
                                    @endif
                                </button>
                                <button class="act-tab" data-act="reports">
                                    Laporan
                                    @if(count($recentReports) > 0)
                                        <span
                                            style="font-size:.62rem;background:rgba(239,68,68,.08);color:#dc2626;border-radius:99px;padding:1px 6px;margin-left:3px">{{ count($recentReports) }}</span>
                                    @endif
                                </button>
                            </div>
                        </div>

                        {{-- Ratings ── --}}
                        <div id="paneRatings">
                            @if(count($recentRatings) > 0)
                                <div class="act-list">
                                    @foreach($recentRatings as $r)
                                        <a href="{{ route('student.ratings.show', $r['tracking_code']) }}" class="act-item card"
                                            style="border-radius:16px">
                                            <div class="act-ico ico-rating">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"
                                                    style="color:var(--primary)">
                                                    <path
                                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                                </svg>
                                            </div>
                                            <div style="flex:1;min-width:0">
                                                <div class="act-title">{{ $r['unit_name'] }}</div>
                                                <div class="act-sub">
                                                    <span class="sbadge sb-{{ $r['status'] }}">{{ $r['status'] }}</span>
                                                    <span>·</span><span>{{ $r['created_at'] }}</span>
                                                </div>
                                            </div>
                                            <div class="act-right">
                                                <div class="act-score">{{ number_format($r['overall_score'], 1) }}</div>
                                                <div class="act-code">{{ $r['tracking_code'] }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.4" style="color:var(--text-muted)">
                                        <path
                                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                    </svg>
                                    Belum ada penilaian.&nbsp;<a href="{{ route('student.units.index') }}"
                                        style="color:var(--primary);font-weight:600">Mulai menilai unit</a>
                                </div>
                            @endif
                            @if(count($recentRatings) >= 5)
                                <div style="text-align:center;margin-top:12px">
                                    <a href="{{ route('student.ratings.history') }}" class="view-all">
                                        Lihat semua <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14" />
                                            <path d="m12 5 7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- Reports ── --}}
                        <div id="paneReports" style="display:none">
                            @if(count($recentReports) > 0)
                                <div class="act-list">
                                    @foreach($recentReports as $r)
                                        <a href="{{ route('student.reports.show', $r['tracking_code']) }}" class="act-item card"
                                            style="border-radius:16px">
                                            <div class="act-ico ico-report">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" style="color:#dc2626">
                                                    <path
                                                        d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                                    <line x1="12" y1="9" x2="12" y2="13" />
                                                </svg>
                                            </div>
                                            <div style="flex:1;min-width:0">
                                                <div class="act-title">{{ $r['title'] }}</div>
                                                <div class="act-sub">
                                                    <span style="font-size:.7rem">{{ $r['unit_name'] }}</span>
                                                    <span>·</span>
                                                    <span class="sbadge sb-{{ $r['status'] }}">{{ $r['status'] }}</span>
                                                </div>
                                            </div>
                                            <div class="act-right">
                                                <div class="pbadge pb-{{ $r['priority'] }}">{{ ucfirst($r['priority']) }}</div>
                                                <div class="act-code" style="margin-top:4px">{{ $r['tracking_code'] }}</div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="empty-state">
                                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.4" style="color:var(--text-muted)">
                                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                    </svg>
                                    Belum ada laporan.
                                </div>
                            @endif
                            @if(count($recentReports) >= 5)
                                <div style="text-align:center;margin-top:12px">
                                    <a href="{{ route('student.reports.history') }}" class="view-all">
                                        Lihat semua <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path d="M5 12h14" />
                                            <path d="m12 5 7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ── SIDEBAR ── --}}
                <div class="sidebar">

                    {{-- Score ring ── --}}
                    <div class="glass" style="border-radius:24px;padding:18px">
                        <div class="stag" style="margin-bottom:14px">Ringkasan Skor</div>
                        <div class="ring-wrap">
                            <svg width="60" height="60" viewBox="0 0 54 54">
                                <circle class="ring-bg" cx="27" cy="27" r="24" />
                                <circle class="ring-fg" id="ratingRing" cx="27" cy="27" r="24" />
                            </svg>
                            <div>
                                <div class="ring-val">
                                    {{ number_format($stats['average_rating'], 2) }}
                                    <span style="font-size:.7rem;font-weight:500;color:var(--text-muted)">/5.00</span>
                                </div>
                                <div class="ring-lbl">Rata-rata skor kamu</div>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:9px">
                            <div
                                style="background:rgba(248,119,60,.05);border:1px solid rgba(248,119,60,.1);border-radius:14px;padding:12px 14px;text-align:center">
                                <div style="font-size:1.3rem;font-weight:800;color:var(--primary)">
                                    {{ $stats['total_ratings'] }}</div>
                                <div
                                    style="font-size:.68rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-top:2px">
                                    Penilaian</div>
                            </div>
                            <div
                                style="background:rgba(248,119,60,.05);border:1px solid rgba(248,119,60,.1);border-radius:14px;padding:12px 14px;text-align:center">
                                <div style="font-size:1.3rem;font-weight:800;color:var(--primary)">
                                    {{ $stats['unique_units_rated'] }}</div>
                                <div
                                    style="font-size:.68rem;font-weight:600;color:var(--text-muted);text-transform:uppercase;letter-spacing:.06em;margin-top:2px">
                                    Unit</div>
                            </div>
                        </div>
                    </div>

                    {{-- Recommended ── --}}
                    @if(count($recommendedUnits) > 0)
                        <div class="card reveal d2" style="border-radius:24px;padding:18px">
                            <div class="card-hdr">
                                <span class="card-hdr-title">Rekomendasi Unit</span>
                                <a href="{{ route('visitor.browse') }}" class="view-all">
                                    Semua <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path d="M5 12h14" />
                                        <path d="m12 5 7 7-7 7" />
                                    </svg>
                                </a>
                            </div>
                            @foreach($recommendedUnits as $i => $u)
                                <a href="{{ route('visitor.unit.show', $u['slug']) }}" class="rec-item">
                                    <div class="rec-rank">{{ $i + 1 }}</div>
                                    <div style="flex:1;min-width:0">
                                        <div class="rec-name">{{ $u['name'] }}</div>
                                        <div class="rec-type">{{ $u['type'] }} · {{ $u['location'] }}</div>
                                    </div>
                                    <div style="text-align:right">
                                        <div class="rec-score">{{ number_format($u['avg_rating'], 1) }}</div>
                                        <div style="font-size:.62rem;color:var(--text-muted)">{{ $u['total_ratings'] }} ulasan</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Quick actions ── --}}
                    <div class="card reveal d3" style="border-radius:24px;padding:18px">
                        <div class="stag" style="margin-bottom:14px">Aksi Cepat</div>
                        <div class="qa-grid">
                            <a href="" class="qa-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                                Jelajahi Unit
                            </a>
                            <a href="{{ route('student.ratings.history') }}" class="qa-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round">
                                    <path
                                        d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                                </svg>
                                Riwayat Nilai
                            </a>
                            <a href="{{ route('student.reports.history') }}" class="qa-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                                    <line x1="12" y1="9" x2="12" y2="13" />
                                </svg>
                                Riwayat Laporan
                            </a>
                            <a href="{{ route('student.profile.show') }}" class="qa-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                                Profil Saya
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>{{-- /db-inner --}}
    </div>{{-- /db --}}

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // ── Reveal ──
            const obs = new IntersectionObserver(
                entries => entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('in'); }),
                { threshold: 0.06, rootMargin: '0px 0px -30px 0px' }
            );
            document.querySelectorAll('.reveal').forEach(el => obs.observe(el));

            // ── Ring animate ──
            const ring = document.getElementById('ratingRing');
            if (ring) {
                const avg = {{ (float) ($stats['average_rating'] ?? 0) }};
                const final = 151 - (151 * (avg / 5));
                ring.style.strokeDashoffset = 151;
                setTimeout(() => { ring.style.strokeDashoffset = final; }, 500);
            }

            // ── Act tabs ──
            document.querySelectorAll('.act-tab').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.act-tab').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    const t = btn.dataset.act;
                    document.getElementById('paneRatings').style.display = t === 'ratings' ? 'block' : 'none';
                    document.getElementById('paneReports').style.display = t === 'reports' ? 'block' : 'none';
                });
            });

            // ── Chart ──
            const ctx = document.getElementById('actChart').getContext('2d');
            const grad = ctx.createLinearGradient(0, 0, 0, 170);
            grad.addColorStop(0, 'rgba(248,119,60,0.2)');
            grad.addColorStop(1, 'rgba(248,119,60,0)');

            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        borderColor: '#f8773c', borderWidth: 2.5,
                        backgroundColor: grad, fill: true, tension: 0.42,
                        pointRadius: 5, pointBackgroundColor: '#f8773c',
                        pointBorderColor: '#fff', pointBorderWidth: 2, pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    animation: { duration: 900, easing: 'easeInOutQuart' },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(255,255,255,0.97)',
                            titleColor: '#1A1A1A', bodyColor: '#555',
                            borderColor: 'rgba(248,119,60,0.2)', borderWidth: 1,
                            cornerRadius: 12, padding: 10,
                            callbacks: { label: c => ` ${c.parsed.y} penilaian` }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false }, border: { display: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#999' }
                        },
                        y: {
                            grid: { color: 'rgba(17,17,17,0.05)' }, border: { display: false },
                            ticks: { stepSize: 1, font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' }, color: '#999' },
                            min: 0
                        }
                    }
                }
            });

            const fetchChart = period => {
                fetch(`{{ route('student.dashboard.index') }}?period=${period}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                    .then(r => r.json())
                    .then(res => {
                        if (!res.success) return;
                        chart.data.labels = res.data.labels;
                        chart.data.datasets[0].data = res.data.data;
                        chart.update('active');
                    });
            };

            fetchChart('week');

            document.querySelectorAll('.period-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    fetchChart(btn.dataset.period);
                });
            });

        });
    </script>
@endsection