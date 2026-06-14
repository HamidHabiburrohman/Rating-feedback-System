<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->index();
            $table->text('value')->nullable();
            $table->string('type')->default('string');
            $table->string('group')->default('general')->index();
            $table->string('subgroup')->nullable()->index();
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->text('hint')->nullable();
            $table->json('options')->nullable();
            $table->string('validation_rules')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_visible')->default(true);
            $table->boolean('is_public')->default(false);
            $table->string('required_permission')->nullable();
            $table->timestamps();
            $table->index(['group', 'subgroup']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};