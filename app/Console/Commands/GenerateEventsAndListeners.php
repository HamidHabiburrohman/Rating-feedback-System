<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateEventsAndListeners extends Command
{
    /**
     * Nama dan signature command.
     */
    protected $signature = 'app:generate-events-listeners';

    /**
     * Deskripsi command.
     */
    protected $description = 'Men-generate struktur folder dan file stub untuk Events dan Listeners (Admin, Employee, Student)';

    /**
     * Eksekusi command.
     */
    public function handle(): int
    {
        $this->info("Memulai pembuatan struktur Events & Listeners...\n");

        $events = [
            'Auth/StudentRegisteredEvent.php' => 'Auth',
            'Auth/AccountDeactivatedEvent.php' => 'Auth',
            'Auth/PasswordResetRequestedEvent.php' => 'Auth',

            'Conversation/MessageSentEvent.php' => 'Conversation',
            'Conversation/MessageReadEvent.php' => 'Conversation',

            'Rating/RatingSubmittedEvent.php' => 'Rating',
            'Rating/RatingRepliedEvent.php' => 'Rating',
            'Rating/RatingStatusUpdatedEvent.php' => 'Rating',

            'Report/ReportSubmittedEvent.php' => 'Report',
            'Report/ReportStatusUpdatedEvent.php' => 'Report',
            'Report/ReportRepliedEvent.php' => 'Report',
        ];

        $listeners = [
            'Auth/SendWelcomeEmailListener.php' => 'Auth',
            'Auth/SendAccountDeactivatedNotificationListener.php' => 'Auth',

            'Conversation/BroadcastNewMessageListener.php' => 'Conversation',
            'Conversation/SendUnreadMessageNotificationListener.php' => 'Conversation',

            'Rating/SendRatingNotificationListener.php' => 'Rating',
            'Rating/RecalculateUnitRatingListener.php' => 'Rating',

            'Report/SendReportNotificationListener.php' => 'Report',
            'Report/CreateReportHistoryLogListener.php' => 'Report',
            'Report/LogModerationActivityListener.php' => 'Report',
        ];

        // 1. Generate Events
        foreach ($events as $fileRelPath => $subNamespace) {
            $fullPath = app_path('Events/' . $fileRelPath);
            $className = pathinfo($fileRelPath, PATHINFO_FILENAME);

            if (!File::exists($fullPath)) {
                File::ensureDirectoryExists(dirname($fullPath));
                File::put($fullPath, $this->getEventStub($subNamespace, $className));
                $this->info("✔ Created Event: Events/{$fileRelPath}");
            } else {
                $this->comment("ℹ Event sudah ada (dilewati): Events/{$fileRelPath}");
            }
        }

        $this->newLine();

        // 2. Generate Listeners
        foreach ($listeners as $fileRelPath => $subNamespace) {
            $fullPath = app_path('Listeners/' . $fileRelPath);
            $className = pathinfo($fileRelPath, PATHINFO_FILENAME);

            if (!File::exists($fullPath)) {
                File::ensureDirectoryExists(dirname($fullPath));
                File::put($fullPath, $this->getListenerStub($subNamespace, $className));
                $this->info("✔ Created Listener: Listeners/{$fileRelPath}");
            } else {
                $this->comment("ℹ Listener sudah ada (dilewati): Listeners/{$fileRelPath}");
            }
        }

        $this->newLine();
        $this->info("Pembuatan struktur Events dan Listeners selesai!");

        return Command::SUCCESS;
    }

    /**
     * Stub template untuk Event class
     */
    private function getEventStub(string $subNamespace, string $className): string
    {
        return <<<PHP
<?php

namespace App\Events\\{$subNamespace};

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class {$className}
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        // TODO: Deklarasikan properti data event
    }
}
PHP;
    }

    /**
     * Stub template untuk Listener class
     */
    private function getListenerStub(string $subNamespace, string $className): string
    {
        return <<<PHP
<?php

namespace App\Listeners\\{$subNamespace};

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class {$className} implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object \$event): void
    {
        // TODO: Tulis logika penanganan event di sini
    }
}
PHP;
    }
}