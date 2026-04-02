<form id="form-rating" class="settings-form" data-group="rating">
    @csrf
    @method('PUT')
    
    @include('admin.settings.partials.header.group-header', ['group' => 'rating'])
    
    @include('admin.settings.partials.header.group-description', ['group' => 'rating'])
    
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

    <!-- Rating Scale Preview -->
    <div class="mb-6">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Rating Scale Preview</h3>
            <span class="badge rounded-pill px-3 py-1" 
                  style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">
                {{ setting('rating_scale_min', 1) }} - {{ setting('rating_scale_max', 5) }} scale
            </span>
        </div>
        
        <div class="card border rounded-4">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h5 class="fw-semibold mb-3">How would you rate our service?</h5>
                    <p class="text-muted mb-4">Select a rating from {{ setting('rating_scale_min', 1) }} to {{ setting('rating_scale_max', 5) }}</p>
                </div>
                
                <div class="d-flex justify-content-center gap-2 mb-4" id="ratingPreview">
                    @php
                        $min = setting('rating_scale_min', 1);
                        $max = setting('rating_scale_max', 5);
                        $allowDecimal = setting('rating_allow_decimal', false);
                    @endphp
                    
                    @for($i = $min; $i <= $max; $i++)
                        <div class="rating-star" data-value="{{ $i }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" 
                                 stroke="#e5e7eb" stroke-width="1.5">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                            <span class="d-block mt-1 small">{{ $i }}</span>
                        </div>
                    @endfor
                    
                    @if($allowDecimal)
                        <div class="ms-4 d-flex align-items-center">
                            <span class="text-muted me-2">or</span>
                            <input type="number" class="form-control" style="width: 80px;" 
                                   min="{{ $min }}" max="{{ $max }}" step="0.1" placeholder="4.5">
                        </div>
                    @endif
                </div>
                
                <div class="text-center">
                    <div class="d-inline-block bg-light rounded-3 p-3">
                        <div class="fw-medium mb-1">Current Average Rating</div>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <div class="display-4 fw-bold" style="color: #f8773c;">4.2</div>
                            <div class="text-start">
                                <div class="d-flex align-items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" 
                                             fill="{{ $i <= 4.2 ? '#fbbf24' : '#e5e7eb' }}" stroke="{{ $i <= 4.2 ? '#fbbf24' : '#e5e7eb' }}">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <div class="small text-muted">Based on 1,234 ratings</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sticky-bottom bg-white py-4 border-top mt-6" style="bottom: 0; z-index: 10;">
        <div class="d-flex justify-content-end gap-3">
            <button type="button" class="btn btn-outline-secondary rounded-pill px-4 reset-group"
                    data-group="rating">
                Reset to Defaults
            </button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 save-group"
                    data-group="rating">
                Save Rating Settings
            </button>
        </div>
    </div>
</form>

<style>
.rating-star {
    cursor: pointer;
    text-align: center;
    transition: all 0.2s ease;
    padding: 8px;
    border-radius: 8px;
}

.rating-star:hover {
    background-color: #f9fafb;
    transform: scale(1.1);
}

.rating-star:hover svg {
    stroke: #fbbf24;
}

.rating-star.active svg {
    fill: #fbbf24;
    stroke: #fbbf24;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingStars = document.querySelectorAll('.rating-star');
    let currentRating = 0;
    
    ratingStars.forEach(star => {
        star.addEventListener('click', function() {
            const value = parseInt(this.dataset.value);
            currentRating = value;
            
            ratingStars.forEach(s => {
                const sValue = parseInt(s.dataset.value);
                const svg = s.querySelector('svg');
                
                if (sValue <= value) {
                    s.classList.add('active');
                    svg.setAttribute('fill', '#fbbf24');
                    svg.setAttribute('stroke', '#fbbf24');
                } else {
                    s.classList.remove('active');
                    svg.setAttribute('fill', 'none');
                    svg.setAttribute('stroke', '#e5e7eb');
                }
            });
        });
        
        star.addEventListener('mouseover', function() {
            const value = parseInt(this.dataset.value);
            
            ratingStars.forEach(s => {
                const sValue = parseInt(s.dataset.value);
                const svg = s.querySelector('svg');
                
                if (sValue <= value) {
                    svg.setAttribute('stroke', '#fbbf24');
                }
            });
        });
        
        star.addEventListener('mouseout', function() {
            ratingStars.forEach(s => {
                const sValue = parseInt(s.dataset.value);
                const svg = s.querySelector('svg');
                
                if (sValue > currentRating) {
                    svg.setAttribute('stroke', '#e5e7eb');
                }
            });
        });
    });
    
    const ratingScaleMin = document.getElementById('rating_scale_min');
    const ratingScaleMax = document.getElementById('rating_scale_max');
    const ratingAllowDecimal = document.getElementById('rating_allow_decimal');
    
    if (ratingScaleMin && ratingScaleMax) {
        ratingScaleMin.addEventListener('change', updateRatingPreview);
        ratingScaleMax.addEventListener('change', updateRatingPreview);
    }
    
    if (ratingAllowDecimal) {
        ratingAllowDecimal.addEventListener('change', function() {
            const decimalInput = document.querySelector('#ratingPreview input[type="number"]');
            if (this.checked && !decimalInput) {
                const div = document.createElement('div');
                div.className = 'ms-4 d-flex align-items-center';
                div.innerHTML = `
                    <span class="text-muted me-2">or</span>
                    <input type="number" class="form-control" style="width: 80px;" 
                           min="1" max="5" step="0.1" placeholder="4.5">
                `;
                document.getElementById('ratingPreview').appendChild(div);
            } else if (!this.checked && decimalInput) {
                decimalInput.parentElement.remove();
            }
        });
    }
    
    function updateRatingPreview() {
        const min = ratingScaleMin ? parseInt(ratingScaleMin.value) : 1;
        const max = ratingScaleMax ? parseInt(ratingScaleMax.value) : 5;
        
        if (min >= max) {
            alert('Minimum rating must be less than maximum rating');
            return;
        }
        
        const ratingPreview = document.getElementById('ratingPreview');
        if (ratingPreview) {
            let starsHtml = '';
            for (let i = min; i <= max; i++) {
                starsHtml += `
                    <div class="rating-star" data-value="${i}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" 
                             stroke="#e5e7eb" stroke-width="1.5">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span class="d-block mt-1 small">${i}</span>
                    </div>
                `;
            }
            
            const decimalSection = ratingPreview.querySelector('.ms-4');
            ratingPreview.innerHTML = starsHtml;
            if (decimalSection) {
                ratingPreview.appendChild(decimalSection);
            }
        }
    }
});
</script>