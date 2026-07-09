@extends('layouts.admin.app')

@section('title', 'Inbox Messages')

@section('admin-content')
<div class="inbox-container">
    <header class="page-header">
        <div>
            <h1 class="page-title">Inbox</h1>
            <p class="page-subtitle">Kelola semua percakapan dengan employee</p>
        </div>
        <div class="header-stats">
            <div class="stat-badge">
                <span class="stat-value">{{ $stats['total'] ?? 0 }}</span>
                <span class="stat-label">Total</span>
            </div>
            <div class="stat-badge unread">
                <span class="stat-value">{{ $stats['unread'] ?? 0 }}</span>
                <span class="stat-label">Belum Dibaca</span>
            </div>
        </div>
    </header>

    <div class="inbox-filters">
        <div class="search-box">
            <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari employee, unit, atau pesan...">
        </div>
        
        <div class="filter-group">
            <select id="statusFilter" class="filter-select">
                <option value="">Semua Status</option>
                <option value="assigned">Assigned</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
            </select>
            
            <select id="priorityFilter" class="filter-select">
                <option value="">Semua Prioritas</option>
                <option value="critical">Critical</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
        </div>
    </div>

    <div class="conversations-list" id="conversationsList">
        @forelse($conversations as $conversation)
            <a href="{{ route('admin.messages.show', $conversation->id) }}" class="conversation-card {{ $conversation->unread_count > 0 ? 'unread' : '' }}">
                <div class="conversation-avatar">
                    @if($conversation->employee && $conversation->employee->photo)
                        <img src="{{ asset('storage/' . $conversation->employee->photo) }}" alt="{{ $conversation->employee->name }}">
                    @else
                        <div class="avatar-placeholder">
                            {{ $conversation->employee ? strtoupper(substr($conversation->employee->name, 0, 2)) : '??' }}
                        </div>
                    @endif
                    
                    @if($conversation->unread_count > 0)
                        <span class="unread-badge">{{ $conversation->unread_count }}</span>
                    @endif
                </div>

                <div class="conversation-content">
                    <div class="conversation-header">
                        <div class="conversation-meta">
                            <h3 class="employee-name">{{ $conversation->employee->name ?? 'Unknown Employee' }}</h3>
                            <span class="separator">•</span>
                            <span class="unit-name">{{ $conversation->unit->name ?? 'Unknown Unit' }}</span>
                        </div>
                        <span class="conversation-time">
                            {{ $conversation->latest_message ? $conversation->latest_message->created_at->diffForHumans() : '-' }}
                        </span>
                    </div>

                    @if($conversation->report)
                        <div class="report-info">
                            <span class="report-badge priority-{{ $conversation->report->priority }}">
                                {{ strtoupper($conversation->report->priority) }}
                            </span>
                            <span class="report-title">{{ $conversation->report->title }}</span>
                        </div>
                    @endif

                    <div class="conversation-preview">
                        @if($conversation->latest_message)
                            <span class="message-sender">
                                @if($conversation->latest_message->sender_type === 'App\Models\Authentication\Admin')
                                    Anda:
                                @else
                                    {{ $conversation->latest_message->sender->name ?? 'Employee' }}:
                                @endif
                            </span>
                            <span class="message-text">{{ Str::limit($conversation->latest_message->message, 100) }}</span>
                        @else
                            <span class="message-text text-muted">Belum ada pesan</span>
                        @endif
                    </div>

                    <div class="conversation-footer">
                        <span class="status-badge status-{{ $conversation->status }}">
                            {{ ucfirst(str_replace('_', ' ', $conversation->status)) }}
                        </span>
                        @if($conversation->latest_message && $conversation->latest_message->attachment)
                            <span class="attachment-indicator">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m21.44 11.05-9.19 9.19a6 6 0 0 1-8.49-8.49l8.57-8.57A4 4 0 1 1 17.98 8.83l-8.58 8.57a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
                                </svg>
                                Attachment
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                    </svg>
                </div>
                <h3 class="empty-title">Belum ada percakapan</h3>
                <p class="empty-description">Percakapan akan muncul di sini ketika employee mengirim pesan terkait report</p>
            </div>
        @endforelse
    </div>

    @if($conversations->hasPages())
        <div class="pagination-wrapper">
            {{ $conversations->links('vendor.pagination.simple-tailwind') }}
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.inbox-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: -0.02em;
    margin: 0 0 0.25rem 0;
}

.page-subtitle {
    color: var(--color-text-secondary);
    font-size: 0.875rem;
    font-weight: 400;
    margin: 0;
}

.header-stats {
    display: flex;
    gap: 0.75rem;
}

.stat-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.75rem 1.25rem;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 12px;
    min-width: 80px;
}

.stat-badge.unread {
    background: var(--color-primary-50);
    border-color: rgba(248, 119, 60, 0.2);
}

.stat-value {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--color-text-primary);
    line-height: 1;
}

.stat-badge.unread .stat-value {
    color: var(--color-primary);
}

.stat-label {
    font-size: 0.6875rem;
    color: var(--color-text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-top: 0.25rem;
}

.inbox-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
}

.search-box {
    flex: 1;
    min-width: 280px;
    position: relative;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--color-text-secondary);
    pointer-events: none;
}

.search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.75rem;
    border: 1px solid var(--color-border);
    border-radius: 12px;
    font-size: 0.875rem;
    background: var(--color-surface);
    transition: var(--transition);
}

.search-input:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1);
}

.filter-group {
    display: flex;
    gap: 0.75rem;
}

.filter-select {
    padding: 0.75rem 1rem;
    border: 1px solid var(--color-border);
    border-radius: 12px;
    font-size: 0.875rem;
    background: var(--color-surface);
    cursor: pointer;
    transition: var(--transition);
    min-width: 160px;
}

.filter-select:focus {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(248, 119, 60, 0.1);
}

.conversations-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.conversation-card {
    display: flex;
    gap: 1rem;
    padding: 1.25rem;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 16px;
    text-decoration: none;
    color: inherit;
    transition: var(--transition);
}

.conversation-card:hover {
    border-color: var(--color-primary);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transform: translateY(-2px);
}

.conversation-card.unread {
    background: var(--color-primary-50);
    border-color: rgba(248, 119, 60, 0.3);
}

.conversation-avatar {
    position: relative;
    flex-shrink: 0;
}

.conversation-avatar img,
.avatar-placeholder {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.avatar-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-primary);
    color: white;
    font-weight: 600;
    font-size: 0.875rem;
}

.unread-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    background: var(--color-primary);
    color: white;
    border-radius: 999px;
    font-size: 0.6875rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid white;
}

.conversation-content {
    flex: 1;
    min-width: 0;
}

.conversation-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 0.5rem;
    gap: 1rem;
}

.conversation-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.employee-name {
    font-size: 0.9375rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0;
}

.separator {
    color: var(--color-text-secondary);
}

.unit-name {
    font-size: 0.8125rem;
    color: var(--color-text-secondary);
}

.conversation-time {
    font-size: 0.75rem;
    color: var(--color-text-secondary);
    white-space: nowrap;
}

.report-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.report-badge {
    padding: 0.125rem 0.5rem;
    border-radius: 6px;
    font-size: 0.625rem;
    font-weight: 700;
    letter-spacing: 0.05em;
}

.report-badge.priority-critical {
    background: #fef2f2;
    color: #dc2626;
}

.report-badge.priority-high {
    background: #fef3c7;
    color: #d97706;
}

.report-badge.priority-medium {
    background: #dbeafe;
    color: #2563eb;
}

.report-badge.priority-low {
    background: #f3f4f6;
    color: #6b7280;
}

.report-title {
    font-size: 0.8125rem;
    color: var(--color-text-secondary);
}

.conversation-preview {
    font-size: 0.875rem;
    color: var(--color-text-primary);
    margin-bottom: 0.5rem;
    line-height: 1.5;
}

.message-sender {
    font-weight: 600;
    color: var(--color-text-primary);
}

.message-text {
    color: var(--color-text-secondary);
}

.text-muted {
    color: var(--color-text-secondary);
    font-style: italic;
}

.conversation-footer {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.status-badge {
    padding: 0.25rem 0.625rem;
    border-radius: 6px;
    font-size: 0.6875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-badge.status-assigned {
    background: #dbeafe;
    color: #2563eb;
}

.status-badge.status-in_progress {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.status-resolved {
    background: #d1fae5;
    color: #059669;
}

.attachment-indicator {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.75rem;
    color: var(--color-text-secondary);
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 4rem 2rem;
    background: var(--color-surface);
    border: 1px solid var(--color-border);
    border-radius: 16px;
    text-align: center;
}

.empty-icon {
    width: 96px;
    height: 96px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--color-bg);
    border-radius: 50%;
    color: var(--color-text-secondary);
    margin-bottom: 1.5rem;
}

.empty-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--color-text-primary);
    margin: 0 0 0.5rem 0;
}

.empty-description {
    font-size: 0.875rem;
    color: var(--color-text-secondary);
    max-width: 400px;
    margin: 0;
}

.pagination-wrapper {
    margin-top: 2rem;
    display: flex;
    justify-content: center;
}

@media (max-width: 768px) {
    .inbox-container {
        padding: 1rem;
    }

    .page-header {
        flex-direction: column;
        gap: 1rem;
    }

    .header-stats {
        width: 100%;
        justify-content: space-between;
    }

    .inbox-filters {
        flex-direction: column;
    }

    .search-box {
        min-width: 100%;
    }

    .filter-group {
        width: 100%;
    }

    .filter-select {
        flex: 1;
    }

    .conversation-card {
        padding: 1rem;
    }

    .conversation-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .conversation-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }

    .separator {
        display: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const priorityFilter = document.getElementById('priorityFilter');
    const conversationsList = document.getElementById('conversationsList');

    let searchTimeout = null;

    function filterConversations() {
        const search = searchInput.value.toLowerCase();
        const status = statusFilter.value;
        const priority = priorityFilter.value;

        const cards = conversationsList.querySelectorAll('.conversation-card');
        
        cards.forEach(card => {
            const text = card.textContent.toLowerCase();
            const cardStatus = card.querySelector('.status-badge')?.textContent.toLowerCase().replace(' ', '_') || '';
            const cardPriority = card.querySelector('.report-badge')?.textContent.toLowerCase() || '';

            const matchesSearch = !search || text.includes(search);
            const matchesStatus = !status || cardStatus.includes(status);
            const matchesPriority = !priority || cardPriority.includes(priority);

            if (matchesSearch && matchesStatus && matchesPriority) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterConversations, 300);
    });

    statusFilter.addEventListener('change', filterConversations);
    priorityFilter.addEventListener('change', filterConversations);
});
</script>
@endpush