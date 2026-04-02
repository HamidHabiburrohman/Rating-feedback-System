@php
$groupTitles = [
    'general' => 'General Settings',
    'rating' => 'Rating Settings',
    'unit' => 'Unit Settings',
    'visitor' => 'Visitor Settings',
    'report' => 'Report Settings',
    'audit' => 'Audit Log Settings',
    'notification' => 'Notification Settings',
    'security' => 'Security Settings',
    'performance' => 'Performance Settings',
    'api' => 'API Settings',
    'display' => 'Display Settings',
];

$groupIcons = [
    'general' => 'M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z',
    'rating' => 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
    'unit' => 'M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z',
    'visitor' => 'M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75',
    'report' => 'M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z M14 2v6h6 M16 13H8 M16 17H8 M10 9H8',
    'audit' => 'M3 6l9 4 9-4M3 6v13l9 4 9-4V6M12 13V22',
    'notification' => 'M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9 M13.73 21a2 2 0 0 1-3.46 0',
    'security' => 'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z',
    'performance' => 'M13 2L3 14h9l-1 8 10-12h-9l1-8z',
    'api' => 'M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2 M7 11l5 5 5-5 M12 4v12',
    'display' => 'M2 12h20M5 12v7M19 12v7M5 5h14v2H5z',
];

$group = $group ?? 'general';
$title = $groupTitles[$group] ?? ucfirst($group) . ' Settings';
$iconPath = $groupIcons[$group] ?? 'M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z';
@endphp

<div class="settings-group-header">
    <div class="group-title-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="{{ $iconPath }}"/>
        </svg>
    </div>
    <div>
        <h2 class="group-title">{{ $title }}</h2>
    </div>
</div>