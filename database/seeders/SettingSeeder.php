<?php

namespace Database\Seeders;

use App\Models\System\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'Rating Units ITN', 'type' => 'string', 'group' => 'general', 'label' => 'Nama Aplikasi'],
            ['key' => 'app_timezone', 'value' => 'Asia/Jakarta', 'type' => 'string', 'group' => 'general', 'label' => 'Zona Waktu'],
            ['key' => 'max_rating_attachments', 'value' => '3', 'type' => 'integer', 'group' => 'rating', 'label' => 'Maksimal Lampiran Rating'],
            ['key' => 'max_report_attachments', 'value' => '3', 'type' => 'integer', 'group' => 'report', 'label' => 'Maksimal Lampiran Report'],
            ['key' => 'max_file_size', 'value' => '5242880', 'type' => 'integer', 'group' => 'upload', 'label' => 'Ukuran Maksimal File'],
            ['key' => 'gps_validation_radius', 'value' => '100', 'type' => 'integer', 'group' => 'qr', 'label' => 'Radius Validasi GPS'],
            ['key' => 'rate_limit_login', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'label' => 'Batas Percobaan Login'],
            ['key' => 'rate_limit_qr', 'value' => '10', 'type' => 'integer', 'group' => 'security', 'label' => 'Batas Validasi QR per Menit'],
            ['key' => 'rate_limit_rating', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'label' => 'Batas Submit Rating per Menit'],
            ['key' => 'rate_limit_report', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'label' => 'Batas Submit Report per Menit'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}