<?php

namespace App\Services;

use App\Models\GoogleDriveToken;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class GoogleDriveService
{
    protected $client;
    protected $service;
    protected $rootFolderId;
    protected $authType;
    protected $userId;

    public function __construct($userId = null)
    {
        if (!config('google-drive.enabled')) {
            throw new Exception('Google Drive integration is not enabled');
        }

        $this->authType = config('google-drive.auth_type', 'service_account');
        // Use shared token (user_id = null) for OAuth, unless specific user is provided
        $this->userId = $this->authType === 'oauth' ? null : ($userId ?? auth()->id());

        $this->initializeClient();
        $this->rootFolderId = config('google-drive.root_folder_id');
    }

    /**
     * Initialize Google Client
     */
    protected function initializeClient()
    {
        $this->client = new \Google_Client();
        $this->client->setApplicationName('STAI AL-FATIH SIAKAD');
        $this->client->addScope(\Google_Service_Drive::DRIVE_FILE);

        if ($this->authType === 'oauth') {
            $this->initializeOAuthClient();
        } else {
            $this->initializeServiceAccountClient();
        }

        $this->service = new \Google_Service_Drive($this->client);
    }

    /**
     * Initialize OAuth Client
     */
    protected function initializeOAuthClient()
    {
        $credentialsPath = base_path(config('google-drive.oauth_credentials_path'));

        if (!file_exists($credentialsPath)) {
            throw new Exception("OAuth credentials file not found at: {$credentialsPath}");
        }

        $this->client->setAuthConfig($credentialsPath);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');

        // Load shared token from database (user_id is null for shared token)
        $tokenRecord = GoogleDriveToken::whereNull('user_id')->first();

        if (!$tokenRecord) {
            throw new Exception('Google Drive has not been connected. Admin must connect first via /oauth/google/connect');
        }

        // Check if token needs refresh
        if ($tokenRecord->needsRefresh() && $tokenRecord->refresh_token) {
            $this->refreshToken($tokenRecord);
        }

        $this->client->setAccessToken([
            'access_token' => $tokenRecord->access_token,
            'refresh_token' => $tokenRecord->refresh_token,
            'expires_in' => $tokenRecord->expires_in,
            'token_type' => $tokenRecord->token_type,
        ]);
    }

    /**
     * Initialize Service Account Client
     */
    protected function initializeServiceAccountClient()
    {
        $credentialsPath = base_path(config('google-drive.service_account_path'));

        if (!file_exists($credentialsPath)) {
            throw new Exception("Service account credentials file not found at: {$credentialsPath}");
        }

        $this->client->setAuthConfig($credentialsPath);
    }

    /**
     * Refresh OAuth token
     */
    protected function refreshToken(GoogleDriveToken $tokenRecord)
    {
        try {
            $this->client->fetchAccessTokenWithRefreshToken($tokenRecord->refresh_token);
            $newToken = $this->client->getAccessToken();

            $tokenRecord->update([
                'access_token' => $newToken['access_token'],
                'expires_in' => $newToken['expires_in'] ?? null,
                'expires_at' => isset($newToken['expires_in'])
                    ? Carbon::now()->addSeconds($newToken['expires_in'])
                    : null,
            ]);

            Log::info('Google Drive token refreshed for user: ' . $this->userId);
        } catch (Exception $e) {
            Log::error('Failed to refresh Google Drive token: ' . $e->getMessage());
            throw new Exception('Failed to refresh Google Drive token. Please reconnect.');
        }
    }

    /**
     * Check if Google Drive is connected (shared token)
     */
    public static function isConnected(): bool
    {
        if (config('google-drive.auth_type') !== 'oauth') {
            return true; // Service account always connected
        }

        // Check for shared token (user_id is null)
        return GoogleDriveToken::whereNull('user_id')->exists();
    }

    /**
     * Create folder if not exists
     *
     * @param string $folderName
     * @param string|null $parentId
     * @return string Folder ID
     */
    public function createFolder(string $folderName, ?string $parentId = null): string
    {
        try {
            // Check if folder already exists
            $existingFolder = $this->findFolder($folderName, $parentId);
            if ($existingFolder) {
                return $existingFolder;
            }

            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name' => $folderName,
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$parentId ?? $this->rootFolderId]
            ]);

            $folder = $this->service->files->create($fileMetadata, [
                'fields' => 'id'
            ]);

            Log::info("Google Drive: Created folder '{$folderName}' with ID: {$folder->id}");

            return $folder->id;
        } catch (Exception $e) {
            Log::error("Google Drive: Failed to create folder '{$folderName}': " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Find folder by name and parent
     *
     * @param string $folderName
     * @param string|null $parentId
     * @return string|null Folder ID or null
     */
    public function findFolder(string $folderName, ?string $parentId = null): ?string
    {
        try {
            $query = "mimeType='application/vnd.google-apps.folder' and name='{$folderName}' and trashed=false";

            if ($parentId) {
                $query .= " and '{$parentId}' in parents";
            } else {
                $query .= " and '{$this->rootFolderId}' in parents";
            }

            $response = $this->service->files->listFiles([
                'q' => $query,
                'fields' => 'files(id)',
                'pageSize' => 1
            ]);

            $files = $response->getFiles();

            return !empty($files) ? $files[0]->id : null;
        } catch (Exception $e) {
            Log::error("Google Drive: Failed to find folder '{$folderName}': " . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload file to Google Drive
     *
     * @param string $filePath Local file path
     * @param string $fileName File name in Drive
     * @param string $folderId Parent folder ID
     * @param string|null $mimeType
     * @return array ['id' => file_id, 'webViewLink' => link]
     */
    public function uploadFile(string $filePath, string $fileName, string $folderId, ?string $mimeType = null): array
    {
        try {
            // Validate file path
            if (empty($filePath)) {
                throw new Exception("File path is empty");
            }

            if (!file_exists($filePath)) {
                throw new Exception("File not found: {$filePath}");
            }

            $fileSize = filesize($filePath);
            if ($fileSize === false || $fileSize === 0) {
                throw new Exception("File is empty or unreadable: {$filePath}");
            }

            Log::info("Google Drive: Uploading file '{$fileName}' ({$fileSize} bytes) to folder {$folderId}");

            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name' => $fileName,
                'parents' => [$folderId]
            ]);

            // Read file content
            $content = file_get_contents($filePath);
            if ($content === false) {
                throw new Exception("Failed to read file content: {$filePath}");
            }

            $mimeType = $mimeType ?? $this->detectMimeType($filePath);
            Log::info("Google Drive: MIME type detected: {$mimeType}");

            // Upload to Google Drive
            $file = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink, webContentLink',
                'supportsAllDrives' => true
            ]);

            Log::info("Google Drive: ✅ Successfully uploaded '{$fileName}' with ID: {$file->id}");

            return [
                'id' => $file->id,
                'webViewLink' => $file->webViewLink,
                'webContentLink' => $file->webContentLink ?? null,
            ];
        } catch (Exception $e) {
            Log::error("Google Drive: ❌ Failed to upload '{$fileName}': " . $e->getMessage());
            Log::error("Google Drive: File path was: {$filePath}");
            Log::error("Google Drive: Folder ID was: {$folderId}");
            throw $e;
        }
    }

    /**
     * Upload pembayaran file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $identifier NIM or ID
     * @param string $category Payment category
     * @return array
     */
    public function uploadPembayaran($file, string $identifier, string $category): array
    {
        // Get or create Pembayaran folder
        $pembayaranFolder = $this->findFolder(config('google-drive.folders.pembayaran'))
            ?? $this->createFolder(config('google-drive.folders.pembayaran'));

        // Generate file name
        $timestamp = now()->format(config('google-drive.naming.date_format'));
        $extension = $file->getClientOriginalExtension();
        $separator = config('google-drive.naming.separator');
        $fileName = "{$identifier}{$separator}{$category}{$separator}{$timestamp}.{$extension}";

        // Upload file
        $tempPath = $file->getRealPath();

        return $this->uploadFile($tempPath, $fileName, $pembayaranFolder, $file->getMimeType());
    }

    /**
     * Upload dokumen mahasiswa
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $nim
     * @param string $category
     * @return array
     */
    public function uploadDokumenMahasiswa($file, string $nim, string $category): array
    {
        // Get or create Dokumen-Mahasiswa folder
        $dokumenFolder = $this->findFolder(config('google-drive.folders.dokumen_mahasiswa'))
            ?? $this->createFolder(config('google-drive.folders.dokumen_mahasiswa'));

        // Get or create NIM folder inside Dokumen-Mahasiswa
        $nimFolder = $this->findFolder($nim, $dokumenFolder)
            ?? $this->createFolder($nim, $dokumenFolder);

        // Generate file name
        $timestamp = now()->format(config('google-drive.naming.date_format'));
        $extension = $file->getClientOriginalExtension();
        $separator = config('google-drive.naming.separator');
        $fileName = "{$category}{$separator}{$timestamp}.{$extension}";

        // Upload file
        $tempPath = $file->getRealPath();

        return $this->uploadFile($tempPath, $fileName, $nimFolder, $file->getMimeType());
    }

    /**
     * Upload dokumen SPMB
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $pendaftarId
     * @param string $category
     * @return array
     */
    public function uploadDokumenSPMB($file, string $pendaftarId, string $category): array
    {
        // Get or create SPMB folder
        $spmbFolder = $this->findFolder(config('google-drive.folders.spmb'))
            ?? $this->createFolder(config('google-drive.folders.spmb'));

        // Get or create Pendaftar folder inside SPMB
        $pendaftarFolder = $this->findFolder($pendaftarId, $spmbFolder)
            ?? $this->createFolder($pendaftarId, $spmbFolder);

        // Generate file name
        $timestamp = now()->format(config('google-drive.naming.date_format'));
        $extension = $file->getClientOriginalExtension();
        $separator = config('google-drive.naming.separator');
        $fileName = "{$category}{$separator}{$timestamp}.{$extension}";

        // Upload file
        $tempPath = $file->getRealPath();

        return $this->uploadFile($tempPath, $fileName, $pendaftarFolder, $file->getMimeType());
    }

    /**
     * Delete file from Google Drive
     *
     * @param string $fileId
     * @return bool
     */
    public function deleteFile(string $fileId): bool
    {
        try {
            $this->service->files->delete($fileId);
            Log::info("Google Drive: Deleted file with ID: {$fileId}");
            return true;
        } catch (Exception $e) {
            Log::error("Google Drive: Failed to delete file {$fileId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Detect MIME type of a file
     * Fallback method if fileinfo extension is not available
     *
     * @param string $filePath
     * @return string
     */
    protected function detectMimeType(string $filePath): string
    {
        // Try using mime_content_type if fileinfo extension is available
        if (function_exists('mime_content_type')) {
            try {
                $mimeType = mime_content_type($filePath);
                if ($mimeType !== false) {
                    return $mimeType;
                }
            } catch (Exception $e) {
                Log::warning("mime_content_type failed: " . $e->getMessage());
            }
        }

        // Fallback: Detect based on file extension
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeTypes = [
            // Images
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',

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

            // Archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            '7z' => 'application/x-7z-compressed',

            // Others
            'json' => 'application/json',
            'xml' => 'application/xml',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * Get file metadata
     *
     * @param string $fileId
     * @return \Google_Service_Drive_DriveFile|null
     */
    public function getFile(string $fileId)
    {
        try {
            return $this->service->files->get($fileId, [
                'fields' => 'id, name, mimeType, size, createdTime, webViewLink, webContentLink'
            ]);
        } catch (Exception $e) {
            Log::error("Google Drive: Failed to get file {$fileId}: " . $e->getMessage());
            return null;
        }
    }
}
