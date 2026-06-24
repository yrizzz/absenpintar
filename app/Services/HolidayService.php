<?php

namespace App\Services;

use App\Models\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Syncs Indonesian national holidays from the public Google Calendar ICS feed
 * (no API key required). The feed is the most complete free source — it includes
 * the lunar/shifting holidays (Idul Fitri, Idul Adha, Imlek, Nyepi, Waisak, …) and
 * the government "cuti bersama" joint holidays that key-only APIs like Nager.Date omit.
 *
 * Synced rows are tagged source='auto'; admin-entered rows (source='manual') are never
 * overwritten and always win on a date collision.
 */
class HolidayService
{
    public const FEED_URL = 'https://calendar.google.com/calendar/ical/en.indonesian%23holiday%40group.v.calendar.google.com/public/basic.ics';

    /**
     * Feed entries that are informational markers, not actual non-working holidays.
     */
    private const SKIP = ['ramadan start', 'new year\'s eve'];

    /**
     * Sync holidays for the given years. Returns ['added' => int, 'fetched' => int].
     * Existing source='auto' rows for those years are refreshed; manual rows are left intact.
     */
    public static function sync(array $years): array
    {
        $events = self::fetchEvents();           // ['Y-m-d' => 'Indonesian name', ...]
        $fetched = count($events);

        $targetYears = array_map('intval', $years);

        // Drop stale auto rows for the target years so removed/moved dates don't linger.
        Holiday::where('source', 'auto')
            ->where(function ($q) use ($targetYears) {
                foreach ($targetYears as $y) {
                    $q->orWhereYear('date', $y);
                }
            })
            ->delete();

        // Dates already claimed by a manual entry must not be overwritten.
        $manualDates = Holiday::where('source', 'manual')
            ->pluck('date')
            ->map(fn ($d) => Carbon::parse($d)->toDateString())
            ->flip();

        $added = 0;
        foreach ($events as $date => $name) {
            $year = (int) substr($date, 0, 4);
            if (! in_array($year, $targetYears, true)) {
                continue;
            }
            if ($manualDates->has($date)) {
                continue;
            }
            Holiday::create(['date' => $date, 'name' => $name, 'source' => 'auto']);
            $added++;
        }

        foreach ($targetYears as $y) {
            Cache::forget("holidays.{$y}");
        }

        return ['added' => $added, 'fetched' => $fetched];
    }

    /**
     * Fetch and parse the ICS feed into ['Y-m-d' => normalized Indonesian name].
     * Later events on the same date win (the feed lists specific holidays after
     * joint-holiday markers), which keeps the primary holiday name.
     */
    public static function fetchEvents(): array
    {
        $response = Http::timeout(20)->retry(2, 500)->get(self::FEED_URL);
        if (! $response->successful()) {
            Log::warning('Holiday feed fetch failed', ['status' => $response->status()]);
            throw new \RuntimeException('Gagal mengambil data kalender libur nasional (HTTP ' . $response->status() . ').');
        }

        $body = $response->body();
        $events = [];

        if (! preg_match_all('/BEGIN:VEVENT(.*?)END:VEVENT/s', $body, $blocks)) {
            return $events;
        }

        foreach ($blocks[1] as $block) {
            if (! preg_match('/DTSTART;VALUE=DATE:(\d{8})/', $block, $d)) {
                continue;
            }
            if (! preg_match('/SUMMARY:(.*)/', $block, $s)) {
                continue;
            }
            $raw = trim(str_replace('\\,', ',', $s[1]));
            if (self::shouldSkip($raw)) {
                continue;
            }
            $date = substr($d[1], 0, 4) . '-' . substr($d[1], 4, 2) . '-' . substr($d[1], 6, 2);
            $events[$date] = self::normalizeName($raw);
        }

        ksort($events);

        return $events;
    }

    private static function shouldSkip(string $name): bool
    {
        $lower = mb_strtolower($name);
        foreach (self::SKIP as $needle) {
            if (str_contains($lower, $needle)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Map the feed's English summaries to Indonesian names. "Joint Holiday" → "Cuti Bersama".
     */
    private static function normalizeName(string $name): string
    {
        $name = trim(preg_replace('/\s*\(tentative\)\s*/i', '', $name));
        $lower = mb_strtolower($name);
        $isJoint = str_contains($lower, 'joint holiday') || str_contains($lower, 'cuti bersama');

        // Ordered keyword → Indonesian base-name rules (first match wins).
        $map = [
            'idul fitri'       => 'Idul Fitri',
            'idul adha'        => 'Idul Adha',
            'eid al-adha'      => 'Idul Adha',
            'eid al-fitr'      => 'Idul Fitri',
            'chinese new year' => 'Tahun Baru Imlek',
            'nyepi'            => 'Hari Suci Nyepi',
            'silence'          => 'Hari Suci Nyepi',
            'waisak'           => 'Hari Raya Waisak',
            'vesak'            => 'Hari Raya Waisak',
            'good friday'      => 'Wafat Isa Almasih',
            'easter'           => 'Paskah',
            'ascension of the prophet' => 'Isra Mikraj Nabi Muhammad SAW',
            'isra'             => 'Isra Mikraj Nabi Muhammad SAW',
            'ascension'        => 'Kenaikan Isa Almasih',
            'labor day'        => 'Hari Buruh Internasional',
            'labour day'       => 'Hari Buruh Internasional',
            'pancasila'        => 'Hari Lahir Pancasila',
            'independence'     => 'Hari Kemerdekaan RI',
            'islamic new year' => 'Tahun Baru Islam',
            'muharram'         => 'Tahun Baru Islam',
            'maulid'           => 'Maulid Nabi Muhammad SAW',
            'mawlid'           => 'Maulid Nabi Muhammad SAW',
            'birthday of the prophet' => 'Maulid Nabi Muhammad SAW',
            'christmas'        => 'Hari Raya Natal',
            'new year'         => 'Tahun Baru Masehi',
        ];

        foreach ($map as $needle => $base) {
            if (str_contains($lower, $needle)) {
                return $isJoint ? "Cuti Bersama {$base}" : $base;
            }
        }

        return $name; // unknown entry: keep the feed's own label
    }
}
