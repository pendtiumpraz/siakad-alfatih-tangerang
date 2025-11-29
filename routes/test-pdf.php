<?php

use Illuminate\Support\Facades\Route;

// TEST PDF GENERATION - Access at: http://localhost:8000/test-pdf-simple
Route::get('/test-pdf-simple', function () {
    try {
        // Test 1: Simple HTML without images
        $html = '<h1>Test PDF Generation</h1><p>This is a test without images.</p>';
        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('test-simple.pdf');
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

// TEST PDF with base64 image
Route::get('/test-pdf-image', function () {
    try {
        $logoPath = public_path('images/logo-alfatih.png');
        $logoBase64 = '';
        
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
            
            $html = '<h1>Test PDF with Image</h1>';
            $html .= '<img src="' . $logoBase64 . '" style="width: 100px; height: 100px;" />';
            $html .= '<p>Logo should appear above.</p>';
            
            $pdf = \PDF::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream('test-image.pdf');
        } else {
            return response()->json(['error' => 'Logo file not found: ' . $logoPath]);
        }
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

// TEST GD Extension
Route::get('/test-gd-check', function () {
    $gdInfo = [];
    
    if (extension_loaded('gd')) {
        $gdInfo['status'] = 'ENABLED';
        $gdInfo['gd_info'] = gd_info();
        $gdInfo['functions'] = [
            'imagecreatefrompng' => function_exists('imagecreatefrompng'),
            'imagecreatefromjpeg' => function_exists('imagecreatefromjpeg'),
            'imagecreatefromgif' => function_exists('imagecreatefromgif'),
        ];
    } else {
        $gdInfo['status'] = 'NOT ENABLED';
    }
    
    return response()->json([
        'php_version' => phpversion(),
        'php_sapi' => php_sapi_name(),
        'loaded_extensions' => get_loaded_extensions(),
        'gd_info' => $gdInfo,
    ]);
});
