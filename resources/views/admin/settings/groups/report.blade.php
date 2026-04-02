<form id="form-report" class="settings-form" data-group="report">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'report'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'report'])
    
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

    <!-- Report Schedule -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Report Schedule</h3>
            <span class="badge rounded-pill px-3 py-1" 
                  style="background: linear-gradient(135deg, #f1c3ae, #f8773c); color: white; font-size: 0.75rem;">
                {{ setting('report_schedule', 'monthly') }}
            </span>
        </div>
        
        <div class="card border rounded-4">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Next Scheduled Reports</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Monthly Summary</div>
                                        <div class="text-muted small">All units rating report</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-medium">Next: 1 Mar 2024</div>
                                        <div class="text-muted small">in 7 days</div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Weekly Performance</div>
                                        <div class="text-muted small">Top performing units</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-medium">Next: 26 Feb 2024</div>
                                        <div class="text-muted small">in 2 days</div>
                                    </div>
                                </div>
                            </div>
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">Quarterly Audit</div>
                                        <div class="text-muted small">System audit report</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-medium">Next: 1 Apr 2024</div>
                                        <div class="text-muted small">in 35 days</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <h6 class="fw-semibold mb-3">Recent Reports</h6>
                        <div class="d-flex flex-column gap-3">
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px; background: #f1f5f9;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                                             stroke="#64748b" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                            <polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-medium">January Report</div>
                                        <div class="text-muted small">Generated 1 Feb 2024</div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Download</a>
                            </div>
                            
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px; background: #f1f5f9;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" 
                                             stroke="#64748b" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                            <line x1="16" y1="13" x2="8" y2="13"/>
                                            <line x1="16" y1="17" x2="8" y2="17"/>
                                            <polyline points="10 9 9 9 8 9"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="fw-medium">Weekly #5</div>
                                        <div class="text-muted small">Generated 19 Feb 2024</div>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">Download</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Formats -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Available Formats</h3>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border rounded-4 h-100 text-center">
                    <div class="card-body p-4">
                        <div class="rounded-circle mx-auto mb-3" 
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #fee2e2, #ef4444); 
                                    display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" 
                                 stroke="white" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10 9 9 9 8 9"/>
                            </svg>
                        </div>
                        <h6 class="fw-semibold mb-2">PDF</h6>
                        <p class="text-muted small mb-3">Portable Document Format</p>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                            Default
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border rounded-4 h-100 text-center">
                    <div class="card-body p-4">
                        <div class="rounded-circle mx-auto mb-3" 
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #dcfce7, #16a34a); 
                                    display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" 
                                 stroke="white" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <path d="M16 13H8M16 17H8M10 9H9"/>
                            </svg>
                        </div>
                        <h6 class="fw-semibold mb-2">Excel</h6>
                        <p class="text-muted small mb-3">Spreadsheet format (.xlsx)</p>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">
                            Available
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border rounded-4 h-100 text-center">
                    <div class="card-body p-4">
                        <div class="rounded-circle mx-auto mb-3" 
                             style="width: 60px; height: 60px; background: linear-gradient(135deg, #fef3c7, #d97706); 
                                    display: flex; align-items: center; justify-content: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" 
                                 stroke="white" stroke-width="2">
                                <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                                <polyline points="13 2 13 9 20 9"/>
                                <path d="M8 13h8M8 17h8M8 9h1"/>
                            </svg>
                        </div>
                        <h6 class="fw-semibold mb-2">CSV</h6>
                        <p class="text-muted small mb-3">Comma Separated Values</p>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">
                            Available
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-white py-4 border-top mt-6" style="bottom: 0; z-index: 10;">
        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 reset-group"
                    data-group="report">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="report">
                Save Report Settings
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const reportSchedule = document.getElementById('report_schedule');
    const autoGenerate = document.getElementById('report_auto_generate');
    
    if (reportSchedule) {
        reportSchedule.addEventListener('change', function() {
            const scheduleBadge = document.querySelector('.badge[style*="linear-gradient"]');
            if (scheduleBadge) {
                scheduleBadge.textContent = this.value;
            }
            
            const nextDates = {
                'daily': 'Tomorrow',
                'weekly': 'Next week',
                'monthly': 'Next month',
                'quarterly': 'Next quarter'
            };
            
            const nextDateElement = document.querySelector('.list-group-item:first-child .fw-medium');
            if (nextDateElement && nextDates[this.value]) {
                nextDateElement.textContent = `Next: ${nextDates[this.value]}`;
            }
        });
    }
    
    if (autoGenerate) {
        autoGenerate.addEventListener('change', function() {
            const scheduleSection = document.querySelector('.mb-6:first-child');
            if (scheduleSection) {
                if (this.checked) {
                    scheduleSection.style.display = 'block';
                    setTimeout(() => {
                        scheduleSection.style.opacity = '1';
                    }, 10);
                } else {
                    scheduleSection.style.opacity = '0.5';
                    setTimeout(() => {
                        scheduleSection.style.display = 'none';
                    }, 300);
                }
            }
        });
    }
});
</script>