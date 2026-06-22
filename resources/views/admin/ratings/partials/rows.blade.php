@if(is_object($ratings) && method_exists($ratings, 'count') && $ratings->count())
    @foreach($ratings as $rating)
        @php
            $statusMap = [
                'active' => ['label' => 'Active', 'class' => 'bg-success-subtle text-success'],
                'edited' => ['label' => 'Edited', 'class' => 'bg-info-subtle text-info'],
                'archived' => ['label' => 'Archived', 'class' => 'bg-secondary-subtle text-secondary']
            ];
            $status = $statusMap[$rating->status] ?? $statusMap['active'];

            $score = $rating->overall_score ?? 0;
        @endphp

        <tr>
            <td class="ps-4">
                <div class="form-check">
                    <input class="form-check-input rating-checkbox" type="checkbox" value="{{ $rating->id }}">
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div>
                        <div class="fw-semibold">{{ Str::limit($rating->unit->name ?? '-', 20) }}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="fw-medium">{{ Str::limit($rating->student->name ?? 'Anonymous', 13) }}</div>
            </td>
            <td>
                <div class="rating-badge">
                    <span class="rating-score fs-3 fw-medium">{{ number_format($score, 1) }}</span>
                    <span class="rating-star">★</span>
                </div>
            </td>
            <td class="text-center">
                <x-shared.status-badge status="{{ $rating->status }}" sizeClass="sm" />
            </td>
            <td class="text-center pe-4">
                <div class="d-flex justify-content-center gap-1">
                    <x-admin.button type="show" url="{{ route('admin.ratings.show', $rating->id) }}" tooltip="View Rating" />

                    @if(!$rating->adminReply)
                        <x-admin.button type="reply" modalId="replyModal{{ $rating->id }}" tooltip="Reply to Rating" />
                    @endif

                    <x-admin.button type="delete" onclick="openModal('deleteModal{{ $rating->id }}')" tooltip="Delete Rating" />

                </div>
            </td>
        </tr>

        <x-admin.reply-rating-modal :rating="$rating" />

        <!-- Delete Modal Component -->
        <x-shared.delete-modal id="deleteModal{{ $rating->id }}" title="Delete Rating"
            itemName="Rating for {{ $rating->unit->name ?? 'Unit' }} (Score: {{ number_format($rating->overall_score, 1) }})"
            itemType="rating" deleteRoute="{{ route('admin.ratings.destroy', $rating->id) }}" />
    @endforeach
@elseif(is_string($ratings) || $ratings === null)
    <tr>
        <td colspan="6" class="text-center py-5 text-muted">
            <div style="padding: 40px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" style="opacity: 0.3; margin-bottom: 16px;">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <circle cx="12" cy="8" r="1" fill="currentColor" />
                </svg>
                <p class="mb-2">Error loading ratings</p>
                @if(config('app.debug'))
                    <small class="text-danger">Debug: ratings is {{ gettype($ratings) }}</small>
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
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 16v-4" />
                    <circle cx="12" cy="8" r="1" fill="currentColor" />
                </svg>
                <p class="mb-2">No ratings found</p>
                <p class="small text-muted">Try adjusting your filters or search criteria</p>
            </div>
        </td>
    </tr>
@endif

<style>
    /* Rating Badge - Simple & Minimal Version */
.rating-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: #ffffff;
    border: 1.5px solid #fee9d6;
    border-radius: 9999px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
    transition: all 0.2s ease;
}

.rating-badge:hover {
    background: #fff7ed;
    border-color: #fdda68;
    transform: translateY(-1px);
}

.rating-score {
    font-weight: 700;
    font-size: 1.05rem;
    color: #1e293b;
    line-height: 1;
}

.rating-star {
    color: #fdda68;
    font-size: 1.25rem;
    line-height: 1;
    margin-top: -2px;
}
</style>