<?php

namespace App\Services\Employee;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingReply;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RatingReplyService extends BaseEmployeeService
{
    public function reply(int $ratingId, string $reply, int $employeeId): RatingReply
    {
        return DB::transaction(function () use ($ratingId, $reply, $employeeId) {
            $rating = Rating::findOrFail($ratingId);

            if (!$this->isAssignedToUnit($rating->unit_id)) {
                throw new \Exception('Anda tidak memiliki akses untuk membalas rating unit ini.');
            }

            $ratingReply = RatingReply::create([
                'rating_id' => $ratingId,
                'employee_id' => $employeeId,
                'admin_id' => null,
                'reply' => $reply,
                'is_public' => true,
            ]);

            $rating->update(['last_replied_at' => now()]);

            Cache::tags(['ratings', "rating_{$ratingId}", "unit_{$rating->unit_id}", 'dashboard'])->flush();

            return $ratingReply->fresh(['employee']);
        });
    }

    public function updateReply(int $replyId, string $reply, int $employeeId): RatingReply
    {
        return DB::transaction(function () use ($replyId, $reply, $employeeId) {
            $ratingReply = RatingReply::where('id', $replyId)
                ->where('employee_id', $employeeId)
                ->firstOrFail();

            $rating = $ratingReply->rating;
            if (!$this->isAssignedToUnit($rating->unit_id)) {
                throw new \Exception('Anda tidak memiliki akses untuk mengubah balasan ini.');
            }

            $ratingReply->update(['reply' => $reply]);

            Cache::tags(['ratings', "rating_{$rating->id}", "unit_{$rating->unit_id}"])->flush();

            return $ratingReply;
        });
    }

    public function deleteReply(int $replyId, int $employeeId): bool
    {
        return DB::transaction(function () use ($replyId, $employeeId) {
            $ratingReply = RatingReply::where('id', $replyId)
                ->where('employee_id', $employeeId)
                ->firstOrFail();

            $rating = $ratingReply->rating;
            if (!$this->isAssignedToUnit($rating->unit_id)) {
                throw new \Exception('Anda tidak memiliki akses untuk menghapus balasan ini.');
            }

            $ratingReply->delete();

            Cache::tags(['ratings', "rating_{$rating->id}", "unit_{$rating->unit_id}"])->flush();

            return true;
        });
    }

    public function getReplies(int $ratingId): array
    {
        $rating = Rating::findOrFail($ratingId);
        
        if (!$this->isAssignedToUnit($rating->unit_id)) {
            throw new \Exception('Anda tidak memiliki akses ke rating ini.');
        }

        return $rating->replies()
            ->with(['employee', 'admin'])
            ->latest()
            ->get()
            ->toArray();
    }
}