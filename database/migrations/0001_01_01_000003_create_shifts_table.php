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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('grace_period')->default(15); // minutes
            $table->integer('late_threshold')->default(15); // minutes
            $table->integer('overtime_threshold')->default(60); // minutes
            $table->json('working_days')->nullable(); // [1,2,3,4,5] for Mon-Fri
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Pivot table for user shifts
        Schema::create('shift_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
            
            $table->unique(['shift_id', 'user_id', 'effective_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_user');
        Schema::dropIfExists('shifts');
    }
};
