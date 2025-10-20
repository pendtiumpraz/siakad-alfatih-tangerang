<?php

// Temporary debug route - ADD to routes/web.php for testing
// Remove after CSS is working!

use Illuminate\Support\Facades\Route;

Route::get('/debug-vite', function () {
    $manifestPath = public_path('build/manifest.json');

    return [
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'app_url' => config('app.url'),
        'asset_url' => config('app.asset_url'),
        'manifest_exists' => file_exists($manifestPath),
        'manifest_path' => $manifestPath,
        'manifest_content' => file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : 'NOT FOUND',
        'public_path' => public_path(),
        'build_path' => public_path('build'),
        'vite_test' => \Illuminate\Support\Facades\Vite::asset('resources/css/app.css'),
    ];
})->name('debug.vite');
