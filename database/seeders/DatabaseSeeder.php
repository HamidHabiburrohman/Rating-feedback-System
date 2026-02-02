<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,
            UnitTypeSeeder::class,
            UnitSeeder::class,
            RatingCategorySeeder::class,
            PersonalAccessTokenSeeder::class,
            EmployeeSeeder::class,
            MessagesSeeder::class,
            RatingSeeder::class,
            ReportSeeder::class
        ]);
    }
}