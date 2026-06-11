<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('employee_id', 50)->unique();
            $table->string('photo')->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('position', 120)->nullable();
            $table->string('department', 100)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->string('timezone', 60)->default('Asia/Jakarta');
            $table->json('preferences')->nullable();
            $table->unsignedBigInteger('login_count')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};