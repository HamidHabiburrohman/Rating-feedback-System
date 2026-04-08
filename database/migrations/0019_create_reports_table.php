<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();

            $table->foreignId('rating_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('unit_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('student_identifier');
            $table->foreign('student_identifier')
                ->references('student_identifier')
                ->on('students')
                ->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            
            // Kolom attachment
            $table->string('attachment_path')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->string('attachment_mime_type')->nullable();
            $table->unsignedBigInteger('attachment_size')->nullable();

            $table->enum('priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->enum('status', ['new', 'in_progress', 'replied', 'resolved', 'rejected', 'pending_preview'])->default('new');

            $table->foreignId('admin_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('admin_response')->nullable();
            $table->timestamp('replied_at')->nullable();

            $table->timestamps();

            $table->index(['unit_id', 'status']);
            $table->index(['rating_id', 'status']);
            $table->index('priority');
            $table->index('student_identifier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};