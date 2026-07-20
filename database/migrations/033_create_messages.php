<?php
declare(strict_types=1);
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('reply_to_id')->nullable();
            $table->morphs('sender');
            $table->text('body')->nullable();
            $table->string('type')->default('text');
            $table->timestamp('edited_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['conversation_id', 'created_at']);
            $table->foreign('reply_to_id')->references('id')->on('messages')->nullOnDelete();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};