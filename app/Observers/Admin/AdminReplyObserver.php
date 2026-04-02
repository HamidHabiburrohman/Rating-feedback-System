<?php

namespace App\Observers\Admin;

use App\Models\AdminReply;
use App\Models\ModerationLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminReplyObserver
{
    public function created(AdminReply $reply): void
    {
        $this->clearCache($reply);
        $this->logActivity('created', $reply);
    }

    public function updated(AdminReply $reply): void
    {
        $this->clearCache($reply);
        
        if ($reply->isDirty('reply_message')) {
            $this->logActivity('updated', $reply, [
                'old' => $reply->getOriginal('reply_message'),
            ]);
        }
    }

    public function deleted(AdminReply $reply): void
    {
        $this->clearCache($reply);
        $this->logActivity('deleted', $reply);
    }

    public function restored(AdminReply $reply): void
    {
        $this->clearCache($reply);
        $this->logActivity('restored', $reply);
    }

    protected function clearCache(AdminReply $reply): void
    {
        Cache::forget("rating.{$reply->rating_id}.reply");
        
        if ($reply->rating && $reply->rating->unit_id) {
            Cache::forget("unit.{$reply->rating->unit_id}.recent_replies");
        }
        
        Cache::forget('admin_replies.stats');
    }

    protected function logActivity(string $action, AdminReply $reply, array $additional = []): void
    {
        try {
            ModerationLog::create([
                'admin_id' => Auth::id(),
                'action' => 'admin_reply_' . $action,
                'target_type' => 'admin_reply',
                'target_id' => $reply->id,
                'metadata' => json_encode(array_merge([
                    'rating_id' => $reply->rating_id,
                    'admin_id' => $reply->admin_id,
                ], $additional)),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to log admin reply activity: ' . $e->getMessage());
        }
    }
}