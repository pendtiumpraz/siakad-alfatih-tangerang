<?php

// Temporary debug routes - for development/debugging
// WARNING: Disable these in production or add proper authentication!

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

/**
 * View Laravel logs - for debugging Google Drive upload issues
 * Access: /debug-logs?lines=100
 */
Route::get('/debug-logs', function (\Illuminate\Http\Request $request) {
    // Security: Only allow in development or for authenticated super admin
    if (!config('app.debug') && (!auth()->check() || !auth()->user()->isSuperAdmin())) {
        abort(403, 'Access denied');
    }

    $logPath = storage_path('logs/laravel.log');
    $lines = $request->input('lines', 200); // Default 200 lines
    $filter = $request->input('filter', ''); // Optional filter keyword

    if (!file_exists($logPath)) {
        return response()->json([
            'error' => 'Log file not found',
            'path' => $logPath
        ], 404);
    }

    // Read last N lines
    $file = new \SplFileObject($logPath, 'r');
    $file->seek(PHP_INT_MAX);
    $totalLines = $file->key() + 1;

    $startLine = max(0, $totalLines - $lines);
    $file->seek($startLine);

    $logLines = [];
    while (!$file->eof()) {
        $line = $file->current();

        // Apply filter if specified
        if (empty($filter) || stripos($line, $filter) !== false) {
            $logLines[] = $line;
        }

        $file->next();
    }

    // HTML output for better readability
    $html = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Logs - Last ' . $lines . ' lines</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background: #1e1e1e;
            color: #d4d4d4;
        }
        .header {
            background: #2d2d30;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .log-container {
            background: #252526;
            padding: 20px;
            border-radius: 5px;
            overflow-x: auto;
        }
        .log-line {
            margin: 5px 0;
            padding: 5px;
            border-left: 3px solid transparent;
        }
        .log-line:hover {
            background: #2d2d30;
        }
        .error { border-left-color: #f44747; color: #f48771; }
        .warning { border-left-color: #cca700; color: #dcdcaa; }
        .info { border-left-color: #4fc1ff; color: #4ec9b0; }
        .success { border-left-color: #89d185; color: #b5cea8; }
        .filter-form {
            margin-bottom: 15px;
        }
        input, button {
            padding: 8px 12px;
            font-size: 14px;
            border-radius: 3px;
            border: 1px solid #3e3e42;
            background: #2d2d30;
            color: #d4d4d4;
        }
        button {
            background: #0e639c;
            cursor: pointer;
        }
        button:hover {
            background: #1177bb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Laravel Logs Viewer</h1>
        <p>Showing last <strong>' . $lines . '</strong> lines from: <code>' . $logPath . '</code></p>
        <p>Total lines in file: <strong>' . number_format($totalLines) . '</strong></p>

        <form class="filter-form" method="GET">
            <input type="number" name="lines" value="' . $lines . '" placeholder="Lines" style="width: 100px;">
            <input type="text" name="filter" value="' . htmlspecialchars($filter) . '" placeholder="Filter keyword (e.g., Google Drive)">
            <button type="submit">🔍 Filter</button>
            <a href="/debug-logs" style="color: #4fc1ff; text-decoration: none; margin-left: 10px;">Clear</a>
        </form>
    </div>

    <div class="log-container">';

    foreach ($logLines as $line) {
        $class = '';
        if (stripos($line, 'ERROR') !== false || stripos($line, 'Failed') !== false || stripos($line, '❌') !== false) {
            $class = 'error';
        } elseif (stripos($line, 'WARNING') !== false) {
            $class = 'warning';
        } elseif (stripos($line, 'INFO') !== false || stripos($line, 'Starting') !== false) {
            $class = 'info';
        } elseif (stripos($line, 'SUCCESS') !== false || stripos($line, '✅') !== false) {
            $class = 'success';
        }

        $html .= '<div class="log-line ' . $class . '">' . htmlspecialchars($line) . '</div>';
    }

    $html .= '</div>
</body>
</html>';

    return response($html)->header('Content-Type', 'text/html');
})->name('debug.logs');
