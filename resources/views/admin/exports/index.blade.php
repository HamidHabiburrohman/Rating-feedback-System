@extends('layouts.admin.app')

@section('title', 'Export Data')

@section('admin-content')
<div style="background-color: #ffffff; min-height: 100vh; padding: 2rem 1rem;">
    <div style="max-width: 1280px; margin: 0 auto;">
        <div style="margin-bottom: 2.5rem;">
            <h1 style="font-size: 1.875rem; font-weight: 700; color: #111827; margin-bottom: 0.25rem;">Export Data</h1>
            <p style="color: #6b7280; font-size: 1rem;">Export data ke berbagai format (CSV, Excel, PDF)</p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin-bottom: 4rem;">
            @php
            $cards = [
                [
                    'type' => 'reports',
                    'title' => 'Reports',
                    'gradient' => 'linear-gradient(135deg, #8b5cf6, #3b82f6)',
                    'shadowColor' => '139, 92, 246',
                    'icon' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" /><polyline points="7 10 12 15 17 10" /><line x1="12" y1="15" x2="12" y2="3" />',
                    'stats' => $stats['total_reports'] ?? 0
                ],
                [
                    'type' => 'ratings',
                    'title' => 'Ratings',
                    'gradient' => 'linear-gradient(135deg, #f97316, #ec4899)',
                    'shadowColor' => '249, 115, 22',
                    'icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />',
                    'stats' => $stats['total_ratings'] ?? 0
                ],
                [
                    'type' => 'units',
                    'title' => 'Units',
                    'gradient' => 'linear-gradient(135deg, #06b6d4, #14b8a6)',
                    'shadowColor' => '6, 182, 212',
                    'icon' => '<rect x="3" y="3" width="18" height="18" rx="2" /><line x1="3" y1="9" x2="21" y2="9" /><line x1="9" y1="21" x2="9" y2="9" />',
                    'stats' => $stats['total_units'] ?? 0
                ],
                [
                    'type' => 'unit-types',
                    'title' => 'Unit Types',
                    'gradient' => 'linear-gradient(135deg, #10b981, #34d399)',
                    'shadowColor' => '16, 185, 129',
                    'icon' => '<path d="M22 12h-4l-3 9-4-18-3 9H2" />',
                    'stats' => $stats['total_unit_types'] ?? 0
                ]
            ];
            @endphp

            @foreach($cards as $card)
            <div class="export-card" data-type="{{ $card['type'] }}" style="background: white; border-radius: 24px; padding: 2rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); border: 1px solid rgba(0,0,0,0.04); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; overflow: hidden;">
                <div style="position: absolute; top: -50%; right: -20%; width: 200px; height: 200px; background: {{ $card['gradient'] }}; opacity: 0.03; border-radius: 50%; filter: blur(40px); pointer-events: none;"></div>
                
                <div style="position: relative; margin-bottom: 1.5rem;">
                    <div style="position: absolute; top: 8px; left: 8px; width: 80px; height: 80px; background: {{ $card['gradient'] }}; border-radius: 20px; opacity: 0.2; filter: blur(12px); transition: all 0.3s ease;"></div>
                    
                    <div style="position: relative; width: 80px; height: 80px; background: {{ $card['gradient'] }}; border-radius: 20px; display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px -5px rgba({{ $card['shadowColor'] }}, 0.4); transition: all 0.3s ease;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            {!! $card['icon'] !!}
                        </svg>
                    </div>
                </div>

                <div style="margin-bottom: 1rem;">
                    <span style="font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 0.25rem;">EXPORT</span>
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin: 0; letter-spacing: -0.02em;">{{ $card['title'] }}</h2>
                </div>

                <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 0.375rem; font-size: 0.875rem; color: #6b7280;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        <span>{{ number_format($card['stats']) }} records</span>
                    </div>
                </div>

                <div style="display: flex; gap: 0.75rem; margin-bottom: 1.5rem;">
                    <button class="format-btn active" data-format="csv" style="flex: 1; padding: 0.625rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb; background: #f3f4f6; font-size: 0.875rem; font-weight: 500; color: #111827; cursor: pointer; transition: all 0.2s ease;">
                        CSV
                    </button>
                    <button class="format-btn" data-format="excel" style="flex: 1; padding: 0.625rem 1rem; border-radius: 12px; border: 2px solid #e5e7eb; background: transparent; font-size: 0.875rem; font-weight: 500; color: #6b7280; cursor: pointer; transition: all 0.2s ease;">
                        Excel
                    </button>
                </div>

                <button type="button" class="export-action-btn" onclick="exportData('{{ $card['type'] }}')" style="width: 100%; padding: 0.875rem 1.5rem; border-radius: 9999px; border: none; background: #f3f4f6; color: #374151; font-size: 0.875rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s ease;">
                    <span>Export</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition: transform 0.2s ease;">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                </button>
            </div>
            @endforeach
        </div>

        @if(isset($recentExports) && $recentExports->isNotEmpty())
        <div style="background: white; border-radius: 24px; padding: 1.5rem 2rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03); border: 1px solid rgba(0,0,0,0.04);">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #111827; margin-bottom: 1.5rem;">Recent Exports</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 1px solid #f3f4f6;">
                            <th style="padding-bottom: 0.875rem; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">File Name</th>
                            <th style="padding-bottom: 0.875rem; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Type</th>
                            <th style="padding-bottom: 0.875rem; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Format</th>
                            <th style="padding-bottom: 0.875rem; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em;">Date</th>
                            <th style="padding-bottom: 0.875rem; font-size: 0.75rem; font-weight: 600; color: #9ca3af; text-transform: uppercase; letter-spacing: 0.05em; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentExports as $export)
                        <tr style="border-bottom: 1px solid #f9fafb;">
                            <td style="padding: 1rem 0; font-weight: 500; color: #111827;">{{ $export->file_name }}</td>
                            <td style="padding: 1rem 0;">
                                <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background: #eff6ff; color: #3b82f6;">
                                    {{ ucfirst($export->export_type) }}
                                </span>
                            </td>
                            <td style="padding: 1rem 0;">
                                <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 500; background: #f3f4f6; color: #374151; text-transform: uppercase;">
                                    {{ $export->format }}
                                </span>
                            </td>
                            <td style="padding: 1rem 0; color: #6b7280; font-size: 0.875rem;">{{ $export->created_at->format('d M Y H:i') }}</td>
                            <td style="padding: 1rem 0; text-align: right;">
                                <a href="{{ route('admin.exports.download', $export->id) }}" style="display: inline-flex; align-items: center; gap: 0.375rem; padding: 0.5rem 1rem; border-radius: 9999px; background: #f3f4f6; color: #374151; font-size: 0.875rem; font-weight: 500; text-decoration: none;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7 10 12 15 17 10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    <span>Download</span>
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

<style>
    .export-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12) !important;
    }
    
    .export-card:hover div[style*="filter: blur(12px)"] {
        opacity: 0.3 !important;
        transform: scale(1.1);
    }
    
    .export-card:hover div[style*="box-shadow: 0 10px 25px"] {
        transform: scale(1.05);
        box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.2) !important;
    }
    
    .format-btn.active {
        background: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #111827 !important;
    }
    
    .format-btn:hover:not(.active) {
        border-color: #d1d5db !important;
        background: #f9fafb !important;
    }
    
    .export-action-btn:hover {
        background: #e5e7eb !important;
        transform: translateY(-1px);
    }
    
    .export-action-btn:hover svg {
        transform: translateY(2px);
    }
    
    tbody tr:hover {
        background: #f9fafb;
    }
    
    @media (max-width: 768px) {
        div[style*="grid-template-columns: repeat(2, 1fr)"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>

@push('scripts')
<script>
let currentFormat = 'csv';

document.querySelectorAll('.format-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const parent = this.closest('.export-card');
        parent.querySelectorAll('.format-btn').forEach(b => {
            b.classList.remove('active');
            b.style.background = 'transparent';
            b.style.color = '#6b7280';
        });
        this.classList.add('active');
        this.style.background = '#f3f4f6';
        this.style.color = '#111827';
        currentFormat = this.dataset.format;
    });
});

function exportData(type) {
    const card = document.querySelector(`.export-card[data-type="${type}"]`);
    const activeFormat = card.querySelector('.format-btn.active');
    const format = activeFormat ? activeFormat.dataset.format : 'csv';
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = getExportUrl(type);
    form.style.display = 'none';

    const csrfToken = document.createElement('input');
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    const formatInput = document.createElement('input');
    formatInput.name = 'format';
    formatInput.value = format;
    form.appendChild(formatInput);

    document.body.appendChild(form);
    form.submit();
}

function getExportUrl(type) {
    const urls = {
        'reports': '{{ route("admin.exports.reports") }}',
        'ratings': '{{ route("admin.exports.ratings") }}',
        'units': '{{ route("admin.exports.units") }}',
        'unit-types': '{{ route("admin.exports.unit-types") }}'
    };
    return urls[type] || urls.reports;
}
</script>
@endpush
@endsection