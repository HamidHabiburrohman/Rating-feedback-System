<?php

namespace App\Observers\Shared;

use App\Models\ModerationLog;
use Illuminate\Support\Facades\Cache;

class ModerationLogObserver
{
    public function created(ModerationLog $log): void
    {
        $this->clearCache();
    }

    public function updated(ModerationLog $log): void
    {
        $this->clearCache();
    }

    public function deleted(ModerationLog $log): void
    {
        $this->clearCache();
    }

    protected function clearCache(): void
    {
        Cache::forget('moderation_logs.filter_data');
        Cache::forget('moderation_logs.stats');
        Cache::forget('moderation_logs.recent');
    }
}