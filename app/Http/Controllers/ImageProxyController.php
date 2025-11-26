<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ImageProxyController extends Controller
{
    /**
     * Proxy Google Drive image to bypass CORS
     */
    public function proxy(Request $request)
    {
        $fileId = $request->query('id');
        
        if (!$fileId) {
            return response('Missing file ID', 400);
        }

        // Cache key based on file ID
        $cacheKey = "gdrive_image_{$fileId}";
        
        // Check cache first (cache for 1 hour)
        $imageData = Cache::remember($cacheKey, 3600, function() use ($fileId) {
            try {
                // Download from Google Drive
                $url = "https://drive.usercontent.google.com/download?id={$fileId}&export=download&authuser=0";
                
                $context = stream_context_create([
                    'http' => [
                        'method' => 'GET',
                        'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n"
                    ]
                ]);
                
                $imageContent = @file_get_contents($url, false, $context);
                
                if (!$imageContent) {
                    \Log::warning("Failed to download image from Google Drive: {$fileId}");
                    return null;
                }
                
                // Detect MIME type
                $mimeType = 'image/jpeg'; // default
                if (class_exists('finfo')) {
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mimeType = $finfo->buffer($imageContent);
                }
                
                return [
                    'content' => $imageContent,
                    'mime' => $mimeType
                ];
            } catch (\Exception $e) {
                \Log::error("Image proxy error: " . $e->getMessage());
                return null;
            }
        });
        
        if (!$imageData) {
            return response('Image not found', 404);
        }
        
        // Return image with proper headers
        return response($imageData['content'])
            ->header('Content-Type', $imageData['mime'])
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('Access-Control-Allow-Origin', '*');
    }
}
