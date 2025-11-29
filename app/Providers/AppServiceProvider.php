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
        // Override local filesystem driver with fallback MIME detector
        $this->app->resolving(\Illuminate\Filesystem\FilesystemManager::class, function ($manager) {
            $manager->extend('local', function ($app, $config) {
                return $this->createFallbackLocalDriver($config);
            });

            $manager->extend('public', function ($app, $config) {
                return $this->createFallbackLocalDriver($config);
            });
        });
    }

    /**
     * Create a fallback local filesystem driver
     *
     * @param array $config
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected function createFallbackLocalDriver(array $config)
    {
        $adapter = new \App\Support\FallbackLocalFilesystemAdapter(
            $config['root'] ?? storage_path('app'),
            null, // visibility converter - will use default
            $config['lock'] ?? LOCK_EX,
            \League\Flysystem\Local\LocalFilesystemAdapter::DISALLOW_LINKS
        );

        $driver = new \League\Flysystem\Filesystem($adapter, $config);

        return new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter, $config);
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

        // Register observers
        \App\Models\Semester::observe(\App\Observers\SemesterObserver::class);
    }
}
