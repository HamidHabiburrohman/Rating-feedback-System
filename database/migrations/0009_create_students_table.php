<?php
// database/migrations/0009_create_students_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_identifier')->unique();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('major', 100)->nullable();
            $table->string('class_year', 10)->nullable();
            $table->text('bio')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();

            $table->index('student_identifier');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};