<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RestructureAdminControllers extends Command
{
    /**
     * Nama dan signature command.
     *
     * @var string
     */
    protected $signature = 'admin:restructure-controllers';

    /**
     * Deskripsi command.
     *
     * @var string
     */
    protected $description = 'Merestrukturisasi folder Controller Admin sesuai hirarki domain terbaru';

    /**
     * Eksekusi command.
     */
    public function handle(): int
    {
        $basePath = app_path('Http/Controllers/Admin');

        if (!File::exists($basePath)) {
            $this->error("Directory tidak ditemukan: {$basePath}");
            return Command::FAILURE;
        }

        $this->info("Memulai restrukturisasi Admin Controllers...\n");

        // 1. Pindahkan file controller & sesuaikan namespace
        $moves = [
            'Facility/FacilityController.php' => [
                'target' => 'Unit/FacilityController.php',
                'old_namespace' => 'namespace App\Http\Controllers\Admin\Facility;',
                'new_namespace' => 'namespace App\Http\Controllers\Admin\Unit;',
            ],
            'Export/ExportController.php' => [
                'target' => 'Report/ExportController.php',
                'old_namespace' => 'namespace App\Http\Controllers\Admin\Export;',
                'new_namespace' => 'namespace App\Http\Controllers\Admin\Report;',
            ],
        ];

        foreach ($moves as $sourceRel => $config) {
            $sourcePath = $basePath . '/' . $sourceRel;
            $targetPath = $basePath . '/' . $config['target'];

            if (File::exists($sourcePath)) {
                // Pastikan folder tujuan ada
                File::ensureDirectoryExists(dirname($targetPath));

                // Baca konten & update namespace
                $content = File::get($sourcePath);
                $updatedContent = str_replace(
                    $config['old_namespace'],
                    $config['new_namespace'],
                    $content
                );

                // Tulis ke lokasi baru & hapus file lama
                File::put($targetPath, $updatedContent);
                File::delete($sourcePath);

                $this->info("✔ Dipindahkan: {$sourceRel} -> {$config['target']}");

                // Hapus folder lama jika sudah kosong
                $oldDir = dirname($sourcePath);
                if (File::isDirectory($oldDir) && count(File::files($oldDir)) === 0) {
                    File::deleteDirectory($oldDir);
                    $this->comment("  Hapus folder kosong: " . basename($oldDir));
                }
            } else {
                $this->warn("⚠ File asal tidak ditemukan: {$sourceRel}");
            }
        }

        // 2. Generate UserController.php jika belum ada
        $userControllerPath = $basePath . '/User/UserController.php';
        if (!File::exists($userControllerPath)) {
            File::ensureDirectoryExists(dirname($userControllerPath));
            File::put($userControllerPath, $this->getUserControllerStub());
            $this->info("✔ Dibuat file controller baru: User/UserController.php");
        } else {
            $this->comment("ℹ User/UserController.php sudah ada.");
        }

        $this->newLine();
        $this->info("Restrukturisasi Controllers Selesai!");

        return Command::SUCCESS;
    }

    /**
     * Stub dasar untuk UserController baru
     */
    private function getUserControllerStub(): string
    {
        return <<<'PHP'
<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // TODO: Simpan user/admin baru
    }

    public function show(string $id)
    {
        return view('admin.users.show');
    }

    public function edit(string $id)
    {
        return view('admin.users.edit');
    }

    public function update(Request $request, string $id)
    {
        // TODO: Update data user/admin
    }

    public function destroy(string $id)
    {
        // TODO: Hapus user/admin
    }

    public function toggleStatus(string $id)
    {
        // TODO: Toggle status aktif/non-aktif user
    }
}
PHP;
    }
}