<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('permission_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['ijin_datang_terlambat', 'ijin_pulang_awal', 'ijin_tidak_masuk', 'ijin_setengah_hari']);
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('reason');
            $table->string('attachment_path')->nullable();
            
            // Department Head Approval Flow
            $table->enum('status_dept_head', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('dept_head_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('dept_head_approved_at')->nullable();
            
            // HR Approval Flow
            $table->enum('status_hr', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('hr_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('hr_approved_at')->nullable();
            
            // Overall status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            
            $table->text('approval_notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'status']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_requests');
    }
};
