@if(is_object($logs) && method_exists($logs, 'count') && $logs->count())
    @foreach($logs as $log)
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;">
                        <span class="fw-bold" style="color: #f8773c;">
                            {{ substr($log->admin->nama ?? 'S', 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <div class="fw-medium">{{ $log->admin->nama  }}</div>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge bg-light text-dark rounded-pill px-3">
                    {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                </span>
            </td>
            <td>
                <div>{{ $log->target_type }}</div>
            </td>
            <td>
                <div>{{ $log->created_at->format('d M Y') }}</div>
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="show" url="{{ route('admin.moderation-logs.show', $log->id) }}" tooltip="View Log" />

                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $log->id }}')" tooltip="Delete Log" />
                </div>
            </td>
            <x-shared.delete-modal id="deleteModal{{ $log->id }}" title="Delete Log"
                itemName="Log #{{ $log->id }}" deleteRoute="{{ route('admin.moderation-logs.destroy', $log->id) }}" />
        </tr>
    @endforeach
@elseif(is_string($logs) || $logs === null)
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <p>Error loading logs</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: logs is {{ gettype($logs) }}</small>
                @endif
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <p>No logs found</p>
            </div>
        </td>
    </tr>
@endif