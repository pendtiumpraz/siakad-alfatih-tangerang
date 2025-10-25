<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use League\MimeTypeDetection\MimeTypeDetector;

class MimeTypeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Override MIME type detector with fallback implementation
        $this->app->singleton(MimeTypeDetector::class, function ($app) {
            return new \App\Support\FallbackMimeTypeDetector();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
