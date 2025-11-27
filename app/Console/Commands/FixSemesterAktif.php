<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mahasiswa;
use App\Models\Semester;
use Carbon\Carbon;

class FixSemesterAktif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mahasiswa:fix-semester-aktif 
                            {angkatan? : Angkatan to fix (optional, will process all if not specified)}
                            {--dry-run : Show what would be updated without actually updating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix semester_aktif for mahasiswa based on current date and angkatan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== FIX SEMESTER AKTIF ===');
        $this->newLine();

        // Get active semester
        $semester = Semester::where('is_active', true)->first();
        
        if (!$semester) {
            $this->error('No active semester found!');
            return 1;
        }

        $this->info("Active Semester: {$semester->nama_semester} {$semester->tahun_akademik}");
        $this->info("Current Date: " . now()->format('F Y'));
        $this->newLine();

        // Get current calculation params
        $currentYear = (int) substr($semester->tahun_akademik, 0, 4);
        $currentMonth = (int) now()->format('m');

        // Get mahasiswa to process
        $query = Mahasiswa::where('status', 'aktif');
        
        if ($angkatan = $this->argument('angkatan')) {
            $query->where('angkatan', $angkatan);
            $this->info("Processing angkatan: {$angkatan}");
        } else {
            $this->info("Processing ALL active mahasiswa");
        }
        
        $mahasiswas = $query->get();

        if ($mahasiswas->isEmpty()) {
            $this->warn('No active mahasiswa found!');
            return 1;
        }

        $this->info("Found {$mahasiswas->count()} mahasiswa to process");
        $this->newLine();

        // Dry run mode
        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('DRY RUN MODE - No changes will be saved');
            $this->newLine();
        }

        // Process each mahasiswa
        $updated = 0;
        $skipped = 0;
        $errors = [];

        $this->info('Processing...');
        $progressBar = $this->output->createProgressBar($mahasiswas->count());

        foreach ($mahasiswas as $mhs) {
            // Calculate correct semester
            $yearDiff = $currentYear - $mhs->angkatan;
            
            if ($currentMonth >= 8) {
                $correctSem = ($yearDiff * 2) + 1;
            } elseif ($currentMonth >= 2) {
                $correctSem = ($yearDiff * 2);
            } else {
                $correctSem = (($yearDiff - 1) * 2) + 1;
            }

            // Max 14 semester
            $correctSem = min($correctSem, 14);

            // Check if update needed
            if ($mhs->semester_aktif == $correctSem) {
                $skipped++;
                $progressBar->advance();
                continue;
            }

            // Update
            $oldSem = $mhs->semester_aktif;
            
            if (!$dryRun) {
                try {
                    $mhs->semester_aktif = $correctSem;
                    $mhs->save();
                    $updated++;
                } catch (\Exception $e) {
                    $errors[] = "NIM {$mhs->nim}: " . $e->getMessage();
                }
            } else {
                $updated++;
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info('=== SUMMARY ===');
        
        if ($dryRun) {
            $this->info("Would update: {$updated} mahasiswa");
        } else {
            $this->info("✅ Updated: {$updated} mahasiswa");
        }
        
        $this->info("⏭️  Skipped (already correct): {$skipped} mahasiswa");
        
        if (!empty($errors)) {
            $this->error("❌ Errors: " . count($errors));
            foreach ($errors as $error) {
                $this->error("  - {$error}");
            }
        }

        $this->newLine();

        // Show sample updates if any
        if ($updated > 0 && !$dryRun) {
            $this->info('Sample updated mahasiswa (first 5):');
            
            $query = Mahasiswa::where('status', 'aktif');
            if ($angkatan = $this->argument('angkatan')) {
                $query->where('angkatan', $angkatan);
            }
            
            $samples = $query->take(5)->get();
            
            foreach ($samples as $mhs) {
                $this->line("  {$mhs->nim} | {$mhs->nama_lengkap} | Semester: {$mhs->semester_aktif}");
            }
        }

        $this->newLine();
        $this->info('✅ Done!');

        return 0;
    }
}
