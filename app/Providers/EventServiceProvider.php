<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\Auth\AccountDeactivatedEvent;
use App\Events\Auth\StudentRegisteredEvent;
use App\Events\Conversation\MessageSentEvent;
use App\Listeners\Auth\SendAccountDeactivatedNotificationListener;
use App\Listeners\Auth\SendWelcomeEmailListener;
use App\Listeners\Conversation\BroadcastNewMessageListener;
use App\Listeners\Conversation\SendUnreadMessageNotificationListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // ================================================================
        // AUTH DOMAIN
        // ================================================================
        AccountDeactivatedEvent::class => [
            SendAccountDeactivatedNotificationListener::class,
        ],
        StudentRegisteredEvent::class => [
            SendWelcomeEmailListener::class,
        ],

        // ================================================================
        // CONVERSATION DOMAIN
        // ================================================================
        MessageSentEvent::class => [
            BroadcastNewMessageListener::class,
            SendUnreadMessageNotificationListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }
}