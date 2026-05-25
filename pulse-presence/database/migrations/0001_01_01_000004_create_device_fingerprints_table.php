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
        Schema::create('device_fingerprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('device_hash')->unique();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('platform')->nullable();
            $table->string('timezone')->nullable();
            $table->string('language')->nullable();
            $table->string('screen_resolution')->nullable();
            $table->integer('hardware_concurrency')->nullable();
            $table->text('gpu_info')->nullable();
            $table->boolean('trusted')->default(false);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_fingerprints');
    }
};
