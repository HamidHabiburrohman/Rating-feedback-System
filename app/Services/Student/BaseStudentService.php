<?php

namespace App\Services\Student;

use App\Services\Shared\BaseService;

abstract class BaseStudentService extends BaseService
{
    protected function getStudentId(): ?int
    {
        return session('student_id');
    }

    protected function ensureStudentAuthenticated(): void
    {
        if (!$this->getStudentId()) {
            throw new \Exception('Sesi mahasiswa tidak ditemukan');
        }
    }

    protected function formatRatingResponse($rating): array
    {
        return [
            'id' => $rating->id,
            'tracking_code' => $rating->tracking_code,
            'unit_id' => $rating->unit_id,
            'unit_name' => $rating->unit?->name,
            'overall_score' => $rating->overall_score,
            'comment' => $rating->comment,
            'status' => $rating->status,
            'created_at' => $rating->created_at->toISOString(),
            'can_edit' => $this->canEditRating($rating),
            'can_report' => $this->canReportRating($rating)
        ];
    }

    protected function canEditRating($rating): bool
    {
        if ($rating->student_id !== $this->getStudentId()) {
            return false;
        }

        if ($rating->status === 'archived') {
            return false;
        }

        $hasActiveReport = $rating->activeReport()->exists();
        
        if ($hasActiveReport) {
            return false;
        }

        $lastReport = $rating->report()->where('status', 'resolved')->latest()->first();
        
        if ($lastReport && $lastReport->updated_at->addDays(7) > now()) {
            return false;
        }

        return true;
    }

    protected function canReportRating($rating): bool
    {
        if ($rating->student_id !== $this->getStudentId()) {
            return false;
        }

        if ($rating->status === 'archived') {
            return false;
        }

        if ($rating->activeReport()->exists()) {
            return false;
        }

        $lastReport = $rating->report()->where('status', 'resolved')->latest()->first();
        
        if ($lastReport && $lastReport->updated_at->addDays(7) > now()) {
            return false;
        }

        return true;
    }
}