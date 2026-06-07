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
        Schema::table('device_fingerprints', function (Blueprint $table) {
            // Drop old globally unique key on device_hash
            $table->dropUnique('device_fingerprints_device_hash_unique');
            
            // Add composite unique key on user_id and device_hash
            $table->unique(['user_id', 'device_hash']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_fingerprints', function (Blueprint $table) {
            // Drop composite unique key
            $table->dropUnique('device_fingerprints_user_id_device_hash_unique');
            
            // Re-add old globally unique key
            $table->unique('device_hash');
        });
    }
};
