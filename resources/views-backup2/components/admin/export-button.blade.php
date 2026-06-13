@props([
    'formats' => ['excel', 'pdf'],
    'filters' => [],
    'exportRoute' => '',
    'label' => 'Export',
    'buttonClass' => 'btn btn-white border rounded-pill px-3 d-flex align-items-center gap-2 dropdown-toggle-btn',
    'buttonStyle' => 'height: 44px; background-color: white; border-color: #d1d5db;'
])

<div class="dropdown">
    <button class="{{ $buttonClass }}" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="{{ $buttonStyle }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
            <polyline points="7 10 12 15 17 10" />
            <line x1="12" y1="15" x2="12" y2="3" />
        </svg>
        <span class="fw-medium">{{ $label }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="dropdown-icon" style="transition:.3s">
            <path d="M6 9l6 6 6-6" />
        </svg>
    </button>
    
    <ul class="dropdown-menu border-0 shadow-lg rounded-3 py-2 mt-2">
        @if(in_array('pdf', $formats))
        <li>
            <form action="{{ $exportRoute }}" method="GET" class="d-inline w-100">
                @foreach($filters as $key => $value)
                    @if($value !== null && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <input type="hidden" name="format" value="pdf">
                <button type="submit" class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark w-100 border-0 bg-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <line x1="16" y1="13" x2="8" y2="13" />
                        <line x1="16" y1="17" x2="8" y2="17" />
                        <polyline points="10 9 9 9 8 9" />
                    </svg>
                    PDF Report
                </button>
            </form>
        </li>
        @endif
        
        @if(in_array('excel', $formats))
        <li>
            <form action="{{ $exportRoute }}" method="GET" class="d-inline w-100">
                @foreach($filters as $key => $value)
                    @if($value !== null && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <input type="hidden" name="format" value="excel">
                <button type="submit" class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark w-100 border-0 bg-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="M16 13H8" />
                        <path d="M16 17H8" />
                        <path d="M10 9H9H8" />
                    </svg>
                    Excel Sheet
                </button>
            </form>
        </li>
        @endif
        
        @if(in_array('csv', $formats))
        <li>
            <form action="{{ $exportRoute }}" method="GET" class="d-inline w-100">
                @foreach($filters as $key => $value)
                    @if($value !== null && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <input type="hidden" name="format" value="csv">
                <button type="submit" class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark w-100 border-0 bg-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                        <path d="M16 13H8" />
                        <path d="M16 17H8" />
                        <path d="M10 9H9H8" />
                    </svg>
                    CSV File
                </button>
            </form>
        </li>
        @endif
        
        @if(in_array('print', $formats))
        <li>
            <form action="{{ $exportRoute }}" method="GET" target="_blank" class="d-inline w-100">
                @foreach($filters as $key => $value)
                    @if($value !== null && $value !== '')
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <input type="hidden" name="format" value="print">
                <button type="submit" class="dropdown-item py-2 px-3 d-flex align-items-center gap-2 text-dark w-100 border-0 bg-transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                        <rect x="6" y="14" width="12" height="8" />
                    </svg>
                    Print View
                </button>
            </form>
        </li>
        @endif
    </ul>
</div>