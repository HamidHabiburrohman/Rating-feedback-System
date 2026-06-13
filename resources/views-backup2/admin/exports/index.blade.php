@extends('layouts.admin.app')

@section('title', 'Export Data')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* =============================================
               60-30-10 | Modern Minimalist
               60% → #fafafa / #fff  (backgrounds)
               30% → #111 / #444     (text, labels)
               10% → #f8773c          (primary accent)
            ============================================= */
        :root {
            --bg: #ffffff;
            --surface: #ffffff;
            --ink: #111111;
            --ink-2: #666666;
            --ink-3: #bbbbbb;
            --border: #ebebeb;
            --primary: #f8773c;
            --primary-soft: #fff6f2;
            --primary-bd: #fde0d2;
            --radius-card: 18px;
            --radius-btn: 10px;
            --radius-sm: 8px;
            --font: 'Geist', system-ui, sans-serif;
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
        }

        .exp-page {
            font-family: var(--font);
            background: var(--bg);
            min-height: 100vh;
            padding: 2.5rem 2rem;
            color: var(--ink);
        }

        /* Header */
        .exp-eyebrow {
            font-size: 0.68rem;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }

        .exp-page h1 {
            font-size: 1.875rem;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: -0.03em;
            line-height: 1.1;
            margin: 0 0 0.35rem;
        }

        .exp-header-sub {
            font-size: 0.85rem;
            color: var(--ink-3);
            margin: 0 0 2rem;
            font-weight: 400;
        }

        /* Stat chips */
        .exp-strip {
            display: flex;
            gap: 0.6rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .exp-chip {
            border: 1px solid var(--border);
            background: var(--surface);
            border-radius: 100px;
            padding: 0.38rem 1rem;
            font-size: 0.78rem;
            color: var(--ink-2);
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 400;
        }

        .exp-chip-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--primary);
            flex-shrink: 0;
        }

        /* Grid */
        .exp-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .exp-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Card */
        .exp-card {
            background: var(--surface);
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            padding: 1.6rem;
            transition: box-shadow 0.22s var(--ease), transform 0.22s var(--ease);
        }

        .exp-card:hover {
            box-shadow: 0 8px 28px -6px rgba(0, 0, 0, 0.08);
            transform: translateY(-2px);
        }

        /* Icon — soft orange tint, no black bg */
        .exp-icon-wrap {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--primary-soft);
            border: 1px solid var(--primary-bd);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.1rem;
        }

        .exp-icon-wrap svg {
            width: 20px;
            height: 20px;
            stroke: var(--primary);
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .exp-c-label {
            font-size: 0.63rem;
            font-weight: 500;
            letter-spacing: 0.13em;
            text-transform: uppercase;
            color: var(--ink-3);
            margin-bottom: 0.18rem;
        }

        .exp-c-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: -0.025em;
            margin-bottom: 0.5rem;
        }

        .exp-c-meta {
            font-size: 0.78rem;
            color: var(--ink-3);
            margin-bottom: 1.1rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .exp-c-meta svg {
            width: 12px;
            height: 12px;
            stroke: #ccc;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Format toggle */
        .exp-fmt-grp {
            display: flex;
            background: #f5f5f5;
            border-radius: 10px;
            padding: 3px;
            gap: 3px;
            margin-bottom: 1rem;
        }

        .exp-fmt-btn {
            flex: 1;
            padding: 0.38rem;
            border-radius: var(--radius-sm);
            border: none;
            background: transparent;
            font-size: 0.78rem;
            font-weight: 500;
            color: #aaa;
            cursor: pointer;
            transition: all 0.16s;
            font-family: var(--font);
        }

        .exp-fmt-btn.active {
            background: var(--surface);
            color: var(--ink);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Action row */
        .exp-act-row {
            display: flex;
            gap: 0.5rem;
        }

        .exp-btn-export {
            flex: 1;
            padding: 0.65rem 1rem;
            border-radius: var(--radius-btn);
            border: none;
            background: var(--primary);
            color: #fff;
            font-size: 0.82rem;
            font-weight: 500;
            font-family: var(--font);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: background 0.18s var(--ease), transform 0.18s var(--ease);
            letter-spacing: -0.01em;
        }

        .exp-btn-export:hover {
            background: #e86428;
            transform: translateY(-1px);
        }

        .exp-btn-export svg {
            width: 14px;
            height: 14px;
            stroke: #fff;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Print button */
        .exp-btn-print {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-btn);
            border: 1px solid var(--border);
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            flex-shrink: 0;
            transition: border-color 0.18s, background 0.18s;
        }

        .exp-btn-print:hover {
            border-color: var(--primary);
            background: var(--primary-soft);
        }

        .exp-btn-print svg {
            width: 15px;
            height: 15px;
            stroke: #999;
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            transition: stroke 0.18s;
        }

        .exp-btn-print:hover svg {
            stroke: var(--primary);
        }

        /* Recent table */
        .exp-recent {
            background: var(--surface);
            border-radius: var(--radius-card);
            border: 1px solid var(--border);
            padding: 1.5rem 1.6rem;
        }

        .exp-recent-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.1rem;
        }

        .exp-recent-title {
            font-size: 0.92rem;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: -0.02em;
        }

        .exp-see-all {
            font-size: 0.76rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .exp-table {
            width: 100%;
            border-collapse: collapse;
        }

        .exp-table thead th {
            font-size: 0.63rem;
            font-weight: 500;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #ccc;
            padding-bottom: 0.7rem;
            border-bottom: 1px solid #f2f2f2;
            text-align: left;
        }

        .exp-table tbody td {
            padding: 0.8rem 0;
            font-size: 0.83rem;
            color: #222;
            border-bottom: 1px solid #f7f7f7;
            vertical-align: middle;
        }

        .exp-table tbody tr:last-child td {
            border-bottom: none;
        }

        .exp-table tbody tr:hover td {
            background: #fafafa;
        }

        .exp-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.6rem;
            border-radius: 100px;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .exp-badge-type {
            background: var(--primary-soft);
            color: #d0622a;
        }

        .exp-badge-fmt {
            background: #f5f5f5;
            color: #666;
            letter-spacing: 0.04em;
        }

        .exp-dl-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f5f5f5;
            color: #444;
            border-radius: var(--radius-sm);
            padding: 0.35rem 0.8rem;
            font-size: 0.75rem;
            font-weight: 500;
            text-decoration: none;
            border: 1px solid var(--border);
            transition: border-color 0.18s, background 0.18s, color 0.18s;
            font-family: var(--font);
        }

        .exp-dl-btn:hover {
            border-color: var(--primary);
            background: var(--primary-soft);
            color: var(--primary);
        }

        .exp-dl-btn svg {
            width: 11px;
            height: 11px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        @media print {

            .exp-strip,
            .exp-act-row,
            .exp-fmt-grp,
            .exp-recent {
                display: none !important;
            }

            .exp-card {
                break-inside: avoid;
                box-shadow: none !important;
            }

            .exp-page {
                background: #fff;
                padding: 0;
            }
        }
    </style>
@endpush

@section('admin-content')
    <div class="exp-page">
        <div style="max-width: 1280px; margin: 0 auto;">

            {{-- Header --}}
            <div style="margin-bottom: 2.25rem;">
                <div class="exp-eyebrow">Data Management</div>
                <h1>Export Data</h1>
                <p class="exp-header-sub">Export data ke berbagai format — CSV, Excel, atau Print</p>
            </div>

            {{-- Export cards --}}
            @php
                $cards = [
                    [
                        'type' => 'reports',
                        'title' => 'Reports',
                        'stats' => $stats['total_reports'] ?? 0,
                        'icon' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/>',
                        'route' => 'admin.exports.reports',
                    ],
                    [
                        'type' => 'ratings',
                        'title' => 'Ratings',
                        'stats' => $stats['total_ratings'] ?? 0,
                        'icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
                        'route' => 'admin.exports.ratings',
                    ],
                    [
                        'type' => 'units',
                        'title' => 'Units',
                        'stats' => $stats['total_units'] ?? 0,
                        'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/>',
                        'route' => 'admin.exports.units',
                    ],
                    [
                        'type' => 'unit-types',
                        'title' => 'Unit Types',
                        'stats' => $stats['total_unit_types'] ?? 0,
                        'icon' => '<path d="M22 12h-4l-3 9-4-18-3 9H2"/>',
                        'route' => 'admin.exports.unit-types',
                    ],
                ];
            @endphp

            <div class="exp-grid">
                @foreach($cards as $card)
                    <div class="exp-card" data-type="{{ $card['type'] }}" data-route="{{ route($card['route']) }}">

                        <div class="exp-icon-wrap">
                            <svg viewBox="0 0 24 24">{!! $card['icon'] !!}</svg>
                        </div>

                        <div class="exp-c-label">Export</div>
                        <div class="exp-c-title">{{ $card['title'] }}</div>

                        <div class="exp-c-meta">
                            <svg viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            {{ number_format($card['stats']) }} records
                        </div>

                        <div class="exp-fmt-grp">
                            <button class="exp-fmt-btn active" data-format="csv">CSV</button>
                            <button class="exp-fmt-btn" data-format="excel">Excel</button>
                        </div>

                        <div class="exp-act-row">
                            <button type="button" class="exp-btn-export" onclick="exportData('{{ $card['type'] }}')">
                                <svg viewBox="0 0 24 24">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="7 10 12 15 17 10" />
                                    <line x1="12" y1="15" x2="12" y2="3" />
                                </svg>
                                Export
                            </button>
                        </div>

                    </div>
                @endforeach
            </div>

            {{-- Recent exports --}}
            @if(isset($recentExports) && $recentExports->isNotEmpty())
                <div class="exp-recent">
                    <div class="exp-recent-hd">
                        <div class="exp-recent-title">Recent Exports</div>
                        <a href="#" class="exp-see-all">See all →</a>
                    </div>
                    <div style="overflow-x: auto;">
                        <table class="exp-table">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Type</th>
                                    <th>Format</th>
                                    <th>Date</th>
                                    <th style="text-align:right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentExports as $export)
                                    <tr>
                                        <td style="font-weight:500;">{{ $export->file_name }}</td>
                                        <td><span class="exp-badge exp-badge-type">{{ ucfirst($export->export_type) }}</span></td>
                                        <td><span class="exp-badge exp-badge-fmt">{{ strtoupper($export->format) }}</span></td>
                                        <td style="color:#bbb; font-size:0.78rem;">{{ $export->created_at->format('d M Y H:i') }}
                                        </td>
                                        <td style="text-align:right;">
                                            <a href="{{ route('admin.exports.download', $export->id) }}" class="exp-dl-btn">
                                                <svg viewBox="0 0 24 24">
                                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                    <polyline points="7 10 12 15 17 10" />
                                                    <line x1="12" y1="15" x2="12" y2="3" />
                                                </svg>
                                                Download
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.exp-fmt-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const grp = this.closest('.exp-fmt-grp');
                    grp.querySelectorAll('.exp-fmt-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            function exportData(type) {
                const card = document.querySelector(`.exp-card[data-type="${type}"]`);
                const active = card.querySelector('.exp-fmt-btn.active');
                const format = active ? active.dataset.format : 'csv';
                const action = card.dataset.route;

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = action;
                form.style.display = 'none';

                const csrf = document.createElement('input');
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const fmt = document.createElement('input');
                fmt.name = 'format';
                fmt.value = format;
                form.appendChild(fmt);

                document.body.appendChild(form);
                form.submit();
            }

            function printCard(type, title, count) {
                const win = window.open('', '_blank', 'width=640,height=480');
                win.document.write(`
                            <!DOCTYPE html><html><head><title>Print — ${title}</title>
                            <style>
                                body { font-family: system-ui, sans-serif; padding: 2.5rem; color: #111; }
                                .tag { display:inline-block; background:#fff6f2; color:#d06020;
                                        border-radius:100px; padding:.2rem .8rem; font-size:.75rem;
                                        font-weight:500; margin-bottom:1.5rem; }
                                h2   { font-size:1.5rem; font-weight:600; margin:0 0 .4rem; letter-spacing:-.02em; }
                                p    { font-size:.88rem; color:#999; margin:0 0 1.5rem; }
                                hr   { border:none; border-top:1px solid #eee; margin:1.5rem 0; }
                                small{ font-size:.75rem; color:#bbb; }
                            </style></head><body>
                            <div class="tag">Export · ${title}</div>
                            <h2>${title}</h2>
                            <p>${count.toLocaleString()} records</p>
                            <hr>
                            <small>Printed on ${new Date().toLocaleString()}</small>
                            <script>window.onload=function(){window.print();window.close();}<\/script>
                            </body></html>
                        `);
                win.document.close();
            }
        </script>
    @endpush
@endsection