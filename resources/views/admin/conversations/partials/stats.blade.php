@props(['statistics' => []])

<div class="conv-stats-grid">
    <div class="conv-stat-card">
        <div class="conv-stat-icon conv-stat-icon--blue"><i class="ti ti-messages"></i></div>
        <div class="conv-stat-info">
            <span class="conv-stat-value">{{ number_format($statistics['total_messages'] ?? 0) }}</span>
            <span class="conv-stat-label">Messages</span>
        </div>
    </div>

    <div class="conv-stat-card">
        <div class="conv-stat-icon conv-stat-icon--green"><i class="ti ti-users"></i></div>
        <div class="conv-stat-info">
            <span class="conv-stat-value">{{ number_format($statistics['total_participants'] ?? 0) }}</span>
            <span class="conv-stat-label">Participants</span>
        </div>
    </div>

    <div class="conv-stat-card">
        <div class="conv-stat-icon conv-stat-icon--orange"><i class="ti ti-mail"></i></div>
        <div class="conv-stat-info">
            <span class="conv-stat-value">{{ number_format($statistics['unread_count'] ?? 0) }}</span>
            <span class="conv-stat-label">Unread</span>
        </div>
    </div>

    <div class="conv-stat-card">
        <div class="conv-stat-icon conv-stat-icon--purple"><i class="ti ti-paperclip"></i></div>
        <div class="conv-stat-info">
            <span class="conv-stat-value">{{ number_format($statistics['attachments'] ?? 0) }}</span>
            <span class="conv-stat-label">Files Shared</span>
        </div>
    </div>
</div>

@once
@push('styles')
<style>
.conv-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.conv-stat-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 16px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 16px;
    transition: all 0.2s ease;
}
.conv-stat-card:hover { border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
.conv-stat-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.conv-stat-icon i { font-size: 22px; stroke-width: 1.5; }
.conv-stat-icon--blue { background: #eff6ff; color: #3b82f6; }
.conv-stat-icon--green { background: #f0fdf4; color: #10b981; }
.conv-stat-icon--orange { background: #fff7ed; color: #f8773c; }
.conv-stat-icon--purple { background: #faf5ff; color: #a855f7; }
.conv-stat-info { display: flex; flex-direction: column; min-width: 0; }
.conv-stat-value { font-size: 22px; font-weight: 700; color: #0f172a; line-height: 1.2; letter-spacing: -0.02em; }
.conv-stat-label { font-size: 12px; font-weight: 500; color: #64748b; margin-top: 2px; }
</style>
@endpush
@endonce