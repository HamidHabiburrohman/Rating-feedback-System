@php
$subgroupTitles = [
    'basic' => 'Basic Information',
    'localization' => 'Localization',
    'scale' => 'Rating Scale',
    'calculation' => 'Calculation',
    'submission' => 'Submission Rules',
    'session' => 'Session Settings',
    'privacy' => 'Privacy & Data',
    'limits' => 'Limits & Restrictions',
    'generation' => 'Report Generation',
    'retention' => 'Data Retention',
    'format' => 'Format & Export',
    'authentication' => 'Authentication',
    'email' => 'Email Settings',
    'channels' => 'Notification Channels',
    'alert' => 'Alerts & Thresholds',
];

$subgroupIcons = [
    'basic' => 'M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z',
    'localization' => 'M21 12a9 9 0 1 1-9-9c2.52 0 4.93 1 6.74 2.74L21 8M21 3v5h-5',
    'scale' => 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
    'calculation' => 'M3 3h18v18H3zM3 21L21 3',
    'session' => 'M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z',
    'privacy' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
    'email' => 'M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z M22 6l-10 7L2 6',
];

$subgroup = $subgroup ?? null;
$count = $count ?? 0;
$hasSubgroup = !empty($subgroup) && is_string($subgroup);
$subgroupTitle = $hasSubgroup && isset($subgroupTitles[$subgroup]) ? $subgroupTitles[$subgroup] : ($hasSubgroup ? ucfirst($subgroup) : '');
$iconPath = $hasSubgroup && isset($subgroupIcons[$subgroup]) ? $subgroupIcons[$subgroup] : 'M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z';
@endphp

@if($hasSubgroup && $count > 0)
    <div class="subgroup-header">
        <div class="subgroup-title-wrapper">
            <span class="subgroup-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="{{ $iconPath }}"/>
                </svg>
            </span>
            <h3 class="subgroup-title">{{ $subgroupTitle }}</h3>
        </div>
        <span class="subgroup-badge">{{ $count }} {{ $count === 1 ? 'setting' : 'settings' }}</span>
    </div>
@endif