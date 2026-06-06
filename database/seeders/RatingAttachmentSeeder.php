<?php

namespace Database\Seeders;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingAttachment;
use Illuminate\Database\Seeder;

class RatingAttachmentSeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::inRandomOrder()->limit(20)->get();

        foreach ($ratings as $rating) {
            $attachmentCount = rand(1, 3);

            for ($i = 0; $i < $attachmentCount; $i++) {
                RatingAttachment::factory()
                    ->forRating($rating)
                    ->withSortOrder($i)
                    ->create();
            }
        }
    }
}