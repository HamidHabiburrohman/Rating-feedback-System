@php
    use App\Models\Settings;
@endphp

@if($settings->isEmpty())
    @include('admin.settings.partials.content.empty-state', [
        'title' => 'No API Settings Available',
        'message' => 'There are no API settings configured.',
    ])
@else
    @include('admin.settings.partials.forms.form-wrapper', [
        'settings' => $settings,
        'group' => 'api'
    ])
@endif

@section('additional-content')
    @if(isset($settings) && $settings->count() > 0)
<div class="mb-6">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="h5 fw-semibold mb-0" style="color: #374151;">API Access Control</h3>
        <span class="badge rounded-pill px-3 py-1"
              style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">
            {{ $settings->count() }} settings
        </span>
    </div>

    <div class="card border rounded-4">
        <div class="card-body p-4">
            <div class="row g-4">
                @foreach($settings as $setting)
                    @if($setting->is_visible)
                        @include('admin.settings.partials.forms.setting-card', ['setting' => $setting])
                    @endif
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-top">
                <h6 class="fw-semibold mb-3">API Tokens</h6>
                <div class="alert alert-info rounded-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-medium">
                            Manage API tokens in the
                            <a href="" class="alert-link">
                                API Tokens section
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if(isset($settings) && $settings->count() > 0)
<div class="mb-6">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="h5 fw-semibold mb-0" style="color: #374151;">Rate Limiting</h3>
        <span class="badge rounded-pill px-3 py-1"
              style="background: #f1f5f9; color: #64748b; font-size: 0.75rem;">
            {{ setting('api_rate_limit', 60) }} req/min
        </span>
    </div>

    <div class="card border rounded-4">
        <div class="card-body p-4">

            <div class="row g-4">
                @foreach($settings as $setting)
                    @if($setting->is_visible)
                        @include('admin.settings.partials.forms.setting-card', ['setting' => $setting])
                    @endif
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-top">
                <h6 class="fw-semibold mb-3">Current Limits</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="bg-light rounded-3 p-3 text-center">
                            <div class="fw-bold fs-4 text-danger">
                                {{ setting('api_rate_limit', 60) }}
                            </div>
                            <div class="small text-muted">Requests per minute</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light rounded-3 p-3 text-center">
                            <div class="fw-bold fs-4 text-success">
                                {{ setting('api_daily_limit', 1000) }}
                            </div>
                            <div class="small text-muted">Daily limit</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="bg-light rounded-3 p-3 text-center">
                            <div class="fw-bold fs-4 text-primary">
                                {{ setting('api_burst_limit', 10) }}
                            </div>
                            <div class="small text-muted">Burst limit</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endif

<div class="mb-6">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="h5 fw-semibold mb-0" style="color: #374151;">API Documentation</h3>
        <a href="" class="btn btn-sm btn-outline-primary rounded-pill px-3">
            Full Documentation
        </a>
    </div>

    <div class="card border rounded-4">
        <div class="card-body p-4">

            <h6 class="fw-semibold mb-3">Base URL</h6>
            <div class="bg-light rounded-3 p-3 mb-3">
                <code class="text-primary">{{ config('app.url') }}/api/v1</code>
            </div>

            <h6 class="fw-semibold mb-3 mt-4">Authentication</h6>
            <div class="bg-light rounded-3 p-3">
                <code class="text-primary">
                    Authorization: Bearer {your_api_token}
                </code>
            </div>

        </div>
    </div>
</div>

@endsection
