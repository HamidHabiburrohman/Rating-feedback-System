<DOCUMENT filename="reply-rating-modal.blade.php">
    @props(['rating'])

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        #replyModal{{ $rating->id }} * {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            box-sizing: border-box;
        }

        #replyModal{{ $rating->id }} .modal-dialog {
            max-width: 560px;
        }

        #replyModal{{ $rating->id }} .rrm-wrap {
            border: none;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgb(15 23 42 / 0.15);
            background: #ffffff;
        }

        #replyModal{{ $rating->id }} .rrm-header {
            padding: 24px 28px 18px;
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
        }

        #replyModal{{ $rating->id }} .rrm-header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        #replyModal{{ $rating->id }} .rrm-pills {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #replyModal{{ $rating->id }} .rrm-pill-admin {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff7ed;
            color: #f8773c;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 9999px;
        }

        #replyModal{{ $rating->id }} .rrm-pill-admin::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #f8773c;
            border-radius: 50%;
        }

        #replyModal{{ $rating->id }} .rrm-pill-track {
            background: #f8fafc;
            border: 1.5px solid #e5e7eb;
            color: #64748b;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 4px 14px;
            border-radius: 9999px;
        }

        #replyModal{{ $rating->id }} .rrm-close {
            width: 32px;
            height: 32px;
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #replyModal{{ $rating->id }} .rrm-close:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        #replyModal{{ $rating->id }} .rrm-heading {
            font-size: 1.875rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.04em;
            line-height: 1.1;
            margin: 0;
        }

        #replyModal{{ $rating->id }} .rrm-body {
            padding: 24px 28px;
            background: #ffffff;
            max-height: 72vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #e5e7eb transparent;
        }

        #replyModal{{ $rating->id }} .rrm-sec-title {
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 12px;
        }

        /* Rating Details Card */
        #replyModal{{ $rating->id }} .rrm-card {
            background: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 28px;
            box-shadow: 0 4px 6px -1px rgb(15 23 42 / 0.05);
        }

        #replyModal{{ $rating->id }} .rrm-card-top {
            padding: 20px 24px 16px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        #replyModal{{ $rating->id }} .rrm-card-icon {
            width: 48px;
            height: 48px;
            background: #fff7ed;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1.4rem;
            font-weight: 700;
            color: #f8773c;
        }

        #replyModal{{ $rating->id }} .rrm-card-info {
            flex: 1;
            min-width: 0;
        }

        #replyModal{{ $rating->id }} .rrm-card-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 10px;
        }

        #replyModal{{ $rating->id }} .rrm-card-title {
            font-size: 1.125rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
        }

        #replyModal{{ $rating->id }} .rrm-meta-row {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            font-size: 0.8125rem;
            color: #64748b;
        }

        #replyModal{{ $rating->id }} .rrm-meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #replyModal{{ $rating->id }} .rrm-card-desc {
            padding: 0 24px 24px;
            border-top: 1px solid #f1f5f9;
            font-size: 0.9rem;
            line-height: 1.6;
            color: #334155;
        }

        /* Response Section */
        #replyModal{{ $rating->id }} .rrm-response-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        #replyModal{{ $rating->id }} .rrm-response-label {
            font-size: 0.6875rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #64748b;
        }

        #replyModal{{ $rating->id }} .rrm-response-label .req {
            color: #f8773c;
        }

        #replyModal{{ $rating->id }} .rrm-textarea {
            width: 100%;
            min-height: 136px;
            padding: 18px 22px;
            border: 1.5px solid #e5e7eb;
            border-radius: 20px;
            font-size: 0.95rem;
            line-height: 1.55;
            color: #0f172a;
            background: #ffffff;
            resize: vertical;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #replyModal{{ $rating->id }} .rrm-textarea:focus {
            border-color: #f8773c;
            box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.12);
            outline: none;
        }

        #replyModal{{ $rating->id }} .rrm-counter {
            text-align: right;
            font-size: 0.75rem;
            font-weight: 500;
            color: #94a3b8;
            margin-top: 8px;
        }

        /* Quick Responses */
        #replyModal{{ $rating->id }} .quick-responses {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        #replyModal{{ $rating->id }} .quick-response {
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 9999px;
            padding: 8px 16px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #replyModal{{ $rating->id }} .quick-response:hover {
            background: #fff7ed;
            border-color: #f8773c;
            color: #f8773c;
        }

        /* Footer */
        #replyModal{{ $rating->id }} .rrm-footer {
            padding: 20px 28px 28px;
            background: #ffffff;
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: flex-end;
        }

        #replyModal{{ $rating->id }} .rrm-btn-cancel {
            padding: 13px 28px;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            color: #334155;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 9999px;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #replyModal{{ $rating->id }} .rrm-btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        #replyModal{{ $rating->id }} .rrm-btn-send {
            padding: 13px 28px;
            background: #f8773c;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 9999px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(248, 119, 60, 0.35);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #replyModal{{ $rating->id }} .rrm-btn-send:hover {
            background: #f0641f;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(248, 119, 60, 0.4);
        }

        @keyframes rrmFade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <div class="modal fade" id="replyModal{{ $rating->id }}" tabindex="-1"
        aria-labelledby="replyModalLabel{{ $rating->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rrm-wrap">

                <!-- Header -->
                <div class="rrm-header">
                    <div class="rrm-header-top">
                        <div class="rrm-pills">
                            <span class="rrm-pill-admin">Admin Reply</span>
                            <span class="rrm-pill-track">#{{ $rating->id }}</span>
                        </div>
                        <button type="button" class="rrm-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                    </div>
                    <h4 class="rrm-heading" id="replyModalLabel{{ $rating->id }}">Balas Rating</h4>
                </div>

                <!-- Body -->
                <div class="rrm-body">
                    <p class="rrm-sec-title">Rating Details</p>
                    <div class="rrm-card">
                        <div class="rrm-card-top">
                            <div class="rrm-card-icon">
                                {{ substr($rating->student->name ?? 'A', 0, 1) }}
                            </div>
                            <div class="rrm-card-info">
                                <div class="rrm-card-title-row">
                                    <span class="rrm-card-title">{{ $rating->unit->name ?? 'Unit' }}</span>
                                </div>
                                <div class="rrm-meta-row">
                                    <span class="rrm-meta-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.75">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                        {{ $rating->created_at->format('d M Y') }}
                                    </span>
                                    <span class="rrm-meta-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.75">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                            <circle cx="12" cy="7" r="4" />
                                        </svg>
                                        {{ $rating->student->name ?? 'Anonymous' }}
                                    </span>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-3">
                                    <div class="d-flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                                style="margin-right: 2px;">
                                                <polygon
                                                    points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"
                                                    fill="{{ $i <= round($rating->overall_score ?? 0) ? '#f8773c' : '#e5e7eb' }}"
                                                    stroke="none" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="fw-bold" style="color: #f8773c; font-size: 1.1rem;">
                                        {{ number_format($rating->overall_score ?? 0, 1) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if ($rating->comment)
                            <p class="rrm-card-desc">{{ Str::limit($rating->comment, 280) }}</p>
                        @endif
                    </div>

                    <form action="', $rating->id) }}" method="POST"
                        id="replyForm{{ $rating->id }}">
                        @csrf

                        <div class="rrm-response-row">
                            <span class="rrm-response-label">YOUR RESPONSE <span class="req">*</span></span>
                        </div>

                        <textarea id="reply_message_{{ $rating->id }}" name="reply_message" class="rrm-textarea" required maxlength="1000"
                            placeholder="Type your reply here..."></textarea>
                        <div class="rrm-counter" id="replyCounter{{ $rating->id }}">0 / 1000</div>

                        <!-- Quick Responses -->
                        <div class="quick-responses">
                            <button type="button" class="quick-response"
                                data-target="reply_message_{{ $rating->id }}"
                                data-response="Terima kasih atas rating positifnya. Kami senang Anda puas dengan pelayanan kami.">Terima
                                kasih</button>
                            <button type="button" class="quick-response"
                                data-target="reply_message_{{ $rating->id }}"
                                data-response="Terima kasih atas masukannya. Kami akan segera menindaklanjuti untuk perbaikan.">Akan
                                ditindaklanjuti</button>
                            <button type="button" class="quick-response"
                                data-target="reply_message_{{ $rating->id }}"
                                data-response="Mohon maaf atas ketidaknyamanannya. Kami akan berusaha meningkatkan kualitas pelayanan.">Permintaan
                                maaf</button>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="rrm-footer">
                    <button type="button" class="rrm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="replyForm{{ $rating->id }}" class="rrm-btn-send">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round">
                            <line x1="22" y1="2" x2="11" y2="13" />
                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                        </svg>
                        Kirim Tanggapan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            var id = '{{ $rating->id }}';
            var ta = document.getElementById('reply_message_' + id);
            var ct = document.getElementById('replyCounter' + id);

            if (ta && ct) {
                ta.addEventListener('input', function() {
                    var n = this.value.length;
                    ct.textContent = n + ' / 1000';
                    ct.classList.toggle('warn', n >= 800 && n < 1000);
                    ct.classList.toggle('over', n >= 1000);
                });
            }

            // Quick response buttons
            document.querySelectorAll('#replyModal' + id + ' .quick-response').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (ta) {
                        ta.value = this.dataset.response;
                        ta.dispatchEvent(new Event('input'));
                    }
                });
            });
        })();
    </script>
</DOCUMENT>
