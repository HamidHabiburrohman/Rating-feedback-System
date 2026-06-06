<?php

namespace Database\Seeders;

use App\Models\System\Export;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class ExportSeeder extends Seeder
{
    public function run(): void
    {
        $admins = Admin::all();

        if ($admins->isEmpty()) {
            $this->command->info('No admins found, skipping ExportSeeder');
            return;
        }

        foreach ($admins as $admin) {
            Export::factory(3)->forAdmin($admin)->completed()->create();
            Export::factory(1)->forAdmin($admin)->processing()->create();
            Export::factory(1)->forAdmin($admin)->failed()->create();
        }

        $this->command->info('ExportSeeder completed.');
    }
}