<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RestructureAdminServices extends Command
{
    protected $signature = 'admin:restructure-services';
    protected $description = 'Merestrukturisasi folder Services Admin secara aman dan presisi';

    public function handle(): int
    {
        $basePath = app_path('Services/Admin');

        if (!File::exists($basePath)) {
            $this->error("Directory tidak ditemukan: {$basePath}");
            return Command::FAILURE;
        }

        $this->info("Memulai restrukturisasi Admin Services...\n");

        // Daftar pemindahan file secara eksplisit
        $moveMap = [
            // 1. Pindahkan ModerationLogService.php ke subfolder Moderation
            [
                'source' => $basePath . '/ModerationLogService.php',
                'target' => $basePath . '/Moderation/ModerationLogService.php',
                'old_namespace' => 'namespace App\Services\Admin;',
                'new_namespace' => 'namespace App\Services\Admin\Moderation;',
            ],
            // 2. Pindahkan FacilityService.php ke subfolder Unit
            [
                'source' => $basePath . '/Facility/FacilityService.php',
                'target' => $basePath . '/Unit/FacilityService.php',
                'old_namespace' => 'namespace App\Services\Admin\Facility;',
                'new_namespace' => 'namespace App\Services\Admin\Unit;',
            ],
            // 3. Pindahkan IconService.php ke subfolder Shared
            [
                'source' => $basePath . '/Facility/IconService.php',
                'target' => $basePath . '/Shared/IconService.php',
                'old_namespace' => 'namespace App\Services\Admin\Facility;',
                'new_namespace' => 'namespace App\Services\Admin\Shared;',
            ],
        ];

        foreach ($moveMap as $item) {
            $sourcePath = $item['source'];
            $targetPath = $item['target'];

            if (File::exists($sourcePath)) {
                // Pastikan folder tujuan ada
                File::ensureDirectoryExists(dirname($targetPath));

                // Update namespace di dalam file
                $content = File::get($sourcePath);
                $updatedContent = str_replace(
                    $item['old_namespace'],
                    $item['new_namespace'],
                    $content
                );

                // Tulis file ke lokasi baru dan hapus file lama
                File::put($targetPath, $updatedContent);
                File::delete($sourcePath);

                $this->info("✔ Dipindahkan: " . str_replace($basePath . '/', '', $sourcePath) . " -> " . str_replace($basePath . '/', '', $targetPath));
            } else {
                $this->warn("⚠ File tidak ditemukan (dilewati): " . str_replace($basePath . '/', '', $sourcePath));
            }
        }

        // Hapus folder Facility HANYA jika folder tersebut ada dan sudah kosong
        $facilityDir = $basePath . '/Facility';
        if (File::isDirectory($facilityDir)) {
            $filesInFacility = File::allFiles($facilityDir);
            if (count($filesInFacility) === 0) {
                File::deleteDirectory($facilityDir);
                $this->comment("✔ Folder kosong 'Facility' berhasil dihapus.");
            }
        }

        // Pastikan UserService.php ada di folder User
        $userServicePath = $basePath . '/User/UserService.php';
        if (!File::exists($userServicePath)) {
            File::ensureDirectoryExists(dirname($userServicePath));
            File::put($userServicePath, $this->getUserServiceStub());
            $this->info("✔ Dibuat file service baru: User/UserService.php");
        } else {
            $this->comment("ℹ User/UserService.php sudah ada.");
        }

        $this->newLine();
        $this->info("Restrukturisasi Services Selesai Tanpa Masalah!");

        return Command::SUCCESS;
    }

    private function getUserServiceStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Services\Admin\User;

use App\Services\Admin\Shared\BaseAdminService;

class UserService extends BaseAdminService
{
    public function getAllUsers(array $filters = [])
    {
        // TODO: Logika mengambil daftar user/admin
    }

    public function createUser(array $data)
    {
        // TODO: Logika pembuatan akun user/admin
    }

    public function updateUser(string $id, array $data)
    {
        // TODO: Logika update akun user/admin
    }

    public function deleteUser(string $id)
    {
        // TODO: Logika hapus akun user/admin
    }

    public function toggleUserStatus(string $id)
    {
        // TODO: Logika toggle status user/admin
    }
}
PHP;
    }
}