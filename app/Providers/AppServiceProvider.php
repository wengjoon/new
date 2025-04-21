<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
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
        // Force file-based cache driver to avoid database connection issues
        \Illuminate\Support\Facades\Config::set('cache.default', 'file');
        
        // Ensure the cache directory exists and is writable
        $cachePath = storage_path('framework/cache/data');
        if (!file_exists($cachePath)) {
            try {
                mkdir($cachePath, 0775, true);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to create cache directory: " . $e->getMessage());
            }
        }

        // Share analytics ID with all views
        View::composer('*', function ($view) {
            $view->with('googleAnalyticsId', config('analytics.google_id'));
        });
    }
}
