<div class="conv-filters-bar" x-data="{ 
    status: '{{ request('status', '') }}',
    sort: '{{ request('sort', 'latest') }}',
    applyFilters() {
        const params = new URLSearchParams(window.location.search);
        if(this.status) params.set('status', this.status); else params.delete('status');
        if(this.sort) params.set('sort', this.sort); else params.delete('sort');
        params.delete('page');
        window.location.search = params.toString();
    }
}">
    <div class="conv-dropdown" x-data="{ open: false }">
        <button @click="open = !open" class="conv-filter-btn">
            <i class="ti ti-filter"></i>
            <span x-text="status ? status.charAt(0).toUpperCase() + status.slice(1) : 'All Status'"></span>
            <i class="ti ti-chevron-down" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" @click.away="open = false" x-transition class="conv-dropdown-menu">
            <button @click="status = ''; open = false; applyFilters()" class="conv-dropdown-item">All Status</button>
            <button @click="status = 'active'; open = false; applyFilters()" class="conv-dropdown-item">Active</button>
            <button @click="status = 'archived'; open = false; applyFilters()" class="conv-dropdown-item">Archived</button>
            <button @click="status = 'closed'; open = false; applyFilters()" class="conv-dropdown-item">Closed</button>
        </div>
    </div>

    <div class="conv-dropdown" x-data="{ open: false }">
        <button @click="open = !open" class="conv-filter-btn">
            <i class="ti ti-arrows-sort"></i>
            <span x-text="sort === 'latest' ? 'Latest' : (sort === 'oldest' ? 'Oldest' : 'Unread')"></span>
            <i class="ti ti-chevron-down" :class="open ? 'rotate-180' : ''"></i>
        </button>
        <div x-show="open" @click.away="open = false" x-transition class="conv-dropdown-menu">
            <button @click="sort = 'latest'; open = false; applyFilters()" class="conv-dropdown-item">Latest First</button>
            <button @click="sort = 'oldest'; open = false; applyFilters()" class="conv-dropdown-item">Oldest First</button>
            <button @click="sort = 'unread'; open = false; applyFilters()" class="conv-dropdown-item">Most Unread</button>
        </div>
    </div>
    
    <button class="conv-filter-btn" onclick="window.location.reload()" title="Refresh">
        <i class="ti ti-refresh"></i>
    </button>
</div>

@once
@push('styles')
<style>
.conv-filters-bar {
    display: flex; align-items: center; gap: 10px; margin-bottom: 24px; flex-wrap: wrap;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.conv-dropdown { position: relative; }
.conv-filter-btn {
    display: flex; align-items: center; gap: 8px; padding: 9px 14px;
    background: #ffffff; border: 1px solid rgba(15, 23, 42, 0.06); border-radius: 10px;
    font-size: 13px; font-weight: 600; color: #334155; cursor: pointer; transition: all 0.2s ease;
}
.conv-filter-btn:hover { border-color: #cbd5e1; background: #f8fafc; }
.conv-filter-btn i { font-size: 16px; color: #64748b; stroke-width: 1.5; }
.conv-filter-btn .ti-chevron-down { transition: transform 0.2s ease; font-size: 14px; }
.rotate-180 { transform: rotate(180deg); }
.conv-dropdown-menu {
    position: absolute; top: calc(100% + 6px); left: 0; min-width: 180px;
    background: #ffffff; border: 1px solid rgba(15, 23, 42, 0.06); border-radius: 12px;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08); padding: 6px; z-index: 50;
}
.conv-dropdown-item {
    width: 100%; text-align: left; padding: 8px 12px; background: transparent; border: none;
    border-radius: 8px; font-size: 13px; font-weight: 500; color: #475569; cursor: pointer; transition: all 0.15s ease;
}
.conv-dropdown-item:hover { background: #fff5f0; color: #f8773c; }
</style>
@endpush
@endonce