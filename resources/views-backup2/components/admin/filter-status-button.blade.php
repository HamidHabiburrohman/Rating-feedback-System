@props(['label', 'value', 'currentStatus' => [], 'isActive' => false])

@php
    $activeStyle = 'background: linear-gradient(135deg, #f1c3ae, #f8773c); border: none; color: white;';
    $inactiveStyle = 'background: white; border: 1px solid #d1d5db; color: #6b7280;';
    
    $buttonStyle = $isActive ? $activeStyle : $inactiveStyle;
@endphp

<button type="button" 
    class="btn btn-sm rounded-pill px-3 fw-medium filter-status" 
    data-value="{{ $value }}"
    data-active="{{ $isActive ? 'true' : 'false' }}"
    style="{{ $buttonStyle }} transition: all 0.2s;">
    {{ $label }}
</button>

<script src="{{ asset('assets/components/components.js')}}"></script>