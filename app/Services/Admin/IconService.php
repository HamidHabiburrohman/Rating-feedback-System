<?php

namespace App\Services\Admin;

class IconService
{
    protected array $icons = [
        'flask' => [
            'name' => 'Lab',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3L15 3"/><path d="M10 9L14 9"/><path d="M6 12L18 12"/><path d="M8 21L16 21"/><path d="M9 3L9 9"/><path d="M15 3L15 9"/><path d="M5 12C5 12 6 15 7 18C8 21 12 21 12 21"/><path d="M19 12C19 12 18 15 17 18C16 21 12 21 12 21"/></svg>'
        ],
        'book-open' => [
            'name' => 'Perpustakaan',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>'
        ],
        'heart-pulse' => [
            'name' => 'Klinik',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M12 12l-2-2-2 2 2 2 2-2z"/></svg>'
        ],
        'presentation' => [
            'name' => 'Ruang Kelas',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2" ry="2"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="12" y2="14"/></svg>'
        ],
        'theater' => [
            'name' => 'Auditorium',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 10s3-3 6-3 6 3 6 3"/><path d="M22 10s-3-3-6-3-6 3-6 3"/><path d="M2 14s3 3 6 3 6-3 6-3"/><path d="M22 14s-3 3-6 3-6-3-6-3"/><circle cx="12" cy="10" r="2"/><circle cx="12" cy="14" r="2"/></svg>'
        ],
        'coffee' => [
            'name' => 'Cafetaria',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>'
        ],
        'dumbbell' => [
            'name' => 'Sports Center',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 5 3 3-3 3"/><path d="m6 19-3-3 3-3"/><path d="m6 5 3 3-3 3"/><path d="m18 19-3-3 3-3"/><path d="M9 8h6"/><path d="M9 16h6"/><path d="M6 12h12"/><path d="M9 5v14"/><path d="M15 5v14"/></svg>'
        ],
        'building' => [
            'name' => 'Gedung',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="16" y2="14"/><line x1="8" y1="18" x2="12" y2="18"/></svg>'
        ],
        'sofa' => [
            'name' => 'Student Lounge',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 9V6a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v3"/><path d="M2 16h20"/><path d="M4 12v4h16v-4a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2Z"/></svg>'
        ],
        'computer' => [
            'name' => 'Computer Lab',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>'
        ],
        'music' => [
            'name' => 'Music Room',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>'
        ],
        'parking' => [
            'name' => 'Parking',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 7v10"/><path d="M9 7h4a3 3 0 0 1 0 6H9"/></svg>'
        ],
    ];

    public function getAllIcons(): array
    {
        return $this->icons;
    }

    public function getIcon(string $key): ?array
    {
        return $this->icons[$key] ?? null;
    }

    public function getSvg(string $key, array $attributes = []): string
    {
        if (empty($key) || !isset($this->icons[$key])) {
            return $this->getDefaultSvg($attributes);
        }

        $svg = $this->icons[$key]['svg'];

        if (!empty($attributes['class'])) {
            $svg = preg_replace('/<svg/', '<svg class="' . $attributes['class'] . '"', $svg);
        }

        if (!empty($attributes['style'])) {
            $svg = preg_replace('/<svg/', '<svg style="' . $attributes['style'] . '"', $svg);
        }

        if (!empty($attributes['width'])) {
            $svg = preg_replace('/<svg/', '<svg width="' . $attributes['width'] . '"', $svg);
        }

        if (!empty($attributes['height'])) {
            $svg = preg_replace('/<svg/', '<svg height="' . $attributes['height'] . '"', $svg);
        }

        return $svg;
    }

    public function getIconKey(string $key): array
    {
        if (empty($key) || !isset($this->icons[$key])) {
            return [
                'key' => $key,
                'name' => ucfirst($key ?? 'Default'),
                'svg' => $this->getDefaultSvg()
            ];
        }

        return [
            'key' => $key,
            'name' => $this->icons[$key]['name'] ?? ucfirst($key),
            'svg' => $this->getSvg($key)
        ];
    }

    public function getIconOptions(): array
    {
        $options = [];
        foreach ($this->icons as $key => $icon) {
            $options[$key] = $icon['name'];
        }
        return $options;
    }

    public function getIconPreviews(): array
    {
        $previews = [];
        foreach ($this->icons as $key => $icon) {
            $previews[$key] = [
                'name' => $icon['name'],
                'svg' => $this->getSvg($key, ['class' => 'w-6 h-6'])
            ];
        }
        return $previews;
    }

    protected function getDefaultSvg(array $attributes = []): string
    {
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>';

        if (!empty($attributes['class'])) {
            $svg = preg_replace('/<svg/', '<svg class="' . $attributes['class'] . '"', $svg);
        }

        return $svg;
    }

    public function isValidIcon(string $key): bool
    {
        return !empty($key) && isset($this->icons[$key]);
    }
}