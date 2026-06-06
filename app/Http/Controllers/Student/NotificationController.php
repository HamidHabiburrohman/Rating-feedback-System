<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\Notification\MarkAsReadRequest;
use App\Services\Student\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $student = auth('student')->user();
        $this->notificationService->setStudent($student);

        $type = $request->get('type', 'all');

        if ($type === 'unread') {
            $notifications = $this->notificationService->getUnread();
        } elseif ($type === 'read') {
            $notifications = $this->notificationService->getRead();
        } else {
            $notifications = $this->notificationService->getAll();
        }

        $unreadCount = $this->notificationService->getUnreadCount();

        return view('student.notifications.index', compact('notifications', 'unreadCount', 'type'));
    }

    public function markAsRead(MarkAsReadRequest $request, $id)
    {
        $student = auth('student')->user();
        $this->notificationService->setStudent($student);

        $marked = $this->notificationService->markAsRead($id);

        if (!$marked) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Notification marked as read']);
    }

    public function markAllAsRead()
    {
        $student = auth('student')->user();
        $this->notificationService->setStudent($student);

        $this->notificationService->markAllAsRead();

        return redirect()->route('student.notifications.index')->with('success', 'All notifications marked as read');
    }

    public function destroy($id)
    {
        $student = auth('student')->user();
        $this->notificationService->setStudent($student);

        $deleted = $this->notificationService->delete($id);

        if (!$deleted) {
            return response()->json(['success' => false, 'message' => 'Notification not found'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Notification deleted']);
    }

    public function unreadCount()
    {
        $student = auth('student')->user();
        $this->notificationService->setStudent($student);

        $count = $this->notificationService->getUnreadCount();

        return response()->json(['success' => true, 'count' => $count]);
    }
}