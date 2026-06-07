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
        Schema::create('suspicious_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('attendance_log_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('event_type', [
                'fake_gps_suspected',
                'vpn_usage',
                'multiple_devices',
                'impossible_travel',
                'face_mismatch',
                'failed_verification',
                'new_device',
                'timezone_mismatch',
                'low_gps_accuracy'
            ]);
            $table->integer('risk_score');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'false_positive'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'event_type']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspicious_events');
    }
};
