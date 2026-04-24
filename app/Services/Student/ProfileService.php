<?php

namespace App\Services\Student;

use App\Models\Student;
use App\Models\StudentSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

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
        $student = $this->student->where('student_identifier', $studentIdentifier)->first();

        if (!$student) {
            throw new \Exception('Student not found');
        }

        return $student;
    }

    public function getStats(string $studentIdentifier): array
    {
        $student = $this->getProfile($studentIdentifier);

        $ratingsCount = $student->ratings()->count();
        $reportsCount = $student->reports()->count();
        $sessionsCount = $student->sessions()->count();

        return [
            'total_ratings' => $ratingsCount,
            'total_reports' => $reportsCount,
            'total_sessions' => $sessionsCount,
            'last_active' => $student->sessions()
                ->latest('last_activity_at')
                ->first()?->last_activity_at?->diffForHumans() ?? 'Never',
            'average_rating' => round($student->ratings()->avg('overall_score') ?? 0, 2),
            'member_since' => $student->created_at->format('d M Y')
        ];
    }

    public function getRecentActivities(string $studentIdentifier): Collection
    {
        $student = $this->getProfile($studentIdentifier);
        
        $ratings = $student->ratings()->with('unit')->latest()->limit(10)->get()->map(function($rating) {
            return (object)[
                'type' => 'rating',
                'description' => 'Memberikan rating untuk ' . ($rating->unit->name ?? 'Unit'),
                'created_at' => $rating->created_at,
                'unit_name' => $rating->unit->name ?? null,
            ];
        });
        
        $reports = $student->reports()->with('rating.unit')->latest()->limit(10)->get()->map(function($report) {
            return (object)[
                'type' => 'report',
                'description' => 'Melaporkan masalah pada ' . ($report->rating->unit->name ?? 'Unit'),
                'created_at' => $report->created_at,
                'unit_name' => $report->rating->unit->name ?? null,
            ];
        });
        
        $activities = $ratings->concat($reports)->sortByDesc('created_at')->take(10);
        
        return $activities->values();
    }

    public function getWeeklyEngagement(string $studentIdentifier): array
    {
        $student = $this->getProfile($studentIdentifier);
        
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = $student->ratings()
                ->whereDate('created_at', $date)
                ->count();
            $weeklyData[] = $count;
        }
        
        return $weeklyData;
    }

    public function updateProfile(string $studentIdentifier, array $data, $photo = null): Student
    {
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

        if ($photo && $photo->isValid()) {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                Storage::disk('public')->delete($student->photo);
            }

            $path = $photo->store('student/profile-photos', 'public');
            $filteredData['photo'] = $path;
        }

        if (!empty($filteredData)) {
            $student->update($filteredData);
        }

        return $student->fresh();
    }

    public function getActiveSessions(string $studentIdentifier): array
    {
        $student = $this->getProfile($studentIdentifier);

        return $this->studentSession->where('student_id', $student->id)
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
        $student = $this->getProfile($studentIdentifier);

        $session = $this->studentSession->where('student_id', $student->id)
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
        $student = $this->getProfile($studentIdentifier);

        return $this->studentSession->where('student_id', $student->id)
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