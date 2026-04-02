<form id="form-display" class="settings-form" data-group="display">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'display'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'display'])
    
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

    <!-- Theme Preview Section -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Theme Preview</h3>
        </div>
        
        <div class="row g-4">
            <div class="col-12">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="fw-semibold mb-3">Current Theme</h5>
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <div id="themeColorPreview" class="rounded-circle" 
                                         style="width: 40px; height: 40px; background: linear-gradient(135deg, #f1c3ae, #f8773c);">
                                    </div>
                                    <div>
                                        <p class="mb-1 fw-medium" id="themeName">Orange Sunrise</p>
                                        <p class="text-muted small mb-0" id="themeCode">#f8773c</p>
                                    </div>
                                </div>
                                
                                <div class="mt-4">
                                    <h6 class="fw-medium mb-2">Preview Elements</h6>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button class="btn btn-sm rounded-pill px-3" 
                                                style="background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;">
                                            Primary Button
                                        </button>
                                        <span class="badge rounded-pill px-3 py-1" 
                                              style="background: linear-gradient(135deg, #f1c3ae, #f8773c); color: white;">
                                            Badge
                                        </span>
                                        <div class="progress" style="height: 6px; width: 150px;">
                                            <div class="progress-bar rounded" 
                                                 style="background: linear-gradient(135deg, #f1c3ae, #f8773c); width: 65%;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5 class="fw-semibold mb-3">Available Themes</h5>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="theme-option card border rounded-3 p-3 text-center" 
                                             data-theme="blue" data-color="#3b82f6" data-name="Ocean Blue">
                                            <div class="rounded-circle mx-auto mb-2" 
                                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #93c5fd, #3b82f6);">
                                            </div>
                                            <p class="fw-medium mb-0 small">Ocean Blue</p>
                                            <p class="text-muted small mb-0">#3b82f6</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="theme-option card border rounded-3 p-3 text-center active" 
                                             data-theme="orange" data-color="#f8773c" data-name="Orange Sunrise">
                                            <div class="rounded-circle mx-auto mb-2" 
                                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #f1c3ae, #f8773c);">
                                            </div>
                                            <p class="fw-medium mb-0 small">Orange Sunrise</p>
                                            <p class="text-muted small mb-0">#f8773c</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="theme-option card border rounded-3 p-3 text-center" 
                                             data-theme="green" data-color="#10b981" data-name="Forest Green">
                                            <div class="rounded-circle mx-auto mb-2" 
                                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #a7f3d0, #10b981);">
                                            </div>
                                            <p class="fw-medium mb-0 small">Forest Green</p>
                                            <p class="text-muted small mb-0">#10b981</p>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="theme-option card border rounded-3 p-3 text-center" 
                                             data-theme="purple" data-color="#8b5cf6" data-name="Royal Purple">
                                            <div class="rounded-circle mx-auto mb-2" 
                                                 style="width: 60px; height: 60px; background: linear-gradient(135deg, #ddd6fe, #8b5cf6);">
                                            </div>
                                            <p class="fw-medium mb-0 small">Royal Purple</p>
                                            <p class="text-muted small mb-0">#8b5cf6</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Preview Section -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Chart Preview</h3>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Bar Chart</h6>
                        <div class="chart-preview">
                            <div class="d-flex align-items-end gap-2" style="height: 120px;">
                                <div class="flex-fill d-flex flex-column align-items-center">
                                    <div class="rounded" style="width: 20px; height: 60px; background: linear-gradient(135deg, #f1c3ae, #f8773c);"></div>
                                    <span class="small mt-2">Jan</span>
                                </div>
                                <div class="flex-fill d-flex flex-column align-items-center">
                                    <div class="rounded" style="width: 20px; height: 80px; background: linear-gradient(135deg, #f1c3ae, #f8773c);"></div>
                                    <span class="small mt-2">Feb</span>
                                </div>
                                <div class="flex-fill d-flex flex-column align-items-center">
                                    <div class="rounded" style="width: 20px; height: 45px; background: linear-gradient(135deg, #f1c3ae, #f8773c);"></div>
                                    <span class="small mt-2">Mar</span>
                                </div>
                                <div class="flex-fill d-flex flex-column align-items-center">
                                    <div class="rounded" style="width: 20px; height: 95px; background: linear-gradient(135deg, #f1c3ae, #f8773c);"></div>
                                    <span class="small mt-2">Apr</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Pie Chart</h6>
                        <div class="chart-preview d-flex justify-content-center">
                            <div style="width: 100px; height: 100px; border-radius: 50%; 
                                        background: conic-gradient(
                                            #f8773c 0% 40%,
                                            #3b82f6 40% 70%,
                                            #10b981 70% 100%
                                        );">
                            </div>
                        </div>
                        <div class="mt-3 d-flex justify-content-center gap-3">
                            <div class="d-flex align-items-center gap-1">
                                <div style="width: 12px; height: 12px; border-radius: 2px; background: #f8773c;"></div>
                                <span class="small">40%</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <div style="width: 12px; height: 12px; border-radius: 2px; background: #3b82f6;"></div>
                                <span class="small">30%</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <div style="width: 12px; height: 12px; border-radius: 2px; background: #10b981;"></div>
                                <span class="small">30%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border rounded-4">
                    <div class="card-body p-4">
                        <h6 class="fw-semibold mb-3">Line Chart</h6>
                        <div class="chart-preview">
                            <svg width="100%" height="100" viewBox="0 0 200 80" style="overflow: visible;">
                                <path d="M10,60 L50,40 L90,50 L130,30 L170,20 L190,10" 
                                      fill="none" 
                                      stroke="#f8773c" 
                                      stroke-width="2"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"/>
                                <circle cx="10" cy="60" r="3" fill="#f8773c"/>
                                <circle cx="50" cy="40" r="3" fill="#f8773c"/>
                                <circle cx="90" cy="50" r="3" fill="#f8773c"/>
                                <circle cx="130" cy="30" r="3" fill="#f8773c"/>
                                <circle cx="170" cy="20" r="3" fill="#f8773c"/>
                                <circle cx="190" cy="10" r="3" fill="#f8773c"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-white py-4 border-top mt-6" style="bottom: 0; z-index: 10;">
        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 reset-group"
                    data-group="display">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="display">
                Save Display Settings
            </button>
        </div>
    </div>
</form>

<style>
.theme-option {
    cursor: pointer;
    transition: all 0.3s ease;
}

.theme-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.theme-option.active {
    border-color: #f8773c;
    box-shadow: 0 4px 12px rgba(248, 119, 60, 0.2);
}

.chart-preview {
    min-height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const themeOptions = document.querySelectorAll('.theme-option');
    const themeColorPreview = document.getElementById('themeColorPreview');
    const themeName = document.getElementById('themeName');
    const themeCode = document.getElementById('themeCode');
    const themeInput = document.getElementById('theme_color');
    
    themeOptions.forEach(option => {
        option.addEventListener('click', function() {
            themeOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            const theme = this.dataset.theme;
            const color = this.dataset.color;
            const name = this.dataset.name;
            
            if (themeColorPreview) {
                themeColorPreview.style.background = `linear-gradient(135deg, ${getLightColor(color)}, ${color})`;
            }
            
            if (themeName) themeName.textContent = name;
            if (themeCode) themeCode.textContent = color;
            
            if (themeInput) {
                themeInput.value = theme;
            }
            
            updatePreviewTheme(color);
        });
    });
    
    function getLightColor(color) {
        const lightColors = {
            '#f8773c': '#f1c3ae',
            '#3b82f6': '#93c5fd',
            '#10b981': '#a7f3d0',
            '#8b5cf6': '#ddd6fe',
        };
        return lightColors[color] || '#f1c3ae';
    }
    
    function updatePreviewTheme(color) {
        const primaryButtons = document.querySelectorAll('.btn-primary');
        const badges = document.querySelectorAll('.badge[style*="linear-gradient"]');
        const progressBars = document.querySelectorAll('.progress-bar');
        const chartElements = document.querySelectorAll('.chart-preview [style*="#f8773c"]');
        
        const lightColor = getLightColor(color);
        const gradient = `linear-gradient(135deg, ${lightColor}, ${color})`;
        
        primaryButtons.forEach(btn => {
            btn.style.background = gradient;
        });
        
        badges.forEach(badge => {
            badge.style.background = gradient;
        });
        
        progressBars.forEach(bar => {
            bar.style.background = gradient;
        });
        
        chartElements.forEach(el => {
            const style = el.getAttribute('style');
            if (style) {
                el.setAttribute('style', style.replace(/#f8773c/g, color));
            }
        });
        
        const svgPaths = document.querySelectorAll('svg path[stroke="#f8773c"]');
        const svgCircles = document.querySelectorAll('svg circle[fill="#f8773c"]');
        
        svgPaths.forEach(path => {
            path.setAttribute('stroke', color);
        });
        
        svgCircles.forEach(circle => {
            circle.setAttribute('fill', color);
        });
    }
    
    const chartTypeInput = document.getElementById('default_chart_type');
    if (chartTypeInput) {
        chartTypeInput.addEventListener('change', function() {
            const chartPreviews = document.querySelectorAll('.chart-preview');
            chartPreviews.forEach(preview => {
                preview.style.opacity = '0.5';
                setTimeout(() => {
                    preview.style.opacity = '1';
                }, 300);
            });
        });
    }
    
    const showStatsInput = document.getElementById('show_rating_stats');
    if (showStatsInput) {
        showStatsInput.addEventListener('change', function() {
            const chartSection = document.querySelector('.mb-6:nth-child(4)');
            if (chartSection) {
                if (this.checked) {
                    chartSection.style.display = 'block';
                    setTimeout(() => {
                        chartSection.style.opacity = '1';
                    }, 10);
                } else {
                    chartSection.style.opacity = '0.5';
                    setTimeout(() => {
                        chartSection.style.display = 'none';
                    }, 300);
                }
            }
        });
    }
});
</script>