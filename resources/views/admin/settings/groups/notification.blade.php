<form id="form-notification" class="settings-form" data-group="notification">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'notification'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'notification'])
    
    @php
        $subgroups = $settings->groupBy('subgroup');
        $channels = setting('notification_channels', 'mail,database');
        $channelCount = 0;
        
        if (is_array($channels)) {
            $channelCount = count($channels);
        } elseif (is_string($channels)) {
            $channelCount = count(explode(',', $channels));
        } else {
            $channelCount = 2; // default
        }
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

    <!-- Notification Channels -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Notification Channels</h3>
            <span class="badge rounded-pill px-3 py-1" 
                  style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">
                {{ $channelCount }} active
            </span>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle mx-auto mb-3" 
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #fef3c7, #f59e0b); 
                                    display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" 
                                 stroke="white" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <h6 class="fw-semibold mb-2">Email Notifications</h6>
                        <p class="text-muted small mb-3">Send notifications to email addresses</p>
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input" type="checkbox" checked style="width: 3em; height: 1.5em;">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle mx-auto mb-3" 
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #dbeafe, #3b82f6); 
                                    display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" 
                                 stroke="white" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                        </div>
                        <h6 class="fw-semibold mb-2">In-App Notifications</h6>
                        <p class="text-muted small mb-3">Show notifications within the application</p>
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input" type="checkbox" checked style="width: 3em; height: 1.5em;">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border rounded-4 h-100">
                    <div class="card-body p-4 text-center">
                        <div class="rounded-circle mx-auto mb-3" 
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #dcfce7, #16a34a); 
                                    display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" 
                                 stroke="white" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>
                        <h6 class="fw-semibold mb-2">SMS Notifications</h6>
                        <p class="text-muted small mb-3">Send text message notifications</p>
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input" type="checkbox" style="width: 3em; height: 1.5em;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-white py-4 border-top mt-6" style="bottom: 0; z-index: 10;">
        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 reset-group"
                    data-group="notification">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="notification">
                Save Notification Settings
            </button>
        </div>
    </div>
</form>