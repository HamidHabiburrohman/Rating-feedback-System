<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminUserSeeder::class,         // 1. User/Admin dulu (dibutuhkan Report & Rating)
            UnitTypeSeeder::class,          // 2. Tipe Unit (dibutuhkan Unit)
            UnitSeeder::class,              // 3. Unit (dibutuhkan Report & Visit)
            VisitorSessionSeeder::class,    // 4. Session (dibutuhkan Report & Visit)
            ReportSeeder::class,            // 5. Report (butuh Admin, Unit, Session)
            UnitVisitSeeder::class,         // 6. Visit (butuh Unit & Session)
            RatingCategorySeeder::class,    // 7. Kategori Rating
            RatingSeeder::class,            // 8. Rating (biasanya butuh Unit/Report & Session)
            PersonalAccessTokenSeeder::class,
        ]);
    }
}