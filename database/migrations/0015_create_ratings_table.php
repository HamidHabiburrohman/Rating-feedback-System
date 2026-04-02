<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_code')->unique();

            $table->foreignId('unit_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('student_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('overall_score', 3, 2);

            $table->text('comment')->nullable();
            $table->boolean('is_comment_censored')->default(false);

            $table->enum('status', ['active', 'edited', 'archived'])->default('active');

            $table->timestamp('last_edited_at')->nullable();
            $table->timestamp('last_replied_at')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['unit_id', 'student_id']);
            $table->index(['unit_id', 'status']);
            $table->index('overall_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};