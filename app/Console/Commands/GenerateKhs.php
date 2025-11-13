<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateKhs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'khs:generate 
                            {semester_id? : ID semester yang akan digenerate KHS-nya}
                            {--prodi= : Filter by program studi ID}
                            {--force : Force regenerate even if already generated}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate KHS (Kartu Hasil Studi) untuk mahasiswa berdasarkan semester';

    protected $khsGenerator;

    /**
     * Create a new command instance.
     */
    public function __construct(\App\Services\KhsGeneratorService $khsGenerator)
    {
        parent::__construct();
        $this->khsGenerator = $khsGenerator;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $semesterId = $this->argument('semester_id');
        $prodiId = $this->option('prodi');
        $force = $this->option('force');

        // Get semester
        if ($semesterId) {
            $semester = \App\Models\Semester::find($semesterId);
            
            if (!$semester) {
                $this->error("Semester dengan ID {$semesterId} tidak ditemukan!");
                return 1;
            }
        } else {
            // Use active semester
            $semester = \App\Models\Semester::where('is_active', true)->first();
            
            if (!$semester) {
                $this->error("Tidak ada semester aktif! Silakan tentukan semester_id.");
                return 1;
            }
        }

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("  📚 KHS GENERATOR - SIAKAD STAI Al-Fatih");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();
        $this->info("Semester: {$semester->tahun_akademik}");
        
        if ($prodiId) {
            $prodi = \App\Models\ProgramStudi::find($prodiId);
            $this->info("Program Studi: " . ($prodi->nama_prodi ?? 'Unknown'));
        } else {
            $this->info("Program Studi: Semua");
        }
        
        $this->newLine();

        // Check if can generate
        $this->info("Memeriksa kelayakan generate...");
        $check = $this->khsGenerator->canGenerate($semester);
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Mahasiswa Aktif', $check['total_mahasiswa']],
                ['Mahasiswa dengan Nilai', $check['mahasiswas_with_nilai']],
                ['Persentase', $check['percentage'] . '%'],
            ]
        );

        if (!$check['can_generate']) {
            $this->error("Tidak ada mahasiswa dengan nilai untuk semester ini!");
            return 1;
        }

        // Confirm before generating
        if (!$force && !$this->confirm("Lanjutkan generate KHS?", true)) {
            $this->warn("Generate KHS dibatalkan.");
            return 0;
        }

        $this->newLine();
        $this->info("Memulai generate KHS...");
        
        // Generate with progress bar
        $stats = $this->khsGenerator->generateForSemester($semester, $prodiId);

        $this->newLine();
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("  ✅ GENERATE KHS SELESAI!");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->newLine();

        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Berhasil', $stats['success']],
                ['❌ Gagal', $stats['failed']],
                ['⏭️  Dilewati (no nilai)', $stats['skipped']],
                ['📊 Total', $stats['total']],
            ]
        );

        if ($stats['failed'] > 0) {
            $this->warn("Ada {$stats['failed']} mahasiswa yang gagal digenerate. Cek log untuk detail.");
            return 1;
        }

        $this->info("Semua KHS berhasil digenerate! 🎉");
        return 0;
    }
}
