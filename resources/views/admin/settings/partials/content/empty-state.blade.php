@php
    $title = $title ?? 'No Settings Available';
    $message = $message ?? 'There are no settings configured for this section.';
    $action = $action ?? null;
@endphp

<div class="empty-state">
    <div class="empty-state-icon mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" 
             stroke="#cbd5e1" stroke-width="1.5">
            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"/>
            <path d="M12 8v4M12 16h.01"/>
        </svg>
    </div>
    
    <h4 class="empty-state-title mb-2">{{ $title }}</h4>
    <p class="empty-state-message mb-4">{{ $message }}</p>
    
    @if($action)
        <a href="{{ $action['url'] ?? '#' }}" class="btn btn-primary rounded-pill px-4">
            @if(isset($action['icon']))
                <i class="{{ $action['icon'] }} me-2"></i>
            @endif
            {{ $action['label'] ?? 'Take Action' }}
        </a>
    @endif
</div>

<style>
.empty-state {
    text-align: center;
    padding: 3rem 2rem;
    background: #f8fafc;
    border-radius: 0.75rem;
    border: 2px dashed #e2e8f0;
}

.empty-state-icon {
    color: #cbd5e1;
}

.empty-state-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.5rem;
}

.empty-state-message {
    color: #64748b;
    margin-bottom: 1.5rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}
</style>