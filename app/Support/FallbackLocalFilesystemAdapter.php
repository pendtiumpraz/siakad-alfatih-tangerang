<?php

namespace App\Support;

use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use League\MimeTypeDetection\MimeTypeDetector;

class FallbackLocalFilesystemAdapter extends LocalFilesystemAdapter
{
    /**
     * Create a new LocalFilesystemAdapter instance with fallback MIME detector
     *
     * @param string $location
     * @param \League\Flysystem\UnixVisibility\VisibilityConverter|null $visibility
     * @param int $writeFlags
     * @param int $linkHandling
     * @param MimeTypeDetector|null $mimeTypeDetector
     * @return void
     */
    public function __construct(
        string $location,
        ?\League\Flysystem\UnixVisibility\VisibilityConverter $visibility = null,
        int $writeFlags = LOCK_EX,
        int $linkHandling = self::DISALLOW_LINKS,
        ?MimeTypeDetector $mimeTypeDetector = null
    ) {
        // Use our fallback MIME type detector
        $mimeTypeDetector = $mimeTypeDetector ?? new FallbackMimeTypeDetector();

        // Call parent constructor with all parameters
        parent::__construct(
            $location,
            $visibility ?? PortableVisibilityConverter::fromArray([
                'file' => [
                    'public' => 0644,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0700,
                ],
            ]),
            $writeFlags,
            $linkHandling,
            $mimeTypeDetector
        );
    }
}
