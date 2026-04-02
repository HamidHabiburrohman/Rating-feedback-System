<form id="form-visitor" class="settings-form" data-group="visitor">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'visitor'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'visitor'])
    
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

    <!-- Visitor Analytics -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Visitor Analytics</h3>
            <span class="badge rounded-pill px-3 py-1" 
                  style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">
                Last 30 days
            </span>
        </div>
        
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Total Visitors</h6>
                                <p class="text-muted small mb-0">Unique visitors</p>
                            </div>
                            <div class="display-5 fw-bold" style="color: #f8773c;">1,234</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Ratings Submitted</h6>
                                <p class="text-muted small mb-0">Total ratings</p>
                            </div>
                            <div class="display-5 fw-bold" style="color: #10b981;">987</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Avg Session</h6>
                                <p class="text-muted small mb-0">Time spent</p>
                            </div>
                            <div class="display-5 fw-bold" style="color: #3b82f6;">4.5m</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-semibold mb-1" style="color: #1a1a1a;">Bounce Rate</h6>
                                <p class="text-muted small mb-0">Single page</p>
                            </div>
                            <div class="display-5 fw-bold" style="color: #ef4444;">12%</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Privacy & Data Collection -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Data Collection Settings</h3>
        </div>
        
        <div class="card border rounded-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Collected Information</h6>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="collectIp" checked>
                                <label class="form-check-label" for="collectIp">
                                    IP Address
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="collectBrowser" checked>
                                <label class="form-check-label" for="collectBrowser">
                                    Browser & Device Info
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="collectLocation">
                                <label class="form-check-label" for="collectLocation">
                                    Approximate Location
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="collectReferrer" checked>
                                <label class="form-check-label" for="collectReferrer">
                                    Referrer URL
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Data Retention</h6>
                        <div class="alert alert-info rounded-3">
                            <div class="d-flex align-items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" 
                                     stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="16" x2="12" y2="12"/>
                                    <line x1="12" y1="8" x2="12.01" y2="8"/>
                                </svg>
                                <span class="fw-medium">Data is anonymized after {{ setting('anonymize_after_days', 30) }} days</span>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <label class="form-label fw-medium">Retention Period</label>
                            <select class="form-select" style="border-radius: 8px;">
                                <option>30 days</option>
                                <option>60 days</option>
                                <option selected>90 days</option>
                                <option>180 days</option>
                                <option>1 year</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="row">
                    <div class="col-12">
                        <h6 class="fw-semibold mb-3">Privacy Notice</h6>
                        <div class="bg-light rounded-3 p-3">
                            <p class="small mb-0">
                                We value your privacy. Visitor data is collected to improve our services and analyze usage patterns. 
                                All data is stored securely and anonymized according to our retention policy. 
                                No personally identifiable information is stored without consent.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-white py-4 border-top mt-6" style="bottom: 0; z-index: 10;">
        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 reset-group"
                    data-group="visitor">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="visitor">
                Save Visitor Settings
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const collectVisitorData = document.getElementById('collect_visitor_data');
    const anonymizeVisitors = document.getElementById('anonymize_visitors');
    
    if (collectVisitorData) {
        collectVisitorData.addEventListener('change', function() {
            const dataCollectionSection = document.querySelector('.mb-6:last-child');
            if (dataCollectionSection) {
                if (this.checked) {
                    dataCollectionSection.style.display = 'block';
                    setTimeout(() => {
                        dataCollectionSection.style.opacity = '1';
                    }, 10);
                } else {
                    dataCollectionSection.style.opacity = '0.5';
                    setTimeout(() => {
                        dataCollectionSection.style.display = 'none';
                    }, 300);
                }
            }
        });
    }
    
    if (anonymizeVisitors) {
        anonymizeVisitors.addEventListener('change', function() {
            const alertElement = document.querySelector('.alert-info');
            if (alertElement) {
                if (this.checked) {
                    alertElement.querySelector('span').textContent = `Data is anonymized after ${document.getElementById('anonymize_after_days')?.value || 30} days`;
                } else {
                    alertElement.querySelector('span').textContent = 'Data anonymization is disabled';
                }
            }
        });
    }
});
</script>