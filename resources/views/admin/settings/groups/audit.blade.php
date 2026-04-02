<form id="form-audit" class="settings-form" data-group="audit">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'audit'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'audit'])
    
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

    <!-- Audit Log Preview -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Log Preview</h3>
            <span class="badge rounded-pill px-3 py-1" 
                  style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">
                {{ setting('audit_log_retention', 90) }} days retention
            </span>
        </div>
        
        <div class="card border rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8fafc;">
                            <tr>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">Timestamp</th>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">User</th>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">Event</th>
                                <th class="py-3 px-4 fw-semibold" style="color: #64748b; font-size: 0.875rem;">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="text-muted">Just now</div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle" style="width: 24px; height: 24px; background: #f1c3ae;"></div>
                                        <span>Admin User</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1">
                                        Settings Updated
                                    </span>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="text-muted">Updated audit log settings</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="text-muted">2 hours ago</div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle" style="width: 24px; height: 24px; background: #93c5fd;"></div>
                                        <span>Manager</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2 py-1">
                                        Report Generated
                                    </span>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="text-muted">Generated monthly report</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="text-muted">1 day ago</div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle" style="width: 24px; height: 24px; background: #a7f3d0;"></div>
                                        <span>Visitor</span>
                                    </div>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">
                                        Rating Submitted
                                    </span>
                                </td>
                                <td class="py-3 px-4" style="font-size: 0.875rem;">
                                    <span class="text-muted">Submitted rating for Unit A</span>
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
                    data-group="audit">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="audit">
                Save Audit Settings
            </button>
        </div>
    </div>
</form>