<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class RegenerateViewsStructure extends Command
{
    protected $signature = 'views:regenerate 
                            {--dry-run : Preview changes without executing}
                            {--no-backup : Skip backup of old views folder}
                            {--force : Skip confirmation prompt}';

    protected $description = 'Regenerate views folder structure with backup, cleanup, and modern email templates';

    protected string $viewsPath;
    protected string $backupPath;
    protected array $actions = [];
    protected bool $dryRun;

    public function handle(): int
    {
        $this->dryRun = $this->option('dry-run');
        $this->viewsPath = resource_path('views');
        $this->backupPath = resource_path('views_backup_' . date('Y-m-d_His'));

        $this->displayHeader();

        if (!$this->confirmExecution()) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $this->executeBackup();
        $this->executeCleanup();
        $this->executeRenames();
        $this->executeRemovals();
        $this->createMissingFolders();
        $this->createSharedComponents();
        $this->createEmailTemplates();
        $this->createPlaceholderViews();

        $this->displaySummary();

        return 0;
    }

    protected function displayHeader(): void
    {
        $this->newLine();
        $this->line('==================================================');
        $this->line('  VIEWS STRUCTURE REGENERATOR');
        $this->line('==================================================');
        $this->newLine();

        if ($this->dryRun) {
            $this->warn('DRY RUN MODE - No files will be modified');
            $this->newLine();
        }
    }

    protected function confirmExecution(): bool
    {
        if ($this->option('force') || $this->dryRun) {
            return true;
        }

        $this->info('This command will:');
        $this->line('  - Backup current views folder');
        $this->line('  - Remove backup files and duplicates');
        $this->line('  - Rename/move files to new structure');
        $this->line('  - Create 14 modern email templates');
        $this->line('  - Create 5 shared components');
        $this->line('  - Create missing folders for CRUD views');
        $this->newLine();

        return $this->confirm('Do you want to continue?', true);
    }

    protected function executeBackup(): void
    {
        if ($this->option('no-backup')) {
            $this->recordAction('SKIP', 'Backup skipped by --no-backup flag');
            return;
        }

        if (!File::isDirectory($this->viewsPath)) {
            $this->recordAction('SKIP', 'Views folder does not exist');
            return;
        }

        if ($this->dryRun) {
            $this->recordAction('BACKUP', "Would copy views to: {$this->backupPath}");
            return;
        }

        try {
            File::copyDirectory($this->viewsPath, $this->backupPath);
            $this->recordAction('BACKUP', "Views backed up to: views_backup_" . basename($this->backupPath));
        } catch (\Exception $e) {
            $this->recordAction('ERROR', 'Backup failed: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function executeCleanup(): void
    {
        $filesToDelete = [
            'admin/units/create.blade.php.backup',
            'admin/units/edit.blade.php.backup',
            'admin/units/show.blade.php.backup',
            'admin/units/trashed.blade.php.backup',
            'landing/index.blade.php.backup',
            'landing/code.txt',
        ];

        foreach ($filesToDelete as $file) {
            $this->deleteFileIfExists($file);
        }
    }

    protected function executeRenames(): void
    {
        $renames = [
            'components/admin/logout-modal.php' => 'components/admin/logout-modal.blade.php',
            'emails/student-verification.blade.php' => 'emails/student/verification.blade.php',
            'admin/dashboard.blade.php' => 'admin/dashboard/index.blade.php',
        ];

        foreach ($renames as $from => $to) {
            $this->renameFileIfExists($from, $to);
        }

        $this->renameFolderIfExists('student/activities', 'student/dashboard');
    }

    protected function executeRemovals(): void
    {
        $foldersToRemove = [
            'admin/replies',
            'layouts/admin/auth',
            'layouts/employee/auth',
            'layouts/student/auth',
        ];

        foreach ($foldersToRemove as $folder) {
            $this->removeFolderIfExists($folder);
        }

        $duplicateComponents = [
            'components/admin/alert.blade.php',
            'components/admin/button.blade.php',
            'components/admin/delete-modal.blade.php',
            'components/admin/empty-state.blade.php',
            'components/admin/pagination.blade.php',
            'components/admin/status-badge.blade.php',
            'components/student/alert.blade.php',
            'components/student/button.blade.php',
            'components/student/empty-state.blade.php',
            'components/student/pagination.blade.php',
            'components/student/status-badge.blade.php',
        ];

        foreach ($duplicateComponents as $component) {
            $this->deleteFileIfExists($component);
        }
    }

    protected function createMissingFolders(): void
    {
        $folders = [
            'admin/employees/partials',
            'admin/unit-photos',
            'admin/qr-codes',
            'admin/settings',
            'admin/admins/partials',
            'admin/notifications',
            'admin/dashboard/partials',
            'employee/qr-codes',
            'employee/notifications',
            'employee/dashboard/partials',
            'student/qr',
            'student/notifications',
            'student/dashboard/partials',
            'student/verify',
            'emails/admin',
            'emails/employee',
            'emails/student',
            'emails/shared',
        ];

        foreach ($folders as $folder) {
            $this->createFolderIfMissing($folder);
        }
    }

    protected function createSharedComponents(): void
    {
        $components = [
            'components/shared/toast.blade.php' => $this->getToastStub(),
            'components/shared/breadcrumb.blade.php' => $this->getBreadcrumbStub(),
            'components/shared/loading-spinner.blade.php' => $this->getLoadingSpinnerStub(),
            'components/shared/confirm-modal.blade.php' => $this->getConfirmModalStub(),
            'components/shared/data-table.blade.php' => $this->getDataTableStub(),
        ];

        foreach ($components as $path => $content) {
            $this->createFileIfMissing($path, $content);
        }
    }

    protected function createEmailTemplates(): void
    {
        $templates = [
            'emails/admin/reset-password.blade.php' => $this->getResetPasswordEmailStub('Admin'),
            'emails/admin/welcome.blade.php' => $this->getAdminWelcomeEmailStub(),
            'emails/admin/account-deactivated.blade.php' => $this->getAccountDeactivatedEmailStub(),
            'emails/employee/reset-password.blade.php' => $this->getResetPasswordEmailStub('Employee'),
            'emails/employee/welcome.blade.php' => $this->getEmployeeWelcomeEmailStub(),
            'emails/student/reset-password.blade.php' => $this->getResetPasswordEmailStub('Student'),
            'emails/student/verification.blade.php' => $this->getVerificationEmailStub(),
            'emails/student/welcome.blade.php' => $this->getStudentWelcomeEmailStub(),
            'emails/student/rating-submitted.blade.php' => $this->getRatingSubmittedEmailStub(),
            'emails/student/report-submitted.blade.php' => $this->getReportSubmittedEmailStub(),
            'emails/student/report-status-changed.blade.php' => $this->getReportStatusChangedEmailStub(),
            'emails/student/rating-replied.blade.php' => $this->getRatingRepliedEmailStub(),
            'emails/student/report-replied.blade.php' => $this->getReportRepliedEmailStub(),
            'emails/shared/account-deactivated.blade.php' => $this->getAccountDeactivatedEmailStub(),
        ];

        foreach ($templates as $path => $content) {
            $this->createFileIfMissing($path, $content);
        }
    }

    protected function createPlaceholderViews(): void
    {
        $placeholders = [
            'admin/employees/index.blade.php' => $this->getCrudIndexStub('Employees', 'employee'),
            'admin/employees/create.blade.php' => $this->getCrudFormStub('Create Employee', 'employee'),
            'admin/employees/edit.blade.php' => $this->getCrudFormStub('Edit Employee', 'employee'),
            'admin/employees/show.blade.php' => $this->getCrudShowStub('Employee', 'employee'),
            'admin/unit-photos/index.blade.php' => $this->getCrudIndexStub('Unit Photos', 'photo'),
            'admin/qr-codes/index.blade.php' => $this->getCrudIndexStub('QR Codes', 'qrcode'),
            'admin/settings/index.blade.php' => $this->getSettingsIndexStub(),
            'admin/admins/index.blade.php' => $this->getCrudIndexStub('Admins', 'admin'),
            'admin/admins/create.blade.php' => $this->getCrudFormStub('Create Admin', 'admin'),
            'admin/admins/edit.blade.php' => $this->getCrudFormStub('Edit Admin', 'admin'),
            'admin/admins/show.blade.php' => $this->getCrudShowStub('Admin', 'admin'),
            'admin/notifications/index.blade.php' => $this->getCrudIndexStub('Notifications', 'notification'),
            'employee/qr-codes/index.blade.php' => $this->getCrudIndexStub('QR Codes', 'qrcode'),
            'employee/notifications/index.blade.php' => $this->getCrudIndexStub('Notifications', 'notification'),
            'student/qr/scan.blade.php' => $this->getQrScanStub(),
            'student/qr/result.blade.php' => $this->getQrResultStub(),
            'student/notifications/index.blade.php' => $this->getCrudIndexStub('Notifications', 'notification'),
            'student/verify/email.blade.php' => $this->getVerifyEmailStub(),
            'student/verify/success.blade.php' => $this->getVerifySuccessStub(),
        ];

        foreach ($placeholders as $path => $content) {
            $this->createFileIfMissing($path, $content);
        }
    }

    protected function deleteFileIfExists(string $relativePath): void
    {
        $fullPath = $this->viewsPath . '/' . $relativePath;

        if (!File::exists($fullPath)) {
            return;
        }

        if ($this->dryRun) {
            $this->recordAction('DELETE', $relativePath);
            return;
        }

        File::delete($fullPath);
        $this->recordAction('DELETE', $relativePath);
    }

    protected function renameFileIfExists(string $from, string $to): void
    {
        $fromPath = $this->viewsPath . '/' . $from;
        $toPath = $this->viewsPath . '/' . $to;

        if (!File::exists($fromPath)) {
            return;
        }

        if (File::exists($toPath)) {
            $this->recordAction('SKIP', "{$from} -> target already exists");
            return;
        }

        if ($this->dryRun) {
            $this->recordAction('RENAME', "{$from} -> {$to}");
            return;
        }

        $toDir = dirname($toPath);
        if (!File::isDirectory($toDir)) {
            File::makeDirectory($toDir, 0755, true);
        }

        File::move($fromPath, $toPath);
        $this->recordAction('RENAME', "{$from} -> {$to}");
    }

    protected function renameFolderIfExists(string $from, string $to): void
    {
        $fromPath = $this->viewsPath . '/' . $from;
        $toPath = $this->viewsPath . '/' . $to;

        if (!File::isDirectory($fromPath)) {
            return;
        }

        if (File::isDirectory($toPath)) {
            $this->recordAction('SKIP', "Folder {$from} -> target already exists");
            return;
        }

        if ($this->dryRun) {
            $this->recordAction('RENAME FOLDER', "{$from} -> {$to}");
            return;
        }

        File::moveDirectory($fromPath, $toPath);
        $this->recordAction('RENAME FOLDER', "{$from} -> {$to}");
    }

    protected function removeFolderIfExists(string $relativePath): void
    {
        $fullPath = $this->viewsPath . '/' . $relativePath;

        if (!File::isDirectory($fullPath)) {
            return;
        }

        if ($this->dryRun) {
            $this->recordAction('REMOVE FOLDER', $relativePath);
            return;
        }

        File::deleteDirectory($fullPath);
        $this->recordAction('REMOVE FOLDER', $relativePath);
    }

    protected function createFolderIfMissing(string $relativePath): void
    {
        $fullPath = $this->viewsPath . '/' . $relativePath;

        if (File::isDirectory($fullPath)) {
            return;
        }

        if ($this->dryRun) {
            $this->recordAction('CREATE FOLDER', $relativePath);
            return;
        }

        File::makeDirectory($fullPath, 0755, true);
        $this->recordAction('CREATE FOLDER', $relativePath);
    }

    protected function createFileIfMissing(string $relativePath, string $content): void
    {
        $fullPath = $this->viewsPath . '/' . $relativePath;

        if (File::exists($fullPath)) {
            $this->recordAction('SKIP', "{$relativePath} already exists");
            return;
        }

        if ($this->dryRun) {
            $this->recordAction('CREATE', $relativePath);
            return;
        }

        $dir = dirname($fullPath);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        File::put($fullPath, $content);
        $this->recordAction('CREATE', $relativePath);
    }

    protected function recordAction(string $type, string $description): void
    {
        $this->actions[] = ['type' => $type, 'description' => $description];

        $colors = [
            'DELETE' => 'red',
            'REMOVE FOLDER' => 'red',
            'RENAME' => 'yellow',
            'RENAME FOLDER' => 'yellow',
            'CREATE' => 'green',
            'CREATE FOLDER' => 'green',
            'BACKUP' => 'cyan',
            'SKIP' => 'gray',
            'ERROR' => 'red',
        ];

        $color = $colors[$type] ?? 'white';
        $tag = str_pad("[{$type}]", 16);
        $this->line("  <fg={$color}>{$tag}</> {$description}");
    }

    protected function displaySummary(): void
    {
        $this->newLine();
        $this->line('==================================================');
        $this->line('  SUMMARY');
        $this->line('==================================================');
        $this->newLine();

        $summary = [];
        foreach ($this->actions as $action) {
            $type = $action['type'];
            $summary[$type] = ($summary[$type] ?? 0) + 1;
        }

        $table = [];
        foreach ($summary as $type => $count) {
            $table[] = [$type, $count];
        }

        $this->table(['Action', 'Count'], $table);
        $this->newLine();

        if ($this->dryRun) {
            $this->warn('This was a dry run. No files were modified.');
            $this->line('Run without --dry-run to apply changes.');
        } else {
            $this->info('Views structure regenerated successfully.');
            if (!$this->option('no-backup')) {
                $this->line("Backup location: {$this->backupPath}");
            }
        }

        $this->newLine();
    }

    protected function getEmailBaseStub(string $title, string $preheader, string $contentHtml, string $buttonUrl = '', string $buttonText = ''): string
    {
        $buttonHtml = '';
        if ($buttonUrl && $buttonText) {
            $buttonHtml = <<<HTML
<table role="presentation" cellspacing="0" cellpadding="0" border="0" style="margin: 32px auto;">
    <tr>
        <td style="border-radius: 6px; background: #E8520A;">
            <a href="{$buttonUrl}" target="_blank" style="display: inline-block; padding: 14px 32px; font-family: 'Inter', sans-serif; font-size: 14px; font-weight: 600; color: #ffffff; text-decoration: none; border-radius: 6px; letter-spacing: 0.05em; text-transform: uppercase;">{$buttonText}</a>
        </td>
    </tr>
</table>
HTML;
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{$title}</title>
    <style>
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f4f4f4; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; }
        @media screen and (max-width: 600px) {
            .container { width: 100% !important; max-width: 100% !important; }
            .content-padding { padding: 24px 20px !important; }
            .h1 { font-size: 24px !important; line-height: 1.2 !important; }
            .h2 { font-size: 20px !important; }
            .body-text { font-size: 15px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
    <div style="display: none; max-height: 0; overflow: hidden; opacity: 0; color: transparent;">{$preheader}</div>

    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f4f4f4;">
        <tr>
            <td align="center" style="padding: 40px 20px;">

                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" class="container" style="max-width: 600px; width: 100%;">

                    <tr>
                        <td align="center" style="padding: 24px 0; font-family: 'Inter', sans-serif;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td style="font-size: 24px; font-weight: 700; letter-spacing: 0.06em; color: #0a0a0a;">ITENAS</td>
                                    <td style="font-size: 24px; font-weight: 700; letter-spacing: 0.06em; color: #E8520A; padding-left: 4px;">UNITS</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td style="height: 4px; background: linear-gradient(90deg, #E8520A 0%, #c04408 100%);"></td>
                                </tr>
                            </table>

                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" class="content-padding">
                                <tr>
                                    <td style="padding: 48px 48px 24px 48px;" class="content-padding">
                                        {$contentHtml}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 0 48px 48px 48px;" class="content-padding">
                                        {$buttonHtml}
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 32px 20px 16px; font-family: 'Inter', sans-serif;">
                            <p style="margin: 0; font-size: 12px; color: #888888; line-height: 1.6;">
                                &copy; {{ date('Y') }} Institut Teknologi Nasional. All rights reserved.<br>
                                Jl. PHH. Mustapa No.23, Bandung, Jawa Barat, Indonesia
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding: 0 20px 24px; font-family: 'Inter', sans-serif;">
                            <p style="margin: 0; font-size: 11px; color: #aaaaaa;">
                                This is an automated message. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }

    protected function getResetPasswordEmailStub(string $role): string
    {
        $nameVar = strtolower($role) . 'Name';
        $content = <<<HTML
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Reset Password</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ \${$nameVar} }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Kami menerima permintaan untuk mereset password akun {$role} Anda di ITENAS Units Portal. Klik tombol di bawah untuk melanjutkan.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px; border-left: 3px solid #E8520A;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 4px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Link expires in</p>
            <p style="margin: 0; font-size: 16px; color: #0a0a0a; font-weight: 600;">{{ \$expiresInMinutes }} minutes</p>
        </td>
    </tr>
</table>
<p class="body-text" style="margin: 24px 0 0; font-size: 14px; line-height: 1.6; color: #666666;">
    Jika Anda tidak meminta reset password, abaikan email ini. Password Anda akan tetap aman.
</p>
HTML;
        return $this->getEmailBaseStub(
            "Reset Password {$role}",
            "Reset password untuk akun {$role} ITENAS Units Portal",
            $content,
            '{{ $resetUrl }}',
            'Reset Password'
        );
    }

    protected function getVerificationEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Verifikasi Email</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $studentName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Terima kasih telah mendaftar di ITENAS Units Portal. Untuk menyelesaikan registrasi, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px; border-left: 3px solid #E8520A;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 4px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Email to verify</p>
            <p style="margin: 0; font-size: 16px; color: #0a0a0a; font-weight: 600;">{{ $studentEmail }}</p>
        </td>
    </tr>
</table>
<p class="body-text" style="margin: 24px 0 0; font-size: 14px; line-height: 1.6; color: #666666;">
    Link verifikasi akan kadaluarsa dalam <strong>{{ $expiresInHours }} jam</strong>. Jika Anda tidak merasa mendaftar, abaikan email ini.
</p>
HTML;
        return $this->getEmailBaseStub(
            'Verifikasi Email - ITENAS Units',
            'Verifikasi email Anda untuk mengaktifkan akun ITENAS Units Portal',
            $content,
            '{{ $verificationUrl }}',
            'Verifikasi Email'
        );
    }

    protected function getAdminWelcomeEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Selamat Datang, Admin!</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $adminName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Akun <strong>{{ $role }}</strong> Anda di ITENAS Units Portal telah berhasil dibuat. Berikut adalah kredensial login Anda:
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px; border-left: 3px solid #E8520A;">
    <tr>
        <td style="padding: 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 12px;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Email</span><br><strong style="color: #0a0a0a;">{{ $adminEmail }}</strong></p>
            <p style="margin: 0;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Temporary Password</span><br><strong style="color: #E8520A; font-family: 'Courier New', monospace; font-size: 18px;">{{ $temporaryPassword }}</strong></p>
        </td>
    </tr>
</table>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #fff4ed; border-radius: 6px;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #c04408;">
                <strong>⚠ Important:</strong> Segera ganti password Anda setelah login pertama untuk keamanan akun.
            </p>
        </td>
    </tr>
</table>
HTML;
        return $this->getEmailBaseStub(
            'Welcome Admin - ITENAS Units',
            'Akun admin Anda di ITENAS Units Portal telah dibuat',
            $content,
            '{{ $loginUrl }}',
            'Login Sekarang'
        );
    }

    protected function getEmployeeWelcomeEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Selamat Datang, Employee!</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $employeeName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Anda telah ditambahkan sebagai Employee di ITENAS Units Portal. Berikut adalah informasi akun Anda:
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px; border-left: 3px solid #E8520A;">
    <tr>
        <td style="padding: 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 12px;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Email</span><br><strong style="color: #0a0a0a;">{{ $employeeEmail }}</strong></p>
            <p style="margin: 0;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Temporary Password</span><br><strong style="color: #E8520A; font-family: 'Courier New', monospace; font-size: 18px;">{{ $temporaryPassword }}</strong></p>
        </td>
    </tr>
</table>
@if(!empty($assignedUnits))
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0;">
    <tr>
        <td style="font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 12px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Assigned Units</p>
            @foreach($assignedUnits as $unitName)
            <p style="margin: 0 0 8px; font-size: 14px; color: #0a0a0a;">✓ {{ $unitName }}</p>
            @endforeach
        </td>
    </tr>
</table>
@endif
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #fff4ed; border-radius: 6px;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #c04408;">
                <strong>⚠ Important:</strong> Ganti password Anda setelah login pertama.
            </p>
        </td>
    </tr>
</table>
HTML;
        return $this->getEmailBaseStub(
            'Welcome Employee - ITENAS Units',
            'Akun employee Anda di ITENAS Units Portal telah dibuat',
            $content,
            '{{ $loginUrl }}',
            'Login Sekarang'
        );
    }

    protected function getStudentWelcomeEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Selamat Datang di ITENAS Units!</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $studentName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Email Anda telah berhasil diverifikasi. Sekarang Anda adalah bagian dari komunitas ITENAS Units Portal.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px; border-left: 3px solid #E8520A;">
    <tr>
        <td style="padding: 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 4px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Your Student ID</p>
            <p style="margin: 0; font-size: 20px; color: #E8520A; font-weight: 700; font-family: 'Courier New', monospace;">{{ $studentIdentifier }}</p>
        </td>
    </tr>
</table>
<p class="body-text" style="margin: 24px 0 0; font-size: 16px; line-height: 1.6; color: #444444;">
    <strong>What you can do now:</strong>
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 16px 0;">
    <tr><td style="padding: 8px 0; font-size: 15px; color: #444444;">✓ Browse semua unit kegiatan mahasiswa</td></tr>
    <tr><td style="padding: 8px 0; font-size: 15px; color: #444444;">✓ Scan QR Code unit untuk check-in</td></tr>
    <tr><td style="padding: 8px 0; font-size: 15px; color: #444444;">✓ Give ratings & reviews</td></tr>
    <tr><td style="padding: 8px 0; font-size: 15px; color: #444444;">✓ Submit reports jika ada masalah</td></tr>
</table>
HTML;
        return $this->getEmailBaseStub(
            'Welcome to ITENAS Units',
            'Selamat datang di ITENAS Units Portal',
            $content,
            '{{ $exploreUrl }}',
            'Explore Units'
        );
    }

    protected function getRatingSubmittedEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Rating Berhasil Dikirim</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $studentName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Terima kasih telah memberikan rating untuk <strong>{{ $unitName }}</strong>. Feedback Anda sangat berarti untuk meningkatkan kualitas unit.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px;">
    <tr>
        <td style="padding: 24px; font-family: 'Inter', sans-serif; text-align: center;">
            <p style="margin: 0 0 8px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Your Rating</p>
            <p style="margin: 0; font-size: 48px; font-weight: 700; color: #E8520A; line-height: 1;">{{ number_format($overallScore, 1) }}</p>
            <p style="margin: 8px 0 0; font-size: 14px; color: #888888;">out of 5.0</p>
        </td>
    </tr>
</table>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 16px 0; background: #f9f9f9; border-radius: 6px;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 4px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Tracking Code</p>
            <p style="margin: 0; font-size: 16px; color: #0a0a0a; font-weight: 600; font-family: 'Courier New', monospace;">{{ $trackingCode }}</p>
        </td>
    </tr>
</table>
<p class="body-text" style="margin: 24px 0 0; font-size: 14px; line-height: 1.6; color: #666666;">
    Anda dapat mengubah rating Anda dalam waktu 7 hari jika diperlukan.
</p>
HTML;
        return $this->getEmailBaseStub(
            'Rating Submitted - ITENAS Units',
            'Rating Anda untuk unit berhasil dikirim',
            $content,
            '{{ $viewUrl }}',
            'View Rating'
        );
    }

    protected function getReportSubmittedEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Laporan Diterima</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $studentName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Laporan Anda untuk <strong>{{ $unitName }}</strong> telah kami terima dan akan segera ditindaklanjuti oleh tim terkait.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px;">
    <tr>
        <td style="padding: 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 12px;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Tracking Code</span><br><strong style="color: #0a0a0a; font-family: 'Courier New', monospace;">{{ $trackingCode }}</strong></p>
            <p style="margin: 0 0 12px;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Title</span><br><strong style="color: #0a0a0a;">{{ $reportTitle }}</strong></p>
            <p style="margin: 0;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Priority</span><br><strong style="color: #E8520A;">{{ $priority }}</strong></p>
        </td>
    </tr>
</table>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #fff4ed; border-radius: 6px;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0; font-size: 14px; line-height: 1.6; color: #c04408;">
                <strong>What's next?</strong> Tim kami akan mereview laporan Anda dan Anda akan menerima notifikasi email saat status berubah.
            </p>
        </td>
    </tr>
</table>
HTML;
        return $this->getEmailBaseStub(
            'Report Submitted - ITENAS Units',
            'Laporan Anda telah diterima',
            $content,
            '{{ $viewUrl }}',
            'Track Report'
        );
    }

    protected function getReportStatusChangedEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Status Laporan Diperbarui</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $studentName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Status laporan Anda dengan judul <strong>{{ $reportTitle }}</strong> telah diperbarui.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0;">
    <tr>
        <td align="center" style="padding: 20px; font-family: 'Inter', sans-serif;">
            <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="background: #f9f9f9; padding: 12px 20px; border-radius: 6px;">
                        <p style="margin: 0; font-size: 14px; color: #666666;">{{ $oldStatus }}</p>
                    </td>
                    <td style="padding: 0 16px; font-size: 24px; color: #E8520A;">→</td>
                    <td style="background: #E8520A; padding: 12px 20px; border-radius: 6px;">
                        <p style="margin: 0; font-size: 14px; color: #ffffff; font-weight: 600;">{{ $newStatus }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 16px 0; background: #f9f9f9; border-radius: 6px;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 8px;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Tracking Code</span><br><strong style="color: #0a0a0a; font-family: 'Courier New', monospace;">{{ $trackingCode }}</strong></p>
            @if($changedByName)
            <p style="margin: 0 0 8px;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Updated by</span><br><strong style="color: #0a0a0a;">{{ $changedByName }} ({{ $changedByRole ?? 'Staff' }})</strong></p>
            @endif
            @if($reason)
            <p style="margin: 0;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Reason</span><br><strong style="color: #0a0a0a;">{{ $reason }}</strong></p>
            @endif
        </td>
    </tr>
</table>
HTML;
        return $this->getEmailBaseStub(
            'Report Status Updated',
            'Status laporan Anda telah diperbarui',
            $content,
            '{{ $viewUrl }}',
            'View Report'
        );
    }

    protected function getRatingRepliedEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Rating Anda Dibalas</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $studentName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    <strong>{{ $repliedByName }}</strong> ({{ $repliedByRole }}) dari <strong>{{ $unitName }}</strong> telah membalas rating Anda.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px; border-left: 3px solid #E8520A;">
    <tr>
        <td style="padding: 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 8px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Reply from {{ $repliedByName }}</p>
            <p style="margin: 0; font-size: 15px; color: #0a0a0a; line-height: 1.6; font-style: italic;">"{{ $replyPreview }}"</p>
        </td>
    </tr>
</table>
<p class="body-text" style="margin: 24px 0 0; font-size: 14px; line-height: 1.6; color: #666666;">
    Klik tombol di bawah untuk melihat balasan lengkap dan melanjutkan diskusi.
</p>
HTML;
        return $this->getEmailBaseStub(
            'Rating Reply - ITENAS Units',
            'Rating Anda telah dibalas',
            $content,
            '{{ $viewUrl }}',
            'View Reply'
        );
    }

    protected function getReportRepliedEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Laporan Anda Dibalas</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $studentName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    <strong>{{ $repliedByName }}</strong> ({{ $repliedByRole }}) telah membalas laporan Anda: <strong>{{ $reportTitle }}</strong>.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px; border-left: 3px solid #E8520A;">
    <tr>
        <td style="padding: 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 8px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Reply from {{ $repliedByName }}</p>
            <p style="margin: 0; font-size: 15px; color: #0a0a0a; line-height: 1.6; font-style: italic;">"{{ $replyPreview }}"</p>
        </td>
    </tr>
</table>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 16px 0; background: #f9f9f9; border-radius: 6px;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 4px; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Tracking Code</p>
            <p style="margin: 0; font-size: 16px; color: #0a0a0a; font-weight: 600; font-family: 'Courier New', monospace;">{{ $trackingCode }}</p>
        </td>
    </tr>
</table>
HTML;
        return $this->getEmailBaseStub(
            'Report Reply - ITENAS Units',
            'Laporan Anda telah dibalas',
            $content,
            '{{ $viewUrl }}',
            'View Reply'
        );
    }

    protected function getAccountDeactivatedEmailStub(): string
    {
        $content = <<<'HTML'
<h1 class="h1" style="margin: 0 0 16px; font-size: 28px; font-weight: 700; color: #0a0a0a; letter-spacing: -0.01em; line-height: 1.2;">Akun Dinonaktifkan</h1>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Halo <strong>{{ $recipientName }}</strong>,
</p>
<p class="body-text" style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #444444;">
    Kami informasikan bahwa akun <strong>{{ $role }}</strong> Anda di ITENAS Units Portal telah <strong style="color: #c04408;">dinonaktifkan</strong> oleh <strong>{{ $deactivatedByName }}</strong>.
</p>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 24px 0; background: #f9f9f9; border-radius: 6px;">
    <tr>
        <td style="padding: 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 12px;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Account Email</span><br><strong style="color: #0a0a0a;">{{ $recipientEmail }}</strong></p>
            <p style="margin: 0;"><span style="font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 0.1em;">Role</span><br><strong style="color: #0a0a0a;">{{ $role }}</strong></p>
        </td>
    </tr>
</table>
@if($reason)
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 16px 0; background: #fff4ed; border-radius: 6px; border-left: 3px solid #E8520A;">
    <tr>
        <td style="padding: 16px 20px; font-family: 'Inter', sans-serif;">
            <p style="margin: 0 0 4px; font-size: 12px; color: #c04408; text-transform: uppercase; letter-spacing: 0.1em;">Reason</p>
            <p style="margin: 0; font-size: 14px; color: #0a0a0a; line-height: 1.6;">{{ $reason }}</p>
        </td>
    </tr>
</table>
@endif
<p class="body-text" style="margin: 24px 0 0; font-size: 14px; line-height: 1.6; color: #666666;">
    Jika Anda merasa ini adalah kesalahan atau memiliki pertanyaan, silakan hubungi kami di <a href="mailto:{{ $supportEmail }}" style="color: #E8520A; text-decoration: underline;">{{ $supportEmail }}</a>.
</p>
HTML;
        return $this->getEmailBaseStub(
            'Account Deactivated - ITENAS Units',
            'Akun Anda di ITENAS Units Portal telah dinonaktifkan',
            $content
        );
    }

    protected function getToastStub(): string
    {
        return <<<'BLADE'
@props(['type' => 'info', 'message' => '', 'dismissible' => true])

@php
$colors = [
    'success' => ['bg' => 'bg-green-50', 'border' => 'border-green-400', 'text' => 'text-green-800', 'icon' => '✓'],
    'error' => ['bg' => 'bg-red-50', 'border' => 'border-red-400', 'text' => 'text-red-800', 'icon' => '✕'],
    'warning' => ['bg' => 'bg-yellow-50', 'border' => 'border-yellow-400', 'text' => 'text-yellow-800', 'icon' => '⚠'],
    'info' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-400', 'text' => 'text-blue-800', 'icon' => 'ℹ'],
];
$color = $colors[$type] ?? $colors['info'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-md p-4 border-l-4 {$color['bg']} {$color['border']} {$color['text']}"]) }} role="alert">
    <div class="flex">
        <div class="shrink-0">
            <span class="text-lg">{{ $color['icon'] }}</span>
        </div>
        <div class="ml-3 flex-1">
            <p class="text-sm">{{ $message ?: $slot }}</p>
        </div>
        @if($dismissible)
        <button type="button" onclick="this.parentElement.parentElement.remove()" class="ml-auto">
            <span class="text-xl">&times;</span>
        </button>
        @endif
    </div>
</div>
BLADE;
    }

    protected function getBreadcrumbStub(): string
    {
        return <<<'BLADE'
@props(['items' => []])

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-2 text-sm">
        <li>
            <a href="{{ url('/') }}" class="text-gray-500 hover:text-orange-600">Home</a>
        </li>
        @foreach($items as $label => $url)
        <li class="flex items-center">
            <span class="text-gray-400 mx-2">/</span>
            @if($loop->last)
            <span class="text-gray-900 font-medium">{{ $label }}</span>
            @else
            <a href="{{ $url }}" class="text-gray-500 hover:text-orange-600">{{ $label }}</a>
            @endif
        </li>
        @endforeach
    </ol>
</nav>
BLADE;
    }

    protected function getLoadingSpinnerStub(): string
    {
        return <<<'BLADE'
@props(['size' => 'md', 'text' => 'Loading...'])

@php
$sizes = ['sm' => 'w-4 h-4', 'md' => 'w-8 h-8', 'lg' => 'w-12 h-12'];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center gap-3']) }}>
    <div class="{{ $sizeClass }} border-4 border-gray-200 border-t-orange-500 rounded-full animate-spin"></div>
    @if($text)
    <p class="text-sm text-gray-600">{{ $text }}</p>
    @endif
</div>
BLADE;
    }

    protected function getConfirmModalStub(): string
    {
        return <<<'BLADE'
@props([
    'id' => 'confirm-modal',
    'title' => 'Are you sure?',
    'message' => 'This action cannot be undone.',
    'confirmText' => 'Confirm',
    'cancelText' => 'Cancel',
    'type' => 'danger'
])

@php
$confirmColors = [
    'danger' => 'bg-red-600 hover:bg-red-700',
    'warning' => 'bg-yellow-600 hover:bg-yellow-700',
    'info' => 'bg-blue-600 hover:bg-blue-700',
];
$confirmColor = $confirmColors[$type] ?? $confirmColors['danger'];
@endphp

<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
            <p class="text-sm text-gray-600 mb-6">{{ $message }}</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    {{ $cancelText }}
                </button>
                <form id="{{ $id }}-form" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white {{ $confirmColor }} rounded-md">
                        {{ $confirmText }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
BLADE;
    }

    protected function getDataTableStub(): string
    {
        return <<<'BLADE'
@props(['headers' => [], 'paginator' => null])

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $header }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    @if($paginator && $paginator->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $paginator->links() }}
    </div>
    @endif
</div>
BLADE;
    }

    protected function getCrudIndexStub(string $title, string $entity): string
    {
        return <<<BLADE
@extends('layouts.admin.app')

@section('title', '{$title}')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-shared.breadcrumb :items="['{$title}' => route('admin.dashboard')]" />
        
        <div class="sm:flex sm:items-center sm:justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{$title}</h1>
            <a href="#" class="inline-flex items-center px-4 py-2 bg-orange-600 text-white text-sm font-medium rounded-md hover:bg-orange-700">
                + Add New {$entity}
            </a>
        </div>

        <x-shared.data-table :headers="['ID', 'Name', 'Status', 'Actions']">
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                    <x-shared.empty-state message="No {$entity} data available" />
                </td>
            </tr>
        </x-shared.data-table>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getCrudFormStub(string $title, string $entity): string
    {
        return <<<BLADE
@extends('layouts.admin.app')

@section('title', '{$title}')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-shared.breadcrumb :items="[
            '{$entity}s' => '#',
            '{$title}' => null
        ]" />
        
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">{$title}</h1>
            
            <form method="POST" action="#" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500" required>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="#" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-md hover:bg-orange-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getCrudShowStub(string $title, string $entity): string
    {
        return <<<BLADE
@extends('layouts.admin.app')

@section('title', '{$title} Detail')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-shared.breadcrumb :items="[
            '{$entity}s' => '#',
            'Detail' => null
        ]" />
        
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">{$title} Detail</h1>
            
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">-</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1"><x-shared.status-badge status="active" /></dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getSettingsIndexStub(): string
    {
        return <<<'BLADE'
@extends('layouts.admin.app')

@section('title', 'Settings')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-shared.breadcrumb :items="['Settings' => null]" />
        
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Application Settings</h1>
                <p class="mt-1 text-sm text-gray-500">Manage global application configuration</p>
            </div>
            
            <form method="POST" action="#" class="p-6 space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Application Name</label>
                        <input type="text" name="settings[app_name]" value="ITENAS Units Portal" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Support Email</label>
                        <input type="email" name="settings[support_email]" value="support@itenas.ac.id" class="w-full px-3 py-2 border border-gray-300 rounded-md">
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-orange-600 rounded-md hover:bg-orange-700">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getQrScanStub(): string
    {
        return <<<'BLADE'
@extends('layouts.student.app')

@section('title', 'Scan QR Code')

@section('content')
<div class="py-6">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 text-center">Scan QR Code</h1>
            <p class="text-sm text-gray-500 text-center mb-6">Scan the QR code at the unit location</p>
            
            <div class="aspect-square bg-gray-100 rounded-lg mb-4 flex items-center justify-center">
                <div id="qr-reader" class="w-full h-full"></div>
            </div>
            
            <form method="POST" action="{{ route('student.qr.validate') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Or enter code manually</label>
                    <input type="text" name="code" placeholder="e.g., UNIT-ABC12345" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                </div>
                <button type="submit" class="w-full py-3 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700">Validate</button>
            </form>
        </div>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getQrResultStub(): string
    {
        return <<<'BLADE'
@extends('layouts.student.app')

@section('title', 'QR Scan Result')

@section('content')
<div class="py-6">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white rounded-lg shadow-lg p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="text-3xl text-green-600">✓</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">QR Code Valid</h1>
            <p class="text-sm text-gray-500 mb-6">{{ $unit->name }}</p>
            
            <div class="space-y-3">
                <a href="{{ route('student.ratings.create', $unit->slug) }}" class="block w-full py-3 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700">Give Rating</a>
                <a href="{{ route('student.units.show', $unit->slug) }}" class="block w-full py-3 bg-white text-gray-700 font-medium border border-gray-300 rounded-md hover:bg-gray-50">View Unit Details</a>
            </div>
        </div>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getVerifyEmailStub(): string
    {
        return <<<'BLADE'
@extends('layouts.student.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="text-4xl">📧</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Verify Your Email</h1>
        <p class="text-gray-600 mb-6">We've sent a verification link to your email address. Please check your inbox and click the link to activate your account.</p>
        
        <div class="bg-gray-50 rounded-md p-4 mb-6">
            <p class="text-sm text-gray-500">Didn't receive the email?</p>
            <form method="POST" action="{{ route('student.verify.resend') }}" class="mt-2">
                @csrf
                <button type="submit" class="text-orange-600 font-medium hover:text-orange-700">Resend verification email</button>
            </form>
        </div>
        
        <a href="{{ route('student.login') }}" class="text-sm text-gray-500 hover:text-gray-700">Back to login</a>
    </div>
</div>
@endsection
BLADE;
    }

    protected function getVerifySuccessStub(): string
    {
        return <<<'BLADE'
@extends('layouts.student.app')

@section('title', 'Email Verified')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="text-4xl text-green-600">✓</span>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Email Verified!</h1>
        <p class="text-gray-600 mb-6">Your email has been successfully verified. You can now login to your account.</p>
        <a href="{{ route('student.login') }}" class="inline-block w-full py-3 bg-orange-600 text-white font-medium rounded-md hover:bg-orange-700">Login Now</a>
    </div>
</div>
@endsection
BLADE;
    }
}