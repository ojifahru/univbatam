<?php

namespace App\Providers;

use Illuminate\Support\Composer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Increase memory limit to 1GB
        ini_set('memory_limit', '1024M');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // view composer logo

    }
}
