<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rating_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rating_category_id')->constrained()->restrictOnDelete();
            $table->decimal('score', 2, 1);
            $table->timestamps();
            $table->unique(['rating_id', 'rating_category_id']);
            $table->index(['rating_category_id', 'score']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_scores');
    }
};