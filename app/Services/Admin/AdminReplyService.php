<?php

namespace App\Services\Admin;

use App\Models\AdminReply;
use App\Models\Rating;
use App\Services\Admin\BaseAdminService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class AdminReplyService extends BaseAdminService
{
    protected array $searchableColumns = ['reply_message'];
    protected array $filterableColumns = ['admin_id', 'rating_id'];
    protected string $defaultSort = 'created_at';
    protected string $defaultOrder = 'desc';

    public function __construct(AdminReply $adminReply)
    {
        $this->model = $adminReply;
        parent::__construct();
    }

    public function getPaginatedReplies(array $filters = []): LengthAwarePaginator
    {
        try {
            $query = $this->getBaseQuery($filters)
                ->with(['admin', 'rating.unit']);

            return $this->executePaginate($query, $filters);
        } catch (\Exception $e) {
            Log::error('AdminReplyService::getPaginatedReplies error', [
                'message' => $e->getMessage(),
                'filters' => $filters,
                'trace' => $e->getTraceAsString()
            ]);

            return new LengthAwarePaginator(
                collect([]),
                0,
                $filters['per_page'] ?? 10,
                1,
                ['path' => request()->url()]
            );
        }
    }

    public function getReplyStats(): array
    {
        return [
            'total' => $this->model->count(),
            'today' => $this->model->whereDate('created_at', today())->count(),
            'this_week' => $this->model->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month' => $this->model->whereMonth('created_at', now()->month)->count(),
        ];
    }

    public function canReplyToRating(Rating $rating): bool
    {
        if ($rating->adminReply()->exists()) {
            return false;
        }

        if ($rating->status !== 'active') {
            return false;
        }

        return true;
    }

    public function createReply(Rating $rating, string $message, int $adminId): AdminReply
    {
        $reply = $this->model->create([
            'rating_id' => $rating->id,
            'admin_id' => $adminId,
            'reply_message' => $message,
            'replied_at' => now(),
        ]);

        $this->logAdminAction('create_reply', $reply, null, [
            'rating_id' => $rating->id,
            'rating_code' => $rating->tracking_code
        ]);

        return $reply;
    }

    public function getReplyById(int $id): ?AdminReply
    {
        return $this->model->with(['admin', 'rating.unit'])->find($id);
    }

    public function updateReply(AdminReply $reply, string $message): AdminReply
    {
        $oldMessage = $reply->reply_message;
        $reply->update(['reply_message' => $message]);

        $this->logAdminAction('update_reply', $reply, null, [
            'old_message' => $oldMessage,
            'new_message' => $message
        ]);

        return $reply->fresh('admin');
    }

    public function deleteReply(AdminReply $reply): bool
    {
        $ratingId = $reply->rating_id;
        $result = $reply->delete();

        if ($result) {
            $this->logAdminAction('delete_reply', $reply, null, [
                'rating_id' => $ratingId
            ]);
        }

        return $result;
    }

    public function getRecentRepliesForUnit(int $unitId, int $limit = 5): array
    {
        return $this->model->whereHas('rating', function ($query) use ($unitId) {
            $query->where('unit_id', $unitId);
        })
            ->with(['admin', 'rating.student'])
            ->latest()
            ->limit($limit)
            ->get()
            ->toArray();
    }

    public function find(int $id, array $with = []): ?AdminReply
    {
        return parent::find($id, $with);
    }

    public function findOrFail(int $id, array $with = []): AdminReply
    {
        return parent::findOrFail($id, $with);
    }
}
