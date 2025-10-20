<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        // Force HTTPS in production to fix mixed content issues
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Register view composers for notifications
        view()->composer(
            ['layouts.admin', 'layouts.dosen', 'layouts.operator', 'layouts.mahasiswa'],
            \App\View\Composers\NotificationComposer::class
        );
    }
}
