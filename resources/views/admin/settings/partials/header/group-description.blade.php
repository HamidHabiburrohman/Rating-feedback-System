@php
$descriptions = [
    'general' => 'These settings control the basic behavior and appearance of your application.',
    'rating' => 'Configure how ratings are collected, calculated, and displayed throughout the system.',
    'unit' => 'Manage unit categories, visibility, and rating collection preferences.',
    'visitor' => 'Control visitor session handling, data collection, and privacy settings.',
    'report' => 'Set up automated reporting, formats, and data retention policies.',
    'audit' => 'Configure what system activities are logged and how long audit trails are kept.',
    'notification' => 'Manage email, push, and system notification preferences.',
    'security' => 'Configure authentication, password policies, and security features.',
    'performance' => 'Optimize system performance with caching, logging, and optimization settings.',
    'api' => 'Control API access, rate limits, and token management.',
    'display' => 'Customize the look and feel of your application interface.',
];

$group = $group ?? null;
$description = isset($descriptions[$group]) ? $descriptions[$group] : null;
@endphp

@if($description)
    <div class="settings-group-description">
        <i class="bi bi-info-circle"></i>
        <span>{{ $description }}</span>
    </div>
@endif