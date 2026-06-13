<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Authentication\Student;
use App\Services\Student\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected NotificationService $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        /** @var Student $student */
        $student = auth('student')->user();
        
        try {
            $filters = $request->only(['is_read', 'per_page']);
            $notifications = $this->service->getAll($student->id, $filters);
            
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('student.notifications.partials.list', compact('notifications'))->render(),
                    'pagination' => view('student.notifications.partials.pagination', ['paginator' => $notifications])->render()
                ]);
            }
            
            return view('student.notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memuat notifikasi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('student.dashboard.index')
                ->with('error', 'Gagal memuat notifikasi: ' . $e->getMessage());
        }
    }

    public function markAsRead(Request $request, int $id)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $this->service->markAsRead($student->id, $id);
            
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil ditandai sebagai dibaca'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function markAllAsRead()
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $count = $this->service->markAllAsRead($student->id);
            
            return response()->json([
                'success' => true,
                'message' => "{$count} notifikasi berhasil ditandai sebagai dibaca"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, int $id)
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $this->service->delete($student->id, $id);
            
            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus notifikasi: ' . $e->getMessage()
            ], 500);
        }
    }

    public function unreadCount()
    {
        /** @var \App\Models\Authentication\Student $student */
        $student = auth('student')->user();
        
        try {
            $count = $this->service->getUnreadCount($student->id);
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil jumlah notifikasi'
            ], 500);
        }
    }
}