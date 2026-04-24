<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Laravel\Sanctum\PersonalAccessToken;

class PersonalAccessTokenSeeder extends Seeder
{
    public function run()
    {
        $admin = Admin::where('email', 'superadmin@admin.com')->first();
        
        if ($admin) {
            $token = $admin->createToken('admin-api-token', ['admin:access'])->plainTextToken;
            
            $this->command->info('Admin API Token: ' . $token);
            $this->command->info('Simpan token ini untuk testing API');
        }
    }
}