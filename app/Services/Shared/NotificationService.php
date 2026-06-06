<?php

namespace App\Services\Shared;

use App\Models\System\Notification;
use Illuminate\Database\Eloquent\Model;

class NotificationService
{
    public function send(Model $notifiable, string $type, string $title, string $body, array $data = []): Notification
    {
        return Notification::create([
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->id,
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'data' => $data,
        ]);
    }

    public function sendToAdmins(string $type, string $title, string $body, array $data = []): void
    {
        $admins = \App\Models\Authentication\Admin::where('is_active', true)->get();

        foreach ($admins as $admin) {
            $this->send($admin, $type, $title, $body, $data);
        }
    }

    public function sendToEmployee(int $employeeId, string $type, string $title, string $body, array $data = []): ?Notification
    {
        $employee = \App\Models\Authentication\Employee::find($employeeId);

        if (!$employee) {
            return null;
        }

        return $this->send($employee, $type, $title, $body, $data);
    }

    public function sendToUnitEmployees(int $unitId, string $type, string $title, string $body, array $data = []): void
    {
        $employees = \App\Models\Authentication\Employee::whereHas('unitAssignments', function ($query) use ($unitId) {
            $query->where('unit_id', $unitId)
                ->where('is_active', true);
        })->get();

        foreach ($employees as $employee) {
            $this->send($employee, $type, $title, $body, $data);
        }
    }

    public function sendToStudent(int $studentId, string $type, string $title, string $body, array $data = []): ?Notification
    {
        $student = \App\Models\Authentication\Student::find($studentId);

        if (!$student) {
            return null;
        }

        return $this->send($student, $type, $title, $body, $data);
    }

    public function markAsRead(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);

        if (!$notification) {
            return false;
        }

        $notification->read_at = now();
        return $notification->save();
    }
}