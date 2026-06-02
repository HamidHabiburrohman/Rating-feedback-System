<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();

            // ── Identity ──────────────────────────────────────────────
            $table->string('nama');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'super_admin', 'unit'])->default('admin');

            // ── Profile ───────────────────────────────────────────────
            $table->string('photo')->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('location', 150)->nullable();

            // ── Work Details ──────────────────────────────────────────
            $table->string('employee_id', 50)->nullable()->unique()->comment('Internal employee ID, e.g. EMP-001');
            $table->string('position', 120)->nullable()->comment('Job title, e.g. Content Manager');
            $table->string('department', 100)->nullable()->comment('Department or team name');
            $table->text('bio')->nullable();

            // ── System / Account ──────────────────────────────────────
            $table->string('timezone', 60)->default('Asia/Jakarta');
            $table->boolean('is_active')->default(true)->index()->comment('Soft-disable without deleting the account');
            $table->boolean('two_factor_enabled')->default(false);

            // ── Permissions & Preferences ─────────────────────────────
            $table->json('permissions')->nullable()->comment('Granular permission list for non-super-admins');
            $table->json('preferences')->nullable()->comment('UI preferences: theme, language, notifications, etc.');

            // ── Login Audit ───────────────────────────────────────────
            $table->unsignedBigInteger('login_count')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // ── Password Reset Tokens ─────────────────────────────────────
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ── Sessions ──────────────────────────────────────────────────
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('admins');
    }
};