<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (!Schema::hasColumn('facilities', 'icon_key')) {
                $table->string('icon_key')->nullable()->after('name');
            }
        });

        $mapping = [
            'air-conditioner' => 'air-conditioner',
            'air_conditioner' => 'air-conditioner',
            'ac' => 'air-conditioner',
            'projector' => 'projector',
            'printer' => 'printer',
            'mosque' => 'mosque',
            'masjid' => 'mosque',
            'musala' => 'mosque',
            'water-dispenser' => 'water-dispenser',
            'water_dispenser' => 'water-dispenser',
            'air_minum' => 'water-dispenser',
            'locker' => 'locker',
            'speaker' => 'speaker',
            'sound_system' => 'speaker',
            'wifi' => 'wifi',
            'whiteboard' => 'whiteboard',
            'toilet' => 'toilet',
            'cafe' => 'cafe',
            'cafetaria' => 'cafe',
            'kantin' => 'cafe',
            'wheelchair' => 'wheelchair',
            'kursi_roda' => 'wheelchair',
            'waiting-room' => 'waiting-room',
            'waiting_room' => 'waiting-room',
            'ruang_tunggu' => 'waiting-room',
            'flask' => 'flask',
            'lab' => 'flask',
            'book-open' => 'book-open',
            'book_open' => 'book-open',
            'perpustakaan' => 'book-open',
            'library' => 'book-open',
            'heart-pulse' => 'heart-pulse',
            'heart_pulse' => 'heart-pulse',
            'klinik' => 'heart-pulse',
            'clinic' => 'heart-pulse',
            'presentation' => 'presentation',
            'ruang_kelas' => 'presentation',
            'classroom' => 'presentation',
            'theater' => 'theater',
            'auditorium' => 'theater',
            'coffee' => 'coffee',
            'dumbbell' => 'dumbbell',
            'sports' => 'dumbbell',
            'olahraga' => 'dumbbell',
            'building' => 'building',
            'gedung' => 'building',
            'sofa' => 'sofa',
            'lounge' => 'sofa',
            'computer' => 'computer',
            'komputer' => 'computer',
            'music' => 'music',
            'musik' => 'music',
            'parking' => 'parking',
            'parkir' => 'parking',
        ];

        $updatedCount = 0;
        $notFoundCount = 0;
        $notFoundKeys = [];

        foreach ($mapping as $oldKey => $newKey) {
            $affected = DB::table('facilities')
                ->where('icon_key', $oldKey)
                ->update(['icon_key' => $newKey]);
            
            if ($affected > 0) {
                $updatedCount += $affected;
            } else {
                $exists = DB::table('facilities')
                    ->where('icon_key', $oldKey)
                    ->exists();
                
                if ($exists) {
                    $notFoundCount++;
                    $notFoundKeys[] = $oldKey;
                }
            }
        }

        $nullCount = DB::table('facilities')
            ->whereNull('icon_key')
            ->update(['icon_key' => 'building']);

        if ($nullCount > 0) {
            $updatedCount += $nullCount;
        }

        if ($notFoundCount > 0) {
            \Illuminate\Support\Facades\Log::info('Migration: Some icon keys were not found', [
                'not_found_keys' => $notFoundKeys
            ]);
        }

        \Illuminate\Support\Facades\Log::info('Migration completed: Facility icon keys updated', [
            'total_updated' => $updatedCount,
            'null_filled' => $nullCount,
            'total_facilities' => DB::table('facilities')->count()
        ]);
    }

    public function down(): void
    {
        $reverseMapping = [
            'air-conditioner' => 'air-conditioner',
            'projector' => 'projector',
            'printer' => 'printer',
            'mosque' => 'mosque',
            'water-dispenser' => 'water-dispenser',
            'locker' => 'locker',
            'speaker' => 'speaker',
            'wifi' => 'wifi',
            'whiteboard' => 'whiteboard',
            'toilet' => 'toilet',
            'cafe' => 'cafe',
            'wheelchair' => 'wheelchair',
            'waiting-room' => 'waiting-room',
            'flask' => 'flask',
            'book-open' => 'book-open',
            'heart-pulse' => 'heart-pulse',
            'presentation' => 'presentation',
            'theater' => 'theater',
            'coffee' => 'coffee',
            'dumbbell' => 'dumbbell',
            'building' => 'building',
            'sofa' => 'sofa',
            'computer' => 'computer',
            'music' => 'music',
            'parking' => 'parking',
        ];

        foreach ($reverseMapping as $newKey => $oldKey) {
            DB::table('facilities')
                ->where('icon_key', $newKey)
                ->update(['icon_key' => $oldKey]);
        }

        Schema::table('facilities', function (Blueprint $table) {
            if (Schema::hasColumn('facilities', 'icon_key')) {
                // Don't drop column, just keep it
            }
        });
    }
};