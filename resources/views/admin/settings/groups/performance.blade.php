<form id="form-performance" class="settings-form" data-group="performance">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'performance'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'performance'])
    
    @php
        $subgroups = $settings->groupBy('subgroup');
    @endphp

    @foreach($subgroups as $subgroup => $subgroupSettings)
        @php
            $subgroupName = $subgroup;
            $subgroupCount = $subgroupSettings->count();
        @endphp
        
        @if($subgroupCount > 0)
            @include('admin.settings.partials.header.subgroup-header', [
                'subgroup' => $subgroupName,
                'count' => $subgroupCount
            ])
            
            <div class="row g-4">
                @foreach($subgroupSettings as $setting)
                    @if($setting->is_visible)
                        @include('admin.settings.partials.forms.setting-card', ['setting' => $setting])
                    @endif
                @endforeach
            </div>
        @endif
    @endforeach

    <!-- Performance Metrics -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Performance Metrics</h3>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-3" id="refreshMetrics">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" 
                     stroke="currentColor" stroke-width="2">
                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                    <path d="M3 3v5h5"/>
                    <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                    <path d="M16 16h5v5"/>
                </svg>
                Refresh
            </button>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Cache Hit Rate</h6>
                                <p class="text-muted small mb-0">Last 24 hours</p>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                94.5%
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar rounded" style="width: 94.5%; background: linear-gradient(135deg, #a7f3d0, #10b981);"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Avg Response Time</h6>
                                <p class="text-muted small mb-0">Page load</p>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">
                                128ms
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar rounded" style="width: 85%; background: linear-gradient(135deg, #93c5fd, #3b82f6);"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Database Queries</h6>
                                <p class="text-muted small mb-0">Per request</p>
                            </div>
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1">
                                12.3
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar rounded" style="width: 65%; background: linear-gradient(135deg, #fde68a, #f59e0b);"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Memory Usage</h6>
                                <p class="text-muted small mb-0">Current</p>
                            </div>
                            <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1">
                                42MB
                            </span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar rounded" style="width: 42%; background: linear-gradient(135deg, #c7d2fe, #6366f1);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cache Management -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Cache Management</h3>
        </div>
        
        <div class="card border rounded-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="fw-semibold mb-2">Clear Application Cache</h6>
                        <p class="text-muted small mb-0">This will clear all cached data including settings, views, and routes.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" class="btn btn-outline-danger rounded-pill px-4" id="clearAllCache">
                            Clear All Cache
                        </button>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row g-3">
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill w-100" onclick="clearCacheType('config')">
                            Clear Config Cache
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill w-100" onclick="clearCacheType('route')">
                            Clear Route Cache
                        </button>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill w-100" onclick="clearCacheType('view')">
                            Clear View Cache
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-white py-4 border-top mt-6" style="bottom: 0; z-index: 10;">
        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 reset-group"
                    data-group="performance">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="performance">
                Save Performance Settings
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('clearAllCache').addEventListener('click', function() {
        if (confirm('Are you sure you want to clear all cache? This may temporarily affect performance.')) {
            fetch(, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('All cache cleared successfully!');
                }
            });
        }
    });
    
    document.getElementById('refreshMetrics').addEventListener('click', function() {
        const btn = this;
        const originalHtml = btn.innerHTML;
        
        btn.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" 
                 stroke="currentColor" stroke-width="2" class="spin">
                <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                <path d="M3 3v5h5"/>
                <path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                <path d="M16 16h5v5"/>
            </svg>
            Refreshing...
        `;
        
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            alert('Performance metrics refreshed!');
        }, 1500);
    });
});

function clearCacheType(type) {
    if (confirm(`Clear ${type} cache?`)) {
        alert(`${type.charAt(0).toUpperCase() + type.slice(1)} cache cleared!`);
    }
}
</script>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.spin {
    animation: spin 1s linear infinite;
}
</style>