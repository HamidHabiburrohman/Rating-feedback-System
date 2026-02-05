<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Index untuk tabel-tabel yang sering di-query
        Schema::table('ratings', function (Blueprint $table) {
            $table->index(['created_at', 'unit_id']);
            $table->index(['status', 'created_at']);
        });

        Schema::table('rating_scores', function (Blueprint $table) {
            $table->index(['rating_category_id', 'created_at']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->index(['type_id', 'status_aktif']);
            $table->index(['gedung', 'lantai']); 
            $table->fulltext(['nama_unit', 'deskripsi', 'lokasi']);
        });


        Schema::table('reports', function (Blueprint $table) {
            $table->index(['tipe', 'status', 'created_at']);
            $table->fulltext(['judul', 'deskripsi']);
        });

        Schema::table('unit_visits', function (Blueprint $table) {
            $table->index(['tanggal', 'unit_id']);
            $table->index(['waktu_masuk', 'waktu_keluar']);
        });

        Schema::table('unit_types', function (Blueprint $table) {
            $table->index(['is_active', 'sort_order']);
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropIndex(['created_at', 'unit_id']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('rating_scores', function (Blueprint $table) {
            $table->dropIndex(['rating_category_id', 'created_at']);
        });

        Schema::table('units', function (Blueprint $table) {
            $table->dropIndex(['type_id', 'status_aktif']);
            $table->dropIndex(['gedung', 'lantai']);
            $table->dropFulltext(['nama_unit', 'deskripsi', 'lokasi']);
        });

        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex(['tipe', 'status', 'created_at']);
            $table->dropFulltext(['judul', 'deskripsi']);
        });

        Schema::table('unit_visits', function (Blueprint $table) {
            $table->dropIndex(['tanggal', 'unit_id']);
            $table->dropIndex(['waktu_masuk', 'waktu_keluar']);
        });

        Schema::table('unit_types', function (Blueprint $table) {
            $table->dropIndex(['is_active', 'sort_order']);
            $table->dropIndex(['name']);
        });
    }
};