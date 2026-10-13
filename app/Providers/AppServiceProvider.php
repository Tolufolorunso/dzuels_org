<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
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
        // On production environments or remote live domains, ignore any accidental local "hot" file
        // and strictly load compiled production assets from manifest.json
        if (! $this->app->isLocal() || (request()->hasHeader('host') && ! in_array(request()->getHost(), ['localhost', '127.0.0.1', '::1']))) {
            Vite::useHotFile(storage_path('framework/cache/vite.hot'));
        }
    }
}
