<?php

namespace App\Observers\Admin;

use App\Models\StudentSession;
use App\Models\ModerationLog;
use Illuminate\Support\Facades\Log;

class StudentSessionObserver
{
    public function created(StudentSession $session): void
    {
        $this->logActivity('created', $session);
    }

    public function updated(StudentSession $session): void
    {
        if ($session->isDirty('last_activity_at')) {
            $this->logActivity('activity_updated', $session, [
                'old' => $session->getOriginal('last_activity_at')?->toDateTimeString(),
                'new' => $session->last_activity_at?->toDateTimeString(),
            ]);
        }
    }

    public function deleted(StudentSession $session): void
    {
        $this->logActivity('deleted', $session);
    }

    protected function logActivity(string $action, StudentSession $session, array $additional = []): void
    {
        try {
            $data = [
                'student_id' => $session->student_id,
                'session_token' => substr($session->session_token, 0, 10) . '...',
                'ip_address' => $session->ip_address,
            ];

            ModerationLog::create([
                'admin_id' => null, // Ini dari system, bukan admin
                'action' => 'student_session_' . $action,
                'target_type' => 'student_session',
                'target_id' => $session->id,
                'metadata' => json_encode(array_merge($data, $additional)),
            ]);

            Log::info('StudentSession ' . $action, [
                'session_id' => $session->id,
                'student_id' => $session->student_id,
                'additional' => $additional,
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log student session activity: ' . $e->getMessage());
        }
    }
}