<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('unit_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('unit_department_id')->constrained()->restrictOnDelete();
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('building')->nullable();
            $table->string('floor')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->unsignedInteger('capacity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('operational_status', ['open', 'full', 'maintenance', 'closed'])->default('open');
            $table->foreignId('primary_qr_code_id')->nullable()->constrained('qr_codes')->nullOnDelete();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};