<DOCUMENT filename="reply-modal.blade.php">
@props([
    'reportId'         => 0,
    'reportTitle'      => 'Laporan',
    'trackingCode'     => '#',
    'reportDescription'=> null,
    'priority'         => null,
    'reporterName'     => null,
    'reporterLocation' => null,
    'reportedAt'       => null,
])

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

#replyReportModal{{ $reportId }} * {
    font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
    box-sizing: border-box;
}

#replyReportModal{{ $reportId }} .modal-dialog {
    max-width: 560px;
}

#replyReportModal{{ $reportId }} .rrm-wrap {
    border: none;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 25px 50px -12px rgb(15 23 42 / 0.15);
    background: #ffffff;
}

#replyReportModal{{ $reportId }} .rrm-header {
    padding: 24px 28px 18px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
#replyReportModal{{ $reportId }} .rrm-header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 14px;
}
#replyReportModal{{ $reportId }} .rrm-pills {
    display: flex;
    align-items: center;
    gap: 8px;
}
#replyReportModal{{ $reportId }} .rrm-pill-admin {
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
#replyReportModal{{ $reportId }} .rrm-pill-admin::before {
    content: '';
    width: 6px; height: 6px;
    background: #f8773c;
    border-radius: 50%;
}
#replyReportModal{{ $reportId }} .rrm-pill-track {
    background: #f8fafc;
    border: 1.5px solid #e5e7eb;
    color: #64748b;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 14px;
    border-radius: 9999px;
}
#replyReportModal{{ $reportId }} .rrm-close {
    width: 32px; height: 32px;
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
#replyReportModal{{ $reportId }} .rrm-close:hover {
    background: #f1f5f9;
    color: #0f172a;
}

#replyReportModal{{ $reportId }} .rrm-heading {
    font-size: 1.875rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.04em;
    line-height: 1.1;
    margin: 0;
}

#replyReportModal{{ $reportId }} .rrm-body {
    padding: 24px 28px;
    background: #ffffff;
    max-height: 72vh;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #e5e7eb transparent;
}

#replyReportModal{{ $reportId }} .rrm-sec-title {
    font-size: 0.6875rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #64748b;
    margin-bottom: 12px;
}

#replyReportModal{{ $reportId }} .rrm-card {
    background: #ffffff;
    border: 1px solid #f1f5f9;
    border-radius: 20px;
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 4px 6px -1px rgb(15 23 42 / 0.05);
}
#replyReportModal{{ $reportId }} .rrm-card-top {
    padding: 20px 24px 16px;
    display: flex;
    align-items: flex-start;
    gap: 16px;
}
#replyReportModal{{ $reportId }} .rrm-card-icon {
    width: 48px;
    height: 48px;
    background: #fff7ed;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
#replyReportModal{{ $reportId }} .rrm-card-info { flex: 1; min-width: 0; }
#replyReportModal{{ $reportId }} .rrm-card-title-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 10px;
}
#replyReportModal{{ $reportId }} .rrm-card-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.3;
}
#replyReportModal{{ $reportId }} .rrm-badge-high {
    background: #fefce8;
    color: #854d0e;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 5px 15px;
    border-radius: 9999px;
    white-space: nowrap;
    flex-shrink: 0;
}
#replyReportModal{{ $reportId }} .rrm-badge-medium {
    background: #dbeafe;
    color: #1e40af;
    padding: 5px 15px;
    border-radius: 9999px;
}
#replyReportModal{{ $reportId }} .rrm-badge-low {
    background: #ecfdf5;
    color: #166534;
    padding: 5px 15px;
    border-radius: 9999px;
}
#replyReportModal{{ $reportId }} .rrm-meta-row {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
    font-size: 0.8125rem;
    color: #64748b;
}
#replyReportModal{{ $reportId }} .rrm-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}
#replyReportModal{{ $reportId }} .rrm-card-desc {
    padding: 0 24px 24px;
    border-top: 1px solid #f1f5f9;
    font-size: 0.9rem;
    line-height: 1.6;
    color: #334155;
}

#replyReportModal{{ $reportId }} .rrm-response-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
#replyReportModal{{ $reportId }} .rrm-response-label {
    font-size: 0.6875rem;
    font-weight: 700;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: #64748b;
}
#replyReportModal{{ $reportId }} .rrm-response-label .req { color: #f8773c; }

#replyReportModal{{ $reportId }} .rrm-tpl-wrap { position: relative; }
#replyReportModal{{ $reportId }} .rrm-tpl-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: #ffffff;
    border: 1.5px solid #e5e7eb;
    border-radius: 16px;
    font-size: 0.8125rem;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
#replyReportModal{{ $reportId }} .rrm-tpl-btn:hover {
    border-color: #f8773c;
    color: #f8773c;
    background: #fff7ed;
}
#replyReportModal{{ $reportId }} .rrm-tpl-btn.open .chev { transform: rotate(180deg); }

#replyReportModal{{ $reportId }} .rrm-tpl-drop {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    background: #ffffff;
    border: 1.5px solid #e5e7eb;
    border-radius: 20px;
    box-shadow: 0 20px 25px -5px rgb(15 23 42 / 0.1);
    min-width: 240px;
    z-index: 50;
    overflow: hidden;
}
#replyReportModal{{ $reportId }} .rrm-tpl-drop.show { display: block; animation: rrmFade 0.2s ease; }
#replyReportModal{{ $reportId }} .rrm-tpl-item {
    padding: 14px 20px;
    font-size: 0.9rem;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
}
#replyReportModal{{ $reportId }} .rrm-tpl-item:last-child { border-bottom: none; }
#replyReportModal{{ $reportId }} .rrm-tpl-item:hover {
    background: #fff7ed;
    color: #f8773c;
}

#replyReportModal{{ $reportId }} .rrm-textarea {
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
#replyReportModal{{ $reportId }} .rrm-textarea:focus {
    border-color: #f8773c;
    box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.12);
    outline: none;
}
#replyReportModal{{ $reportId }} .rrm-counter {
    text-align: right;
    font-size: 0.75rem;
    font-weight: 500;
    color: #94a3b8;
    margin-top: 8px;
}
#replyReportModal{{ $reportId }} .rrm-counter.warn { color: #f59e0b; }
#replyReportModal{{ $reportId }} .rrm-counter.over { color: #ef4444; }

#replyReportModal{{ $reportId }} .rrm-status-wrap { position: relative; margin-bottom: 16px; }
#replyReportModal{{ $reportId }} .rrm-status-trigger {
    width: 100%;
    padding: 16px 22px;
    border: 1.5px solid #e5e7eb;
    border-radius: 20px;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    font-size: 0.95rem;
    font-weight: 600;
    color: #0f172a;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}
#replyReportModal{{ $reportId }} .rrm-status-trigger:hover { border-color: #cbd5e1; }
#replyReportModal{{ $reportId }} .rrm-status-trigger.open { border-color: #f8773c; box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.12); }

#replyReportModal{{ $reportId }} .rrm-status-drop {
    display: none;
    margin-top: 8px;
    background: #ffffff;
    border: 1.5px solid #e5e7eb;
    border-radius: 20px;
    box-shadow: 0 20px 25px -5px rgb(15 23 42 / 0.1);
    overflow: hidden;
}
#replyReportModal{{ $reportId }} .rrm-status-drop.show { display: block; animation: rrmFade 0.2s ease; }
#replyReportModal{{ $reportId }} .rrm-sopt {
    padding: 14px 22px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.95rem;
    font-weight: 500;
    color: #334155;
    cursor: pointer;
    border-bottom: 1px solid #f1f5f9;
}
#replyReportModal{{ $reportId }} .rrm-sopt:last-child { border-bottom: none; }
#replyReportModal{{ $reportId }} .rrm-sopt:hover { background: #fff7ed; }
#replyReportModal{{ $reportId }} .rrm-sopt.active { background: #fff7ed; color: #f8773c; }
#replyReportModal{{ $reportId }} .rrm-dot { width: 9px; height: 9px; border-radius: 50%; }

#replyReportModal{{ $reportId }} .rrm-footer {
    padding: 20px 28px 28px;
    background: #ffffff;
    display: flex;
    align-items: center;
    gap: 12px;
    justify-content: flex-end;
}
#replyReportModal{{ $reportId }} .rrm-btn-cancel {
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
#replyReportModal{{ $reportId }} .rrm-btn-cancel:hover { background: #f8fafc; border-color: #cbd5e1; }

#replyReportModal{{ $reportId }} .rrm-btn-send {
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
#replyReportModal{{ $reportId }} .rrm-btn-send:hover {
    background: #f0641f;
    transform: translateY(-1px);
    box-shadow: 0 8px 20px rgba(248, 119, 60, 0.4);
}

@keyframes rrmFade {
    from { opacity: 0; transform: translateY(8px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>

<div class="modal fade" id="replyReportModal{{ $reportId }}" tabindex="-1" aria-labelledby="rrmLabel{{ $reportId }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rrm-wrap">

            <div class="rrm-header">
                <div class="rrm-header-top">
                    <div class="rrm-pills">
                        <span class="rrm-pill-admin">Admin Reply</span>
                        <span class="rrm-pill-track">{{ $trackingCode }}</span>
                    </div>
                    <button type="button" class="rrm-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                </div>
                <h4 class="rrm-heading" id="rrmLabel{{ $reportId }}">Reply to Report</h4>
            </div>

            <div class="rrm-body">
                <p class="rrm-sec-title">Report Details</p>
                <div class="rrm-card">
                    <div class="rrm-card-top">
                        <div class="rrm-card-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#f8773c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="8" width="18" height="13" rx="1"/>
                                <path d="M7 8V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v3"/>
                                <path d="M15 21v-4a2 2 0 0 0-2-2H11a2 2 0 0 0-2 2v4"/>
                            </svg>
                        </div>
                        <div class="rrm-card-info">
                            <div class="rrm-card-title-row">
                                <span class="rrm-card-title">{{ $reportTitle }}</span>
                                @if($priority)
                                    @php
                                        $pk = strtolower($priority);
                                        $pMap = [
                                            'high' => ['rrm-badge-high', 'High'],
                                            'medium' => ['rrm-badge-medium', 'Medium'],
                                            'low' => ['rrm-badge-low', 'Low'],
                                        ];
                                        [$pCls, $pLbl] = $pMap[$pk] ?? ['rrm-badge-low', ucfirst($priority)];
                                    @endphp
                                    <span class="{{ $pCls }}">{{ $pLbl }}</span>
                                @endif
                            </div>
                            <div class="rrm-meta-row">
                                @if($reportedAt)
                                <span class="rrm-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    {{ $reportedAt }}
                                </span>
                                @endif
                                @if($reporterName)
                                <span class="rrm-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    {{ $reporterName }}
                                </span>
                                @endif
                                @if($reporterLocation)
                                <span class="rrm-meta-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.75"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                    {{ $reporterLocation }}
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if($reportDescription)
                        <p class="rrm-card-desc">{{ Str::limit($reportDescription, 320) }}</p>
                    @endif
                </div>

                <form action="{{ route('admin.reports.reply', $reportId) }}" method="POST" id="rrmForm{{ $reportId }}">
                    @csrf

                    <div class="rrm-response-row">
                        <span class="rrm-response-label">YOUR RESPONSE <span class="req">*</span></span>
                        <div class="rrm-tpl-wrap" id="rrmTW{{ $reportId }}">
                            <button type="button" class="rrm-tpl-btn" id="rrmTB{{ $reportId }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
                                Use Template
                                <svg class="chev" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg>
                            </button>
                            <div class="rrm-tpl-drop" id="rrmTD{{ $reportId }}">
                                <div class="rrm-tpl-item" data-text="Laporan telah kami selesaikan. Konten yang dilaporkan telah dimoderasi. Terima kasih atas partisipasinya.">Completed Processing</div>
                                <div class="rrm-tpl-item" data-text="Terima kasih atas laporannya. Kami akan segera menindaklanjuti dan melakukan verifikasi.">Will Be Followed Up</div>
                                <div class="rrm-tpl-item" data-text="Laporan telah kami terima. Tim moderasi akan melakukan peninjauan dalam 1x24 jam.">On Review</div>
                                <div class="rrm-tpl-item" data-text="Setelah kami periksa, laporan ini tidak ditemukan bukti pelanggaran yang cukup. Mohon maaf.">Report Rejected</div>
                            </div>
                        </div>
                    </div>

                    <textarea id="rrmTA{{ $reportId }}" name="tanggapan_admin" class="rrm-textarea" required maxlength="250"
                        placeholder="Type your administrative response here..."></textarea>
                    <div class="rrm-counter" id="rrmCT{{ $reportId }}">0 / 250</div>

                    <p class="rrm-sec-title">Report Status</p>

                    <div class="rrm-status-wrap" id="rrmSW{{ $reportId }}">
                        <div class="rrm-status-trigger" id="rrmST{{ $reportId }}" role="button" tabindex="0">
                            <div class="rrm-status-disp" id="rrmSD{{ $reportId }}">
                                <span class="rrm-dot" style="background:#f59e0b;"></span>
                                <span>Pending Review</span>
                            </div>
                            <svg class="rrm-schev" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="rrm-status-drop" id="rrmSDD{{ $reportId }}">
                            <div class="rrm-sopt active" data-val="pending_review" data-color="#f59e0b" data-label="Pending Review"><span class="rrm-dot" style="background:#f59e0b;"></span> Pending Review</div>
                            <div class="rrm-sopt" data-val="in_progress" data-color="#3b82f6" data-label="In Progress"><span class="rrm-dot" style="background:#3b82f6;"></span> In Progress</div>
                            <div class="rrm-sopt" data-val="resolved" data-color="#22c55e" data-label="Resolved"><span class="rrm-dot" style="background:#22c55e;"></span> Resolved</div>
                            <div class="rrm-sopt" data-val="rejected" data-color="#CE2626" data-label="Rejected"><span class="rrm-dot" style="background:#CE2626;"></span> Rejected</div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="rrm-footer">
                <button type="button" class="rrm-btn-cancel" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="rrmForm{{ $reportId }}" class="rrm-btn-send">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="22" y1="2" x2="11" y2="13"/>
                        <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                    </svg>
                    Send Response
                </button>
            </div>

        </div>
    </div>
</div>

<script>
(function () {
    var id = '{{ $reportId }}';
    var ta  = document.getElementById('rrmTA' + id);
    var ct  = document.getElementById('rrmCT' + id);
    var tb  = document.getElementById('rrmTB' + id);
    var td  = document.getElementById('rrmTD' + id);
    var st  = document.getElementById('rrmST' + id);
    var sdd = document.getElementById('rrmSDD' + id);
    var sd  = document.getElementById('rrmSD' + id);

    function closeAll() {
        td?.classList.remove('show'); tb?.classList.remove('open');
        sdd?.classList.remove('show'); st?.classList.remove('open');
    }

    if (ta && ct) {
        ta.addEventListener('input', () => {
            const n = ta.value.length;
            ct.textContent = `${n} / 250`;
            ct.classList.toggle('warn', n >= 200 && n < 250);
            ct.classList.toggle('over', n >= 250);
        });
    }

    if (tb && td) {
        tb.addEventListener('click', e => {
            e.stopPropagation();
            const open = td.classList.contains('show');
            closeAll();
            if (!open) { td.classList.add('show'); tb.classList.add('open'); }
        });
        td.querySelectorAll('.rrm-tpl-item').forEach(item => {
            item.addEventListener('click', () => {
                if (ta) ta.value = item.dataset.text;
                closeAll();
            });
        });
    }

    if (st && sdd) {
        st.addEventListener('click', e => {
            e.stopPropagation();
            const open = sdd.classList.contains('show');
            closeAll();
            if (!open) { sdd.classList.add('show'); st.classList.add('open'); }
        });
        sdd.querySelectorAll('.rrm-sopt').forEach(opt => {
            opt.addEventListener('click', () => {
                const val = opt.dataset.val;
                const clr = opt.dataset.color;
                const lbl = opt.dataset.label;
                sd.innerHTML = `<span class="rrm-dot" style="background:${clr};"></span><span>${lbl}</span>`;
                sdd.querySelectorAll('.rrm-sopt').forEach(o => o.classList.remove('active'));
                opt.classList.add('active');
                sdd.classList.remove('show');
                st.classList.remove('open');
            });
        });
    }

    document.addEventListener('click', closeAll);
})();
</script>
</DOCUMENT>