<?php

namespace App\Services\Student;

use App\Models\Authentication\Student;
use App\Models\Feedback\Rating;
use App\Models\Report\Report;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileService extends BaseStudentService
{
    public function getProfile(int $studentId): array
    {
        $cacheKey = "student_profile_{$studentId}";

        return Cache::tags(['profile', "student_{$studentId}"])->remember($cacheKey, 300, function () use ($studentId) {
            $student = Student::findOrFail($studentId);

            return [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'student_identifier' => $student->student_identifier,
                'phone' => $student->phone,
                'photo' => $student->photo,
                'bio' => $student->bio,
                'is_active' => $student->is_active,
                'is_verified' => $student->is_verified,
                'created_at' => $student->created_at,
            ];
        });
    }

    public function updateProfile(int $studentId, array $data): bool
    {
        $student = Student::findOrFail($studentId);
        $result = $student->update($data);

        if ($result) {
            Cache::tags(['profile', "student_{$studentId}"])->flush();
        }

        return $result;
    }

    public function updatePassword(int $studentId, string $currentPassword, string $newPassword): bool
    {
        $student = Student::findOrFail($studentId);

        if (!Hash::check($currentPassword, $student->password)) {
            throw new \Exception('Password saat ini tidak sesuai');
        }

        $result = $student->update(['password' => Hash::make($newPassword)]);

        return $result;
    }

    public function updatePhoto(int $studentId, $file): string
    {
        $student = Student::findOrFail($studentId);

        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        $path = $file->store('students/photos', 'public');
        $student->update(['photo' => $path]);

        Cache::tags(['profile', "student_{$studentId}"])->flush();

        return $path;
    }

    public function removePhoto(int $studentId): bool
    {
        $student = Student::findOrFail($studentId);

        if ($student->photo && Storage::disk('public')->exists($student->photo)) {
            Storage::disk('public')->delete($student->photo);
        }

        $result = $student->update(['photo' => null]);

        if ($result) {
            Cache::tags(['profile', "student_{$studentId}"])->flush();
        }

        return $result;
    }

    public function getStats(int $studentId): array
    {
        $cacheKey = "student_profile_stats_{$studentId}";

        return Cache::tags(['profile', "student_{$studentId}"])->remember($cacheKey, 300, function () use ($studentId) {
            $student = Student::findOrFail($studentId);

            return [
                'total_ratings' => $student->ratings()->count(),
                'total_reports' => $student->reports()->count(),
                'total_units_visited' => $student->visits()->distinct('unit_id')->count('unit_id'),
            ];
        });
    }

    public function getRecentActivities(int $studentId, int $limit = 10): array
    {
        $cacheKey = "student_recent_activities_{$studentId}";

        return Cache::tags(['profile', "student_{$studentId}"])->remember($cacheKey, 300, function () use ($studentId, $limit) {
            $student = Student::findOrFail($studentId);

            $ratings = $student->ratings()
                ->with('unit')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function ($rating) {
                    return [
                        'type' => 'rating',
                        'title' => 'Rating untuk ' . ($rating->unit->name ?? 'Unit'),
                        'description' => 'Memberikan rating ' . $rating->overall_score . ' bintang',
                        'created_at' => $rating->created_at,
                        'url' => route('student.ratings.show', $rating->tracking_code),
                    ];
                });

            $reports = $student->reports()
                ->with('unit')
                ->latest()
                ->limit($limit)
                ->get()
                ->map(function ($report) {
                    return [
                        'type' => 'report',
                        'title' => 'Laporan: ' . $report->title,
                        'description' => 'Status: ' . ucfirst($report->status),
                        'created_at' => $report->created_at,
                        'url' => route('student.reports.show', $report->tracking_code),
                    ];
                });

            return $ratings->concat($reports)
                ->sortByDesc('created_at')
                ->take($limit)
                ->values()
                ->toArray();
        });
    }

    public function getWeeklyEngagement(int $studentId): array
    {
        $cacheKey = "student_weekly_engagement_{$studentId}";

        return Cache::tags(['profile', "student_{$studentId}"])->remember($cacheKey, 300, function () use ($studentId) {
            $student = Student::findOrFail($studentId);
            $startDate = now()->subDays(7);

            $ratingsThisWeek = $student->ratings()
                ->where('created_at', '>=', $startDate)
                ->count();

            $reportsThisWeek = $student->reports()
                ->where('created_at', '>=', $startDate)
                ->count();

            $visitsThisWeek = $student->visits()
                ->where('visited_at', '>=', $startDate)
                ->distinct('unit_id')
                ->count('unit_id');

            return [
                'ratings' => $ratingsThisWeek,
                'reports' => $reportsThisWeek,
                'visits' => $visitsThisWeek,
                'total_engagement' => $ratingsThisWeek + $reportsThisWeek + $visitsThisWeek,
            ];
        });
    }

    public function getSessions(int $studentId): array
    {
        try {
            $sessions = DB::table('sessions')
                ->where('user_id', $studentId)
                ->orderByDesc('last_activity')
                ->get()
                ->map(function ($session) {
                    return [
                        'id' => $session->id,
                        'ip_address' => $session->ip_address,
                        'user_agent' => $session->user_agent,
                        'last_activity' => \Carbon\Carbon::createFromTimestamp($session->last_activity),
                        'payload' => $session->payload,
                    ];
                })
                ->toArray();

            return $sessions;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function terminateSession(int $studentId, string $sessionId): bool
    {
        return DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', $studentId)
            ->delete() > 0;
    }

    public function terminateAllSessions(int $studentId): int
    {
        return DB::table('sessions')
            ->where('user_id', $studentId)
            ->delete();
    }
}
