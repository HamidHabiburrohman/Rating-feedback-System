<?php

namespace App\Services\Student;

use App\Models\Authentication\Student;
use App\Models\System\Notification;
use Illuminate\Database\Eloquent\Collection;

class NotificationService
{
    protected Student $student;

    public function setStudent(Student $student): self
    {
        $this->student = $student;
        return $this;
    }

    public function getAll(): Collection
    {
        return $this->student->notifications()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUnread(): Collection
    {
        return $this->student->notifications()
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRead(): Collection
    {
        return $this->student->notifications()
            ->whereNotNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getUnreadCount(): int
    {
        return $this->student->notifications()
            ->whereNull('read_at')
            ->count();
    }

    public function markAsRead(int $notificationId): bool
    {
        $notification = $this->student->notifications()
            ->where('id', $notificationId)
            ->first();

        if (!$notification) {
            return false;
        }

        $notification->read_at = now();
        return $notification->save();
    }

    public function markAllAsRead(): bool
    {
        return $this->student->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function delete(int $notificationId): bool
    {
        $notification = $this->student->notifications()
            ->where('id', $notificationId)
            ->first();

        if (!$notification) {
            return false;
        }

        return $notification->delete();
    }
}