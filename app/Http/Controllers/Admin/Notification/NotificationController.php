<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return view('admin.notifications.index');
    }

    public function latest(Request $request)
    {
        $admin = auth('admin')->user();
        
        if (!$admin || !method_exists($admin, 'notifications')) {
            return response()->json(['success' => true, 'notifications' => []]);
        }

        $notifications = $admin->notifications()
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($notif) {
                return [
                    'id' => $notif->id,
                    'type' => $notif->type ?? 'info',
                    'icon' => $this->getIconForType($notif->type ?? 'info'),
                    'message' => $notif->data['message'] ?? 'New notification',
                    'time' => $notif->created_at->diffForHumans(),
                    'read_at' => $notif->read_at
    ];
            });

        return response()->json(['success' => true, 'notifications' => $notifications]);
    }

    public function markAllAsRead()
    {
        $admin = auth('admin')->user();
        
        if ($admin && method_exists($admin, 'notifications')) {
            $admin->unreadNotifications->markAsRead();
        }

        return response()->json(['success' => true]);
    }

    protected function getIconForType(string $type): string
    {
        return match($type) {
            'warning' => 'alert-triangle',
            'success' => 'star',
            'error' => 'alert-circle',
            default => 'bell',
        };
    }
}