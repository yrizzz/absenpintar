<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

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
        // -------------------------------------------------------
        // Livewire 4 — Obfuscated routes (WordPress mockup)
        // Nginx sudah dikonfigurasi untuk:
        //   location = /wp-admin.js  → serve via Laravel
        //   location /wp-admin/      → serve via Laravel
        // -------------------------------------------------------
        Livewire::setScriptRoute(function ($handle) {
            return Route::get('/wp-admin.js', $handle);
        });

        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/wp-admin/update', $handle)
                ->middleware('web');
        });

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
