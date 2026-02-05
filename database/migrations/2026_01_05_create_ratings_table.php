<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visitor_session_id')->constrained()->cascadeOnDelete();
            $table->text('komentar')->nullable();
            $table->enum('status', ['pending', 'dibalas', 'selesai'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamp('dibalas_pada')->nullable();
            $table->timestamps();

            $table->index(['unit_id', 'status']);
            $table->index('visitor_session_id');
        });
    }

    public function down() {
        Schema::dropIfExists('ratings');
    }
};