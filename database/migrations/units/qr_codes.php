<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->string('code')->unique();
            $table->string('path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('last_generated_at')->nullable();
            $table->foreignId('generated_by_admin_id')->constrained('admins');
            $table->timestamps();
            $table->softDeletes();
            $table->index('code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};