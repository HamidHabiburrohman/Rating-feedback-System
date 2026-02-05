<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('visitor_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->ipAddress('ip_address');
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('terakhir_aktivitas')->useCurrentOnUpdate();
            $table->timestamps();

            $table->index(['session_id', 'terakhir_aktivitas']);
        });
    }

    public function down() {
        Schema::dropIfExists('visitor_sessions');
    }
};