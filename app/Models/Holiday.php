<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A single non-working national holiday. The five fixed-date holidays are produced
 * automatically by ReportsIndex::getNationalHolidays(); this table holds the
 * variable-date ones (Idul Fitri, Imlek, Nyepi, …) and any custom company holidays,
 * managed by admins from the Settings → Hari Libur tab.
 */
class Holiday extends Model
{
    protected $fillable = ['date', 'name', 'source'];

    protected $casts = [
        'date' => 'date',
    ];
}
