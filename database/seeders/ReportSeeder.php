<?php

namespace Database\Seeders;

use App\Models\Rating;
use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::all();
        
        if ($ratings->isEmpty()) {
            $this->command->info('Tidak ada rating, lewati pembuatan laporan');
            return;
        }

        $this->command->info('Membuat laporan untuk rating yang ada...');

        $jumlahLaporan = (int) ($ratings->count() * 0.2);
        
        if ($jumlahLaporan > 0) {
            $ratingsWithReport = $ratings->random(min($jumlahLaporan, $ratings->count()));

            foreach ($ratingsWithReport as $rating) {
                if (!$rating->report) {
                    Report::factory()
                        ->forRating($rating)
                        ->create();
                }
            }
            
            $this->command->info("✓ Membuat {$ratingsWithReport->count()} laporan acak");
        }

        $this->createSpecificReports($ratings);
    }

    private function createSpecificReports($ratings): void
    {
        $ratingsWithoutReport = $ratings->filter(function ($rating) {
            return !$rating->report;
        });

        if ($ratingsWithoutReport->isEmpty()) {
            $this->command->info('Semua rating sudah memiliki laporan');
            return;
        }

        $this->command->info('Membuat laporan dengan status spesifik...');

        $newCount = min(3, $ratingsWithoutReport->count());
        if ($newCount > 0) {
            $newReportRatings = $ratingsWithoutReport->random($newCount);
            foreach ($newReportRatings as $rating) {
                Report::factory()
                    ->forRating($rating)
                    ->asNew()
                    ->low()
                    ->create();
                
                $ratingsWithoutReport = $ratingsWithoutReport->reject(function ($item) use ($rating) {
                    return $item->id === $rating->id;
                });
            }
            $this->command->info("✓ Membuat {$newCount} laporan baru (status: new)");
        }

        $inProgressCount = min(3, $ratingsWithoutReport->count());
        if ($inProgressCount > 0) {
            $inProgressRatings = $ratingsWithoutReport->random($inProgressCount);
            foreach ($inProgressRatings as $rating) {
                Report::factory()
                    ->forRating($rating)
                    ->asInProgress()
                    ->medium()
                    ->create();
                
                $ratingsWithoutReport = $ratingsWithoutReport->reject(function ($item) use ($rating) {
                    return $item->id === $rating->id;
                });
            }
            $this->command->info("✓ Membuat {$inProgressCount} laporan diproses (status: in_progress)");
        }

        $repliedCount = min(3, $ratingsWithoutReport->count());
        if ($repliedCount > 0) {
            $repliedRatings = $ratingsWithoutReport->random($repliedCount);
            foreach ($repliedRatings as $rating) {
                Report::factory()
                    ->forRating($rating)
                    ->asReplied()
                    ->high()
                    ->create();
                
                $ratingsWithoutReport = $ratingsWithoutReport->reject(function ($item) use ($rating) {
                    return $item->id === $rating->id;
                });
            }
            $this->command->info("✓ Membuat {$repliedCount} laporan ditanggapi (status: replied)");
        }

        $resolvedCount = min(3, $ratingsWithoutReport->count());
        if ($resolvedCount > 0) {
            $resolvedRatings = $ratingsWithoutReport->random($resolvedCount);
            foreach ($resolvedRatings as $rating) {
                Report::factory()
                    ->forRating($rating)
                    ->asResolved()
                    ->create();
                
                $ratingsWithoutReport = $ratingsWithoutReport->reject(function ($item) use ($rating) {
                    return $item->id === $rating->id;
                });
            }
            $this->command->info("✓ Membuat {$resolvedCount} laporan selesai (status: resolved)");
        }

        $rejectedCount = min(2, $ratingsWithoutReport->count());
        if ($rejectedCount > 0) {
            $rejectedRatings = $ratingsWithoutReport->random($rejectedCount);
            foreach ($rejectedRatings as $rating) {
                Report::factory()
                    ->forRating($rating)
                    ->asRejected()
                    ->create();
                
                $ratingsWithoutReport = $ratingsWithoutReport->reject(function ($item) use ($rating) {
                    return $item->id === $rating->id;
                });
            }
            $this->command->info("✓ Membuat {$rejectedCount} laporan ditolak (status: rejected)");
        }

        $criticalCount = min(2, $ratingsWithoutReport->count());
        if ($criticalCount > 0) {
            $criticalRatings = $ratingsWithoutReport->random($criticalCount);
            foreach ($criticalRatings as $rating) {
                Report::factory()
                    ->forRating($rating)
                    ->asNew()
                    ->critical()
                    ->create();
            }
            $this->command->info("✓ Membuat {$criticalCount} laporan prioritas kritis");
        }

        $totalReports = Report::count();
        $this->command->info("Total laporan yang berhasil dibuat: {$totalReports}");
    }
}