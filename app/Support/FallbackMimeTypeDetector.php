<?php

namespace App\Support;

use League\MimeTypeDetection\MimeTypeDetector;

class FallbackMimeTypeDetector implements MimeTypeDetector
{
    /**
     * Detect MIME type from file path
     *
     * @param string $path
     * @param string|resource|null $contents
     * @return string|null
     */
    public function detectMimeType(string $path, $contents): ?string
    {
        // Try using mime_content_type if available
        if (function_exists('mime_content_type') && file_exists($path)) {
            try {
                $mimeType = mime_content_type($path);
                if ($mimeType !== false) {
                    return $mimeType;
                }
            } catch (\Exception $e) {
                // Continue to fallback
            }
        }

        // Fallback: Detect based on file extension
        return $this->detectFromExtension($path);
    }

    /**
     * Detect MIME type from file path only
     *
     * @param string $path
     * @return string|null
     */
    public function detectMimeTypeFromPath(string $path): ?string
    {
        return $this->detectFromExtension($path);
    }

    /**
     * Detect MIME type from file buffer
     *
     * @param string $contents
     * @return string|null
     */
    public function detectMimeTypeFromBuffer(string $contents): ?string
    {
        // Try to detect from buffer content
        // This is a simplified implementation
        return 'application/octet-stream';
    }

    /**
     * Detect MIME type from file (required by interface)
     *
     * @param string $path
     * @return string|null
     */
    public function detectMimeTypeFromFile(string $path): ?string
    {
        // Try using mime_content_type if available
        if (function_exists('mime_content_type') && file_exists($path)) {
            try {
                $mimeType = mime_content_type($path);
                if ($mimeType !== false) {
                    return $mimeType;
                }
            } catch (\Exception $e) {
                // Continue to fallback
            }
        }

        // Fallback: Detect based on file extension
        return $this->detectFromExtension($path);
    }

    /**
     * Detect MIME type from file extension
     *
     * @param string $path
     * @return string|null
     */
    protected function detectFromExtension(string $path): ?string
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        $mimeTypes = [
            // Images
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'ico' => 'image/x-icon',

            // Documents
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'txt' => 'text/plain',
            'csv' => 'text/csv',
            'rtf' => 'application/rtf',
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',

            // Archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            '7z' => 'application/x-7z-compressed',
            'tar' => 'application/x-tar',
            'gz' => 'application/gzip',

            // Web
            'html' => 'text/html',
            'htm' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',

            // Others
            'mp3' => 'audio/mpeg',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'mov' => 'video/quicktime',
            'wmv' => 'video/x-ms-wmv',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
