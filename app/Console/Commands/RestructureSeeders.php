<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;

class RestructureSeeders extends Command
{
    protected $signature = 'seeders:restructure 
                            {--force : Overwrite existing seeders}';

    protected $description = 'Restructure all seeders to match final models';

    protected Filesystem $files;

    protected array $seeders = [];

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
        $this->defineSeeders();
    }

    protected function defineSeeders(): void
    {
        $this->seeders = [
            'DatabaseSeeder.php' => $this->getDatabaseSeeder(),
            'AdminSeeder.php' => $this->getAdminSeeder(),
            'StudentSeeder.php' => $this->getStudentSeeder(),
            'EmployeeSeeder.php' => $this->getEmployeeSeeder(),
            'UnitTypeSeeder.php' => $this->getUnitTypeSeeder(),
            'UnitDepartmentSeeder.php' => $this->getUnitDepartmentSeeder(),
            'FacilitySeeder.php' => $this->getFacilitySeeder(),
            'UnitSeeder.php' => $this->getUnitSeeder(),
            'UnitFacilitySeeder.php' => $this->getUnitFacilitySeeder(),
            'UnitPhotoSeeder.php' => $this->getUnitPhotoSeeder(),
            'QrCodeSeeder.php' => $this->getQrCodeSeeder(),
            'RatingCategorySeeder.php' => $this->getRatingCategorySeeder(),
            'RatingSeeder.php' => $this->getRatingSeeder(),
            'RatingScoreSeeder.php' => $this->getRatingScoreSeeder(),
            'RatingAttachmentSeeder.php' => $this->getRatingAttachmentSeeder(),
            'RatingReplySeeder.php' => $this->getRatingReplySeeder(),
            'UnitVisitSeeder.php' => $this->getUnitVisitSeeder(),
            'ReportCategorySeeder.php' => $this->getReportCategorySeeder(),
            'ReportSeeder.php' => $this->getReportSeeder(),
            'ReportAttachmentSeeder.php' => $this->getReportAttachmentSeeder(),
            'ReportReplySeeder.php' => $this->getReportReplySeeder(),
            'ReportStatusHistorySeeder.php' => $this->getReportStatusHistorySeeder(),
            'EmployeePositionSeeder.php' => $this->getEmployeePositionSeeder(),
            'EmployeeUnitAssignmentSeeder.php' => $this->getEmployeeUnitAssignmentSeeder(),
            'NotificationSeeder.php' => $this->getNotificationSeeder(),
            'SettingSeeder.php' => $this->getSettingSeeder(),
            'ExportSeeder.php' => $this->getExportSeeder(),
            'ModerationLogSeeder.php' => $this->getModerationLogSeeder(),
        ];
    }

    protected function getSeedersToDelete(): array
    {
        return [
            'AdminReplySeeder.php',
            'PersonalAccessTokenSeeder.php',
            'RatingScoreSeeder.php',
            'StudentSessionSeeder.php',
            'StudentWithActivitySeeder.php',
            'VisitorSessionSeeder.php',
        ];
    }

    public function handle(): int
    {
        $this->info('Restructuring seeders...');

        $basePath = database_path('seeders');

        $this->deleteOldSeeders($basePath);

        $this->createSeeders($basePath);

        $this->info('All seeders restructured successfully.');

        return Command::SUCCESS;
    }

    protected function deleteOldSeeders(string $basePath): void
    {
        foreach ($this->getSeedersToDelete() as $seeder) {
            $path = $basePath . '/' . $seeder;
            if (File::exists($path)) {
                File::delete($path);
                $this->line("Deleted: {$seeder}");
            }
        }
    }

    protected function createSeeders(string $basePath): void
    {
        foreach ($this->seeders as $fileName => $content) {
            $fullPath = $basePath . '/' . $fileName;

            if (File::exists($fullPath) && !$this->option('force')) {
                $this->warn("Skipping {$fileName} (use --force to overwrite)");
                continue;
            }

            File::put($fullPath, $content);
            $this->info("Created: {$fileName}");
        }
    }

    protected function getDatabaseSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            StudentSeeder::class,
            EmployeeSeeder::class,
            UnitTypeSeeder::class,
            UnitDepartmentSeeder::class,
            FacilitySeeder::class,
            UnitSeeder::class,
            UnitFacilitySeeder::class,
            UnitPhotoSeeder::class,
            QrCodeSeeder::class,
            RatingCategorySeeder::class,
            RatingSeeder::class,
            RatingScoreSeeder::class,
            RatingAttachmentSeeder::class,
            RatingReplySeeder::class,
            UnitVisitSeeder::class,
            ReportCategorySeeder::class,
            ReportSeeder::class,
            ReportAttachmentSeeder::class,
            ReportReplySeeder::class,
            ReportStatusHistorySeeder::class,
            EmployeePositionSeeder::class,
            EmployeeUnitAssignmentSeeder::class,
            NotificationSeeder::class,
            SettingSeeder::class,
            ExportSeeder::class,
            ModerationLogSeeder::class,
        ]);
    }
}
PHP;
    }

    protected function getAdminSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'nama' => 'Super Admin',
            'email' => 'superadmin@itn.ac.id',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        Admin::create([
            'nama' => 'Admin Unit',
            'email' => 'admin@itn.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        Admin::factory(5)->create();
    }
}
PHP;
    }

    protected function getStudentSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Authentication\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'student_identifier' => '20240001',
            'name' => 'Mahasiswa Satu',
            'email' => 'student1@itn.ac.id',
            'password' => Hash::make('password'),
            'is_active' => true,
            'major' => 'Teknik Informatika',
            'class_year' => '2024',
        ]);

        Student::create([
            'student_identifier' => '20240002',
            'name' => 'Mahasiswa Dua',
            'email' => 'student2@itn.ac.id',
            'password' => Hash::make('password'),
            'is_active' => true,
            'major' => 'Teknik Sipil',
            'class_year' => '2024',
        ]);

        Student::factory(20)->create();
    }
}
PHP;
    }

    protected function getEmployeeSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Authentication\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::create([
            'name' => 'Karyawan Satu',
            'email' => 'employee1@itn.ac.id',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP-001',
            'is_active' => true,
            'position' => 'Kepala Laboratorium',
            'department' => 'Laboratorium Komputer',
        ]);

        Employee::create([
            'name' => 'Karyawan Dua',
            'email' => 'employee2@itn.ac.id',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP-002',
            'is_active' => true,
            'position' => 'Staff Perpustakaan',
            'department' => 'Perpustakaan',
        ]);

        Employee::factory(10)->create();
    }
}
PHP;
    }

    protected function getUnitTypeSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Units\UnitType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'Laboratorium', 'icon_key' => 'flask'],
            ['name' => 'Perpustakaan', 'icon_key' => 'book-open'],
            ['name' => 'Ruang Kelas', 'icon_key' => 'presentation'],
            ['name' => 'Auditorium', 'icon_key' => 'theater'],
            ['name' => 'Kantin', 'icon_key' => 'coffee'],
            ['name' => 'Olahraga', 'icon_key' => 'dumbbell'],
            ['name' => 'Klinik', 'icon_key' => 'heart-pulse'],
            ['name' => 'Layanan', 'icon_key' => 'building'],
        ];

        foreach ($types as $type) {
            UnitType::create([
                'name' => $type['name'],
                'slug' => Str::slug($type['name']),
                'icon_key' => $type['icon_key'],
                'is_active' => true,
            ]);
        }
    }
}
PHP;
    }

    protected function getUnitDepartmentSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Units\UnitDepartment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UnitDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Fakultas Teknik',
            'Fakultas Ekonomi',
            'Fakultas Hukum',
            'Fakultas Kedokteran',
            'Fakultas Ilmu Komputer',
            'Fakultas Psikologi',
            'Direktorat Akademik',
            'Kemahasiswaan',
            'UPT Perpustakaan',
            'UPT Laboratorium',
        ];

        foreach ($departments as $department) {
            UnitDepartment::create([
                'name' => $department,
                'slug' => Str::slug($department),
                'is_active' => true,
            ]);
        }
    }
}
PHP;
    }

    protected function getFacilitySeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Units\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            ['name' => 'AC', 'icon_key' => 'air-conditioner'],
            ['name' => 'Proyektor', 'icon_key' => 'projector'],
            ['name' => 'WiFi', 'icon_key' => 'wifi'],
            ['name' => 'Whiteboard', 'icon_key' => 'whiteboard'],
            ['name' => 'Toilet', 'icon_key' => 'toilet'],
            ['name' => 'Kantin', 'icon_key' => 'cafe'],
            ['name' => 'Parkir', 'icon_key' => 'parking'],
            ['name' => 'Musala', 'icon_key' => 'mosque'],
            ['name' => 'Loker', 'icon_key' => 'locker'],
            ['name' => 'Speaker', 'icon_key' => 'speaker'],
            ['name' => 'Kursi Roda', 'icon_key' => 'wheelchair'],
            ['name' => 'Ruang Tunggu', 'icon_key' => 'waiting-room'],
            ['name' => 'Komputer', 'icon_key' => 'computer'],
            ['name' => 'Printer', 'icon_key' => 'printer'],
            ['name' => 'Air Minum', 'icon_key' => 'water-dispenser'],
        ];

        foreach ($facilities as $facility) {
            Facility::create([
                'name' => $facility['name'],
                'slug' => Str::slug($facility['name']),
                'icon_key' => $facility['icon_key'],
                'is_active' => true,
            ]);
        }
    }
}
PHP;
    }

    protected function getUnitSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Units\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        Unit::factory(30)->create();
    }
}
PHP;
    }

    protected function getUnitFacilitySeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Units\Unit;
use App\Models\Units\Facility;
use App\Models\Units\UnitFacility;
use Illuminate\Database\Seeder;

class UnitFacilitySeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $facilities = Facility::all();

        foreach ($units as $unit) {
            $randomFacilities = $facilities->random(rand(3, 8));

            foreach ($randomFacilities as $facility) {
                UnitFacility::create([
                    'unit_id' => $unit->id,
                    'facility_id' => $facility->id,
                ]);
            }
        }
    }
}
PHP;
    }

    protected function getUnitPhotoSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Units\Unit;
use App\Models\Units\UnitPhoto;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class UnitPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $admin = Admin::first();

        foreach ($units as $index => $unit) {
            $photoCount = rand(1, 3);

            for ($i = 0; $i < $photoCount; $i++) {
                UnitPhoto::create([
                    'unit_id' => $unit->id,
                    'uploaded_by_admin_id' => $admin?->id,
                    'disk' => 'public',
                    'original_path' => 'photos/placeholder.jpg',
                    'thumbnail_path' => 'photos/thumb/placeholder.jpg',
                    'medium_path' => 'photos/medium/placeholder.jpg',
                    'large_path' => 'photos/large/placeholder.jpg',
                    'file_name' => "placeholder-{$unit->id}-{$i}.jpg",
                    'mime_type' => 'image/jpeg',
                    'file_size' => 50000,
                    'sort_order' => $i,
                    'is_primary' => $i === 0,
                ]);
            }
        }
    }
}
PHP;
    }

    protected function getQrCodeSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Units\QrCode;
use App\Models\Units\Unit;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class QrCodeSeeder extends Seeder
{
    public function run(): void
    {
        $units = Unit::all();
        $admin = Admin::first();

        foreach ($units as $unit) {
            QrCode::factory()
                ->forUnit($unit)
                ->create([
                    'generated_by_admin_id' => $admin?->id,
                ]);
        }
    }
}
PHP;
    }

    protected function getRatingCategorySeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Feedback\RatingCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RatingCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kebersihan', 'sort_order' => 1],
            ['name' => 'Kenyamanan', 'sort_order' => 2],
            ['name' => 'Pelayanan', 'sort_order' => 3],
            ['name' => 'Fasilitas', 'sort_order' => 4],
            ['name' => 'Aksesibilitas', 'sort_order' => 5],
        ];

        foreach ($categories as $category) {
            RatingCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'is_active' => true,
                'sort_order' => $category['sort_order'],
                'min_score' => 1.0,
                'max_score' => 5.0,
                'default_score' => 3.0,
            ]);
        }
    }
}
PHP;
    }

    protected function getRatingSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Feedback\Rating;
use Illuminate\Database\Seeder;

class RatingSeeder extends Seeder
{
    public function run(): void
    {
        Rating::factory(50)->create();
    }
}
PHP;
    }

    protected function getRatingScoreSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingCategory;
use App\Models\Feedback\RatingScore;
use Illuminate\Database\Seeder;

class RatingScoreSeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::all();
        $categories = RatingCategory::all();

        foreach ($ratings as $rating) {
            foreach ($categories as $category) {
                RatingScore::create([
                    'rating_id' => $rating->id,
                    'rating_category_id' => $category->id,
                    'score' => rand(1, 5),
                ]);
            }
        }
    }
}
PHP;
    }

    protected function getRatingAttachmentSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingAttachment;
use Illuminate\Database\Seeder;

class RatingAttachmentSeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::inRandomOrder()->limit(20)->get();

        foreach ($ratings as $rating) {
            $attachmentCount = rand(1, 3);

            for ($i = 0; $i < $attachmentCount; $i++) {
                RatingAttachment::factory()
                    ->forRating($rating)
                    ->withSortOrder($i)
                    ->create();
            }
        }
    }
}
PHP;
    }

    protected function getRatingReplySeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Feedback\Rating;
use App\Models\Feedback\RatingReply;
use App\Models\Authentication\Employee;
use Illuminate\Database\Seeder;

class RatingReplySeeder extends Seeder
{
    public function run(): void
    {
        $ratings = Rating::inRandomOrder()->limit(30)->get();
        $employees = Employee::all();

        foreach ($ratings as $rating) {
            if (rand(0, 1)) {
                RatingReply::factory()
                    ->forRating($rating)
                    ->byEmployee($employees->random())
                    ->create();
            }
        }
    }
}
PHP;
    }

    protected function getUnitVisitSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Feedback\UnitVisit;
use Illuminate\Database\Seeder;

class UnitVisitSeeder extends Seeder
{
    public function run(): void
    {
        UnitVisit::factory(100)->create();
    }
}
PHP;
    }

    protected function getReportCategorySeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Reports\ReportCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ReportCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Komentar Tidak Pantas',
            'Informasi Palsu',
            'Ujaran Kebencian',
            'Spam',
            'Konten Ilegal',
            'Pelanggaran Hak Cipta',
            'Pencemaran Nama Baik',
        ];

        foreach ($categories as $category) {
            ReportCategory::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'is_active' => true,
            ]);
        }
    }
}
PHP;
    }

    protected function getReportSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Reports\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        Report::factory(30)->create();
    }
}
PHP;
    }

    protected function getReportAttachmentSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Reports\Report;
use App\Models\Reports\ReportAttachment;
use Illuminate\Database\Seeder;

class ReportAttachmentSeeder extends Seeder
{
    public function run(): void
    {
        $reports = Report::all();

        foreach ($reports as $report) {
            $attachmentCount = rand(1, 3);

            for ($i = 0; $i < $attachmentCount; $i++) {
                ReportAttachment::factory()
                    ->forReport($report)
                    ->withSortOrder($i)
                    ->create();
            }
        }
    }
}
PHP;
    }

    protected function getReportReplySeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Reports\Report;
use App\Models\Reports\ReportReply;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class ReportReplySeeder extends Seeder
{
    public function run(): void
    {
        $reports = Report::whereIn('status', ['in_progress', 'replied', 'resolved'])->get();
        $employees = Employee::all();
        $admins = Admin::all();

        foreach ($reports as $report) {
            if (rand(0, 1)) {
                $isEmployee = rand(0, 1);

                if ($isEmployee && $employees->count() > 0) {
                    ReportReply::factory()
                        ->forReport($report)
                        ->byEmployee($employees->random())
                        ->create();
                } elseif ($admins->count() > 0) {
                    ReportReply::factory()
                        ->forReport($report)
                        ->byAdmin($admins->random())
                        ->create();
                }
            }
        }
    }
}
PHP;
    }

    protected function getReportStatusHistorySeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Reports\Report;
use App\Models\Reports\ReportStatusHistory;
use Illuminate\Database\Seeder;

class ReportStatusHistorySeeder extends Seeder
{
    public function run(): void
    {
        $reports = Report::all();

        foreach ($reports as $report) {
            $statuses = ['new', 'assigned', 'in_progress', 'replied', 'resolved'];
            $currentIndex = array_search($report->status, $statuses);

            if ($currentIndex === false) {
                $currentIndex = 0;
            }

            for ($i = 0; $i <= $currentIndex; $i++) {
                ReportStatusHistory::factory()
                    ->forReport($report)
                    ->withTransition(
                        $i > 0 ? $statuses[$i - 1] : 'new',
                        $statuses[$i]
                    )
                    ->create();
            }
        }
    }
}
PHP;
    }

    protected function getEmployeePositionSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Employee\EmployeePosition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeePositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            'Kepala Laboratorium',
            'Asisten Laboratorium',
            'Teknisi',
            'Administrasi',
            'Manajer',
            'Supervisor',
            'Staff',
            'Koordinator',
        ];

        foreach ($positions as $position) {
            EmployeePosition::create([
                'name' => $position,
                'slug' => Str::slug($position),
                'is_active' => true,
            ]);
        }
    }
}
PHP;
    }

    protected function getEmployeeUnitAssignmentSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\Employee\EmployeeUnitAssignment;
use App\Models\Authentication\Employee;
use App\Models\Units\Unit;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class EmployeeUnitAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $units = Unit::all();
        $admin = Admin::first();

        foreach ($employees as $employee) {
            $assignedUnits = $units->random(rand(1, 3));

            foreach ($assignedUnits as $unit) {
                EmployeeUnitAssignment::factory()
                    ->forEmployee($employee)
                    ->forUnit($unit)
                    ->create([
                        'assigned_by_admin_id' => $admin?->id,
                    ]);
            }
        }
    }
}
PHP;
    }

    protected function getNotificationSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\System\Notification;
use App\Models\Authentication\Admin;
use App\Models\Authentication\Employee;
use App\Models\Authentication\Student;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $admins = Admin::all();
        $employees = Employee::all();
        $students = Student::all();

        foreach ($admins as $admin) {
            Notification::factory(3)->forAdmin($admin)->unread()->create();
            Notification::factory(2)->forAdmin($admin)->read()->create();
        }

        foreach ($employees as $employee) {
            Notification::factory(5)->forEmployee($employee)->unread()->create();
            Notification::factory(3)->forEmployee($employee)->read()->create();
        }

        foreach ($students as $student) {
            Notification::factory(5)->forStudent($student)->unread()->create();
            Notification::factory(3)->forStudent($student)->read()->create();
        }
    }
}
PHP;
    }

    protected function getSettingSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\System\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'app_name', 'value' => 'Rating Units ITN', 'type' => 'string', 'group' => 'general', 'label' => 'Nama Aplikasi'],
            ['key' => 'app_timezone', 'value' => 'Asia/Jakarta', 'type' => 'string', 'group' => 'general', 'label' => 'Zona Waktu'],
            ['key' => 'max_rating_attachments', 'value' => '3', 'type' => 'integer', 'group' => 'rating', 'label' => 'Maksimal Lampiran Rating'],
            ['key' => 'max_report_attachments', 'value' => '3', 'type' => 'integer', 'group' => 'report', 'label' => 'Maksimal Lampiran Report'],
            ['key' => 'max_file_size', 'value' => '5242880', 'type' => 'integer', 'group' => 'upload', 'label' => 'Ukuran Maksimal File'],
            ['key' => 'gps_validation_radius', 'value' => '100', 'type' => 'integer', 'group' => 'qr', 'label' => 'Radius Validasi GPS'],
            ['key' => 'rate_limit_login', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'label' => 'Batas Percobaan Login'],
            ['key' => 'rate_limit_qr', 'value' => '10', 'type' => 'integer', 'group' => 'security', 'label' => 'Batas Validasi QR per Menit'],
            ['key' => 'rate_limit_rating', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'label' => 'Batas Submit Rating per Menit'],
            ['key' => 'rate_limit_report', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'label' => 'Batas Submit Report per Menit'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
PHP;
    }

    protected function getExportSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\System\Export;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class ExportSeeder extends Seeder
{
    public function run(): void
    {
        $admins = Admin::all();

        foreach ($admins as $admin) {
            Export::factory(3)->forAdmin($admin)->completed()->create();
            Export::factory(1)->forAdmin($admin)->processing()->create();
            Export::factory(1)->forAdmin($admin)->failed()->create();
        }
    }
}
PHP;
    }

    protected function getModerationLogSeeder(): string
    {
        return <<<'PHP'
<?php

namespace Database\Seeders;

use App\Models\System\ModerationLog;
use App\Models\Authentication\Admin;
use Illuminate\Database\Seeder;

class ModerationLogSeeder extends Seeder
{
    public function run(): void
    {
        $admins = Admin::all();

        foreach ($admins as $admin) {
            ModerationLog::factory(5)->forRating()->byAdmin($admin)->create();
            ModerationLog::factory(3)->forReport()->byAdmin($admin)->create();
            ModerationLog::factory(2)->forUnit()->byAdmin($admin)->create();
        }
    }
}
PHP;
    }
}