@props(['conversation'])

<div class="conv-actions-bar">
    @if($conversation->status === 'active')
        <form action="{{ route('admin.conversations.archive', $conversation) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="conv-action-btn" title="Archive">
                <i class="ti ti-archive"></i> <span class="d-none d-md-inline">Archive</span>
            </button>
        </form>
    @elseif(in_array($conversation->status, ['archived', 'closed']))
        <form action="{{ route('admin.conversations.reopen', $conversation) }}" method="POST" class="d-inline">
            @csrf @method('PATCH')
            <button type="submit" class="conv-action-btn conv-action-btn--success" title="Reopen">
                <i class="ti ti-arrow-back-up"></i> <span class="d-none d-md-inline">Reopen</span>
            </button>
        </form>
    @endif

    <div class="conv-dropdown" x-data="{ open: false }">
        <button @click="open = !open" class="conv-action-btn" title="More Actions">
            <i class="ti ti-dots-vertical"></i>
        </button>
        <div x-show="open" @click.away="open = false" x-transition class="conv-dropdown-menu conv-dropdown-menu--right">
            <button class="conv-dropdown-item"><i class="ti ti-file-export"></i> Export Chat</button>
            @if($conversation->status === 'active')
                <form action="{{ route('admin.conversations.close', $conversation) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="conv-dropdown-item"><i class="ti ti-lock"></i> Close Conversation</button>
                </form>
            @endif
            <div class="conv-dropdown-divider"></div>
            <form action="{{ route('admin.conversations.destroy', $conversation) }}" method="POST" onsubmit="return confirm('Permanently delete this conversation?')">
                @csrf @method('DELETE')
                <button type="submit" class="conv-dropdown-item conv-dropdown-danger"><i class="ti ti-trash"></i> Delete</button>
            </form>
        </div>
    </div>
</div>

@once
@push('styles')
<style>
.conv-actions-bar { display: flex; align-items: center; gap: 8px; font-family: 'Plus Jakarta Sans', sans-serif; }
.conv-action-btn {
    display: flex; align-items: center; gap: 6px; padding: 8px 14px;
    background: #ffffff; border: 1px solid rgba(15, 23, 42, 0.06); border-radius: 10px;
    font-size: 13px; font-weight: 600; color: #475569; cursor: pointer; transition: all 0.2s ease;
}
.conv-action-btn:hover { border-color: #cbd5e1; background: #f8fafc; color: #0f172a; }
.conv-action-btn i { font-size: 16px; stroke-width: 1.5; }
.conv-action-btn--success:hover { color: #10b981; border-color: #10b981; background: #f0fdf4; }
.conv-dropdown { position: relative; }
.conv-dropdown-menu--right { right: 0; left: auto; min-width: 200px; }
.conv-dropdown-divider { height: 1px; background: rgba(15, 23, 42, 0.06); margin: 4px 0; }
.conv-dropdown-item {
    width: 100%; display: flex; align-items: center; gap: 10px; padding: 8px 12px;
    background: transparent; border: none; border-radius: 8px; font-size: 13px; font-weight: 500;
    color: #475569; cursor: pointer; transition: all 0.15s ease; text-align: left;
}
.conv-dropdown-item:hover { background: #f8fafc; color: #0f172a; }
.conv-dropdown-item i { font-size: 16px; stroke-width: 1.5; color: #94a3b8; }
.conv-dropdown-item:hover i { color: #0f172a; }
.conv-dropdown-danger { color: #dc2626 !important; }
.conv-dropdown-danger:hover { background: #fef2f2 !important; }
.conv-dropdown-danger i { color: #f87171 !important; }
.conv-dropdown-danger:hover i { color: #dc2626 !important; }
</style>
@endpush
@endonce