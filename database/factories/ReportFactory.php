<?php

namespace Database\Factories;

use App\Models\Rating;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $rating = Rating::inRandomOrder()->first() ?? Rating::factory();
        $status = fake()->randomElement(['new', 'in_progress', 'replied', 'resolved', 'rejected']);
        $adminDitugaskan = null;
        $waktuDitanggapi = null;
        $tanggapanAdmin = null;
        
        if (!in_array($status, ['new'])) {
            $adminDitugaskan = User::whereIn('role', ['admin', 'super_admin'])->inRandomOrder()->first()?->id;
        }
        
        $waktuDibuat = fake()->dateTimeBetween('-3 months', 'now');
        
        if (in_array($status, ['replied', 'resolved'])) {
            $waktuDitanggapi = fake()->dateTimeBetween($waktuDibuat, 'now');
        }
        
        $judulLaporan = fake()->randomElement([
            'Komentar Tidak Pantas pada Rating',
            'Mahasiswa Memberikan Informasi Palsu',
            'Konten Mengandung Ujaran Kebencian',
            'Rating Tidak Sesuai dengan Kondisi Sebenarnya',
            'Mahasiswa Melakukan Spam Rating',
            'Komentar Mengandung SARA',
            'Mahasiswa Mengganggu Pengguna Lain',
            'Konten Bersifat Promosi/ Iklan',
            'Penggunaan Bahasa Kasar dalam Komentar',
            'Rating Diberikan Tanpa Dasar yang Jelas',
            'Komentar Menyudutkan Unit',
            'Mahasiswa Membuat Akun Ganda',
            'Konten Tidak Relevan dengan Unit',
            'Komentar Menghina Petugas Unit',
            'Rating Negatif Berulang dari Mahasiswa yang Sama',
            'Komentar Berisi Ancaman',
            'Mahasiswa Menyebarkan Hoaks',
            'Konten Melanggar Hak Cipta',
            'Rating Tidak Wajar (Semua Nilai 1)',
            'Komentar Berisi Informasi Pribadi Orang Lain'
        ]);
        
        $deskripsiLaporan = fake()->randomElement([
            'Mahasiswa dengan nama anonim memberikan komentar yang tidak sopan dan mengandung kata-kata kasar yang melanggar pedoman komunitas.',
            'Mahasiswa memberikan rating 1 untuk semua kategori namun komentarnya mengatakan "unit ini sangat bagus dan memuaskan".',
            'Komentar mengandung unsur kebencian terhadap suku, agama, ras, dan antargolongan (SARA).',
            'Mahasiswa memberikan rating rendah tanpa alasan yang jelas dan cenderung melakukan provokasi.',
            'Mahasiswa yang sama telah memberikan rating berkali-kali dengan konten yang sama (spam) dalam waktu berdekatan.',
            'Komentar mengandung ujaran kebencian dan provokasi yang dapat mengganggu kenyamanan pengguna lain.',
            'Mahasiswa tersebut terus-menerus mengganggu dengan komentar negatif yang tidak membangun.',
            'Komentar berisi promosi produk/jasa tertentu yang tidak terkait dengan unit ini.',
            'Mahasiswa menggunakan bahasa yang sangat kasar dan tidak pantas dalam komentarnya.',
            'Rating yang diberikan terlihat tidak wajar dan cenderung dibuat-buat untuk menjatuhkan reputasi unit.',
            'Komentar menyudutkan unit dengan tuduhan yang tidak terbukti.',
            'Mahasiswa diduga membuat akun ganda untuk memberikan rating negatif.',
            'Komentar tidak relevan dengan unit yang dinilai.',
            'Komentar menghina petugas unit secara personal.',
            'Mahasiswa yang sama memberikan rating negatif berulang ke unit yang sama dalam kurun waktu singkat.',
            'Komentar berisi ancaman terhadap unit dan petugas.',
            'Mahasiswa menyebarkan hoaks tentang kondisi unit.',
            'Komentar melanggar hak cipta dengan menyertakan materi yang dilindungi tanpa izin.',
            'Rating tidak wajar karena semua kategori mendapatkan nilai 1 tanpa komentar yang berarti.',
            'Komentar berisi informasi pribadi orang lain (doxing).'
        ]);
        
        $tanggapanAdmin = null;
        if (in_array($status, ['replied', 'resolved'])) {
            $tanggapanAdmin = fake()->randomElement([
                'Terima kasih atas laporannya. Tim kami akan segera menindaklanjuti dan melakukan moderasi pada konten yang dilaporkan.',
                'Laporan Anda telah kami terima dan sedang dalam proses peninjauan.',
                'Kami telah meninjau laporan Anda. Konten yang dilaporkan terbukti melanggar pedoman komunitas dan telah kami hapus.',
                'Terima kasih atas kontribusi Anda dalam menjaga kebersihan platform.',
                'Setelah kami investigasi, konten yang dilaporkan memang melanggar aturan.',
                'Laporan telah kami selesaikan. Konten yang dilaporkan telah dimoderasi.',
                'Kami memahami kekhawatiran Anda. Tim kami telah menangani laporan ini.',
                'Terima kasih telah melapor. Setelah ditinjau, konten tersebut tidak melanggar pedoman komunitas.',
                'Laporan Anda valid. Kami telah menghubungi pengguna terkait.',
                'Proses moderasi telah selesai. Konten yang dilaporkan telah dihapus.'
            ]);
        }
        
        $priority = fake()->randomElement(['low', 'medium', 'high', 'critical']);
        
        return [
            'tracking_code' => 'RPT-' . strtoupper(uniqid()),
            'rating_id' => $rating instanceof Rating ? $rating->id : $rating,
            'unit_id' => $rating instanceof Rating ? $rating->unit_id : null,
            'student_identifier' => $rating instanceof Rating ? $rating->student_identifier : null,
            'title' => $judulLaporan,
            'description' => $deskripsiLaporan,
            'priority' => $priority,
            'status' => $status,
            'admin_id' => $adminDitugaskan,
            'admin_response' => $tanggapanAdmin,
            'replied_at' => $waktuDitanggapi,
            'created_at' => $waktuDibuat,
            'updated_at' => $waktuDitanggapi ?? $waktuDibuat,
        ];
    }

    public function asNew(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'new',
            'admin_id' => null,
            'admin_response' => null,
            'replied_at' => null,
        ]);
    }

    public function asInProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'admin_id' => User::whereIn('role', ['admin', 'super_admin'])->inRandomOrder()->first()?->id ?? 1,
            'admin_response' => null,
        ]);
    }

    public function asReplied(): static
    {
        return $this->state(function (array $attributes) {
            $waktuDibuat = $attributes['created_at'] ?? fake()->dateTimeBetween('-3 months', '-2 weeks');
            
            return [
                'status' => 'replied',
                'admin_id' => User::whereIn('role', ['admin', 'super_admin'])->inRandomOrder()->first()?->id ?? 1,
                'admin_response' => fake()->randomElement([
                    'Terima kasih atas laporannya. Tim kami akan segera menindaklanjuti.',
                    'Laporan Anda telah kami terima dan sedang dalam proses peninjauan.',
                    'Kami telah meninjau laporan Anda dan akan segera mengambil tindakan.',
                    'Terima kasih telah melapor. Tim kami sedang melakukan investigasi lebih lanjut.'
                ]),
                'replied_at' => fake()->dateTimeBetween($waktuDibuat, 'now'),
            ];
        });
    }

    public function asResolved(): static
    {
        return $this->state(function (array $attributes) {
            $waktuDibuat = $attributes['created_at'] ?? fake()->dateTimeBetween('-3 months', '-3 weeks');
            
            return [
                'status' => 'resolved',
                'admin_id' => User::whereIn('role', ['admin', 'super_admin'])->inRandomOrder()->first()?->id ?? 1,
                'admin_response' => fake()->randomElement([
                    'Laporan telah ditindaklanjuti. Konten yang dilaporkan telah dihapus.',
                    'Setelah ditinjau, konten yang dilaporkan terbukti melanggar pedoman.',
                    'Laporan selesai diproses. Tim kami telah mengambil tindakan.',
                    'Konten yang dilaporkan telah dihapus dan pengguna diberikan peringatan.',
                    'Laporan telah ditangani dengan baik. Konten yang melanggar telah dihapus.'
                ]),
                'replied_at' => fake()->dateTimeBetween($waktuDibuat, 'now'),
            ];
        });
    }

    public function asRejected(): static
    {
        return $this->state(function (array $attributes) {
            $waktuDibuat = $attributes['created_at'] ?? fake()->dateTimeBetween('-3 months', '-2 weeks');
            
            return [
                'status' => 'rejected',
                'admin_id' => User::whereIn('role', ['admin', 'super_admin'])->inRandomOrder()->first()?->id ?? 1,
                'admin_response' => fake()->randomElement([
                    'Setelah ditinjau, konten yang dilaporkan tidak melanggar pedoman komunitas.',
                    'Laporan tidak dapat diproses karena kurangnya bukti yang mendukung.',
                    'Konten yang dilaporkan telah kami periksa dan dinyatakan aman.',
                    'Laporan ditolak karena konten yang dilaporkan diperbolehkan di platform kami.',
                    'Tim moderasi memutuskan untuk menolak laporan ini.'
                ]),
                'replied_at' => null,
            ];
        });
    }

    public function critical(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'critical',
        ]);
    }

    public function high(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'high',
        ]);
    }

    public function medium(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'medium',
        ]);
    }

    public function low(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => 'low',
        ]);
    }

    public function forRating(Rating $rating): static
    {
        return $this->state(fn (array $attributes) => [
            'rating_id' => $rating->id,
            'unit_id' => $rating->unit_id,
            'student_identifier' => $rating->student_identifier,
        ]);
    }
}