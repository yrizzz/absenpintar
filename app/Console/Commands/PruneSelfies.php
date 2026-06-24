<?php

namespace App\Console\Commands;

use App\Models\AttendanceLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Deletes selfie image files for attendance logs older than the configured retention
 * window so they don't grow the disk without bound. The attendance records themselves
 * are kept (only the image files + their path columns are cleared).
 */
class PruneSelfies extends Command
{
    protected $signature = 'attendance:prune-selfies {--days= : Override retention days}';

    protected $description = 'Delete selfie images older than the configured retention period';

    public function handle(): int
    {
        $days = (int) ($this->option('days') ?: config('attendance.selfie_retention_days', 90));
        if ($days < 1) {
            $this->error('Retention days must be >= 1.');

            return self::FAILURE;
        }

        $cutoff = now()->subDays($days);
        $disk = Storage::disk('public');
        $columns = ['selfie_path', 'selfie_compressed_path', 'selfie_watermarked_path'];

        $files = 0;
        $logs = 0;

        AttendanceLog::where('timestamp', '<', $cutoff)
            ->where(function ($q) use ($columns) {
                foreach ($columns as $c) {
                    $q->orWhereNotNull($c);
                }
            })
            ->chunkById(200, function ($chunk) use ($disk, $columns, &$files, &$logs) {
                foreach ($chunk as $log) {
                    $cleared = false;
                    foreach ($columns as $c) {
                        if ($log->{$c}) {
                            if ($disk->exists($log->{$c})) {
                                $disk->delete($log->{$c});
                                $files++;
                            }
                            $log->{$c} = null;
                            $cleared = true;
                        }
                    }
                    if ($cleared) {
                        $log->saveQuietly();
                        $logs++;
                    }
                }
            });

        $this->info("Selesai. {$files} berkas selfie dihapus dari {$logs} catatan (lebih lama dari {$days} hari).");

        return self::SUCCESS;
    }
}
