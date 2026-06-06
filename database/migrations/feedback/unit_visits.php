<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('qr_code_id')->constrained()->cascadeOnDelete();
            $table->timestamp('visited_at')->useCurrent();
            $table->boolean('is_gps_validated')->default(false);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->unsignedInteger('validation_radius_meters')->default(100);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['unit_id', 'visited_at']);
            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_visits');
    }
};