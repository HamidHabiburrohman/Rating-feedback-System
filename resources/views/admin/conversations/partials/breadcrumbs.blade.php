@props(['conversation' => null])

<nav class="conv-breadcrumbs">
    <a href="{{ route('admin.dashboard') }}" class="conv-bc-link">
        <i class="ti ti-layout-dashboard"></i>
        <span>Dashboard</span>
    </a>
    <i class="ti ti-chevron-right conv-bc-sep"></i>
    <a href="{{ route('admin.conversations.index') }}" class="conv-bc-link">
        <i class="ti ti-messages"></i>
        <span>Conversations</span>
    </a>
    
    @if($conversation)
        <i class="ti ti-chevron-right conv-bc-sep"></i>
        <span class="conv-bc-current" title="{{ $conversation->subject }}">
            {{ Str::limit($conversation->subject, 40) }}
        </span>
    @endif
</nav>

@once
@push('styles')
<style>
.conv-breadcrumbs {
    display: flex;
    align-items: center;
    gap: 10px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.conv-bc-link {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #64748b;
    text-decoration: none;
    transition: color 0.2s ease;
}
.conv-bc-link:hover { color: #f8773c; }
.conv-bc-link i { font-size: 16px; stroke-width: 1.5; }
.conv-bc-sep { font-size: 14px; color: #cbd5e1; stroke-width: 1.5; }
.conv-bc-current {
    color: #0f172a;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 300px;
}
</style>
@endpush
@endonce