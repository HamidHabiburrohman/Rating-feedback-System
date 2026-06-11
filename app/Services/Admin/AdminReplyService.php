<?php

namespace App\Services\Admin;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingReply;
use App\Models\Report\Report;
use App\Models\Report\ReportReply;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminReplyService extends BaseAdminService
{
    public function replyToRating(int $ratingId, string $reply, bool $isPublic, int $adminId): RatingReply
    {
        return DB::transaction(function () use ($ratingId, $reply, $isPublic, $adminId) {
            $rating = Rating::findOrFail($ratingId);
            
            $ratingReply = RatingReply::create([
                'rating_id' => $ratingId,
                'admin_id' => $adminId,
                'employee_id' => null,
                'reply' => $reply,
                'is_public' => $isPublic,
            ]);

            $rating->update(['last_replied_at' => now()]);

            Cache::tags(['ratings', "rating_{$ratingId}", "unit_{$rating->unit_id}"])->flush();

            return $ratingReply;
        });
    }

    public function replyToReport(int $reportId, string $reply, bool $isPublic, int $adminId): ReportReply
    {
        return DB::transaction(function () use ($reportId, $reply, $isPublic, $adminId) {
            $report = Report::findOrFail($reportId);
            
            $reportReply = ReportReply::create([
                'report_id' => $reportId,
                'admin_id' => $adminId,
                'employee_id' => null,
                'reply' => $reply,
                'is_public' => $isPublic,
            ]);

            $report->update(['last_replied_at' => now()]);

            Cache::tags(['reports', "report_{$reportId}", "unit_{$report->unit_id}"])->flush();

            return $reportReply;
        });
    }
}