<div class="setting-group {{ $subgroup ? 'has-subgroup' : '' }}" 
     data-subgroup="{{ $subgroup ?? 'general' }}">
    
    @if($subgroup)
        @include('admin.settings.partials.header.subgroup-header', [
            'subgroup' => $subgroup,
            'count' => count($settings)
        ])
    @endif
    
    <div class="row g-4">
        @foreach($settings as $setting)
            @if($setting->is_visible)
                @include('admin.settings.partials.forms.setting-card', ['setting' => $setting])
            @endif
        @endforeach
    </div>
</div>