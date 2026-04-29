<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminReply\StoreAdminReplyRequest;
use App\Models\Admin;
use App\Services\Admin\AdminReplyService;
use App\Models\Rating;
use App\Models\User;
use App\Models\Unit;
use App\Models\AdminReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminReplyController extends Controller
{
    protected AdminReplyService $service;

    public function __construct(AdminReplyService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'admin_id', 'unit_id', 'date', 'sort', 'order', 'per_page']);
        $replies = $this->service->getPaginatedReplies($filters);
        $stats = $this->service->getReplyStats();
        $admins = Admin::whereIn('role', ['admin', 'super_admin'])->get();
        $units = Unit::all();

        return view('admin.admin-replies.index', compact('replies', 'stats', 'admins', 'units'));
    }

    public function store(StoreAdminReplyRequest $request, Rating $rating)
    {
        try {
            if (!$this->service->canReplyToRating($rating)) {
                return redirect()->route('admin.ratings.show', $rating->id)
                    ->with('error', 'Tidak dapat membalas rating ini');
            }

            $this->service->createReply($rating, $request->reply_message, Auth::id());

            return redirect()->route('admin.ratings.show', $rating->id)
                ->with('success', 'Balasan berhasil dikirim');
        } catch (\Exception $e) {
            return redirect()->route('admin.ratings.show', $rating->id)
                ->with('error', 'Gagal mengirim balasan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(AdminReply $adminReply)
    {
        $reply = $adminReply->load(['admin', 'rating.unit']);

        return view('admin.admin-replies.show', compact('reply'));
    }

    public function edit(AdminReply $adminReply)
    {
        $reply = $adminReply->load(['admin', 'rating.unit']);

        return view('admin.admin-replies.edit', compact('reply'));
    }

    public function update(StoreAdminReplyRequest $request, AdminReply $adminReply)
    {
        try {
            $this->service->updateReply($adminReply, $request->reply_message);

            return redirect()->route('admin.admin-replies.show', $adminReply->id)
                ->with('success', 'Balasan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui balasan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(AdminReply $adminReply)
    {
        try {
            $this->service->deleteReply($adminReply);

            return redirect()->route('admin.admin-replies.index')
                ->with('success', 'Balasan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.admin-replies.index')
                ->with('error', 'Gagal menghapus balasan: ' . $e->getMessage());
        }
    }

    public function stats()
    {
        try {
            $stats = $this->service->getReplyStats();

            return view('admin.admin-replies.stats', compact('stats'));
        } catch (\Exception $e) {
            return redirect()->route('admin.admin-replies.index')
                ->with('error', 'Gagal mengambil statistik: ' . $e->getMessage());
        }
    }

    public function recentForUnit($unitId)
    {
        try {
            $replies = $this->service->getRecentRepliesForUnit($unitId);

            return view('admin.admin-replies.recent', compact('replies', 'unitId'));
        } catch (\Exception $e) {
            return redirect()->route('admin.admin-replies.index')
                ->with('error', 'Gagal mengambil balasan terbaru: ' . $e->getMessage());
        }
    }

    public function checkCanReply(Rating $rating)
    {
        try {
            $canReply = $this->service->canReplyToRating($rating);
            $existingReply = $this->service->getReplyById($rating->id);

            return response()->json([
                'success' => true,
                'can_reply' => $canReply,
                'has_reply' => !is_null($existingReply),
                'reply' => $existingReply
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memeriksa status balasan: ' . $e->getMessage()
            ], 500);
        }
    }
}