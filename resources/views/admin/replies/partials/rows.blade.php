@if(is_object($replies) && method_exists($replies, 'count') && $replies->count())
    @foreach($replies as $reply)
        <tr>
            <td class="ps-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                        style="width: 36px; height: 36px;">
                        <span class="fw-bold" style="color: #f8773c;">
                            {{ substr($reply->admin->nama ?? 'A', 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <div class="fw-semibold ms-1">{{ $reply->admin->nama ?? 'Admin' }}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="fw-medium">{{ $reply->rating->unit->name ?? '-' }}</div>
            </td>
            <td>
                <div>{{ $reply->created_at->format('d M Y') }}</div>
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="show" url="{{ route('admin.admin-replies.show', $reply->id) }}"
                        tooltip="View Reply" />

                    <x-admin.button type="edit" url="{{ route('admin.admin-replies.edit', $reply->id) }}"
                        tooltip="Edit Reply" />

                    <x-admin.button type="delete" url="{{ route('admin.admin-replies.destroy', $reply->id) }}"
                        tooltip="Delete Reply" />
                </div>
            </td>
            </div>
            </td>
            <x-admin.delete-modal-component id="deleteModal{{ $reply->id }}" title="Delete Reply"
                itemName="Reply #{{ $reply->id }}" deleteRoute="{{ route('admin.admin-replies.destroy', $reply->id) }}" />
        </tr>
    @endforeach
@elseif(is_string($replies) || $replies === null)
    <tr>
        <td colspan="5" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <p>Error loading replies</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: replies is {{ gettype($replies) }}</small>
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
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
                <p>No replies found</p>
            </div>
        </td>
    </tr>
@endif