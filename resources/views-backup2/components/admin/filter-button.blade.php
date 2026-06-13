@props([
    'statusOptions' => [
        '' => 'All',
        'active' => 'Active',
        'inactive' => 'Inactive'
    ],
    'currentStatus' => []
])

<div class="dropdown" id="filterContainer">
    <button class="btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn"
        type="button" data-bs-toggle="dropdown" id="filterDropdown"
        style="height:44px;background:white;border-color:#d1d5db;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
        </svg>
        <span class="fw-medium" id="filterText">Filter</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
            <path d="M6 9l6 6 6-6" />
        </svg>
    </button>
    
    <div class="dropdown-menu p-0 border-0 shadow-lg rounded-4 overflow-hidden mt-2"
        style="min-width: 300px; background-color: #ffffff;">
        <div class="p-3">
            <div class="mb-1">
                <label class="small fw-bold text-uppercase mb-2 mt-2 d-block"
                    style="color: #6b7280; letter-spacing: 0.05em;">Status</label>
                <div class="d-flex flex-wrap gap-2" id="statusFilter">
                    @php
                        $activeStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
                        $inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
                    @endphp
                    
                    @foreach($statusOptions as $value => $label)
                        <button type="button" 
                            class="btn btn-sm rounded-pill px-3 fw-medium filter-status"
                            data-value="{{ $value }}"
                            style="{{ in_array($value, $currentStatus) ? $activeStyle : $inactiveStyle }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
        
        <div class="p-3 border-top d-flex gap-2 bg-white">
            <button type="button" id="resetFilter" 
                class="btn btn-sm rounded-pill w-100 fw-semibold d-flex align-items-center justify-content-center"
                style="height: 40px; background: white; border: 1px solid #d1d5db; color: #4b5563;">
                Reset
            </button>
            <button type="button" id="applyFilter" 
                class="btn btn-sm rounded-pill w-100 fw-semibold"
                style="height: 40px; background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;">
                Apply Filter
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let selectedStatus = {!! json_encode($currentStatus) !!};
        
        // Initialize active filters
        selectedStatus.forEach(value => {
            $(`.filter-status[data-value="${value}"]`).addClass('active-filter');
        });
        
        $('.filter-status').click(function() {
            const value = $(this).data('value');
            
            if (value === '') {
                selectedStatus = [];
                $('.filter-status').removeClass('active-filter');
                $(this).addClass('active-filter');
            } else {
                const index = selectedStatus.indexOf(value);
                if (index === -1) {
                    selectedStatus.push(value);
                    $(this).addClass('active-filter');
                } else {
                    selectedStatus.splice(index, 1);
                    $(this).removeClass('active-filter');
                }
                
                if (selectedStatus.length === 0) {
                    $('.filter-status[data-value=""]').addClass('active-filter');
                } else {
                    $('.filter-status[data-value=""]').removeClass('active-filter');
                }
            }
            
            updateFilterStyles();
        });
        
        $('#applyFilter').click(function() {
            const params = new URLSearchParams(window.location.search);
            
            if (selectedStatus.length > 0) {
                params.set('status', selectedStatus.join(','));
            } else {
                params.delete('status');
            }
            
            params.set('page', 1);
            window.location.href = `${window.location.pathname}?${params.toString()}`;
        });
        
        $('#resetFilter').click(function() {
            window.location.href = window.location.pathname;
        });
        
        function updateFilterStyles() {
            $('.filter-status').each(function() {
                const $btn = $(this);
                if ($btn.hasClass('active-filter')) {
                    $btn.css({
                        'background': 'linear-gradient(135deg, #f1c3ae, #f8773c)',
                        'border': 'none',
                        'color': 'white'
                    });
                } else {
                    $btn.css({
                        'background': 'white',
                        'border': '1px solid #d1d5db',
                        'color': '#6b7280'
                    });
                }
            });
        }
    });
</script>
@endpush