<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->cascadeOnDelete();
            $table->enum('old_status', ['new', 'assigned', 'in_progress', 'replied', 'resolved', 'rejected', 'pending_preview']);
            $table->enum('new_status', ['new', 'assigned', 'in_progress', 'replied', 'resolved', 'rejected', 'pending_preview']);
            $table->foreignId('changed_by_admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('changed_by_employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->timestamps();
            $table->index('report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_status_histories');
    }
};