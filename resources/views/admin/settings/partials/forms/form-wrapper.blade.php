<form id="form-{{ $group }}" class="setting-form" data-group="{{ $group }}">
    @csrf
    @method('PUT')

    @include('admin.settings.partials.header.group-header', ['group' => $group])
    @include('admin.settings.partials.header.group-description', ['group' => $group])

    @php
        $subgroups = $settings->groupBy('subgroup');
    @endphp

    @foreach($subgroups as $subgroup => $subgroupSettings)
        @php
            $subgroupName = $subgroup;
            $subgroupCount = $subgroupSettings->count();
        @endphp

        @if($subgroupCount > 0 && !empty($subgroupName))
            @include('admin.settings.partials.header.subgroup-header', [
                'subgroup' => $subgroupName,
                'count' => $subgroupCount
            ])
        @endif

        @foreach($subgroupSettings as $setting)
            @if($setting->is_visible)
                @include('admin.settings.partials.forms.setting-card', ['setting' => $setting])
            @endif
        @endforeach
    @endforeach

    @hasSection('additional-content')
        <div class="additional-content">
            @yield('additional-content')
        </div>
    @endif

    @include('admin.settings.partials.forms.actions', ['group' => $group])
</form>