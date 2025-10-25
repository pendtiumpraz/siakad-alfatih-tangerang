<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;

class GoogleDriveService
{
    protected $client;
    protected $service;
    protected $rootFolderId;

    public function __construct()
    {
        if (!config('google-drive.enabled')) {
            throw new Exception('Google Drive integration is not enabled');
        }

        $this->initializeClient();
        $this->rootFolderId = config('google-drive.root_folder_id');
    }

    /**
     * Initialize Google Client
     */
    protected function initializeClient()
    {
        $credentialsPath = base_path(config('google-drive.credentials_path'));

        if (!file_exists($credentialsPath)) {
            throw new Exception("Google Drive credentials file not found at: {$credentialsPath}");
        }

        $this->client = new \Google_Client();
        $this->client->setAuthConfig($credentialsPath);
        $this->client->addScope(\Google_Service_Drive::DRIVE_FILE);
        $this->client->setApplicationName('STAI AL-FATIH SIAKAD');

        $this->service = new \Google_Service_Drive($this->client);
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
    protected function findFolder(string $folderName, ?string $parentId = null): ?string
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
            if (!file_exists($filePath)) {
                throw new Exception("File not found: {$filePath}");
            }

            $fileMetadata = new \Google_Service_Drive_DriveFile([
                'name' => $fileName,
                'parents' => [$folderId]
            ]);

            $content = file_get_contents($filePath);
            $mimeType = $mimeType ?? mime_content_type($filePath);

            $file = $this->service->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart',
                'fields' => 'id, webViewLink, webContentLink',
                'supportsAllDrives' => true
            ]);

            Log::info("Google Drive: Uploaded file '{$fileName}' with ID: {$file->id}");

            return [
                'id' => $file->id,
                'webViewLink' => $file->webViewLink,
                'webContentLink' => $file->webContentLink ?? null,
            ];
        } catch (Exception $e) {
            Log::error("Google Drive: Failed to upload file '{$fileName}': " . $e->getMessage());
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
