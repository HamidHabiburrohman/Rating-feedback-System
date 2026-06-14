<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rating_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rating_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->text('reply');
            $table->boolean('is_public')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('rating_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rating_replies');
    }
};