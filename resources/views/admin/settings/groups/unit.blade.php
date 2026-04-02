<form id="form-unit" class="settings-form" data-group="unit">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'unit'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'unit'])
    
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

    <!-- Unit Types Preview -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Available Unit Types</h3>
            <span class="badge rounded-pill px-3 py-1" 
                  style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">
                {{ setting('max_units_per_user', 10) }} max per user
            </span>
        </div>
        
        <div class="card border rounded-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="card border rounded-3 text-center p-3">
                            <div class="rounded-circle mx-auto mb-2" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #f1c3ae, #f8773c); 
                                        display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                     stroke="white" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <line x1="3" y1="9" x2="21" y2="9"/>
                                    <line x1="9" y1="21" x2="9" y2="9"/>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1">Department</h6>
                            <p class="text-muted small mb-0">Default</p>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card border rounded-3 text-center p-3">
                            <div class="rounded-circle mx-auto mb-2" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #93c5fd, #3b82f6); 
                                        display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                     stroke="white" stroke-width="2">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1">Branch</h6>
                            <p class="text-muted small mb-0">Regional offices</p>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card border rounded-3 text-center p-3">
                            <div class="rounded-circle mx-auto mb-2" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #a7f3d0, #10b981); 
                                        display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                     stroke="white" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1">Team</h6>
                            <p class="text-muted small mb-0">Project teams</p>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card border rounded-3 text-center p-3">
                            <div class="rounded-circle mx-auto mb-2" 
                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #ddd6fe, #8b5cf6); 
                                        display: flex; align-items: center; justify-content: center;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" 
                                     stroke="white" stroke-width="2">
                                    <circle cx="12" cy="8" r="5"/>
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                </svg>
                            </div>
                            <h6 class="fw-semibold mb-1">Individual</h6>
                            <p class="text-muted small mb-0">Single person</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Unit Statistics -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Unit Statistics</h3>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Total Units</h6>
                                <p class="text-muted small mb-0">Active units</p>
                            </div>
                            <div class="display-5 fw-bold" style="color: #f8773c;">24</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">With Ratings</h6>
                                <p class="text-muted small mb-0">Units rated</p>
                            </div>
                            <div class="display-5 fw-bold" style="color: #10b981;">18</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Avg Rating</h6>
                                <p class="text-muted small mb-0">Overall average</p>
                            </div>
                            <div class="display-5 fw-bold" style="color: #3b82f6;">4.2</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Active Users</h6>
                                <p class="text-muted small mb-0">Managing units</p>
                            </div>
                            <div class="display-5 fw-bold" style="color: #8b5cf6;">8</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-white py-4 border-top mt-6" style="bottom: 0; z-index: 10;">
        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 reset-group"
                    data-group="unit">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="unit">
                Save Unit Settings
            </button>
        </div>
    </div>
</form>