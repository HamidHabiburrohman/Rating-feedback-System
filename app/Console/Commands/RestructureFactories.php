<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class RestructureFactories extends Command
{
    protected $signature = 'factories:restructure 
                            {--force : Overwrite existing factories}';

    protected $description = 'Restructure all factories to match final models';

    protected Filesystem $files;

    protected array $factories = [];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->defineFactories();
    }

    protected function defineFactories(): void
    {
        $this->factories = [
            'AdminFactory.php' => $this->getAdminFactory(),
            'StudentFactory.php' => $this->getStudentFactory(),
            'EmployeeFactory.php' => $this->getEmployeeFactory(),
            'UnitTypeFactory.php' => $this->getUnitTypeFactory(),
            'UnitDepartmentFactory.php' => $this->getUnitDepartmentFactory(),
            'UnitFactory.php' => $this->getUnitFactory(),
            'FacilityFactory.php' => $this->getFacilityFactory(),
            'UnitFacilityFactory.php' => $this->getUnitFacilityFactory(),
            'UnitPhotoFactory.php' => $this->getUnitPhotoFactory(),
            'QrCodeFactory.php' => $this->getQrCodeFactory(),
            'RatingCategoryFactory.php' => $this->getRatingCategoryFactory(),
            'RatingFactory.php' => $this->getRatingFactory(),
            'RatingScoreFactory.php' => $this->getRatingScoreFactory(),
            'RatingAttachmentFactory.php' => $this->getRatingAttachmentFactory(),
            'RatingReplyFactory.php' => $this->getRatingReplyFactory(),
            'UnitVisitFactory.php' => $this->getUnitVisitFactory(),
            'ReportCategoryFactory.php' => $this->getReportCategoryFactory(),
            'ReportFactory.php' => $this->getReportFactory(),
            'ReportAttachmentFactory.php' => $this->getReportAttachmentFactory(),
            'ReportReplyFactory.php' => $this->getReportReplyFactory(),
            'ReportStatusHistoryFactory.php' => $this->getReportStatusHistoryFactory(),
            'EmployeePositionFactory.php' => $this->getEmployeePositionFactory(),
            'EmployeeUnitAssignmentFactory.php' => $this->getEmployeeUnitAssignmentFactory(),
            'NotificationFactory.php' => $this->getNotificationFactory(),
            'SettingFactory.php' => $this->getSettingFactory(),
            'ExportFactory.php' => $this->getExportFactory(),
            'ModerationLogFactory.php' => $this->getModerationLogFactory(),
        ];
    }

    public function handle(): int
    {
        $this->info('Restructuring factories...');

        $basePath = database_path('factories');

        $this->cleanOldFactories($basePath);

        $this->createFactories($basePath);

        $this->info('All factories restructured successfully.');

        return Command::SUCCESS;
    }

    protected function cleanOldFactories(string $basePath): void
    {
        $oldFactories = [
            'AdminReplyFactory.php',
            'StudentSessionFactory.php',
        ];

        foreach ($oldFactories as $factory) {
            $path = $basePath . '/' . $factory;
            if (File::exists($path)) {
                File::delete($path);
                $this->line("Deleted: {$factory}");
            }
        }
    }

    protected function createFactories(string $basePath): void
    {
        foreach ($this->factories as $fileName => $content) {
            $fullPath = $basePath . '/' . $fileName;

            if (File::exists($fullPath) && !$this->option('force')) {
                $this->warn("Skipping {$fileName} (use --force to overwrite)");
                continue;
            }

            File::put($fullPath, $content);
            $this->info("Created: {$fileName}");
        }
    }

    protected function getAdminFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'role' => $this->faker->randomElement(['admin', 'super_admin']),
            'email_verified_at' => $this->faker->optional(0.9)->dateTime(),
            'photo' => null,
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'location' => $this->faker->optional(0.6)->city(),
            'employee_id' => $this->faker->optional(0.8)->unique()->numerify('EMP-####'),
            'position' => $this->faker->optional(0.7)->jobTitle(),
            'department' => $this->faker->optional(0.6)->randomElement(['IT', 'HR', 'Marketing', 'Operations', 'Finance']),
            'bio' => $this->faker->optional(0.5)->paragraph(),
            'timezone' => $this->faker->randomElement(['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura']),
            'is_active' => true,
            'two_factor_enabled' => false,
            'preferences' => json_encode([
                'theme' => $this->faker->randomElement(['light', 'dark']),
                'language' => $this->faker->randomElement(['id', 'en']),
            ]),
            'login_count' => $this->faker->numberBetween(0, 100),
            'last_login_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
            'last_login_ip' => $this->faker->optional(0.5)->ipv4(),
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    public function superAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'super_admin',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
PHP;
    }

    protected function getStudentFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Authentication\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        $year = $this->faker->numberBetween(2020, 2025);
        $sequence = $this->faker->unique()->numberBetween(1, 9999);

        return [
            'student_identifier' => $year . str_pad($sequence, 4, '0', STR_PAD_LEFT),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'email_verified_at' => $this->faker->optional(0.9)->dateTime(),
            'is_active' => true,
            'last_login_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
            'major' => $this->faker->optional(0.8)->randomElement([
                'Teknik Informatika',
                'Teknik Sipil',
                'Teknik Elektro',
                'Teknik Mesin',
                'Teknik Industri',
                'Sistem Informasi',
                'Manajemen',
                'Akuntansi',
                'Hukum',
                'Kedokteran'
            ]),
            'class_year' => $this->faker->optional(0.8)->numberBetween(2020, 2025),
            'bio' => $this->faker->optional(0.5)->paragraph(),
            'phone' => $this->faker->optional(0.7)->phoneNumber(),
            'location' => $this->faker->optional(0.6)->city(),
            'portfolio_url' => $this->faker->optional(0.3)->url(),
            'linkedin_url' => $this->faker->optional(0.3)->url(),
            'photo' => null,
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    public function fromYear(int $year): static
    {
        return $this->state(function (array $attributes) use ($year) {
            $studentId = $year . str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT);
            return [
                'student_identifier' => $studentId,
            ];
        });
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
PHP;
    }

    protected function getEmployeeFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Authentication\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'email_verified_at' => $this->faker->optional(0.9)->dateTime(),
            'employee_id' => $this->faker->unique()->numerify('EMP-####'),
            'photo' => null,
            'phone' => $this->faker->optional(0.8)->phoneNumber(),
            'position' => $this->faker->optional(0.7)->jobTitle(),
            'department' => $this->faker->optional(0.6)->randomElement([
                'Laboratorium', 'Perpustakaan', 'Kesehatan', 'Kemahasiswaan',
                'Fasilitas Umum', 'Teknologi Informasi', 'Akademik', 'Keuangan'
            ]),
            'is_active' => true,
            'timezone' => 'Asia/Jakarta',
            'preferences' => json_encode([
                'theme' => $this->faker->randomElement(['light', 'dark']),
                'language' => $this->faker->randomElement(['id', 'en']),
            ]),
            'login_count' => $this->faker->numberBetween(0, 50),
            'last_login_at' => $this->faker->optional(0.6)->dateTimeThisMonth(),
            'last_login_ip' => $this->faker->optional(0.5)->ipv4(),
            'remember_token' => Str::random(10),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function withDepartment(string $department): static
    {
        return $this->state(fn(array $attributes) => [
            'department' => $department,
        ]);
    }
}
PHP;
    }

    protected function getUnitTypeFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Units\UnitType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitTypeFactory extends Factory
{
    protected $model = UnitType::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Laboratorium', 'Perpustakaan', 'Ruang Kelas', 'Auditorium',
            'Kantin', 'Olahraga', 'Klinik', 'Layanan', 'Pusat Studi'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon_key' => $this->faker->randomElement([
                'flask', 'book-open', 'presentation', 'theater',
                'coffee', 'dumbbell', 'heart-pulse', 'building'
            ]),
            'description' => $this->faker->optional(0.7)->paragraph(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
PHP;
    }

    protected function getUnitDepartmentFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Units\UnitDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitDepartmentFactory extends Factory
{
    protected $model = UnitDepartment::class;

    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Fakultas Teknik', 'Fakultas Ekonomi', 'Fakultas Hukum',
            'Fakultas Kedokteran', 'Direktorat Akademik', 'Kemahasiswaan',
            'UPT Perpustakaan', 'UPT Laboratorium', 'Fakultas Ilmu Komputer',
            'Fakultas Psikologi', 'Fakultas Pertanian', 'Fakultas Peternakan'
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'code' => $this->faker->optional(0.7)->unique()->bothify('??###'),
            'description' => $this->faker->optional(0.6)->paragraph(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
PHP;
    }

    protected function getUnitFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Units\Unit;
use App\Models\Units\UnitType;
use App\Models\Units\UnitDepartment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition(): array
    {
        $unitType = UnitType::inRandomOrder()->first() ?? UnitType::factory();
        $department = UnitDepartment::inRandomOrder()->first() ?? UnitDepartment::factory();

        $prefixes = ['Utama', 'Sentra', 'Pusat', 'Fasilitas', 'Gedung', 'Area', 'Ruang'];

        $buildings = [
            'Gedung Rektorat', 'Gedung Fakultas Teknik', 'Gedung Fakultas Ekonomi',
            'Gedung Fakultas Hukum', 'Gedung Fakultas Kedokteran', 'Gedung FIK',
            'Gedung Serbaguna', 'Student Center', 'Gedung Perpustakaan',
            'Gedung Laboratorium', 'Gedung Kuliah Bersama', 'Gedung Olahraga'
        ];

        $floors = ['Lantai 1', 'Lantai 2', 'Lantai 3', 'Lantai 4', 'Lantai Dasar'];

        $name = $unitType->name . ' ' . $this->faker->randomElement($prefixes) . ' ' . $this->faker->numberBetween(1, 99);

        return [
            'code' => strtoupper($this->faker->unique()->bothify('??-###')),
            'name' => $name,
            'slug' => Str::slug($name . '-' . Str::random(4)),
            'unit_type_id' => $unitType->id,
            'unit_department_id' => $department->id,
            'description' => $this->faker->paragraphs(3, true),
            'location' => $this->faker->randomElement($buildings) . ' ' . $this->faker->randomElement($floors),
            'building' => $this->faker->randomElement($buildings),
            'floor' => $this->faker->randomElement($floors),
            'phone' => $this->faker->optional(0.6)->phoneNumber(),
            'email' => $this->faker->optional(0.5)->email(),
            'open_time' => $this->faker->randomElement(['07:00:00', '08:00:00', '09:00:00']),
            'close_time' => $this->faker->randomElement(['16:00:00', '17:00:00', '18:00:00', '20:00:00', '22:00:00']),
            'capacity' => $this->faker->numberBetween(20, 1000),
            'is_active' => $this->faker->boolean(90),
            'operational_status' => $this->faker->randomElement(['open', 'full', 'maintenance', 'closed']),
            'primary_qr_code_id' => null,
            'metadata' => json_encode([
                'has_ac' => $this->faker->boolean(80),
                'has_wifi' => $this->faker->boolean(70),
                'has_projector' => $this->faker->boolean(50),
                'has_parking' => $this->faker->boolean(85),
            ]),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withPrimaryQrCode(int $qrCodeId): static
    {
        return $this->state(fn(array $attributes) => [
            'primary_qr_code_id' => $qrCodeId,
        ]);
    }
}
PHP;
    }

    protected function getFacilityFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Units\Facility;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FacilityFactory extends Factory
{
    protected $model = Facility::class;

    public function definition(): array
    {
        $facilities = [
            'AC', 'Proyektor', 'WiFi', 'Whiteboard', 'Toilet', 'Kantin',
            'Parkir', 'Musala', 'Loker', 'Speaker', 'Kursi Roda', 'Ruang Tunggu',
            'Komputer', 'Printer', 'Air Minum', 'Papan Tulis', 'Sound System'
        ];

        $name = $this->faker->randomElement($facilities);
        $iconMap = [
            'AC' => 'air-conditioner', 'Proyektor' => 'projector', 'WiFi' => 'wifi',
            'Whiteboard' => 'whiteboard', 'Toilet' => 'toilet', 'Kantin' => 'cafe',
            'Parkir' => 'parking', 'Musala' => 'mosque', 'Loker' => 'locker',
            'Speaker' => 'speaker', 'Kursi Roda' => 'wheelchair', 'Ruang Tunggu' => 'waiting-room',
            'Komputer' => 'computer', 'Printer' => 'printer', 'Air Minum' => 'water-dispenser',
            'Papan Tulis' => 'whiteboard', 'Sound System' => 'speaker'
        ];

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon_key' => $iconMap[$name] ?? 'building',
            'description' => $this->faker->optional(0.5)->sentence(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
PHP;
    }

    protected function getUnitFacilityFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Units\Unit;
use App\Models\Units\Facility;
use App\Models\Units\UnitFacility;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFacilityFactory extends Factory
{
    protected $model = UnitFacility::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'facility_id' => Facility::factory(),
            'value' => $this->faker->optional(0.3)->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function forFacility(Facility $facility): static
    {
        return $this->state(fn(array $attributes) => [
            'facility_id' => $facility->id,
        ]);
    }
}
PHP;
    }

    protected function getUnitPhotoFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Units\Unit;
use App\Models\Units\UnitPhoto;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitPhotoFactory extends Factory
{
    protected $model = UnitPhoto::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'uploaded_by_admin_id' => Admin::factory(),
            'disk' => 'public',
            'original_path' => 'photos/placeholder.jpg',
            'thumbnail_path' => 'photos/thumb/placeholder.jpg',
            'medium_path' => 'photos/medium/placeholder.jpg',
            'large_path' => 'photos/large/placeholder.jpg',
            'file_name' => $this->faker->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'file_size' => $this->faker->numberBetween(100000, 2000000),
            'alt_text' => $this->faker->optional(0.5)->sentence(),
            'sort_order' => 0,
            'is_primary' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function primary(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_primary' => true,
            'sort_order' => 0,
        ]);
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}
PHP;
    }

    protected function getQrCodeFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Units\QrCode;
use App\Models\Units\Unit;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class QrCodeFactory extends Factory
{
    protected $model = QrCode::class;

    public function definition(): array
    {
        return [
            'unit_id' => Unit::factory(),
            'code' => strtoupper(Str::random(12)),
            'path' => 'qr-codes/' . Str::random(20) . '.png',
            'is_active' => true,
            'expires_at' => $this->faker->optional(0.3)->dateTimeBetween('+1 month', '+1 year'),
            'last_generated_at' => now(),
            'generated_by_admin_id' => Admin::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => $this->faker->dateTimeBetween('-1 year', '-1 day'),
        ]);
    }
}
PHP;
    }

    protected function getRatingCategoryFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Feedback\RatingCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RatingCategoryFactory extends Factory
{
    protected $model = RatingCategory::class;

    public function definition(): array
    {
        $categories = [
            'Kebersihan' => 1,
            'Kenyamanan' => 2,
            'Pelayanan' => 3,
            'Fasilitas' => 4,
            'Aksesibilitas' => 5,
        ];

        $name = $this->faker->randomElement(array_keys($categories));
        $sortOrder = $categories[$name];

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'is_active' => true,
            'sort_order' => $sortOrder,
            'min_score' => 1.0,
            'max_score' => 5.0,
            'default_score' => 3.0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}
PHP;
    }

    protected function getRatingFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Feedback\Rating;
use App\Models\Units\Unit;
use App\Models\Authentication\Student;
use App\Models\Feedback\UnitVisit;
use App\Models\Units\QrCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    protected $model = Rating::class;

    public function definition(): array
    {
        return [
            'tracking_code' => 'RTG-' . strtoupper(uniqid()),
            'unit_id' => Unit::factory(),
            'student_id' => Student::factory(),
            'visit_id' => null,
            'qr_code_id' => null,
            'overall_score' => $this->faker->randomFloat(2, 1, 5),
            'comment' => $this->faker->paragraph(),
            'is_comment_censored' => false,
            'status' => $this->faker->randomElement(['active', 'edited', 'archived']),
            'last_edited_at' => null,
            'last_replied_at' => null,
            'metadata' => json_encode([
                'device' => $this->faker->randomElement(['mobile', 'desktop', 'tablet']),
                'browser' => $this->faker->randomElement(['Chrome', 'Firefox', 'Safari']),
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function byStudent(Student $student): static
    {
        return $this->state(fn(array $attributes) => [
            'student_id' => $student->id,
        ]);
    }

    public function withVisit(UnitVisit $visit): static
    {
        return $this->state(fn(array $attributes) => [
            'visit_id' => $visit->id,
            'unit_id' => $visit->unit_id,
            'qr_code_id' => $visit->qr_code_id,
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'archived',
        ]);
    }

    public function edited(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'edited',
            'last_edited_at' => now(),
        ]);
    }
}
PHP;
    }

    protected function getRatingScoreFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Feedback\RatingScore;
use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingScoreFactory extends Factory
{
    protected $model = RatingScore::class;

    public function definition(): array
    {
        return [
            'rating_id' => Rating::factory(),
            'rating_category_id' => RatingCategory::factory(),
            'score' => $this->faker->randomFloat(1, 1, 5),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forRating(Rating $rating): static
    {
        return $this->state(fn(array $attributes) => [
            'rating_id' => $rating->id,
        ]);
    }

    public function forCategory(RatingCategory $category): static
    {
        return $this->state(fn(array $attributes) => [
            'rating_category_id' => $category->id,
        ]);
    }
}
PHP;
    }

    protected function getRatingAttachmentFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Feedback\RatingAttachment;
use App\Models\Feedback\Rating;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingAttachmentFactory extends Factory
{
    protected $model = RatingAttachment::class;

    public function definition(): array
    {
        return [
            'rating_id' => Rating::factory(),
            'path' => 'rating-attachments/' . $this->faker->uuid() . '.jpg',
            'original_name' => $this->faker->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(100000, 5000000),
            'disk' => 'public',
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forRating(Rating $rating): static
    {
        return $this->state(fn(array $attributes) => [
            'rating_id' => $rating->id,
        ]);
    }

    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}
PHP;
    }

    protected function getRatingReplyFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Feedback\RatingReply;
use App\Models\Feedback\Rating;
use App\Models\Authentication\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class RatingReplyFactory extends Factory
{
    protected $model = RatingReply::class;

    public function definition(): array
    {
        return [
            'rating_id' => Rating::factory(),
            'employee_id' => Employee::factory(),
            'reply' => $this->faker->paragraph(),
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forRating(Rating $rating): static
    {
        return $this->state(fn(array $attributes) => [
            'rating_id' => $rating->id,
        ]);
    }

    public function byEmployee(Employee $employee): static
    {
        return $this->state(fn(array $attributes) => [
            'employee_id' => $employee->id,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => false,
        ]);
    }
}
PHP;
    }

    protected function getUnitVisitFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Feedback\UnitVisit;
use App\Models\Units\Unit;
use App\Models\Authentication\Student;
use App\Models\Units\QrCode;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitVisitFactory extends Factory
{
    protected $model = UnitVisit::class;

    public function definition(): array
    {
        $visitedAt = $this->faker->dateTimeBetween('-3 months', 'now');

        return [
            'unit_id' => Unit::factory(),
            'student_id' => Student::factory(),
            'qr_code_id' => QrCode::factory(),
            'visited_at' => $visitedAt,
            'is_gps_validated' => $this->faker->boolean(80),
            'latitude' => $this->faker->optional(0.8)->latitude(),
            'longitude' => $this->faker->optional(0.8)->longitude(),
            'validation_radius_meters' => 100,
            'metadata' => json_encode([
                'source' => $this->faker->randomElement(['qr_scan', 'manual']),
                'device' => $this->faker->randomElement(['mobile', 'tablet']),
            ]),
            'created_at' => $visitedAt,
            'updated_at' => $visitedAt,
        ];
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function byStudent(Student $student): static
    {
        return $this->state(fn(array $attributes) => [
            'student_id' => $student->id,
        ]);
    }

    public function withQrCode(QrCode $qrCode): static
    {
        return $this->state(fn(array $attributes) => [
            'qr_code_id' => $qrCode->id,
        ]);
    }

    public function gpsValidated(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_gps_validated' => true,
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
        ]);
    }

    public function gpsNotValidated(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_gps_validated' => false,
            'latitude' => null,
            'longitude' => null,
        ]);
    }
}
PHP;
    }

    protected function getReportCategoryFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Reports\ReportCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReportCategoryFactory extends Factory
{
    protected $model = ReportCategory::class;

    public function definition(): array
    {
        $categories = [
            'Komentar Tidak Pantas', 'Informasi Palsu', 'Ujaran Kebencian',
            'Spam', 'Konten Ilegal', 'Pelanggaran Hak Cipta', 'Pencemaran Nama Baik'
        ];

        $name = $this->faker->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->optional(0.7)->paragraph(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
PHP;
    }

    protected function getReportFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Reports\Report;
use App\Models\Feedback\Rating;
use App\Models\Units\Unit;
use App\Models\Authentication\Student;
use App\Models\Reports\ReportCategory;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition(): array
    {
        $rating = Rating::inRandomOrder()->first() ?? Rating::factory();
        $status = $this->faker->randomElement(['new', 'assigned', 'in_progress', 'replied', 'resolved', 'rejected']);
        $assignedToEmployeeId = in_array($status, ['assigned', 'in_progress', 'replied', 'resolved'])
            ? Employee::factory()
            : null;

        return [
            'tracking_code' => 'RPT-' . strtoupper(uniqid()),
            'rating_id' => $rating->id,
            'unit_id' => $rating->unit_id,
            'student_id' => $rating->student_id,
            'report_category_id' => ReportCategory::factory(),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(2, true),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => $status,
            'assigned_to_employee_id' => $assignedToEmployeeId,
            'admin_id' => Admin::factory(),
            'admin_response' => $this->faker->optional(0.5)->paragraph(),
            'replied_at' => $this->faker->optional(0.4)->dateTimeBetween('-1 month', 'now'),
            'resolved_at' => $status === 'resolved' ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => fn(array $attributes) => $this->faker->dateTimeBetween($attributes['created_at'], 'now'),
        ];
    }

    public function forRating(Rating $rating): static
    {
        return $this->state(fn(array $attributes) => [
            'rating_id' => $rating->id,
            'unit_id' => $rating->unit_id,
            'student_id' => $rating->student_id,
        ]);
    }

    public function asNew(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'new',
            'assigned_to_employee_id' => null,
            'admin_response' => null,
            'replied_at' => null,
            'resolved_at' => null,
        ]);
    }

    public function asResolved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'resolved',
            'resolved_at' => now(),
        ]);
    }

    public function asRejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'rejected',
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn(array $attributes) => [
            'priority' => 'critical',
        ]);
    }
}
PHP;
    }

    protected function getReportAttachmentFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Reports\ReportAttachment;
use App\Models\Reports\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportAttachmentFactory extends Factory
{
    protected $model = ReportAttachment::class;

    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'path' => 'report-attachments/' . $this->faker->uuid() . '.jpg',
            'original_name' => $this->faker->word() . '.jpg',
            'mime_type' => 'image/jpeg',
            'size' => $this->faker->numberBetween(100000, 5000000),
            'disk' => 'public',
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forReport(Report $report): static
    {
        return $this->state(fn(array $attributes) => [
            'report_id' => $report->id,
        ]);
    }

    public function withSortOrder(int $order): static
    {
        return $this->state(fn(array $attributes) => [
            'sort_order' => $order,
        ]);
    }
}
PHP;
    }

    protected function getReportReplyFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Reports\ReportReply;
use App\Models\Reports\Report;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportReplyFactory extends Factory
{
    protected $model = ReportReply::class;

    public function definition(): array
    {
        $isEmployeeReply = $this->faker->boolean(70);

        return [
            'report_id' => Report::factory(),
            'employee_id' => $isEmployeeReply ? Employee::factory() : null,
            'admin_id' => !$isEmployeeReply ? Admin::factory() : null,
            'reply' => $this->faker->paragraph(),
            'is_public' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forReport(Report $report): static
    {
        return $this->state(fn(array $attributes) => [
            'report_id' => $report->id,
        ]);
    }

    public function byEmployee(Employee $employee): static
    {
        return $this->state(fn(array $attributes) => [
            'employee_id' => $employee->id,
            'admin_id' => null,
        ]);
    }

    public function byAdmin(Admin $admin): static
    {
        return $this->state(fn(array $attributes) => [
            'admin_id' => $admin->id,
            'employee_id' => null,
        ]);
    }

    public function private(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_public' => false,
        ]);
    }
}
PHP;
    }

    protected function getReportStatusHistoryFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Reports\ReportStatusHistory;
use App\Models\Reports\Report;
use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportStatusHistoryFactory extends Factory
{
    protected $model = ReportStatusHistory::class;

    public function definition(): array
    {
        $oldStatus = $this->faker->randomElement(['new', 'assigned', 'in_progress', 'replied']);
        $newStatus = match ($oldStatus) {
            'new' => $this->faker->randomElement(['assigned', 'in_progress']),
            'assigned' => $this->faker->randomElement(['in_progress', 'replied']),
            'in_progress' => $this->faker->randomElement(['replied', 'resolved']),
            'replied' => $this->faker->randomElement(['resolved', 'rejected']),
            default => 'resolved',
        };

        $isAdmin = $this->faker->boolean(80);

        return [
            'report_id' => Report::factory(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by_admin_id' => $isAdmin ? Admin::factory() : null,
            'changed_by_employee_id' => !$isAdmin ? Employee::factory() : null,
            'reason' => $this->faker->optional(0.5)->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forReport(Report $report): static
    {
        return $this->state(fn(array $attributes) => [
            'report_id' => $report->id,
        ]);
    }

    public function withTransition(string $old, string $new): static
    {
        return $this->state(fn(array $attributes) => [
            'old_status' => $old,
            'new_status' => $new,
        ]);
    }
}
PHP;
    }

    protected function getEmployeePositionFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Employee\EmployeePosition;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeePositionFactory extends Factory
{
    protected $model = EmployeePosition::class;

    public function definition(): array
    {
        $positions = [
            'Kepala Lab', 'Asisten Lab', 'Teknisi', 'Administrasi',
            'Manajer', 'Supervisor', 'Staff', 'Koordinator'
        ];

        $name = $this->faker->randomElement($positions);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->optional(0.6)->paragraph(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
PHP;
    }

    protected function getEmployeeUnitAssignmentFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Authentication\Employee;
use App\Models\Units\Unit;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeUnitAssignmentFactory extends Factory
{
    protected $model = EmployeeUnitAssignment::class;

    public function definition(): array
    {
        return [
            'employee_id' => Employee::factory(),
            'unit_id' => Unit::factory(),
            'assigned_by_admin_id' => Admin::factory(),
            'role_in_unit' => $this->faker->optional(0.7)->randomElement(['Kepala', 'Koordinator', 'Staff', 'Teknisi']),
            'assigned_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'ended_at' => $this->faker->optional(0.2)->dateTimeBetween('now', '+6 months'),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function forEmployee(Employee $employee): static
    {
        return $this->state(fn(array $attributes) => [
            'employee_id' => $employee->id,
        ]);
    }

    public function forUnit(Unit $unit): static
    {
        return $this->state(fn(array $attributes) => [
            'unit_id' => $unit->id,
        ]);
    }

    public function ended(): static
    {
        return $this->state(fn(array $attributes) => [
            'ended_at' => $this->faker->dateTimeBetween('-3 months', '-1 day'),
            'is_active' => false,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'ended_at' => null,
            'is_active' => true,
        ]);
    }
}
PHP;
    }

    protected function getNotificationFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\System\Notification;
use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition(): array
    {
        $notifiableType = $this->faker->randomElement([
            Admin::class,
            Employee::class,
            Student::class
        ]);

        $notifiable = match ($notifiableType) {
            Admin::class => Admin::factory(),
            Employee::class => Employee::factory(),
            Student::class => Student::factory(),
        };

        return [
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiable->id,
            'type' => $this->faker->randomElement(['rating_reply', 'report_reply', 'report_status', 'system']),
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
            'data' => json_encode([]),
            'read_at' => $this->faker->optional(0.5)->dateTime(),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    public function unread(): static
    {
        return $this->state(fn(array $attributes) => [
            'read_at' => null,
        ]);
    }

    public function read(): static
    {
        return $this->state(fn(array $attributes) => [
            'read_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function forAdmin(Admin $admin): static
    {
        return $this->state(fn(array $attributes) => [
            'notifiable_type' => Admin::class,
            'notifiable_id' => $admin->id,
        ]);
    }

    public function forEmployee(Employee $employee): static
    {
        return $this->state(fn(array $attributes) => [
            'notifiable_type' => Employee::class,
            'notifiable_id' => $employee->id,
        ]);
    }

    public function forStudent(Student $student): static
    {
        return $this->state(fn(array $attributes) => [
            'notifiable_type' => Student::class,
            'notifiable_id' => $student->id,
        ]);
    }
}
PHP;
    }

    protected function getSettingFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\System\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

class SettingFactory extends Factory
{
    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word(),
            'value' => $this->faker->word(),
            'type' => 'string',
            'group' => 'general',
            'subgroup' => null,
            'label' => $this->faker->words(3, true),
            'description' => $this->faker->optional(0.5)->sentence(),
            'hint' => $this->faker->optional(0.3)->sentence(),
            'options' => null,
            'validation_rules' => null,
            'sort_order' => 0,
            'is_editable' => true,
            'is_visible' => true,
            'is_public' => false,
            'required_permission' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withGroup(string $group): static
    {
        return $this->state(fn(array $attributes) => [
            'group' => $group,
        ]);
    }

    public function withType(string $type): static
    {
        return $this->state(fn(array $attributes) => [
            'type' => $type,
        ]);
    }

    public function notEditable(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_editable' => false,
        ]);
    }
}
PHP;
    }

    protected function getExportFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\System\Export;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExportFactory extends Factory
{
    protected $model = Export::class;

    public function definition(): array
    {
        return [
            'admin_id' => Admin::factory(),
            'export_type' => $this->faker->randomElement(['ratings', 'reports', 'units', 'users']),
            'format' => $this->faker->randomElement(['csv', 'excel', 'pdf']),
            'file_name' => $this->faker->word() . '.' . $this->faker->randomElement(['csv', 'xlsx', 'pdf']),
            'file_path' => 'exports/' . $this->faker->uuid() . '.' . $this->faker->randomElement(['csv', 'xlsx', 'pdf']),
            'file_size' => $this->faker->numberBetween(10000, 5000000),
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'failed']),
            'filters' => json_encode([]),
            'completed_at' => $this->faker->optional(0.7)->dateTime(),
            'expires_at' => $this->faker->optional(0.5)->dateTimeBetween('+1 day', '+1 month'),
            'download_count' => $this->faker->numberBetween(0, 10),
            'last_downloaded_at' => $this->faker->optional(0.3)->dateTime(),
            'created_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'updated_at' => now(),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'processing',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'failed',
        ]);
    }
}
PHP;
    }

    protected function getModerationLogFactory(): string
    {
        return <<<'PHP'
<?php

namespace Database\Factories;

use App\Models\System\ModerationLog;
use App\Models\Authentication\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModerationLogFactory extends Factory
{
    protected $model = ModerationLog::class;

    public function definition(): array
    {
        $targetTypes = ['Rating', 'Report', 'Unit'];
        $targetType = $this->faker->randomElement($targetTypes);

        $actions = match ($targetType) {
            'Rating' => ['censor_comment', 'uncensor_comment', 'edit_rating', 'delete_rating'],
            'Report' => ['assign', 'respond', 'resolve', 'reject'],
            'Unit' => ['edit_unit', 'toggle_status', 'delete_unit'],
        };

        return [
            'admin_id' => Admin::factory(),
            'action' => $this->faker->randomElement($actions),
            'target_type' => $targetType,
            'target_id' => 1,
            'reason' => $this->faker->optional(0.7)->sentence(),
            'metadata' => json_encode([
                'ip_address' => $this->faker->ipv4(),
                'user_agent' => $this->faker->userAgent(),
            ]),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'updated_at' => now(),
        ];
    }

    public function forRating(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'Rating',
            'action' => $this->faker->randomElement(['censor_comment', 'uncensor_comment', 'edit_rating', 'delete_rating']),
        ]);
    }

    public function forReport(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'Report',
            'action' => $this->faker->randomElement(['assign', 'respond', 'resolve', 'reject']),
        ]);
    }

    public function forUnit(): static
    {
        return $this->state(fn(array $attributes) => [
            'target_type' => 'Unit',
            'action' => $this->faker->randomElement(['edit_unit', 'toggle_status', 'delete_unit']),
        ]);
    }

    public function byAdmin(Admin $admin): static
    {
        return $this->state(fn(array $attributes) => [
            'admin_id' => $admin->id,
        ]);
    }
}
PHP;
    }
}