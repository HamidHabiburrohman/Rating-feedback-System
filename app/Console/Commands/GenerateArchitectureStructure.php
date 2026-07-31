<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateArchitectureStructure extends Command
{
    /**
     * Nama dan signature command.
     */
    protected $signature = 'app:generate-architecture';

    /**
     * Deskripsi command.
     */
    protected $description = 'Men-generate struktur folder dan file stub untuk Jobs, Data (DTOs), Notifications, dan Rules';

    /**
     * Eksekusi command.
     */
    public function handle(): int
    {
        $this->info("Memulai pembuatan struktur Jobs, Data (DTOs), Notifications, & Rules...\n");

        $jobs = [
            'Export/ProcessReportExportJob.php' => 'Export',
            'Export/ProcessRatingExportJob.php' => 'Export',
            'Image/ProcessUnitPhotoJob.php' => 'Image',
            'Image/ProcessMessageAttachmentJob.php' => 'Image',
            'QRCode/GenerateQrCodeBatchJob.php' => 'QRCode',
        ];

        $dtos = [
            'Auth/LoginData.php' => 'Auth',
            'Auth/RegisterStudentData.php' => 'Auth',
            'Conversation/SendMessageData.php' => 'Conversation',
            'Rating/CreateRatingData.php' => 'Rating',
            'Rating/ReplyRatingData.php' => 'Rating',
            'Report/CreateReportData.php' => 'Report',
            'Report/UpdateReportStatusData.php' => 'Report',
            'Unit/CreateUnitData.php' => 'Unit',
            'User/CreateUserData.php' => 'User',
        ];

        $notifications = [
            'Auth/AccountDeactivatedNotification.php' => 'Auth',
            'Auth/WelcomeNotification.php' => 'Auth',
            'Conversation/NewMessageNotification.php' => 'Conversation',
            'Rating/RatingSubmittedNotification.php' => 'Rating',
            'Rating/RatingRepliedNotification.php' => 'Rating',
            'Report/ReportSubmittedNotification.php' => 'Report',
            'Report/ReportStatusChangedNotification.php' => 'Report',
            'Report/ReportRepliedNotification.php' => 'Report',
        ];

        $rules = [
            'GPS/ValidGpsRadiusRule.php' => 'GPS',
            'QRCode/ValidQrCodeTokenRule.php' => 'QRCode',
            'Unit/ValidUnitDepartmentRule.php' => 'Unit',
            'User/ValidEmployeeAssignmentRule.php' => 'User',
        ];

        $this->generateFiles('Jobs', $jobs, fn($sub, $class) => $this->getJobStub($sub, $class));

        $this->generateFiles('Data', $dtos, fn($sub, $class) => $this->getDataStub($sub, $class));

        $this->generateFiles('Notifications', $notifications, fn($sub, $class) => $this->getNotificationStub($sub, $class));

        $this->generateFiles('Rules', $rules, fn($sub, $class) => $this->getRuleStub($sub, $class));

        $this->newLine();
        $this->info("Pembuatan struktur Jobs, Data, Notifications, dan Rules selesai!");

        return Command::SUCCESS;
    }

    /**
     * Helper untuk generate file-file stub
     */
    private function generateFiles(string $baseDir, array $files, callable $stubCallback): void
    {
        $this->info("=== Generating {$baseDir} ===");

        foreach ($files as $fileRelPath => $subNamespace) {
            $fullPath = app_path("{$baseDir}/{$fileRelPath}");
            $className = pathinfo($fileRelPath, PATHINFO_FILENAME);

            if (!File::exists($fullPath)) {
                File::ensureDirectoryExists(dirname($fullPath));
                File::put($fullPath, $stubCallback($subNamespace, $className));
                $this->info("✔ Created: {$baseDir}/{$fileRelPath}");
            } else {
                $this->comment("ℹ Sudah ada (dilewati): {$baseDir}/{$fileRelPath}");
            }
        }

        $this->newLine();
    }

    /**
     * Stub template untuk Job class
     */
    private function getJobStub(string $subNamespace, string $className): string
    {
        return <<<PHP
<?php

namespace App\Jobs\\{$subNamespace};

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class {$className} implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // TODO: Deklarasikan data/payload job
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // TODO: Logika pemrosesan background job
    }
}
PHP;
    }

    /**
     * Stub template untuk Data (DTO) class
     */
    private function getDataStub(string $subNamespace, string $className): string
    {
        return <<<PHP
<?php

namespace App\Data\\{$subNamespace};

readonly class {$className}
{
    /**
     * Create a new DTO instance.
     */
    public function __construct(
        // TODO: Definisikan properti ber-tipe data tebal (type-safe)
    ) {}

    /**
     * Membentuk DTO dari array/request
     */
    public static function fromArray(array \$data): self
    {
        return new self(
            // TODO: Mapped array ke constructor
        );
    }

    /**
     * Mengubah DTO menjadi array
     */
    public function toArray(): array
    {
        return [
            // TODO: Mapped DTO ke array
        ];
    }
}
PHP;
    }

    /**
     * Stub template untuk Notification class
     */
    private function getNotificationStub(string $subNamespace, string $className): string
    {
        return <<<PHP
<?php

namespace App\Notifications\\{$subNamespace};

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class {$className} extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        // TODO: Inisialisasi data notifikasi
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object \$notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object \$notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Notifikasi sistem.')
                    ->action('Lihat Detail', url('/'))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object \$notifiable): array
    {
        return [
            // TODO: Payload untuk notifikasi database
        ];
    }
}
PHP;
    }

    /**
     * Stub template untuk Rule class
     */
    private function getRuleStub(string $subNamespace, string $className): string
    {
        return <<<PHP
<?php

namespace App\Rules\\{$subNamespace};

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class {$className} implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  \$fail
     */
    public function validate(string \$attribute, mixed \$value, Closure \$fail): void
    {
        // TODO: Tulis logika validasi khusus di sini
    }
}
PHP;
    }
}