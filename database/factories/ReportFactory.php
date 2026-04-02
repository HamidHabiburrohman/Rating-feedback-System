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
            'Mahasiswa dengan nama anonim memberikan komentar yang tidak sopan dan mengandung kata-kata kasar yang melanggar pedoman komunitas. Komentar tersebut berbunyi: "Pelayanan di sini sangat buruk, petugasnya tidak ramah dan tidak kompeten. Lebih baik tutup saja unit ini!"',
            
            'Mahasiswa memberikan rating 1 untuk semua kategori namun komentarnya mengatakan "unit ini sangat bagus dan memuaskan". Ini menunjukkan ketidaksesuaian antara rating dan komentar. Diduga mahasiswa sengaja memberikan rating rendah untuk menurunkan reputasi unit.',
            
            'Komentar mengandung unsur kebencian terhadap suku, agama, ras, dan antargolongan (SARA). Mahasiswa tersebut menuliskan komentar yang menyudutkan petugas berdasarkan latar belakang agama dan daerah asal.',
            
            'Mahasiswa memberikan rating rendah tanpa alasan yang jelas dan cenderung melakukan provokasi. Dalam komentarnya, mahasiswa menulis hal-hal yang tidak berdasar dan tidak sesuai dengan fakta di lapangan.',
            
            'Mahasiswa yang sama telah memberikan rating berkali-kali dengan konten yang sama (spam) dalam waktu berdekatan. Terdapat 5 rating dari mahasiswa yang sama dengan komentar yang identik dalam 1 jam terakhir.',
            
            'Komentar mengandung ujaran kebencian dan provokasi yang dapat mengganggu kenyamanan pengguna lain. Mahasiswa tersebut sering meninggalkan komentar negatif yang tidak membangun di berbagai unit.',
            
            'Mahasiswa tersebut terus-menerus mengganggu dengan komentar negatif yang tidak membangun. Setiap kali ada rating baru, selalu memberikan komentar yang menjelekkan unit tanpa memberikan solusi.',
            
            'Komentar berisi promosi produk/jasa tertentu yang tidak terkait dengan unit ini. Mahasiswa menuliskan link dan informasi tentang bisnis online-nya di kolom komentar rating.',
            
            'Mahasiswa menggunakan bahasa yang sangat kasar dan tidak pantas dalam komentarnya. Terdapat kata-kata makian dan bahasa vulgar yang melanggar etika berkomunikasi.',
            
            'Rating yang diberikan terlihat tidak wajar dan cenderung dibuat-buat untuk menjatuhkan reputasi unit. Mahasiswa memberikan rating 1 tetapi dalam komentarnya tidak menyebutkan alasan yang jelas.',
            
            'Komentar menyudutkan unit dengan tuduhan yang tidak terbukti. Mahasiswa menuduh unit melakukan praktik tidak etis tanpa bukti yang mendukung.',
            
            'Mahasiswa diduga membuat akun ganda untuk memberikan rating negatif. Terdapat pola rating dengan IP yang sama dari beberapa akun berbeda dalam waktu berdekatan.',
            
            'Komentar tidak relevan dengan unit yang dinilai. Mahasiswa membahas hal-hal di luar lingkup unit, seperti masalah pribadi yang tidak ada hubungannya dengan layanan unit.',
            
            'Komentar menghina petugas unit secara personal. Mahasiswa menyebut nama petugas dan menghina penampilan serta kinerja petugas tersebut.',
            
            'Mahasiswa yang sama memberikan rating negatif berulang ke unit yang sama dalam kurun waktu singkat. Diduga ada motif pribadi di balik rating tersebut.',
            
            'Komentar berisi ancaman terhadap unit dan petugas. Mahasiswa menuliskan ancaman yang dapat menimbulkan rasa tidak aman bagi petugas unit.',
            
            'Mahasiswa menyebarkan hoaks tentang kondisi unit. Informasi yang disampaikan tidak sesuai dengan kondisi sebenarnya dan dapat menyesatkan pengguna lain.',
            
            'Komentar melanggar hak cipta dengan menyertakan materi yang dilindungi tanpa izin. Mahasiswa mengunggah screenshot dokumen internal unit tanpa persetujuan.',
            
            'Rating tidak wajar karena semua kategori mendapatkan nilai 1 tanpa komentar yang berarti. Pola ini mencurigakan karena tidak ada penjelasan mengapa semua aspek dinilai buruk.',
            
            'Komentar berisi informasi pribadi orang lain (doxing). Mahasiswa menyertakan nomor telepon dan alamat rumah petugas unit tanpa izin.'
        ]);
        
        $tanggapanAdmin = null;
        if (in_array($status, ['replied', 'resolved'])) {
            $tanggapanAdmin = fake()->randomElement([
                'Terima kasih atas laporannya. Tim kami akan segera menindaklanjuti dan melakukan moderasi pada konten yang dilaporkan. Mohon waktu 1x24 jam untuk proses verifikasi.',
                
                'Laporan Anda telah kami terima dan sedang dalam proses peninjauan. Tim moderasi akan mengevaluasi konten yang dilaporkan sesuai dengan pedoman komunitas.',
                
                'Kami telah meninjau laporan Anda. Konten yang dilaporkan terbukti melanggar pedoman komunitas dan telah kami hapus. Terima kasih atas partisipasi Anda.',
                
                'Terima kasih atas kontribusi Anda dalam menjaga kebersihan platform. Laporan Anda telah kami proses dan tindakan telah diambil terhadap konten yang melanggar.',
                
                'Setelah kami investigasi, konten yang dilaporkan memang melanggar aturan. Kami telah memberikan peringatan kepada pengguna dan menghapus konten tersebut.',
                
                'Laporan telah kami selesaikan. Konten yang dilaporkan telah dimoderasi dan statusnya diubah menjadi disensor. Terima kasih atas laporannya.',
                
                'Kami memahami kekhawatiran Anda. Tim kami telah menangani laporan ini dan memastikan konten serupa tidak muncul kembali di platform.',
                
                'Terima kasih telah melapor. Setelah ditinjau, konten tersebut tidak melanggar pedoman komunitas. Namun kami tetap mengapresiasi kepedulian Anda.',
                
                'Laporan Anda valid. Kami telah menghubungi pengguna terkait dan memberikan edukasi tentang penggunaan platform yang baik dan benar.',
                
                'Proses moderasi telah selesai. Konten yang dilaporkan telah dihapus dan pengguna diberikan sanksi sesuai tingkat pelanggaran.'
            ]);
        }
        
        $priority = fake()->randomElement(['low', 'medium', 'high', 'critical']);
        
        return [
            'tracking_code' => 'RPT-' . strtoupper(uniqid()),
            'rating_id' => $rating instanceof Rating ? $rating->id : $rating,
            'unit_id' => $rating instanceof Rating ? $rating->unit_id : null,
            'student_id' => $rating instanceof Rating ? $rating->student_id : null,
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
                    'Terima kasih atas laporannya. Tim kami akan segera menindaklanjuti dan melakukan moderasi pada konten yang dilaporkan. Mohon waktu 1x24 jam untuk proses verifikasi.',
                    'Laporan Anda telah kami terima dan sedang dalam proses peninjauan. Tim moderasi akan mengevaluasi konten yang dilaporkan sesuai dengan pedoman komunitas.',
                    'Kami telah meninjau laporan Anda dan akan segera mengambil tindakan yang diperlukan. Terima kasih atas partisipasi Anda.',
                    'Terima kasih telah melapor. Tim kami sedang melakukan investigasi lebih lanjut terkait konten tersebut.'
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
                    'Laporan telah ditindaklanjuti. Konten yang dilaporkan telah dihapus dan pengguna telah diberikan peringatan tertulis.',
                    'Setelah ditinjau, konten yang dilaporkan terbukti melanggar pedoman. Konten telah dimoderasi dan statusnya diubah menjadi disensor.',
                    'Laporan selesai diproses. Tim kami telah mengambil tindakan sesuai dengan kebijakan platform. Terima kasih atas laporannya.',
                    'Konten yang dilaporkan telah dihapus dan pengguna dilarang memberikan rating untuk sementara waktu sebagai sanksi.',
                    'Laporan telah ditangani dengan baik. Konten yang melanggar telah dihapus dari platform dan pengguna diberikan edukasi.'
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
                    'Setelah ditinjau, konten yang dilaporkan tidak melanggar pedoman komunitas. Laporan ditolak.',
                    'Laporan tidak dapat diproses karena kurangnya bukti yang mendukung. Silakan laporkan kembali jika ada bukti tambahan.',
                    'Konten yang dilaporkan telah kami periksa dan dinyatakan aman serta sesuai dengan pedoman komunitas.',
                    'Laporan ditolak karena konten yang dilaporkan termasuk dalam kategori yang diperbolehkan di platform kami.',
                    'Tim moderasi memutuskan untuk menolak laporan ini karena tidak ditemukan pelanggaran yang signifikan.'
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
            'student_id' => $rating->student_id,
        ]);
    }
}