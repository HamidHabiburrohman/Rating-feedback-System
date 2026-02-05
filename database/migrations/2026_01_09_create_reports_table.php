<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('visitor_session_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('tipe', ['masalah', 'saran', 'keluhan', 'lainnya']);
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'kritis'])->default('sedang');
            $table->enum('status', ['baru', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('tanggapan_admin')->nullable();
            $table->timestamp('ditanggapi_pada')->nullable();
            $table->timestamps();

            $table->index(['unit_id', 'status']);
            $table->index('visitor_session_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};