<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UnitPhoto;
use App\Models\Unit;

return new class extends Migration
{
    public function up(): void
    {
        $photos = UnitPhoto::all();
        
        foreach ($photos as $photo) {
            $needsSave = false;
            
            if (str_contains($photo->original_path, 'C:\\') || str_contains($photo->original_path, 'public\\assets')) {
                $photo->original_path = null;
                $needsSave = true;
            }
            
            if (str_contains($photo->thumbnail_path, 'C:\\') || str_contains($photo->thumbnail_path, 'public\\assets')) {
                $photo->thumbnail_path = null;
                $needsSave = true;
            }
            
            if ($needsSave) {
                $photo->save();
            }
        }
        
        $duplicatePrimaries = UnitPhoto::select('unit_id')
            ->where('is_primary', true)
            ->groupBy('unit_id')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('unit_id');
        
        foreach ($duplicatePrimaries as $unitId) {
            $firstPhoto = UnitPhoto::where('unit_id', $unitId)
                ->where('is_primary', true)
                ->orderBy('id')
                ->first();
            
            UnitPhoto::where('unit_id', $unitId)
                ->where('is_primary', true)
                ->where('id', '!=', $firstPhoto->id)
                ->update(['is_primary' => false]);
        }
    }

    public function down(): void
    {
        //
    }
};