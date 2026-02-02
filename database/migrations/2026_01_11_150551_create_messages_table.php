<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            
            // Pengirim & Penerima (Indonesian)
            $table->enum('pengirim_tipe', ['admin', 'unit']);
            $table->foreignId('pengirim_id');
            $table->enum('penerima_tipe', ['admin', 'unit']);
            $table->foreignId('penerima_id');
            
            // Konten Pesan (Indonesian)
            $table->string('judul');
            $table->text('pesan');
            
            // Kategori (English for consistency with unit)
            $table->enum('kategori', [
                'technical',      // masalah teknis
                'status_request', // request ubah status
                'rating_feedback',// feedback rating
                'maintenance',    // maintenance info
                'announcement',   // pengumuman
                'instruction',    // instruksi
                'question',       // pertanyaan
                'emergency'       // darurat
            ]);
            
            $table->enum('prioritas', ['biasa', 'penting', 'sangat_penting'])->default('biasa');
            
            // Status & Tracking (Indonesian)
            $table->enum('status', ['terkirim', 'diterima', 'dibaca', 'ditanggapi', 'selesai'])->default('terkirim');
            $table->boolean('perlu_tindakan')->default(false);
            $table->string('tipe_tindakan')->nullable();
            $table->json('data_tindakan')->nullable();
            
            // Entitas Terkait
            $table->foreignId('unit_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('rating_id')->nullable()->constrained()->onDelete('cascade');
            
            // Timestamps
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamp('tindakan_diambil_pada')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['pengirim_tipe', 'pengirim_id']);
            $table->index(['penerima_tipe', 'penerima_id']);
            $table->index(['status', 'prioritas']);
            $table->index(['kategori', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
};