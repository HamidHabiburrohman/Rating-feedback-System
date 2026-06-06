<?php

namespace App\Services\Employee;

use App\Models\Authentication\Employee;
use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingReply;

class RatingReplyService
{
    protected Employee $employee;

    public function setEmployee(Employee $employee): self
    {
        $this->employee = $employee;
        return $this;
    }

    public function getRepliesForRating(int $ratingId): array
    {
        $rating = Rating::where('id', $ratingId)
            ->whereHas('unit', function ($query) {
                $query->whereHas('employeeAssignments', function ($q) {
                    $q->where('employee_id', $this->employee->id)
                        ->where('is_active', true);
                });
            })
            ->first();

        if (!$rating) {
            return [];
        }

        return $rating->replies()->with('employee')->get()->toArray();
    }

    public function create(int $ratingId, string $reply, bool $isPublic = true): ?RatingReply
    {
        $rating = Rating::where('id', $ratingId)
            ->whereHas('unit', function ($query) {
                $query->whereHas('employeeAssignments', function ($q) {
                    $q->where('employee_id', $this->employee->id)
                        ->where('is_active', true);
                });
            })
            ->first();

        if (!$rating) {
            return null;
        }

        $replyModel = RatingReply::create([
            'rating_id' => $ratingId,
            'employee_id' => $this->employee->id,
            'reply' => $reply,
            'is_public' => $isPublic,
        ]);

        $rating->update(['last_replied_at' => now()]);

        return $replyModel;
    }

    public function update(int $replyId, string $reply): ?RatingReply
    {
        $replyModel = RatingReply::where('id', $replyId)
            ->where('employee_id', $this->employee->id)
            ->first();

        if (!$replyModel) {
            return null;
        }

        $replyModel->reply = $reply;
        $replyModel->save();

        return $replyModel;
    }

    public function delete(int $replyId): bool
    {
        $replyModel = RatingReply::where('id', $replyId)
            ->where('employee_id', $this->employee->id)
            ->first();

        if (!$replyModel) {
            return false;
        }

        return $replyModel->delete();
    }
}