@if($reports->count())
    @foreach($reports as $report)
        @php
            $statusColors = [
                'baru' => 'bg-primary-subtle text-primary',
                'diproses' => 'bg-warning-subtle text-warning',
                'selesai' => 'bg-success-subtle text-success',
                'ditolak' => 'bg-danger-subtle text-danger'
            ];

            $statusLabels = [
                'baru' => 'New',
                'diproses' => 'In Progress',
                'selesai' => 'Completed',
                'ditolak' => 'Rejected'
            ];
        @endphp

        <tr>
            <td class="ps-4">
                <div class="fw-semibold text-dark">{{ $report->unit->nama_unit ?? '-' }}</div>
                <div class="text-muted" style="font-size:.75rem">
                    {{ $report->tracking_code }} • {{ $report->created_at->format('d M Y') }}
                </div>
            </td>

            <td>
                <div class="fw-semibold text-dark">{{ Str::limit($report->judul, 40) }}</div>
                <div class="text-muted small text-truncate" style="max-width: 250px;">
                    {{ Str::limit($report->deskripsi, 50) }}
                </div>
            </td>

            <td>
                <span class="badge rounded-pill px-3 {{ $statusColors[$report->status] ?? '' }}">
                    {{ $statusLabels[$report->status] ?? $report->status }}
                </span>
            </td>

            <td class="text-center pe-4">
                <div class="d-flex justify-content-center align-items-center gap-1">
                    <a href="{{ route('admin.reports.show', $report->id) }}"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn "
                        data-bs-toggle="tooltip" data-bs-title="View Details">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </a>

                    <button type="button"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn open-delete-modal btn-trigger-delete"
                        data-bs-toggle="tooltip" data-bs-title="Delete Report" data-report-id="{{ $report->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            <line x1="10" y1="11" x2="10" y2="17"></line>
                            <line x1="14" y1="11" x2="14" y2="17"></line>
                        </svg>
                    </button>
                </div>
                <x-delete-modal id="deleteModalReport{{ $report->id }}" title="Delete Report" :itemName="$report->judul"
                    itemType="report" :deleteRoute="route('admin.reports.destroy', $report->id)" deleteMethod="DELETE" />
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            No Report found
        </td>
    </tr>
@endif