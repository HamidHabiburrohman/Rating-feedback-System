<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\UnitType;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run()
    {
        if (UnitType::count() === 0) {
            $this->command->warn('⚠️  UnitType masih kosong, membuat data dummy...');
            
            $dummyTypes = [
                ['name' => 'Kesehatan', 'status' => true, 'sort_order' => 1],
                ['name' => 'Akademik', 'status' => true, 'sort_order' => 2],
                ['name' => 'Administrasi', 'status' => true, 'sort_order' => 3],
                ['name' => 'Fasilitas', 'status' => true, 'sort_order' => 4],
                ['name' => 'Teknologi', 'status' => true, 'sort_order' => 5],
                ['name' => 'Olahraga', 'status' => true, 'sort_order' => 6],
                ['name' => 'Kesenian', 'status' => true, 'sort_order' => 7],
                ['name' => 'Kemahasiswaan', 'status' => true, 'sort_order' => 8],
                ['name' => 'Penelitian', 'status' => true, 'sort_order' => 9],
                ['name' => 'Layanan Umum', 'status' => true, 'sort_order' => 10],
                ['name' => 'Lainnya', 'status' => true, 'sort_order' => 11],
            ];
            
            foreach ($dummyTypes as $type) {
                UnitType::create($type);
            }
            
            $this->command->info('✅ UnitType dummy berhasil dibuat!');
        }
        
        $unitTypes = UnitType::all();
        $units = [];
        
        $gedungList = ['Rektorat', 'Teknik', 'Perpustakaan', 'Sains', 'Ekonomi', 'Hukum', 'Kedokteran', 'Bahasa', 'Seni', 'Olahraga', 'Teknologi', 'FISIP', 'Pasca Sarjana'];
        $fakultasList = ['Teknik', 'Sains', 'Ekonomi', 'Hukum', 'Kedokteran', 'Psikologi', 'Bahasa', 'Seni', 'Pertanian', 'Teknologi', 'FISIP'];
        $prodiList = ['Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Teknik Mesin', 'Arsitektur', 'Akuntansi', 'Manajemen', 'Hukum', 'Kedokteran Umum', 'Psikologi', 'Sastra Inggris', 'Desain Komunikasi Visual'];
        $fotoList = [
            'kesehatan' => ['klinik1.jpg', 'klinik2.jpg', 'klinik3.jpg'],
            'akademik' => ['akademik1.jpg', 'akademik2.jpg', 'akademik3.jpg'],
            'fasilitas' => ['fasilitas1.jpg', 'fasilitas2.jpg', 'fasilitas3.jpg'],
            'teknologi' => ['teknologi1.jpg', 'teknologi2.jpg', 'teknologi3.jpg'],
            'olahraga' => ['olahraga1.jpg', 'olahraga2.jpg', 'olahraga3.jpg'],
            'kesenian' => ['seni1.jpg', 'seni2.jpg', 'seni3.jpg'],
            'administrasi' => ['admin1.jpg', 'admin2.jpg', 'admin3.jpg']
        ];
        
        $unitTypeNames = [];
        foreach ($unitTypes as $type) {
            $unitTypeNames[$type->name] = $type->id;
        }

        if (empty($unitTypeNames)) {
            $this->command->error('❌ UnitType masih kosong setelah dibuat ulang!');
            return;
        }

        for ($i = 1; $i <= 60; $i++) {
            $typeId = $this->determineType($unitTypeNames);
            $typeName = array_search($typeId, $unitTypeNames);
            
            $gedung = $gedungList[array_rand($gedungList)];
            $lantai = rand(1, 8);
            $kapasitas = rand(20, 500);
            $statusAktif = rand(0, 4) > 0;
            $status = $this->generateUnitStatus($typeName, $statusAktif);
            
            $nama = $this->generateUnitName($typeName, $i, $fakultasList, $prodiList, $gedungList);
            $deskripsi = $this->generateDescription($typeName, $nama, $gedung);
            
            $jamBuka = $this->generateJamBuka($typeName);
            $jamTutup = $this->generateJamTutup($typeName);
            
            $fotoUnit = null;
            $jenisUnitKey = strtolower($typeName);
            if (isset($fotoList[$jenisUnitKey]) && rand(0, 3) > 0) {
                $fotoUnit = 'units/' . $fotoList[$jenisUnitKey][array_rand($fotoList[$jenisUnitKey])];
            }
            
            $units[] = [
                'kode_unit' => 'U' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_unit' => $nama,
                'deskripsi' => $deskripsi,
                'type_id' => $typeId,
                'lokasi' => 'Gedung ' . $gedung . ' Lantai ' . $lantai,
                'gedung' => $gedung,
                'lantai' => (string) $lantai,
                'kontak_telepon' => '021-' . rand(2000000, 8999999),
                'kontak_email' => strtolower(str_replace([' ', "'", '/'], ['.', '', '.'], $nama)) . '@university.edu',
                'jam_buka' => $jamBuka,
                'jam_tutup' => $jamTutup,
                'kapasitas' => $kapasitas,
                'status_aktif' => $statusAktif,
                'status' => $status,
                'foto_unit' => $fotoUnit,
                'metadata' => json_encode(['created_by' => 'seeder', 'unit_type' => $typeName]),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach (array_chunk($units, 20) as $chunk) {
            Unit::insert($chunk);
        }
        
        $this->command->info('✅ 60 units berhasil di-seed!');
    }
    
    private function generateUnitStatus($typeName, $statusAktif)
    {
        if (!$statusAktif) {
            return 'closed';
        }
        
        $weights = ['open' => 70, 'full' => 15, 'maintenance' => 10, 'closed' => 5];
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $current = 0;
        
        foreach ($weights as $status => $weight) {
            $current += $weight;
            if ($rand <= $current) {
                return $status;
            }
        }
        
        return 'open';
    }
    
    private function determineType($unitTypeNames)
    {
        $weights = [];
        
        foreach (array_keys($unitTypeNames) as $typeName) {
            $weights[$typeName] = $this->getWeightForType($typeName);
        }
        
        $total = array_sum($weights);
        $rand = rand(1, $total);
        $current = 0;
        
        foreach ($weights as $type => $weight) {
            $current += $weight;
            if ($rand <= $current) {
                return $unitTypeNames[$type];
            }
        }
        
        return array_values($unitTypeNames)[0];
    }
    
    private function getWeightForType($typeName)
    {
        $weights = [
            'Kesehatan' => 15,
            'Akademik' => 35,
            'Administrasi' => 10,
            'Fasilitas' => 15,
            'Teknologi' => 8,
            'Olahraga' => 6,
            'Kesenian' => 5,
            'Kemahasiswaan' => 4,
            'Penelitian' => 3,
            'Layanan Umum' => 3,
            'Lainnya' => 3,
        ];
        
        return $weights[$typeName] ?? 5;
    }
    
    private function generateUnitName($typeName, $index, $fakultasList, $prodiList, $gedungList)
    {
        $prefix = '';
        
        switch ($typeName) {
            case 'Kesehatan':
                $names = ['Klinik Mahasiswa', 'Puskesmas Kampus', 'UKS Utama', 'Poliklinik', 'Apotek Kampus', 'Laboratorium Kesehatan', 'Dokter Kampus'];
                $prefix = $names[array_rand($names)];
                if (rand(0, 1)) $prefix .= ' ' . $gedungList[array_rand($gedungList)];
                break;
                
            case 'Akademik':
                if (rand(0, 3)) {
                    $prefix = 'Fakultas ' . $fakultasList[array_rand($fakultasList)];
                } else {
                    $prefix = 'Program Studi ' . $prodiList[array_rand($prodiList)];
                }
                break;
                
            case 'Fasilitas':
                $names = ['Perpustakaan', 'Laboratorium', 'Auditorium', 'Ruang Seminar', 'Kantin', 'Masjid', 'Ruang Kelas', 'Studio', 'Workshop'];
                $prefix = $names[array_rand($names)] . ' ' . $gedungList[array_rand($gedungList)];
                break;
                
            case 'Administrasi':
                $names = ['Bagian Administrasi', 'Keuangan', 'Registrar', 'HRD', 'BAAK', 'BAA', 'Kemahasiswaan'];
                $prefix = $names[array_rand($names)];
                if (rand(0, 1)) $prefix .= ' ' . $gedungList[array_rand($gedungList)];
                break;
                
            case 'Teknologi':
                $names = ['Pusat Komputer', 'Laboratorium IT', 'Data Center', 'Helpdesk IT', 'Ruang Server', 'Studio Multimedia'];
                $prefix = $names[array_rand($names)];
                break;
                
            case 'Olahraga':
                $names = ['Lapangan', 'Gym', 'Kolam Renang', 'Gedung Olahraga', 'Fitness Center', 'Studio Aerobik'];
                $prefix = $names[array_rand($names)] . ' Kampus';
                break;
                
            case 'Kesenian':
                $names = ['Galeri Seni', 'Teater Kampus', 'Studio Musik', 'Sanggar Tari', 'Ruang Pamer', 'Galeri Foto'];
                $prefix = $names[array_rand($names)];
                break;
                
            case 'Kemahasiswaan':
                $names = ['BEM', 'DPM', 'Himpunan Mahasiswa', 'Unit Kegiatan', 'Lembaga Kemahasiswaan', 'OSKM'];
                $prefix = $names[array_rand($names)];
                break;
                
            case 'Penelitian':
                $names = ['Pusat Riset', 'Laboratorium Penelitian', 'Inovasi Center', 'Research Hub', 'Ruang Eksperimen'];
                $prefix = $names[array_rand($names)];
                break;
                
            case 'Layanan Umum':
                $names = ['Koperasi', 'Security', 'Cleaning Service', 'Parkir', 'Transportasi', 'Kantin Utama'];
                $prefix = $names[array_rand($names)] . ' Kampus';
                break;
                
            case 'Lainnya':
                $names = ['Unit Khusus', 'Pusat Pengembangan', 'Unit Kolaborasi', 'Center of Excellence'];
                $prefix = $names[array_rand($names)] . ' ' . $index;
                break;
                
            default:
                $prefix = 'Unit ' . $typeName . ' ' . $index;
        }
        
        return $prefix;
    }
    
    private function generateDescription($typeName, $unitName, $gedung)
    {
        $descriptions = [
            'Kesehatan' => 'Layanan kesehatan untuk mahasiswa dan staf universitas',
            'Akademik' => 'Unit akademik dengan berbagai program studi dan fasilitas pembelajaran',
            'Fasilitas' => 'Fasilitas pendukung aktivitas akademik dan non-akademik',
            'Administrasi' => 'Pelayanan administrasi dan pengelolaan data akademik',
            'Teknologi' => 'Layanan teknologi informasi dan infrastruktur digital',
            'Olahraga' => 'Fasilitas olahraga untuk menjaga kesehatan dan kebugaran',
            'Kesenian' => 'Wadah pengembangan bakat seni dan budaya kampus',
            'Kemahasiswaan' => 'Unit pengembangan organisasi dan kegiatan mahasiswa',
            'Penelitian' => 'Pusat penelitian dan pengembangan inovasi akademik',
            'Layanan Umum' => 'Layanan umum untuk mendukung operasional kampus',
            'Lainnya' => 'Unit operasional universitas'
        ];
        
        $baseDesc = $descriptions[$typeName] ?? 'Unit operasional universitas';
        return $baseDesc . ' di Gedung ' . $gedung;
    }
    
    private function generateJamBuka($typeName)
    {
        $hours = match($typeName) {
            'Kesehatan' => rand(7, 8),
            'Akademik' => rand(6, 7),
            'Fasilitas' => 8,
            'Administrasi' => 8,
            'Teknologi' => 9,
            'Olahraga' => 6,
            'Kesenian' => 9,
            'Kemahasiswaan' => 10,
            'Penelitian' => 8,
            'Layanan Umum' => 7,
            'Lainnya' => 9,
            default => 8
        };
        
        $minutes = rand(0, 1) ? '00' : '30';
        return sprintf('%02d:%s:00', $hours, $minutes);
    }
    
    private function generateJamTutup($typeName)
    {
        $hours = match($typeName) {
            'Kesehatan' => rand(20, 21),
            'Akademik' => rand(20, 22),
            'Fasilitas' => rand(21, 22),
            'Administrasi' => 17,
            'Teknologi' => 22,
            'Olahraga' => 21,
            'Kesenian' => 20,
            'Kemahasiswaan' => 18,
            'Penelitian' => 19,
            'Layanan Umum' => 18,
            'Lainnya' => 17,
            default => 17
        };
        
        $minutes = rand(0, 1) ? '00' : '30';
        return sprintf('%02d:%s:00', $hours, $minutes);
    }
}