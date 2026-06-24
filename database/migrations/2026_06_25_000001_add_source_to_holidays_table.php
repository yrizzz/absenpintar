<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            // 'auto'   = pulled from the national holiday calendar feed
            // 'manual' = added/overridden by an admin in Settings → Hari Libur
            $table->string('source', 16)->default('manual')->after('name');
        });

        // The rows seeded from the previously-hardcoded list are calendar data, not
        // user input, so tag them as auto-managed.
        DB::table('holidays')->update(['source' => 'auto']);
    }

    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};
