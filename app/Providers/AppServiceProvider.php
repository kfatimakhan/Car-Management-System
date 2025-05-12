<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Only register Pest in non-production environments
        if ($this->app->environment('local', 'testing', 'development')) {
            if (class_exists('\Pest\Laravel\PestServiceProvider')) {
                $this->app->register('\Pest\Laravel\PestServiceProvider');
            }
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
