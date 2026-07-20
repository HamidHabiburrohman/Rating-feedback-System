<aside class="conv-spa-sidebar" id="convSidebar">
    <div class="conv-spa-sidebar-header">
        <h2 class="conv-spa-sidebar-title">Conversations</h2>
        <button class="conv-spa-icon-btn" id="convSidebarRefresh" title="Refresh">
            <i class="ti ti-refresh"></i>
        </button>
    </div>

    <div class="conv-spa-sidebar-search">
        <i class="ti ti-search conv-spa-search-icon"></i>
        <input type="text" id="convSearchInput" class="conv-spa-search-input" placeholder="Search conversations...">
        <button id="convSearchClear" class="conv-spa-search-clear" style="display: none;">
            <i class="ti ti-x"></i>
        </button>
    </div>

    <div class="conv-spa-sidebar-filters">
        <button class="conv-spa-filter-pill is-active" data-filter="all">All</button>
        <button class="conv-spa-filter-pill" data-filter="unread">Unread</button>
        <button class="conv-spa-filter-pill" data-filter="archived">Archived</button>
    </div>

    <div class="conv-sidebar-list" id="convList">
        @forelse($conversations as $conversation)
            <x-admin.conversations.conversation-card :conversation="$conversation" :is-active="isset($activeConversation) && $activeConversation->id === $conversation->id" />
        @empty
            <div class="conv-empty-state">
                <div class="conv-empty-icon">
                    <i class="ti ti-message-off"></i>
                </div>
                <h3>No conversations found</h3>
                <p>When you have active discussions, they will appear here.</p>
            </div>
        @endforelse

        <!-- JS Controlled Empty State -->
        <div class="conv-empty-state" id="convSidebarEmptyFilter" style="display: none;">
            <div class="conv-empty-icon">
                <i class="ti ti-message-off"></i>
            </div>
            <h3>No conversations found</h3>
            <p>When you have active discussions, they will appear here.</p>
        </div>
    </div>
</aside>
<div class="conv-spa-overlay" id="convSidebarOverlay"></div>

@push('styles')
<style>
.conv-spa-sidebar {
    width: 380px;
    min-width: 380px;
    background: #ffffff;
    border-right: 1px solid #ececec;
    display: flex;
    flex-direction: column;
    height: 100%;
    z-index: 40;
    padding: 5px 5px;
    scroll-behavior: smooth;
    -ms-overflow-style: none; /* Sembunyikan untuk IE/Edge */
    scrollbar-width: none;  /* Sembunyikan untuk Firefox */
}

/* Sembunyikan scrollbar untuk Chrome, Safari, dan Opera */
.conv-spa-sidebar::-webkit-scrollbar {
    display: none;
}

.conv-spa-sidebar-header {
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid #f1f5f9;
}

.conv-spa-sidebar-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
}

.conv-spa-icon-btn {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
}

.conv-spa-icon-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.conv-spa-sidebar-search {
    padding: 16px 24px;
    position: relative;
}

.conv-spa-search-icon {
    position: absolute;
    left: 36px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    pointer-events: none;
}

.conv-spa-search-input {
    width: 100%;
    padding: 10px 36px 10px 36px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    font-size: 13px;
    background: #f8fafc;
    transition: all 0.2s;
}

.conv-spa-search-input:focus {
    outline: none;
    border-color: #f8773c;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(248, 119, 60, 0.1);
}

.conv-spa-search-clear {
    position: absolute;
    right: 36px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #94a3b8;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
}

.conv-spa-sidebar-filters {
    padding: 0 24px 16px;
    display: flex;
    gap: 8px;
}

.conv-spa-filter-pill {
    padding: 6px 12px;
    border-radius: 9999px;
    border: 1px solid #e2e8f0;
    background: #ffffff;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s;
}

.conv-spa-filter-pill:hover {
    background: #f8fafc;
}

.conv-spa-filter-pill.is-active {
    background: #fff5f0;
    border-color: #f8773c;
    color: #f8773c;
}

/* PERUBAHAN UTAMA DI SINI */
.conv-sidebar-list {
    flex: 1;
    overflow-y: auto;
    padding: 0 12px 24px;
    -ms-overflow-style: none; /* Sembunyikan untuk IE/Edge */
    scrollbar-width: none;  /* Sembunyikan untuk Firefox */
}

/* Sembunyikan scrollbar list untuk Chrome, Safari, dan Opera */
.conv-sidebar-list::-webkit-scrollbar {
    display: none;
}

.conv-sidebar-card {
    display: flex;
    gap: 12px;
    padding: 12px;
    border-radius: 12px;
    text-decoration: none;
    color: inherit;
    transition: all 0.15s;
    margin-bottom: 4px;
    border: 1px solid transparent;
}

.conv-sidebar-card:hover {
    background: #f8fafc;
}

.conv-sidebar-card.is-active {
    background: #fff5f0;
    border-color: rgba(248, 119, 60, 0.2);
}

.conv-sidebar-card-avatar {
    flex-shrink: 0;
}

.conv-sidebar-card-content {
    flex: 1;
    min-width: 0;
}

.conv-sidebar-card-top {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 4px;
}

.conv-sidebar-card-title {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}

.conv-sidebar-card-time {
    font-size: 11px;
    color: #94a3b8;
    font-weight: 500;
    flex-shrink: 0;
    margin-left: 8px;
}

.conv-sidebar-card-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.conv-sidebar-card-preview {
    font-size: 13px;
    color: #64748b;
    margin: 0;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}

.conv-sidebar-card-preview strong {
    color: #475569;
    font-weight: 600;
}

.conv-unread-badge {
    background: #f8773c;
    color: #ffffff;
    font-size: 10px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 9999px;
    min-width: 18px;
    text-align: center;
    flex-shrink: 0;
    margin-left: 8px;
}

/* Empty State Styles */
.conv-empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.conv-empty-state .conv-empty-icon {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
    color: #cbd5e1;
}

.conv-empty-state h3 {
    font-size: 15px;
    font-weight: 600;
    color: #475569;
    margin: 0 0 4px 0;
}

.conv-empty-state p {
    margin: 0;
    font-size: 13px;
}

.conv-sidebar-empty {
    text-align: center;
    padding: 40px 20px;
    color: #94a3b8;
}

.conv-sidebar-empty i {
    font-size: 32px;
    margin-bottom: 12px;
    display: block;
}

.conv-sidebar-empty p {
    margin: 0;
    font-size: 13px;
}

.conv-spa-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.4);
    z-index: 30;
}

@media (max-width: 1024px) {
    .conv-spa-sidebar {
        position: fixed;
        left: -100%;
        top: 0;
        bottom: 0;
        transition: left 0.3s ease;
        box-shadow: none;
    }
    
    .conv-spa-sidebar.is-open {
        left: 0;
        box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
    }
    
    .conv-spa-overlay.is-visible {
        display: block;
    }
}
</style>
@endpush