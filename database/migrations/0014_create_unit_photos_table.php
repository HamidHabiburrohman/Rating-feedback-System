<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('unit_photos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('unit_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('uploaded_by_admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('original_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('medium_path')->nullable();
            $table->string('large_path')->nullable();

            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index('unit_id');
            $table->index(['unit_id', 'is_primary']);
            $table->index(['unit_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_photos');
    }
};