<?php
// app/Services/Student/ProfileService.php

namespace App\Services\Student;

use App\Models\Student;
use App\Models\StudentSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileService extends BaseStudentService
{
    protected Student $student;
    protected StudentSession $studentSession;

    public function __construct(Student $student, StudentSession $studentSession)
    {
        $this->student = $student;
        $this->studentSession = $studentSession;
    }

    public function getProfile(string $studentIdentifier): Student
    {
        Log::info('[PROFILE SERVICE] getProfile called with student_identifier: ' . $studentIdentifier);

        $student = $this->student->where('student_identifier', $studentIdentifier)->first();

        Log::info('[PROFILE SERVICE] where(student_identifier) result: ' . ($student ? 'found - ' . $student->name : 'NULL'));

        if (!$student) {
            Log::error('[PROFILE SERVICE] Student not found for student_identifier: ' . $studentIdentifier);
            throw new \Exception('Student not found');
        }

        Log::info('[PROFILE SERVICE] Student found: ' . $student->student_identifier . ' - ' . $student->name . ' (internal id: ' . $student->id . ')');
        return $student;
    }

    public function getStats(string $studentIdentifier): array
    {
        Log::info('[PROFILE SERVICE] getStats called with student_identifier: ' . $studentIdentifier);

        $student = $this->getProfile($studentIdentifier);

        Log::info('[PROFILE SERVICE] Getting ratings count...');
        $ratingsCount = $student->ratings()->count();
        Log::info('[PROFILE SERVICE] Ratings count: ' . $ratingsCount);

        Log::info('[PROFILE SERVICE] Getting reports count...');
        $reportsCount = $student->reports()->count();
        Log::info('[PROFILE SERVICE] Reports count: ' . $reportsCount);

        Log::info('[PROFILE SERVICE] Getting sessions count...');
        $sessionsCount = $student->sessions()->count();
        Log::info('[PROFILE SERVICE] Sessions count: ' . $sessionsCount);

        $stats = [
            'total_ratings' => $ratingsCount,
            'total_reports' => $reportsCount,
            'total_sessions' => $sessionsCount,
            'last_active' => $student->sessions()
                ->latest('last_activity_at')
                ->first()?->last_activity_at?->diffForHumans() ?? 'Never',
            'average_rating' => round($student->ratings()->avg('overall_score') ?? 0, 2),
            'member_since' => $student->created_at->format('d M Y')
        ];

        Log::info('[PROFILE SERVICE] Stats result: ' . json_encode($stats));
        return $stats;
    }

    public function updateProfile(string $studentIdentifier, array $data, $photo = null): Student
    {
        Log::info('[PROFILE SERVICE] updateProfile called for student_identifier: ' . $studentIdentifier);
        Log::info('[PROFILE SERVICE] Data to update: ' . json_encode($data));

        $student = $this->getProfile($studentIdentifier);

        $allowedFields = [
            'name', 'email', 'password',
            'major', 'class_year', 'bio',
            'phone', 'location', 'portfolio_url', 'linkedin_url'
        ];

        $filteredData = array_intersect_key($data, array_flip($allowedFields));

        if (isset($filteredData['password']) && empty($filteredData['password'])) {
            unset($filteredData['password']);
        }

        Log::info('[PROFILE SERVICE] Filtered data to update: ' . json_encode($filteredData));

        if ($photo && $photo->isValid()) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $path = $photo->store('student/profile-photos', 'public');
            $filteredData['photo'] = $path;
            Log::info('[PROFILE SERVICE] Photo uploaded to: ' . $path);
        }

        if (!empty($filteredData)) {
            $student->update($filteredData);
            Log::info('[PROFILE SERVICE] Update executed successfully');
        } else {
            Log::warning('[PROFILE SERVICE] No data to update');
        }

        return $student->fresh();
    }

    public function getActiveSessions(string $studentIdentifier): array
    {
        Log::info('[PROFILE SERVICE] getActiveSessions called for student_identifier: ' . $studentIdentifier);

        $student = $this->getProfile($studentIdentifier);
        $internalId = $student->id;

        Log::info('[PROFILE SERVICE] Internal student ID for sessions query: ' . $internalId);

        return $this->studentSession->where('student_id', $internalId)
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

    public function terminateSession(string $studentIdentifier, int $sessionId): bool
    {
        Log::info('[PROFILE SERVICE] terminateSession called for student_identifier: ' . $studentIdentifier . ', sessionId: ' . $sessionId);

        $student = $this->getProfile($studentIdentifier);
        $internalId = $student->id;

        $session = $this->studentSession->where('student_id', $internalId)
            ->where('id', $sessionId)
            ->first();

        if (!$session) {
            throw new \Exception('Session not found');
        }

        if ($session->session_token === session()->getId()) {
            throw new \Exception('Tidak dapat mengakhiri sesi saat ini');
        }

        return $session->delete();
    }

    public function terminateAllSessions(string $studentIdentifier): int
    {
        Log::info('[PROFILE SERVICE] terminateAllSessions called for student_identifier: ' . $studentIdentifier);

        $student = $this->getProfile($studentIdentifier);
        $internalId = $student->id;

        return $this->studentSession->where('student_id', $internalId)
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