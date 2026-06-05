<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Mirror persisted settings into the cache once per cache lifetime so that
        // cache()->get('settings.*') readers are DB-backed and survive `cache:clear`.
        // Wrapped defensively: the cache/settings tables may not exist yet during
        // a fresh `php artisan migrate`.
        try {
            if (!Cache::has('settings._hydrated')) {
                Setting::hydrateCache();
            }
        } catch (\Throwable $e) {
            // Ignore during install/migration; settings fall back to coded defaults.
        }
    }
}
