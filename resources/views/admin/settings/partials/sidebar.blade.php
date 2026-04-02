@php
    $basicSettings = [
        'general' => 'General',
        'display' => 'Display',
        'visitor' => 'Visitor',
        'notification' => 'Notification',
    ];

    $uiSettings = [
        'rating' => 'Rating',
        'unit' => 'Unit',
        'report' => 'Report',
        'performance' => 'Performance',
    ];

    $backendSettings = [
        'audit' => 'Audit',
        'security' => 'Security',
        'api' => 'API',
    ];

    $groupIcons = [
        
    ];
@endphp

<div class="settings-menu-panel">
    <div class="settings-panel-header">
        <h3 class="settings-panel-title">Settings</h3>
        <p class="settings-panel-subtitle">System configuration</p>
    </div>
    
    <nav class="settings-panel-nav">
        <div class="settings-nav-section">
            <div class="settings-nav-section-title">
                <span>Basic</span>
            </div>
            @foreach($basicSettings as $groupKey => $groupLabel)
                @if(array_key_exists($groupKey, $groups))
                    @php
                        $isActive = $currentGroup === $groupKey;
                        $route = route('admin.settings.group.show', $groupKey);
                    @endphp
                    <a class="settings-nav-item {{ $isActive ? 'settings-nav-active' : '' }}"
                       href="{{ $route }}"
                       aria-current="{{ $isActive ? 'page' : 'false' }}">
                        @if(isset($groupIcons[$groupKey]))
                        <svg class="settings-nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="{{ $groupIcons[$groupKey] }}"/>
                        </svg>
                        @endif
                        <span class="settings-nav-label">{{ $groupLabel }}</span>
                        @if($isActive)
                        <svg class="settings-nav-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>

        <div class="settings-nav-divider"></div>

        <div class="settings-nav-section">
            <div class="settings-nav-section-title">
                <span>UI & Experience</span>
            </div>
            @foreach($uiSettings as $groupKey => $groupLabel)
                @if(array_key_exists($groupKey, $groups))
                    @php
                        $isActive = $currentGroup === $groupKey;
                        $route = route('admin.settings.group.show', $groupKey);
                    @endphp
                    <a class="settings-nav-item {{ $isActive ? 'settings-nav-active' : '' }}"
                       href="{{ $route }}"
                       aria-current="{{ $isActive ? 'page' : 'false' }}">
                        @if(isset($groupIcons[$groupKey]))
                        <svg class="settings-nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="{{ $groupIcons[$groupKey] }}"/>
                        </svg>
                        @endif
                        <span class="settings-nav-label">{{ $groupLabel }}</span>
                        @if($isActive)
                        <svg class="settings-nav-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>

        <div class="settings-nav-divider"></div>

        <div class="settings-nav-section">
            <div class="settings-nav-section-title">
                <span>Backend & System</span>
            </div>
            @foreach($backendSettings as $groupKey => $groupLabel)
                @if(array_key_exists($groupKey, $groups))
                    @php
                        $isActive = $currentGroup === $groupKey;
                        $route = route('admin.settings.group.show', $groupKey);
                    @endphp
                    <a class="settings-nav-item {{ $isActive ? 'settings-nav-active' : '' }}"
                       href="{{ $route }}"
                       aria-current="{{ $isActive ? 'page' : 'false' }}">
                        @if(isset($groupIcons[$groupKey]))
                        <svg class="settings-nav-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="{{ $groupIcons[$groupKey] }}"/>
                        </svg>
                        @endif
                        <span class="settings-nav-label">{{ $groupLabel }}</span>
                        @if($isActive)
                        <svg class="settings-nav-arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </nav>

    <div class="settings-panel-footer">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <path d="M12 8v8M8 12h8"/>
        </svg>
        <span>System v1.0.0</span>
    </div>
</div>

@push('admin-scripts')
<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.href;
        const navItems = document.querySelectorAll('.settings-nav-item');
        
        navItems.forEach(item => {
            if (item.href === currentUrl) {
                navItems.forEach(i => i.classList.remove('settings-nav-active'));
                item.classList.add('settings-nav-active');
            }
        });
    });
})();
</script>
@endpush

@push('styles')
<style>
.settings-nav-section {
    margin-bottom: 0.5rem;
}

.settings-nav-section-title {
    padding: 0.5rem 1rem 0.25rem;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #94a3b8;
}

.settings-nav-divider {
    height: 1px;
    background: linear-gradient(to right, transparent, #e2e8f0, transparent);
    margin: 1rem 0.5rem;
}

.settings-panel-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid #edf2f7;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: #64748b;
}
</style>
@endpush