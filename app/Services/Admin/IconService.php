<?php

namespace App\Services\Admin;

use Illuminate\Support\Facades\Cache;

class IconService
{
    /**
     * Daftar semua SVG icons yang tersedia di sistem
     * @var array<string, array{name: string, svg: string}>
     */
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
            'name' => 'Parkir',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 7v10"/><path d="M9 7h4a3 3 0 0 1 0 6H9"/></svg>'
        ],
        'air-conditioner' => [
            'name' => 'AC',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="6" width="16" height="12" rx="2"/><path d="M8 10h8"/><path d="M12 6v12"/><path d="M6 12h12"/></svg>'
        ],
        'wifi' => [
            'name' => 'WiFi',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 13.5a7.5 7.5 0 0 1 14 0"/><path d="M8 16.5a4.5 4.5 0 0 1 8 0"/><line x1="12" y1="20" x2="12" y2="20"/></svg>'
        ],
        'projector' => [
            'name' => 'Proyektor',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="8" width="16" height="10" rx="2"/><path d="M8 18v2"/><path d="M16 18v2"/><line x1="12" y1="8" x2="12" y2="4"/><circle cx="12" cy="13" r="1.5"/></svg>'
        ],
        'whiteboard' => [
            'name' => 'Whiteboard',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M7 5v14"/><line x1="12" y1="5" x2="12" y2="19"/><path d="M3 12h18"/></svg>'
        ],
        'printer' => [
            'name' => 'Printer',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="12" height="4" rx="1"/><rect x="4" y="8" width="16" height="12" rx="1"/><path d="M8 12h8"/><path d="M8 16h4"/><path d="M18 8v8"/><path d="M6 8v8"/></svg>'
        ],
        'mosque' => [
            'name' => 'Musala',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.48 3.63a2 2 0 0 0-2.96 0L9.04 9.65c-1.17 1.29-.26 3.35 1.48 3.35h10.96c1.74 0 2.65-2.06 1.48-3.35l-5.48-6.03zM4 28V16c0-1.1.9-2 2-2h20c1.1 0 2 .9 2 2v12M12 28v-6c0-1.1.9-2 2-2h4c1.1 0 2 .9 2 2v6"/><circle cx="16" cy="9" r="2" fill="currentColor"/></svg>'
        ],
        'toilet' => [
            'name' => 'Toilet',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="2"/><path d="M12 12v6"/><path d="M8 8v2a4 4 0 0 0 8 0V8"/><path d="M6 4v4a6 6 0 0 0 12 0V4"/><rect x="5" y="18" width="14" height="2" rx="1"/></svg>'
        ],
        'cafe' => [
            'name' => 'Kantin',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/><path d="M12 12v3"/><path d="M9 13.5l3 1.5 3-1.5"/></svg>'
        ],
        'wheelchair' => [
            'name' => 'Akses Kursi Roda',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="6" r="2"/><path d="M9 8v6"/><path d="M9 14l-3 4"/><path d="M12 12h3l2 3"/><circle cx="16" cy="18" r="2"/><path d="M16 16v4"/><path d="M6 20h12"/></svg>'
        ],
        'waiting-room' => [
            'name' => 'Ruang Tunggu',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><line x1="7" y1="9" x2="17" y2="9"/><line x1="7" y1="13" x2="13" y2="13"/><circle cx="17" cy="13" r="1.5"/><line x1="7" y1="17" x2="17" y2="17"/></svg>'
        ],
        'water-dispenser' => [
            'name' => 'Air Minum',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4"/><path d="M12 6c-2.5 0-4.5 1.5-4.5 4s2 4 4.5 4 4.5-1.5 4.5-4-2-4-4.5-4z"/><path d="M12 14v8"/><path d="M9 22h6"/></svg>'
        ],
        'locker' => [
            'name' => 'Loker',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="9" x2="15" y2="9"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="12" y2="17"/><path d="M9 3v18"/></svg>'
        ],
        'speaker' => [
            'name' => 'Sound System',
            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2"/><circle cx="12" cy="10" r="3"/><path d="M12 13v4"/><path d="M9 18h6"/><line x1="8" y1="6" x2="16" y2="6"/><circle cx="12" cy="17" r="1"/></svg>'
        ],
    ];

    /**
     * Default SVG ketika icon tidak ditemukan
     */
    protected string $defaultSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>';

    /**
     * Mengambil semua icons
     * @return array<string, array{name: string, svg: string}>
     */
    public function getAllIcons(): array
    {
        return Cache::remember('all_icons', 86400, fn() => $this->icons);
    }

    /**
     * Mengambil icon by key
     * @return array{name: string, svg: string}|null
     */
    public function getIcon(string $key): ?array
    {
        return $this->icons[$key] ?? null;
    }

    /**
     * Render SVG dengan custom attributes (class, width, height, dll)
     */
    public function getSvg(string $key, array $attributes = []): string
    {
        if (!$this->isValidIcon($key)) {
            return $this->renderSvg($this->defaultSvg, $attributes);
        }

        return $this->renderSvg($this->icons[$key]['svg'], $attributes);
    }

    /**
     * Mendapatkan info icon lengkap dengan fallback ke default
     * @return array{key: string, name: string, svg: string}
     */
    public function getIconKey(string $key): array
    {
        if (!$this->isValidIcon($key)) {
            return [
                'key' => $key,
                'name' => ucfirst($key ?: 'Default'),
                'svg' => $this->renderSvg($this->defaultSvg, ['class' => 'w-6 h-6']),
            ];
        }

        return [
            'key' => $key,
            'name' => $this->icons[$key]['name'],
            'svg' => $this->renderSvg($this->icons[$key]['svg'], ['class' => 'w-6 h-6']),
        ];
    }

    /**
     * Mengambil options untuk dropdown (key => name)
     * @return array<string, string>
     */
    public function getIconOptions(): array
    {
        return Cache::remember('icon_options', 86400, function () {
            $options = [];
            foreach ($this->icons as $key => $icon) {
                $options[$key] = $icon['name'];
            }
            return $options;
        });
    }

    /**
     * Mengambil data preview untuk UI (digunakan di form edit)
     * @return array<string, array{name: string, svg: string}>
     */
    public function getIconPreviews(): array
    {
        return Cache::remember('icon_previews', 86400, function () {
            $previews = [];
            foreach ($this->icons as $key => $icon) {
                $previews[$key] = [
                    'name' => $icon['name'],
                    'svg' => $this->renderSvg($icon['svg'], ['class' => 'w-6 h-6']),
                ];
            }
            return $previews;
        });
    }

    /**
     * Validasi apakah icon key valid
     */
    public function isValidIcon(string $key): bool
    {
        return !empty($key) && isset($this->icons[$key]);
    }

    /**
     * Mendapatkan total icon yang tersedia
     */
    public function getTotalIcons(): int
    {
        return count($this->icons);
    }

    /**
     * Mendapatkan nama icon by key
     */
    public function getIconName(string $key): string
    {
        return $this->icons[$key]['name'] ?? ucfirst($key ?: 'Unknown');
    }

    /**
     * Render SVG dengan inject custom attributes
     */
    protected function renderSvg(string $svg, array $attributes): string
    {
        if (empty($attributes)) {
            return $svg;
        }

        // Default class jika tidak diset
        if (!isset($attributes['class'])) {
            $attributes['class'] = 'w-6 h-6';
        }

        // Build attribute string
        $attrString = '';
        foreach ($attributes as $attr => $value) {
            $attrString .= ' ' . $attr . '="' . htmlspecialchars($value, ENT_QUOTES) . '"';
        }

        // Hapus class existing dari SVG dan replace dengan yang baru
        $svg = preg_replace('/\s+class="[^"]*"/', '', $svg);

        // Inject attribute ke tag <svg>
        return preg_replace('/<svg\b/', '<svg' . $attrString, $svg, 1);
    }

    /**
     * Clear semua cache icons (dipanggil saat ada perubahan)
     */
    public function clearCache(): void
    {
        Cache::forget('all_icons');
        Cache::forget('icon_options');
        Cache::forget('icon_previews');
    }
}