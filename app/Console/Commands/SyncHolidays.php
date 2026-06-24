<?php

namespace App\Console\Commands;

use App\Services\HolidayService;
use Illuminate\Console\Command;

class SyncHolidays extends Command
{
    protected $signature = 'holidays:sync {--years= : Comma-separated years (defaults to this year + next 2)}';

    protected $description = 'Sync Indonesian national holidays from the public calendar feed';

    public function handle(): int
    {
        if ($this->option('years')) {
            $years = array_map('intval', array_filter(explode(',', $this->option('years'))));
        } else {
            $current = (int) now()->year;
            $years = [$current, $current + 1, $current + 2];
        }

        $this->info('Menyinkronkan hari libur untuk tahun: ' . implode(', ', $years));

        try {
            $result = HolidayService::sync($years);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $this->info("Selesai. {$result['added']} hari libur tersimpan (dari {$result['fetched']} event di feed).");

        return self::SUCCESS;
    }
}
