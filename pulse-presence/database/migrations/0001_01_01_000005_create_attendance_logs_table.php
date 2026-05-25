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
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('type', ['checkin', 'checkout', 'break_start', 'break_end', 'overtime_start', 'overtime_end']);
            $table->timestamp('timestamp');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->float('accuracy')->nullable();
            $table->string('ip_address', 45);
            $table->foreignId('device_fingerprint_id')->nullable()->constrained()->nullOnDelete();
            $table->string('selfie_path')->nullable();
            $table->string('selfie_compressed_path')->nullable();
            $table->string('selfie_watermarked_path')->nullable();
            $table->integer('risk_score')->default(0);
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->enum('status', ['pending', 'approved', 'rejected', 'flagged'])->default('approved');
            $table->enum('work_mode', ['wfo', 'wfh', 'hybrid', 'mobile'])->default('wfo');
            $table->boolean('is_late')->default(false);
            $table->boolean('is_early_leave')->default(false);
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['user_id', 'timestamp']);
            $table->index(['branch_id', 'timestamp']);
            $table->index('risk_level');
            $table->index('status');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
