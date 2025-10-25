<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\GoogleDriveService;
use Exception;

class SetupGoogleDriveFolders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gdrive:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup Google Drive folder structure for STAI AL-FATIH';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!config('google-drive.enabled')) {
            $this->error('Google Drive integration is not enabled!');
            $this->info('Set GOOGLE_DRIVE_ENABLED=true in .env file');
            return 1;
        }

        $this->info('Setting up Google Drive folder structure...');
        $this->newLine();

        try {
            $driveService = new GoogleDriveService();

            // Create main folders
            $folders = config('google-drive.folders');

            $this->info('Creating main folders in root:');
            foreach ($folders as $key => $folderName) {
                $this->info("  - Creating folder: {$folderName}");
                $folderId = $driveService->createFolder($folderName);
                $this->line("    ✓ Folder ID: {$folderId}");
            }

            $this->newLine();
            $this->info('✓ Google Drive folder structure setup completed!');
            $this->newLine();
            $this->info('Folder structure:');
            $this->line('STAI AL-FATIH/');
            $this->line('├── Pembayaran/');
            $this->line('│   └── [Files: NIM_Category_Timestamp.pdf]');
            $this->line('├── Dokumen-Mahasiswa/');
            $this->line('│   └── [Auto-created NIM folders]');
            $this->line('└── SPMB/');
            $this->line('    └── [Auto-created Pendaftar ID folders]');
            $this->newLine();

            return 0;
        } catch (Exception $e) {
            $this->error('Failed to setup Google Drive folders!');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
