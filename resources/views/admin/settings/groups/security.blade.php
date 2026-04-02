<form id="form-security" class="settings-form" data-group="security">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'security'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'security'])
    
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

    <!-- Security Status -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Security Status</h3>
            <span class="badge rounded-pill px-3 py-1" 
                  style="background: linear-gradient(135deg, #a7f3d0, #10b981); color: white; font-size: 0.75rem;">
                Secure
            </span>
        </div>
        
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Password Policy</h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Minimum Length</span>
                                <span class="fw-medium">{{ setting('password_min_length', 8) }} characters</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Expiry Period</span>
                                <span class="fw-medium">{{ setting('password_expiry_days', 90) }} days</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Complexity Required</span>
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                    Enabled
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Login Security</h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Max Login Attempts</span>
                                <span class="fw-medium">{{ setting('max_login_attempts', 5) }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Lockout Duration</span>
                                <span class="fw-medium">{{ setting('lockout_duration', 15) }} minutes</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span>Two-Factor Auth</span>
                                @if(setting('require_two_factor', false))
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                        Required
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1">
                                        Optional
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Audit -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Recent Security Events</h3>
            <button type="button" class="btn btn-outline-secondary rounded-pill px-3">
                View All
            </button>
        </div>
        
        <div class="card border rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">Time</th>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">Event</th>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">User</th>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">IP Address</th>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="text-muted">5 minutes ago</div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" 
                                             stroke="#10b981" stroke-width="2">
                                            <path d="M20 6L9 17l-5-5"/>
                                        </svg>
                                        <span>Successful Login</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span>admin@example.com</span>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <code>192.168.1.100</code>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                        Success
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="text-muted">2 hours ago</div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" 
                                             stroke="#ef4444" stroke-width="2">
                                            <path d="M18 6L6 18M6 6l12 12"/>
                                        </svg>
                                        <span>Failed Login</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span>user@example.com</span>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <code>203.0.113.45</code>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-2 py-1">
                                        Blocked
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="text-muted">1 day ago</div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" 
                                             stroke="#f59e0b" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"/>
                                            <line x1="12" y1="8" x2="12" y2="12"/>
                                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                        <span>Password Changed</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span>manager@example.com</span>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <code>192.168.1.150</code>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-2 py-1">
                                        Warning
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-white py-4 border-top mt-6" style="bottom: 0; z-index: 10;">
        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 reset-group"
                    data-group="security">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="security">
                Save Security Settings
            </button>
        </div>
    </div>
</form>