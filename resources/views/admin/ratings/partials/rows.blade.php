@foreach($ratings as $rating)
    @php
        $meta = is_string($rating->metadata) ? json_decode($rating->metadata, true) : ($rating->metadata ?? []);
        $avg = count($meta) ? array_sum($meta) / count($meta) : 0;

        $colors = [
            'pending' => 'bg-warning-subtle text-warning',
            'dibalas' => 'bg-info-subtle text-info',
            'selesai' => 'bg-success-subtle text-success'
        ];

        $labels = [
            'pending' => 'Pending',
            'dibalas' => 'Responded',
            'selesai' => 'Completed'
        ];
    @endphp

    <tr>
        <td class="ps-4">
            <div class="fw-semibold">{{ $rating->unit->nama_unit ?? '-' }}</div>
            <div class="text-muted" style="font-size:.75rem">
                {{ $rating->unit->kode_unit ?? 'No code' }}
            </div>
        </td>

        <td>
            @if($rating->komentar)
                <div class="fw-semibold">{{ Str::limit($rating->komentar, 50) }}</div>
            @else
                <span class="text-muted">No comment</span>
            @endif
        </td>

        <td>
            <span class="badge rounded-pill px-3 {{ $colors[$rating->status] ?? '' }}">
                {{ $labels[$rating->status] ?? $rating->status }}
            </span>
        </td>

        <td class="text-center pe-4">
            <div class="d-flex justify-content-center align-items-center gap-1">
                <!-- View Details -->
                <a href="{{ route('admin.ratings.show', $rating->id) }}"
                    class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn btn-trigger-show"
                    data-bs-toggle="tooltip" data-bs-title="View Details">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                        <circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </a>

                <!-- Reply Button - Show for pending ratings -->
                @if($rating->status === 'pending')
                    <button type="button"
                        class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn btn-trigger-reply"
                        data-bs-toggle="tooltip" data-bs-title="Reply Rating"
                        onclick="openModal('replyModalRating{{ $rating->id }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </button>
                @endif

                <!-- Delete Button -->
                <button type="button"
                    class="btn btn-sm btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center action-btn btn-trigger-delete"
                    onclick="openModal('deleteModalRating{{ $rating->id }}')" data-bs-toggle="tooltip"
                    data-bs-title="Delete Rating">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        <line x1="10" y1="11" x2="10" y2="17"></line>
                        <line x1="14" y1="11" x2="14" y2="17"></line>
                    </svg>
                </button>
            </div>
        </td>
    </tr>

    <!-- Modal Balas Rating - Landscape Version -->
    <div class="custom-modal" id="replyModalRating{{ $rating->id }}">
        <div class="custom-modal-backdrop" onclick="closeModal('replyModalRating{{ $rating->id }}')"></div>
        <div class="custom-modal-dialog" style="max-width: 500px;">
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <button class="modal-close-btn" onclick="closeModal('replyModalRating{{ $rating->id }}')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                    <div class="message-icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none"
                            stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="message-icon">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <h2 class="modal-title">Balas Rating</h2>
                    <p class="modal-subtitle">Balas komentar dari pengunjung</p>
                </div>

                <div class="custom-modal-body">
                    <form action="{{ route('admin.ratings.reply', $rating->id) }}" method="POST" class="reply-form">
                        @csrf

                        <!-- Unit Info - Compact Layout -->
                        <div class="mb-4 p-3 rounded-3" style="background-color: #f9fafb; border: 1px solid #e5e7eb;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold mb-1" style="color: #1a1a1a;">
                                        {{ $rating->unit->nama_unit ?? 'N/A' }}
                                    </div>
                                    <div class="text-muted small">Kode: {{ $rating->unit->kode_unit ?? 'N/A' }}</div>
                                </div>
                                <div>
                                    <span class="badge rounded-pill px-3 bg-warning-subtle text-warning">
                                        {{ ucfirst($rating->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Original Comment - More Compact -->
                        <div class="mb-3">
                            <label class="form-label fw-medium mb-2 d-flex align-items-center gap-1">
                                Komentar Pengunjung
                            </label>
                            <div class="p-3 rounded-3"
                                style="background-color: #f9fafb; border: 1px solid #e5e7eb; min-height: 60px;">
                                <p class="mb-0" style="line-height: 1.5;">
                                    {{ $rating->komentar ?: 'Tidak ada komentar' }}
                                </p>
                            </div>
                        </div>

                        <!-- Reply Textarea -->
                        <div class="mb-1">
                            <label for="reply_message_{{ $rating->id }}"
                                class="form-label fw-medium mb-2 d-flex align-items-center gap-1">
                                Balasan Anda
                            </label>
                            <textarea class="form-control rounded-3" id="reply_message_{{ $rating->id }}"
                                name="reply_message" rows="3" placeholder="Tulis balasan Anda di sini..."
                                style="border: 1px solid #e5e7eb; resize: vertical; min-height: 80px;"></textarea>
                        </div>

                        <div class="custom-modal-footer mb-3" style="border-top: 0; padding: 24px 0 0;">
                            <div class="footer-buttons">
                                <button type="button" class="btn-cancel"
                                    onclick="closeModal('replyModalRating{{ $rating->id }}')"
                                    style="padding: 10px 20px; font-size: 14px;">
                                    <span class="btn-text">Batal</span>
                                </button>
                                <button type="submit" class="btn-submit" style="padding: 10px 20px; font-size: 14px;">
                                    <span class="btn-text">Kirim Balasan</span>
                                    <div class="loading-spinner" style="display: none;"></div>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete (existing) -->
    <x-delete-modal id="deleteModalRating{{ $rating->id }}" title="Delete Rating" :itemName="($rating->unit->nama_unit ?? 'Unknown Unit') . ' Rating'" itemType="rating" :deleteRoute="route('admin.ratings.destroy', $rating->id)"
        deleteMethod="DELETE" />
@endforeach