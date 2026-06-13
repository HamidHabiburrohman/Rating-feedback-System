<?php

namespace App\Services\Student;

use App\Models\Authentication\Student;
use App\Models\System\Notification;
use Illuminate\Support\Facades\Cache;

class NotificationService extends BaseStudentService
{
    public function getAll(int $studentId, array $filters = [])
    {
        $query = Notification::where('notifiable_type', Student::class)
            ->where('notifiable_id', $studentId);

        if (!empty($filters['is_read'])) {
            $query->whereNotNull('read_at');
        }

        if (isset($filters['is_read']) && $filters['is_read'] === false) {
            $query->whereNull('read_at');
        }

        return $query->latest()->paginate($filters['per_page'] ?? 20);
    }

    public function getUnreadCount(int $studentId): int
    {
        $cacheKey = "student_unread_notifications_{$studentId}";

        return Cache::tags(['notifications', "student_{$studentId}"])->remember($cacheKey, 60, function () use ($studentId) {
            return Notification::where('notifiable_type', Student::class)
                ->where('notifiable_id', $studentId)
                ->whereNull('read_at')
                ->count();
        });
    }

    public function markAsRead(int $studentId, int $notificationId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('notifiable_type', Student::class)
            ->where('notifiable_id', $studentId)
            ->first();

        if (!$notification) {
            return false;
        }

        $notification->update(['read_at' => now()]);

        Cache::tags(['notifications', "student_{$studentId}"])->flush();

        return true;
    }

    public function markAllAsRead(int $studentId): int
    {
        $count = Notification::where('notifiable_type', Student::class)
            ->where('notifiable_id', $studentId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        Cache::tags(['notifications', "student_{$studentId}"])->flush();

        return $count;
    }

    public function delete(int $studentId, int $notificationId): bool
    {
        $notification = Notification::where('id', $notificationId)
            ->where('notifiable_type', Student::class)
            ->where('notifiable_id', $studentId)
            ->first();

        if (!$notification) {
            return false;
        }

        $notification->delete();

        Cache::tags(['notifications', "student_{$studentId}"])->flush();

        return true;
    }

    public static function sendToStudent(Student $student, string $type, string $title, string $message, array $data = []): Notification
    {
        $notification = Notification::create([
            'notifiable_type' => Student::class,
            'notifiable_id' => $student->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => json_encode($data),
            'read_at' => null,
        ]);

        Cache::tags(['notifications', "student_{$student->id}"])->flush();

        return $notification;
    }
}