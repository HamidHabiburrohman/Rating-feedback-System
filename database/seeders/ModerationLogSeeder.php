<?php

namespace Database\Seeders;

use App\Models\System\ModerationLog;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class ModerationLogSeeder extends Seeder
{
    public function run(): void
    {
        $admins = Admin::all();

        foreach ($admins as $admin) {
            ModerationLog::factory(5)->forRating()->byAdmin($admin)->create();
            ModerationLog::factory(3)->forReport()->byAdmin($admin)->create();
            ModerationLog::factory(2)->forUnit()->byAdmin($admin)->create();
        }
    }
}