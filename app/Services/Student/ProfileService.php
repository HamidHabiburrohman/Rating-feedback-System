<?php

namespace App\Services\Student;

use App\Models\Student;
use App\Models\StudentSession;

class ProfileService extends BaseStudentService
{
    protected Student $student;
    protected StudentSession $studentSession;

    public function __construct(Student $student, StudentSession $studentSession)
    {
        $this->student = $student;
        $this->studentSession = $studentSession;
    }

    public function getProfile(int $studentId): Student
    {
        return $this->student->findOrFail($studentId);
    }

    public function getStats(int $studentId): array
    {
        $student = $this->getProfile($studentId);

        return [
            'total_ratings' => $student->ratings()->count(),
            'total_reports' => $student->reports()->count(),
            'total_sessions' => $student->sessions()->count(),
            'last_active' => $student->sessions()
                ->latest('last_activity_at')
                ->first()?->last_activity_at?->diffForHumans() ?? 'Never',
            'average_rating' => round($student->ratings()->avg('overall_score') ?? 0, 2),
            'member_since' => $student->created_at->format('d M Y')
        ];
    }

    public function updateProfile(int $studentId, array $data): Student
    {
        $student = $this->getProfile($studentId);
        $student->update($data);
        
        return $student->fresh();
    }

    public function getActiveSessions(int $studentId): array
    {
        return $this->studentSession->where('student_id', $studentId)
            ->where('last_activity_at', '>=', now()->subMinutes(30))
            ->orderByDesc('last_activity_at')
            ->get()
            ->map(fn($session) => [
                'id' => $session->id,
                'session_token' => substr($session->session_token, 0, 8) . '...',
                'ip_address' => $session->ip_address ?? 'Unknown',
                'user_agent' => $this->parseUserAgent($session->user_agent),
                'last_activity' => $session->last_activity_at?->diffForHumans(),
                'is_current' => $session->session_token === session()->getId()
            ])
            ->toArray();
    }

    public function terminateSession(int $studentId, int $sessionId): bool
    {
        $session = $this->studentSession->where('student_id', $studentId)
            ->where('id', $sessionId)
            ->firstOrFail();

        if ($session->session_token === session()->getId()) {
            throw new \Exception('Tidak dapat mengakhiri sesi saat ini');
        }

        return $session->delete();
    }

    public function terminateAllSessions(int $studentId): int
    {
        return $this->studentSession->where('student_id', $studentId)
            ->where('session_token', '!=', session()->getId())
            ->delete();
    }

    private function parseUserAgent(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        if (str_contains($userAgent, 'Windows')) {
            return 'Windows PC';
        } elseif (str_contains($userAgent, 'Mac')) {
            return 'Mac';
        } elseif (str_contains($userAgent, 'iPhone')) {
            return 'iPhone';
        } elseif (str_contains($userAgent, 'Android')) {
            return 'Android Device';
        } elseif (str_contains($userAgent, 'Linux')) {
            return 'Linux Device';
        }

        return 'Unknown Device';
    }
}