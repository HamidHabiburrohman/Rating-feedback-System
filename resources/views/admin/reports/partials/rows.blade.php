@if(is_object($reports) && method_exists($reports, 'count') && $reports->count())
    @foreach($reports as $report)
        @php
            $priorityColors = [
                'low' => 'bg-info-subtle text-info',
                'medium' => 'bg-warning-subtle text-warning',
                'high' => 'bg-danger-subtle text-danger',
                'critical' => 'bg-danger-subtle text-danger'
            ];
            $priorityClass = $priorityColors[$report->priority] ?? 'bg-secondary-subtle text-secondary';

            $statusColors = [
                'new' => 'bg-primary-subtle text-primary',
                'in_progress' => 'bg-warning-subtle text-warning',
                'replied' => 'bg-purple-subtle text-purple',
                'resolved' => 'bg-success-subtle text-success',
                'rejected' => 'bg-danger-subtle text-danger'
            ];
            $statusClass = $statusColors[$report->status] ?? 'bg-secondary-subtle text-secondary';
        @endphp

        <tr>
            <td class="ps-4">
                <div class="fw-semibold">{{ Str::limit($report->title, 20) }}</div>
            </td>
            <td>
                <div>{{ Str::limit($report->unit->name, 20) ?? '-' }}</div>
            </td>
            <td>
                <x-admin.status-badge status="{{ $report->priority }}" sizeClass="sm" />
            </td>
            <td>
                <x-admin.status-badge status="{{ $report->status }}" sizeClass="sm" />
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="show" url="{{ route('admin.reports.show', $report->id) }}" tooltip="View Report" />
                    <x-admin.button type="edit" url="{{ route('admin.reports.edit', $report->id) }}" tooltip="Edit Report" />
                    @if(in_array($report->status, ['new', 'in_progress']))
                        <x-admin.button 
                            type="reply" 
                            onclick="openReplyReportModal(
                                '{{ $report->id }}', 
                                '{{ addslashes($report->title) }}', 
                                '{{ $report->tracking_code }}', 
                                '{{ addslashes($report->description) }}', 
                                '{{ $report->priority }}'
                            )" 
                            tooltip="Reply to Report" 
                        />
                    @endif
                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $report->id }}')" tooltip="Delete Report" />

                </div>
            </td>
        </tr>

        <!-- Delete Modal -->
        <x-admin.delete-modal-component id="deleteModal{{ $report->id }}" title="Hapus Laporan"
            :item-name="'Report #' . $report->id . ' - ' . $report->title" item-type="laporan"
            :delete-route="route('admin.reports.destroy', $report->id)" />

        <!-- Reply Modal for Report -->
        <x-admin.reply-modal :reportId="$report->id" :reportTitle="$report->title" :trackingCode="$report->tracking_code"
            :reportDescription="$report->description" :priority="$report->priority" :priorityClass="$priorityClass" />
    @endforeach
@elseif(is_string($reports) || $reports === null)
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <circle cx="12" cy="8" r="1" fill="currentColor" />
                </svg>
                <p class="mb-2">Error loading reports</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: reports is {{ gettype($reports) }}</small>
                @endif
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <circle cx="12" cy="8" r="1" fill="currentColor" />
                </svg>
                <p class="mb-2">No reports found</p>
                <p class="small text-muted">Try adjusting your filters or search criteria</p>
            </div>
        </td>
    </tr>
@endif