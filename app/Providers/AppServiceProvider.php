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
        // Register repository bindings
        $this->app->bind(
            \App\Repositories\Contracts\ResultRepositoryInterface::class,
            \App\Repositories\ResultRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\SemesterRepositoryInterface::class,
            \App\Repositories\SemesterRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS in production
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
