<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('name');
            $table->timestamps();
        });

        // Seed the variable-date 2026 national holidays that used to be hardcoded in
        // ReportsIndex::getNationalHolidays(). The five fixed-date holidays (New Year,
        // Labour Day, Pancasila, Independence, Christmas) are added automatically every
        // year by the code, so they are intentionally NOT stored here.
        $now = now();
        $rows = [
            '2026-01-18' => "Isra Mi'raj Nabi Muhammad SAW",
            '2026-02-17' => 'Tahun Baru Imlek',
            '2026-03-19' => 'Hari Suci Nyepi',
            '2026-03-20' => 'Hari Raya Idul Fitri 1447 H',
            '2026-03-21' => 'Hari Raya Idul Fitri 1447 H',
            '2026-04-03' => 'Wafat Yesus Kristus',
            '2026-04-05' => 'Hari Paskah',
            '2026-05-14' => 'Hari Raya Waisak',
            '2026-05-21' => 'Kenaikan Yesus Kristus',
            '2026-05-27' => 'Hari Raya Idul Adha 1447 H',
            '2026-06-17' => 'Tahun Baru Islam 1448 H',
            '2026-08-26' => 'Maulid Nabi Muhammad SAW',
        ];

        $insert = [];
        foreach ($rows as $date => $name) {
            $insert[] = ['date' => $date, 'name' => $name, 'created_at' => $now, 'updated_at' => $now];
        }
        DB::table('holidays')->insert($insert);
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
