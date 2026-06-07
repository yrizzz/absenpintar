<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

/**
 * Key/value application settings, persisted to the database and mirrored into the
 * cache under "settings.{key}" keys so existing cache()->get('settings.x') readers
 * keep working and survive a `cache:clear`.
 */
class Setting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    /**
     * Persist a single setting to the database and refresh its cache mirror.
     * Values are JSON-encoded in storage to preserve scalar types exactly.
     */
    public static function put(string $key, mixed $value): void
    {
        static::query()->updateOrCreate(
            ['key' => $key],
            ['value' => json_encode($value)]
        );

        Cache::forever("settings.{$key}", $value);
    }

    /**
     * Load every persisted setting into the cache. Safe to call before the table
     * exists (no-op) so it can run during application boot.
     */
    public static function hydrateCache(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        foreach (static::all() as $setting) {
            Cache::forever("settings.{$setting->key}", json_decode($setting->value, true));
        }

        Cache::forever('settings._hydrated', true);
    }
}
