<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pendaftar;
use App\Services\GoogleDriveService;
use Illuminate\Support\Facades\Log;

class MakeGoogleDriveFilesPublic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gdrive:make-public {--model=pendaftar : Model to process (pendaftar/mahasiswa)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make existing Google Drive files publicly accessible for embedding';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔧 Making Google Drive files publicly accessible...');
        $this->newLine();

        $model = $this->option('model');

        if ($model === 'pendaftar') {
            $this->processPendaftar();
        } else {
            $this->error('Invalid model. Use: pendaftar or mahasiswa');
            return 1;
        }

        $this->newLine();
        $this->info('✅ Done!');
        return 0;
    }

    /**
     * Process Pendaftar records
     */
    protected function processPendaftar()
    {
        $this->info('Processing SPMB Pendaftar records...');

        $pendaftars = Pendaftar::whereNotNull('google_drive_file_id')
            ->orWhereNotNull('ijazah_google_drive_id')
            ->orWhereNotNull('transkrip_google_drive_id')
            ->orWhereNotNull('ktp_google_drive_id')
            ->orWhereNotNull('kk_google_drive_id')
            ->orWhereNotNull('akta_google_drive_id')
            ->orWhereNotNull('sktm_google_drive_id')
            ->get();

        if ($pendaftars->isEmpty()) {
            $this->warn('No Pendaftar records with Google Drive files found.');
            return;
        }

        $this->info("Found {$pendaftars->count()} pendaftar(s) with Google Drive files.");
        $this->newLine();

        $driveService = new GoogleDriveService();
        $processed = 0;
        $errors = 0;

        $bar = $this->output->createProgressBar($pendaftars->count());
        $bar->start();

        foreach ($pendaftars as $pendaftar) {
            $fileIds = [
                'Foto' => $pendaftar->google_drive_file_id,
                'Ijazah' => $pendaftar->ijazah_google_drive_id,
                'Transkrip' => $pendaftar->transkrip_google_drive_id,
                'KTP' => $pendaftar->ktp_google_drive_id,
                'KK' => $pendaftar->kk_google_drive_id,
                'Akta' => $pendaftar->akta_google_drive_id,
                'SKTM' => $pendaftar->sktm_google_drive_id,
            ];

            foreach ($fileIds as $type => $fileId) {
                if (!empty($fileId)) {
                    try {
                        $driveService->makeFilePublic($fileId);
                        $processed++;
                    } catch (\Exception $e) {
                        $errors++;
                        Log::error("Failed to make {$type} public for {$pendaftar->nomor_pendaftaran}: {$e->getMessage()}");
                    }
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Processed {$processed} file(s)");
        if ($errors > 0) {
            $this->warn("⚠️  Failed to process {$errors} file(s) - check logs");
        }
    }
}
