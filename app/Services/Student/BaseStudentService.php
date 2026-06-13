<?php

namespace App\Services\Student;

use App\Models\Authentication\Student;
use Illuminate\Support\Facades\Auth;

abstract class BaseStudentService
{
    protected function getStudentId(): ?int
    {
        return Auth::guard('student')->id();
    }

    protected function getStudent(): ?Student
    {
        return Auth::guard('student')->user();
    }

    protected function ensureStudentAuthenticated(): void
    {
        if (!$this->getStudentId()) {
            throw new \Exception('Sesi student tidak ditemukan atau sudah kadaluarsa.');
        }
    }

    protected function isOwnerOfRating(int $ratingId): bool
    {
        $studentId = $this->getStudentId();
        if (!$studentId) return false;

        return \App\Models\Feedback\Rating::where('id', $ratingId)
            ->where('student_id', $studentId)
            ->exists();
    }

    protected function isOwnerOfReport(int $reportId): bool
    {
        $studentId = $this->getStudentId();
        if (!$studentId) return false;

        return \App\Models\Report\Report::where('id', $reportId)
            ->where('student_id', $studentId)
            ->exists();
    }
}