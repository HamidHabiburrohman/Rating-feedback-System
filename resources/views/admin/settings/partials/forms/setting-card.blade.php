@php
$displayValue = $setting->castValue();
if (is_array($displayValue)) {
    $displayValue = json_encode($displayValue);
}

$inputId = 'setting-' . str_replace('.', '-', $setting->key);
@endphp

<div class="col-md-6 col-lg-4 mb-4">
    <div class="card h-100" 
         style="border: 1px solid #e5e7eb; border-radius: 12px;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h6 class="fw-semibold mb-1" style="color: #1a1a1a; font-size: 0.95rem;">
                        {{ $setting->label ?? $setting->key }}
                    </h6>
                    <div class="d-flex gap-1 mt-1">
                        @if($setting->is_editable === false)
                            <span class="badge bg-light text-muted border rounded-pill px-2 py-1" 
                                  style="font-size: 0.7rem; border-color: #d1d5db;">
                                Read-only
                            </span>
                        @endif
                        
                        @if($setting->is_public)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1" 
                                  style="font-size: 0.7rem;">
                                Public
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($setting->type === 'boolean')
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input setting-input" 
                               type="checkbox" 
                               id="{{ $inputId }}" 
                               name="{{ $setting->key }}"
                               value="true"
                               {{ $displayValue ? 'checked' : '' }}
                               {{ $setting->is_editable ? '' : 'disabled' }}
                               style="width: 3em; height: 1.5em;"
                               data-type="boolean">
                    </div>
                @endif
            </div>

            @if($setting->description)
                <p class="text-muted small mb-3" style="font-size: 0.85rem; line-height: 1.4;">
                    {{ $setting->description }}
                </p>
            @endif

            <div class="mb-3">
                @if($setting->type === 'boolean')
                @elseif($setting->type === 'integer' || $setting->type === 'float')
                    <input type="number" 
                           class="form-control setting-input" 
                           id="{{ $inputId }}" 
                           name="{{ $setting->key }}"
                           value="{{ $displayValue }}"
                           {{ $setting->is_editable ? '' : 'readonly' }}
                           style="border-radius: 8px; border-color: #d1d5db; padding: 0.5rem 0.75rem;"
                           data-type="{{ $setting->type }}"
                           step="{{ $setting->type === 'float' ? '0.1' : '1' }}">
                @elseif($setting->type === 'json' && $setting->options)
                    <select class="form-select setting-input" 
                            id="{{ $inputId }}" 
                            name="{{ $setting->key }}"
                            {{ $setting->is_editable ? '' : 'disabled' }}
                            style="border-radius: 8px; border-color: #d1d5db; padding: 0.5rem 0.75rem;"
                            data-type="select">
                        @php
                            $options = is_array($setting->options) ? $setting->options : json_decode($setting->options, true);
                        @endphp
                        @if(is_array($options))
                            @foreach($options as $value => $label)
                                <option value="{{ $value }}" {{ (string)$displayValue == (string)$value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        @else
                            <option value="{{ $setting->value }}">{{ $setting->value }}</option>
                        @endif
                    </select>
                @else
                    <input type="text" 
                           class="form-control setting-input" 
                           id="{{ $inputId }}" 
                           name="{{ $setting->key }}"
                           value="{{ $displayValue }}"
                           {{ $setting->is_editable ? '' : 'readonly' }}
                           style="border-radius: 8px; border-color: #d1d5db; padding: 0.5rem 0.75rem;"
                           data-type="{{ $setting->type }}">
                @endif
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                @if($setting->hint)
                    <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.8rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" 
                             stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                        <span>{{ $setting->hint }}</span>
                    </div>
                @else
                    <div></div>
                @endif
                
                @if($setting->validation_rules)
                    <span class="badge bg-light text-muted border rounded-pill px-2 py-1" 
                          style="font-size: 0.7rem;" 
                          title="Validation: {{ $setting->validation_rules }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" 
                             stroke="currentColor" stroke-width="2">
                            <path d="M20 6L9 17l-5-5"/>
                        </svg>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>