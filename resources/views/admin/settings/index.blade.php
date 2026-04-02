@extends('layouts.admin.app')

@section('title', 'System Settings')

@section('admin-content')
<div class="settings-page-header">
    <h1 class="settings-page-title">Settings</h1>
    <p class="settings-page-subtitle">Configure and manage all system settings</p>
</div>

@include('admin.settings.partials.alerts')

<div class="settings-layout">
    <aside class="settings-menu-panel">
        @include('admin.settings.partials.sidebar', [
            'groups' => $groups,
            'currentGroup' => $currentGroup
        ])
    </aside>

    <main class="settings-content-panel">
        @if(view()->exists($viewPath))
            @include($viewPath, [
                'settings' => $settings,
                'group' => $currentGroup
            ])
        @else
            <div class="alert alert-warning">
                View not found: {{ $viewPath }}
            </div>
        @endif
    </main>
</div>

@include('admin.settings.partials.toasts')
@endsection

@push('styles')
<link href="{{ asset('assets/css/settings.css') }}" rel="stylesheet">
@endpush

@push('admin-scripts')
<script src="{{ asset('assets/js/settings/setting.js') }}"></script>

@if($currentGroup === 'display')
<script src="{{ asset('assets/js/settings/setting-display.js') }}"></script>
@endif

@if($currentGroup === 'rating')
<script src="{{ asset('assets/js/settings/settings-rating.js') }}"></script>
@endif

@if($currentGroup === 'performance')
<script src="{{ asset('assets/js/settings/settings-performance.js') }}"></script>
@endif

@if($currentGroup === 'report')
<script src="{{ asset('assets/js/settings/settings-report.js') }}"></script>
@endif

@if($currentGroup === 'visitor')
<script src="{{ asset('assets/js/settings/setting-visitor.js') }}"></script>
@endif
@endpush