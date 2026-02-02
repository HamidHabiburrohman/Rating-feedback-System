<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportSeeder extends Seeder
{
    public function run()
    {
        $units = Unit::pluck('id')->toArray();
        $admins = User::where('role', 'admin')->pluck('id')->toArray();
        $statuses = ['baru', 'diproses', 'selesai', 'ditolak'];
        $types = ['masalah', 'saran', 'keluhan', 'pujian', 'lainnya'];
        $priorities = ['rendah', 'sedang', 'tinggi', 'kritis'];
        
        $titles = [
            'Permasalahan AC tidak dingin',
            'Kebersihan ruangan kurang terjaga',
            'Saran untuk penambahan fasilitas',
            'Keluhan tentang keramahan staff',
            'Pujian untuk pelayanan yang baik',
            'Permasalahan jaringan WiFi',
            'Kursi rusak di ruang meeting',
            'Saran improvement sistem booking',
            'Keluhan tentang kebisingan',
            'Pujian untuk kebersihan toilet',
            'Lampu corridor mati',
            'Saran penambahan signage',
            'Keluhan responsifitas tim IT',
            'Pujian untuk kecepatan service',
            'Permasalahan projector tidak nyala',
            'Saran untuk menu kantin',
            'Keluhan tentang parking space',
            'Pujian untuk keamanan gedung',
            'Toilet tersumbat',
            'Saran penambahan tanaman'
        ];

        $descriptions = [
            'Sudah beberapa hari AC tidak berfungsi maksimal',
            'Lantai terlihat kotor dan berdebu',
            'Akan lebih baik jika ditambah coffee machine',
            'Staff terlihat kurang ramah saat melayani',
            'Pelayanan sangat memuaskan dan cepat tanggap',
            'Sinyal WiFi lemah di area lobby',
            'Kursi nomor 5 sudah rusak dan perlu diperbaiki',
            'Sistem booking online akan lebih efisien',
            'Suara bising dari ruang sebelah mengganggu',
            'Toilet selalu bersih dan wangi',
            'Lampu di corridor lantai 3 mati total',
            'Tambah signage untuk ruang meeting',
            'Tim IT lambat merespon trouble ticket',
            'Service team sangat responsif dan profesional',
            'Projector tidak bisa connect ke laptop',
            'Variasi menu kantin perlu ditambah',
            'Parkir penuh saat jam sibuk',
            'Security sangat teliti dan ramah',
            'Toilet lantai 2 tersumbat parah',
            'Tambahkan tanaman untuk suasana segar'
        ];

        for ($i = 1; $i <= 50; $i++) {
            $status = $statuses[array_rand($statuses)];
            $isResponded = in_array($status, ['diproses', 'selesai', 'ditolak']);
            
            Report::create([
                'unit_id' => !empty($units) ? $units[array_rand($units)] : null,
                'session_id' => Str::random(20),
                'visitor_ip' => $this->generateFakeIP(),
                'judul' => $titles[array_rand($titles)] . ' #' . $i,
                'deskripsi' => $descriptions[array_rand($descriptions)],
                'tipe' => $types[array_rand($types)],
                'prioritas' => $priorities[array_rand($priorities)],
                'status' => $status,
                'admin_id' => $isResponded && !empty($admins) ? $admins[array_rand($admins)] : null,
                'tanggapan_admin' => $isResponded ? 'Terima kasih atas laporannya. Tim kami sedang menanganinya.' : null,
                'ditanggapi_pada' => $isResponded ? now()->subDays(rand(1, 30)) : null,
                'lampiran' => rand(0, 1) ? ['file1.jpg', 'file2.pdf'] : null,
                'created_at' => now()->subDays(rand(0, 90)),
                'updated_at' => now()->subDays(rand(0, 90))
            ]);
        }
    }

    private function generateFakeIP()
    {
        return rand(100, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
    }
}