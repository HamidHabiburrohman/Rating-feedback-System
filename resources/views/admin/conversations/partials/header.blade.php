<header class="conv-header">
    <div class="conv-header-left">
        <h1 class="conv-title">{{ $conversation->subject ?? 'Untitled Conversation' }}</h1>
        <div class="conv-meta">
            <span>{{ $conversation->participants->count() }} Participants</span>
            <span class="conv-meta-dot"></span>
            <x-admin.conversations.conversation-status :status="$conversation->status" size="sm" />
        </div>
    </div>

    <div class="conv-header-center">
        <x-admin.conversations.participant-stack 
            :participants="$conversation->participants->pluck('participant')" 
            :limit="5" 
            size="sm" 
        />
    </div>

    <div class="conv-header-right">
        <button class="conv-header-btn" data-action="search" title="Search">
            <i class="ti ti-search"></i>
        </button>
        <button class="conv-header-btn" data-action="attachments" title="Shared Files">
            <i class="ti ti-paperclip"></i>
        </button>
        <button class="conv-header-btn" data-action="participants" title="Participants">
            <i class="ti ti-users"></i>
        </button>
        
        <div class="conv-dropdown" x-data="{ open: false }">
            <button class="conv-header-btn" @click="open = !open" data-action="more" title="More options">
                <i class="ti ti-dots-vertical"></i>
            </button>
            <div class="conv-dropdown-menu" x-show="open" x-transition @click.away="open = false">
                <button class="conv-dropdown-item" data-action="archive">
                    <i class="ti ti-archive"></i> Archive Conversation
                </button>
                <button class="conv-dropdown-item" data-action="close">
                    <i class="ti ti-lock"></i> Close Conversation
                </button>
                <button class="conv-dropdown-item" data-action="reopen">
                    <i class="ti ti-arrow-back-up"></i> Reopen Conversation
                </button>
                <div class="conv-dropdown-divider"></div>
                <button class="conv-dropdown-item conv-dropdown-danger" data-action="delete">
                    <i class="ti ti-trash"></i> Delete Conversation
                </button>
            </div>
        </div>
    </div>
</header>

<style>
.conv-header {
    position: relative;
    height: 72px;
    background: #ffffff;
    border-bottom: 1px solid rgba(15, 23, 42, 0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    z-index: 20;
    font-family: 'Plus Jakarta Sans', sans-serif;
    gap: 24px;
}

.conv-header-left {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.conv-title {
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 0 4px 0;
    letter-spacing: -0.01em;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.2;
}

.conv-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
    line-height: 1;
}

.conv-meta-dot {
    width: 3px;
    height: 3px;
    border-radius: 50%;
    background: #cbd5e1;
}

.conv-header-center {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.conv-header-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-shrink: 0;
}

.conv-header-btn {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    color: #64748b;
    cursor: pointer;
    transition: all 0.2s ease;
}

.conv-header-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
}

.conv-header-btn:active,
.conv-header-btn.is-active {
    background: #fff5f0;
    color: #f8773c;
}

.conv-header-btn i {
    font-size: 20px;
    stroke-width: 1.5;
}

.conv-dropdown {
    position: relative;
}

.conv-dropdown-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    min-width: 220px;
    background: #ffffff;
    border: 1px solid rgba(15, 23, 42, 0.06);
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
    padding: 6px;
    z-index: 50;
}

.conv-dropdown-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    background: transparent;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: left;
    font-family: inherit;
}

.conv-dropdown-item:hover {
    background: #f8fafc;
    color: #0f172a;
}

.conv-dropdown-item i {
    font-size: 18px;
    stroke-width: 1.5;
    color: #94a3b8;
}

.conv-dropdown-item:hover i {
    color: #0f172a;
}

.conv-dropdown-danger {
    color: #dc2626;
}

.conv-dropdown-danger:hover {
    background: #fef2f2;
    color: #dc2626;
}

.conv-dropdown-danger i {
    color: #f87171;
}

.conv-dropdown-danger:hover i {
    color: #dc2626;
}

.conv-dropdown-divider {
    height: 1px;
    background: rgba(15, 23, 42, 0.06);
    margin: 4px 0;
}

@media (max-width: 1024px) {
    .conv-header-center {
        display: none;
    }
}
</style>