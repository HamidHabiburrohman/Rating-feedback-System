<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit')->unique();
            $table->string('nama_unit');
            $table->text('deskripsi')->nullable();
            $table->foreignId('type_id')->constrained('unit_types')->onDelete('restrict');
            $table->string('lokasi');
            $table->string('foto_unit')->nullable();
            $table->string('gedung')->nullable();
            $table->string('lantai')->nullable();
            $table->string('kontak_telepon')->nullable();
            $table->string('kontak_email')->nullable();
            $table->time('jam_buka')->nullable();
            $table->time('jam_tutup')->nullable();
            $table->integer('kapasitas')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->enum('status', ['open', 'full', 'maintenance', 'closed'])->default('open');
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk status
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};