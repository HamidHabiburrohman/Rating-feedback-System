@foreach($reports as $report)
@php
    $statusColors = [
        'baru' => 'bg-primary-subtle text-primary border border-primary-subtle',
        'diproses' => 'bg-warning-subtle text-warning border border-warning-subtle', 
        'selesai' => 'bg-success-subtle text-success border border-success-subtle',
        'ditolak' => 'bg-danger-subtle text-danger border border-danger-subtle'
    ];
    
    $priorityColors = [
        'rendah' => 'bg-secondary-subtle text-secondary border border-secondary-subtle',
        'sedang' => 'bg-info-subtle text-info border border-info-subtle',
        'tinggi' => 'bg-warning-subtle text-warning border border-warning-subtle',
        'kritis' => 'bg-danger-subtle text-danger border border-danger-subtle'
    ];
    
    $typeColors = [
        'masalah' => 'bg-danger-subtle text-danger border border-danger-subtle',
        'saran' => 'bg-info-subtle text-info border border-info-subtle',
        'keluhan' => 'bg-warning-subtle text-warning border border-warning-subtle',
        'pujian' => 'bg-success-subtle text-success border border-success-subtle',
        'lainnya' => 'bg-secondary-subtle text-secondary border border-secondary-subtle'
    ];
@endphp

<tr>
    <td class="ps-4">
        <div class="fw-semibold">{{ $report->judul }}</div>
        <div class="text-muted" style="font-size:.75rem">
            {{ $report->unit->nama_unit ?? 'No unit' }}
        </div>
    </td>

    <td>
        <div class="text-truncate" style="max-width: 250px;">
            {{ Str::limit($report->deskripsi, 80) }}
        </div>
    </td>

    <td>
        <span class="badge rounded-pill px-3 {{ $statusColors[$report->status] ?? '' }}">
            {{ $report->status_label }}
        </span>
    </td>

    <td class="text-center pe-4">
        <div class="d-flex justify-content-center gap-1">
            <a href="{{ route('admin.reports.show', $report->id) }}"
                class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn"
                data-bs-toggle="tooltip" data-bs-title="View Details">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </a>

            <a href="{{ route('admin.reports.edit', $report->id) }}"
                class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn"
                data-bs-toggle="tooltip" data-bs-title="Edit Report">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                </svg>
            </a>

            <button type="button"
                class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn btn-trigger-delete"
                data-bs-toggle="tooltip" data-bs-title="Delete Report" onclick="openModal('deleteModal{{ $report->id }}')">
                <div class="pulse-ring"></div>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                </svg>
            </button>
        </div>

        <x-delete-modal id="deleteModal{{ $report->id }}" title="Delete Report" itemName="{{ $report->judul }}"
            itemType="report" deleteRoute="{{ route('admin.reports.destroy', $report->id) }}" deleteMethod="DELETE" />
    </td>
</tr>
@endforeach

<style>
.action-btn {
    width: 32px;
    height: 32px;
    border-color: #d1d5db;
    color: #6b7280;
    transition: all 0.2s ease;
    position: relative;
    overflow: visible;
    background: white;
}

.action-btn:hover {
    background-color: #f3f4f6;
    color: #111827;
    border-color: #9ca3af;
}

.btn-trigger-delete:hover {
    background-color: #fef2f2;
    border-color: #dc2626;
    color: #dc2626;
}

.pulse-ring {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
    border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7);
    opacity: 0;
    pointer-events: none;
}

.btn-trigger-delete:hover .pulse-ring {
    animation: rippleEffect 1.5s infinite cubic-bezier(0.4, 0, 0.2, 1);
}

@keyframes rippleEffect {
    0% {
        width: 0%;
        height: 0%;
        opacity: 0.5;
    }
    100% {
        width: 250%;
        height: 250%;
        opacity: 0;
    }
}
</style>