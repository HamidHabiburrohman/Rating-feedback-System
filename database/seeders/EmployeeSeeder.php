<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Unit;
use Carbon\Carbon;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada unit terlebih dahulu
        $units = Unit::all();
        
        if ($units->isEmpty()) {
            // Buat unit dummy jika belum ada
            $units = Unit::factory(5)->create();
        }
        
        $jabatanList = [
            'Manager',
            'Supervisor',
            'Senior Developer',
            'Junior Developer',
            'Quality Assurance',
            'System Analyst',
            'Project Manager',
            'Business Analyst',
            'UI/UX Designer',
            'DevOps Engineer',
            'Network Administrator',
            'Database Administrator',
            'IT Support',
            'HR Manager',
            'Finance Manager',
            'Marketing Manager',
            'Sales Executive',
            'Customer Service',
            'Operations Manager',
            'Product Manager'
        ];
        
        $bidangList = [
            'IT & Technology',
            'Human Resources',
            'Finance & Accounting',
            'Marketing & Sales',
            'Operations',
            'Customer Service',
            'Research & Development',
            'Quality Control',
            'Production',
            'Logistics'
        ];
        
        $employees = [];
        
        for ($i = 1; $i <= 60; $i++) {
            $unit = $units->random();
            $jabatan = $jabatanList[array_rand($jabatanList)];
            $bidang = $bidangList[array_rand($bidangList)];
            $status = ['aktif', 'cuti', 'resign'][rand(0, 2)];
            
            // Tentukan tanggal mulai (dalam 5 tahun terakhir)
            $tanggalMulai = Carbon::now()->subYears(rand(0, 5))->subDays(rand(0, 365));
            
            // Jika status resign, tambahkan tanggal selesai (setelah tanggal mulai)
            $tanggalSelesai = null;
            if ($status === 'resign') {
                $tanggalSelesai = $tanggalMulai->copy()->addYears(rand(1, 3))->addDays(rand(0, 180));
            }
            
            $employees[] = [
                'unit_id' => $unit->id,
                'nama' => $this->generateIndonesianName(),
                'jabatan' => $jabatan,
                'bidang' => $bidang,
                'email' => 'employee' . $i . '@example.com',
                'telepon' => '+628' . rand(111111111, 999999999),
                'status' => $status,
                'tanggal_mulai' => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai,
                'keterangan' => rand(0, 1) ? 'Karyawan dengan kinerja ' . ['baik', 'sangat baik', 'cukup'][rand(0, 2)] : null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insert semua data sekaligus
        Employee::insert($employees);
        
        $this->command->info('60 data employee berhasil ditambahkan!');
    }
    
    /**
     * Generate random Indonesian name.
     */
    private function generateIndonesianName(): string
    {
        $firstNames = [
            'Ahmad', 'Budi', 'Cahya', 'Dewi', 'Eka', 'Fajar', 'Gita', 'Hadi', 'Indra', 'Joko',
            'Kartika', 'Lestari', 'Mulyadi', 'Nina', 'Oki', 'Putri', 'Rizki', 'Sari', 'Tono', 'Umar',
            'Wahyu', 'Yanto', 'Zainal', 'Ani', 'Bambang', 'Citra', 'Dodi', 'Endang', 'Fitri', 'Gunawan',
            'Hendra', 'Intan', 'Juli', 'Kurnia', 'Lina', 'Mega', 'Nur', 'Oman', 'Puji', 'Rina',
            'Surya', 'Tuti', 'Udin', 'Widi', 'Yuni', 'Agus', 'Bayu', 'Cindy', 'Dede', 'Evi'
        ];
        
        $lastNames = [
            'Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Sari', 'Putra', 'Hidayat', 'Saputra', 'Kurniawan', 'Setiawan',
            'Wahyuni', 'Nugroho', 'Purnama', 'Suryadi', 'Handoko', 'Irawan', 'Firmansyah', 'Ramadan', 'Siregar', 'Halim',
            'Susanto', 'Hartono', 'Siregar', 'Tanuwijaya', 'Kusnadi', 'Sihombing', 'Marpaung', 'Simanjuntak', 'Lubis', 'Ginting',
            'Tarigan', 'Situmorang', 'Napitupulu', 'Sinaga', 'Manalu', 'Sihotang', 'Purba', 'Sihombing', 'Pangaribuan', 'Tambunan',
            'Hutagalung', 'Pasaribu', 'Sirait', 'Pardede', 'Sitorus', 'Simbolon', 'Panjaitan', 'Siahaan', 'Hutapea', 'Rajagukguk'
        ];
        
        $firstName = $firstNames[array_rand($firstNames)];
        $lastName = $lastNames[array_rand($lastNames)];
        
        // Kadang-kadang tambahkan middle name
        if (rand(0, 1)) {
            $middleName = $firstNames[array_rand($firstNames)];
            return $firstName . ' ' . $middleName . ' ' . $lastName;
        }
        
        return $firstName . ' ' . $lastName;
    }
}