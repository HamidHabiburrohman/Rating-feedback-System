<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MessagesSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user berdasarkan role field (bukan relation roles())
        $admin = User::where('role', 'admin')->orWhere('role', 'super_admin')->first();
        
        // Ambil user dengan role 'unit' (jika ada field role='unit')
        // Tapi di migration kamu cuma ada 'admin' dan 'super_admin'
        // Jadi kita perlu create dummy unit user
        
        $units = Unit::all();

        // Jika tidak ada user admin, buat dummy
        if (!$admin) {
            $admin = User::create([
                'nama' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
                'role' => 'admin'
            ]);
        }

        // Buat dummy unit user jika tidak ada
        $unitUsers = User::where('role', 'unit')->get();
        
        if ($unitUsers->isEmpty()) {
            // Create 3 dummy unit users
            $unitUsers = User::factory(3)->create([
                'role' => 'unit'
            ]);
        }

        if ($units->isEmpty()) {
            $this->command->warn('⚠️  Data unit tidak lengkap untuk MessageSeeder!');
            return;
        }

        $messages = [];
        $now = now();

        // Kategori dengan contoh pesan
        $kategoriExamples = [
            'technical' => [
                'judul' => ['Masalah AC tidak dingin', 'Internet lambat', 'Printer rusak', 'Lampu mati', 'Toilet tersumbat'],
                'pesan' => [
                    'AC di ruangan sudah tidak dingin sejak kemarin, suhu mencapai 30°C.',
                    'Koneksi internet sangat lambat, tidak bisa akses sistem.',
                    'Printer di lantai 3 tidak bisa mencetak, muncul error paper jam.',
                    'Lampu di koridor lantai 2 mati total, mohon diperbaiki.',
                    'Toilet di gedung A lantai 1 tersumbat, air tidak bisa turun.'
                ]
            ],
            'status_request' => [
                'judul' => ['Request ubah status ke FULL', 'Kapasitas sudah penuh', 'Mohon ubah status', 'Unit sudah mencapai kapasitas'],
                'pesan' => [
                    'Kapasitas unit sudah mencapai 100%, mohon ubah status menjadi FULL.',
                    'Unit sudah tidak bisa menerima pengunjung lagi, status penuh.',
                    'Request perubahan status dari OPEN ke FULL karena antrian panjang.',
                    'Kapasitas maksimal sudah tercapai, tolong update status unit.'
                ]
            ],
            'maintenance' => [
                'judul' => ['Jadwal maintenance AC', 'Perbaikan jaringan', 'Maintenance server', 'Renovasi ruangan'],
                'pesan' => [
                    'Akan ada maintenance AC pada hari Jumat jam 8-12.',
                    'Perbaikan jaringan internet hari Sabtu, mohon maklum.',
                    'Maintenance server database jam 00:00-04:00 dini hari.',
                    'Renovasi ruangan meeting 301 selama 3 hari.'
                ]
            ],
            'announcement' => [
                'judul' => ['Pengumuman meeting', 'Info libur nasional', 'Acara kampus', 'Perubahan jam operasional'],
                'pesan' => [
                    'Meeting seluruh unit hari Jumat jam 10:00 di auditorium.',
                    'Mengingatkan besok libur nasional, unit tutup.',
                    'Ada acara kampus besar-besaran minggu depan.',
                    'Perubahan jam operasional mulai besok: 09:00-18:00.'
                ]
            ],
            'instruction' => [
                'judul' => ['Instruksi kebersihan', 'Prosedur baru', 'Panduan penggunaan', 'SOP terbaru'],
                'pesan' => [
                    'Mohon perhatikan kebersihan ruangan setiap hari.',
                    'Prosedur baru untuk pelaporan inventaris.',
                    'Panduan penggunaan mesin fotokopi yang benar.',
                    'SOP terbaru untuk penanganan pengunjung.'
                ]
            ],
            'question' => [
                'judul' => ['Tanya jadwal', 'Konfirmasi anggaran', 'Clarifikasi SOP', 'Tentang training'],
                'pesan' => [
                    'Kapan jadwal training untuk staff baru?',
                    'Boleh konfirmasi anggaran untuk perbaikan?',
                    'Minta klarifikasi tentang SOP terbaru.',
                    'Ada training software baru bulan depan?'
                ]
            ],
            'rating_feedback' => [
                'judul' => ['Feedback rating rendah', 'Komentar pengunjung', 'Saran perbaikan', 'Review pelayanan'],
                'pesan' => [
                    'Rating unit turun karena pelayanan lambat, mohon diperbaiki.',
                    'Ada komentar dari pengunjung tentang kebersihan.',
                    'Saran dari visitor: tambah kursi tunggu.',
                    'Review: staff kurang ramah, perlu training ulang.'
                ]
            ],
            'emergency' => [
                'judul' => ['KEBAKARAN!', 'KEBOCORAN GAS', 'KECELAKAAN', 'KEADAAN DARURAT'],
                'pesan' => [
                    'ADA ASAP DI GEDUNG B! SEGERA EVAKUASI!',
                    'TERDETEKSI BAU GAS DI DAPUR, MOHON BANTUAN!',
                    'KECELAKAAN DI TANGGA, KORBUTAN TERLUKA!',
                    'KEADAAN DARURAT: LISTRIK PADAM TOTAL!'
                ]
            ]
        ];

        // Prioritas mapping
        $prioritasByKategori = [
            'emergency' => 'sangat_penting',
            'technical' => 'penting',
            'status_request' => 'penting',
            'rating_feedback' => 'biasa',
            'maintenance' => 'biasa',
            'announcement' => 'biasa',
            'instruction' => 'biasa',
            'question' => 'biasa'
        ];

        // Generate 30 messages (lebih sedikit untuk testing)
        for ($i = 1; $i <= 30; $i++) {
            $isFromAdmin = $i % 3 !== 0; // 66% dari admin, 33% dari unit
            $kategori = array_rand($kategoriExamples);
            $unit = $units->random();
            $unitUser = $unitUsers->random();

            $judulList = $kategoriExamples[$kategori]['judul'];
            $pesanList = $kategoriExamples[$kategori]['pesan'];

            $statusOptions = ['terkirim', 'diterima', 'dibaca', 'ditanggapi', 'selesai'];
            $statusWeights = [20, 20, 20, 20, 20];
            $status = $this->weightedRandom($statusOptions, $statusWeights);

            // Tentukan perlu_tindakan berdasarkan kategori
            $perluTindakan = in_array($kategori, ['technical', 'status_request', 'question', 'emergency']);

            // Data tindakan khusus untuk status_request
            $dataTindakan = null;
            if ($kategori === 'status_request') {
                $statusOptions = ['open', 'full', 'maintenance', 'closed'];
                $requestedStatus = $statusOptions[array_rand($statusOptions)];
                
                $dataTindakan = [
                    'unit_id' => $unit->id,
                    'unit_name' => $unit->nama_unit,
                    'current_status' => $unit->status,
                    'requested_status' => $requestedStatus,
                    'reason' => 'Request dari unit'
                ];
            }

            // Timestamps
            $createdAt = $now->copy()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $dibacaPada = null;
            $tindakanDiambilPada = null;

            if (in_array($status, ['dibaca', 'ditanggapi', 'selesai'])) {
                $dibacaPada = $createdAt->copy()->addMinutes(rand(5, 120));
            }

            if ($status === 'selesai') {
                $tindakanDiambilPada = $dibacaPada ? $dibacaPada->copy()->addMinutes(rand(10, 60)) : $createdAt->copy()->addHours(rand(1, 24));
            }

            $messages[] = [
                'pengirim_tipe' => $isFromAdmin ? 'admin' : 'unit',
                'pengirim_id' => $isFromAdmin ? $admin->id : $unitUser->id,
                'penerima_tipe' => $isFromAdmin ? 'unit' : 'admin',
                'penerima_id' => $isFromAdmin ? $unitUser->id : $admin->id,
                'unit_id' => $unit->id,
                'judul' => $judulList[array_rand($judulList)] . ($i > 1 ? " #$i" : ''),
                'pesan' => $pesanList[array_rand($pesanList)],
                'kategori' => $kategori,
                'prioritas' => $prioritasByKategori[$kategori],
                'status' => $status,
                'perlu_tindakan' => $perluTindakan,
                'tipe_tindakan' => $perluTindakan ? $this->getTipeTindakan($kategori) : null,
                'data_tindakan' => $dataTindakan ? json_encode($dataTindakan) : null,
                'dibaca_pada' => $dibacaPada,
                'tindakan_diambil_pada' => $tindakanDiambilPada,
                'created_at' => $createdAt,
                'updated_at' => $tindakanDiambilPada ?? $dibacaPada ?? $createdAt,
            ];
        }

        // Insert messages in batches
        foreach (array_chunk($messages, 10) as $chunk) {
            Message::insert($chunk);
        }

        $this->command->info('✅ ' . count($messages) . ' pesan berhasil di-seed!');
        $this->command->info('📊 Statistik:');
        $this->command->info('   - Dari Admin: ' . count(array_filter($messages, fn($m) => $m['pengirim_tipe'] === 'admin')));
        $this->command->info('   - Dari Unit: ' . count(array_filter($messages, fn($m) => $m['pengirim_tipe'] === 'unit')));
        $this->command->info('   - Perlu Tindakan: ' . count(array_filter($messages, fn($m) => $m['perlu_tindakan'])));
    }

    private function weightedRandom($items, $weights)
    {
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $current = 0;

        foreach ($items as $index => $item) {
            $current += $weights[$index];
            if ($rand <= $current) {
                return $item;
            }
        }

        return $items[0];
    }

    private function getTipeTindakan($kategori)
    {
        $mapping = [
            'technical' => 'technical_support',
            'status_request' => 'update_unit_status',
            'question' => 'provide_information',
            'emergency' => 'emergency_response',
            'rating_feedback' => 'quality_improvement'
        ];

        return $mapping[$kategori] ?? 'general_action';
    }
}