<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaiAlfatihProgramStudiSeederCorrect extends Seeder
{
    /**
     * STAI AL-FATIH Tangerang - Program Studi Seeder
     * Berdasarkan data resmi dari https://staialfatih.or.id/
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $programs = [
                // ========================================
                // S1 PROGRAMS (Luring/Offline)
                // ========================================
                [
                    'kode_prodi' => 'PAI-S1-L',
                    'nama_prodi' => 'Pendidikan Agama Islam',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'MPI-S1-L',
                    'nama_prodi' => 'Manajemen Pendidikan Islam',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'HES-S1-L',
                    'nama_prodi' => 'Hukum Ekonomi Syariah/Muamalah',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'PGMI-S1-L',
                    'nama_prodi' => 'Pendidikan Guru Madrasah Ibtidaiyah',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],

                // ========================================
                // S1 PROGRAMS (Daring/Online)
                // ========================================
                [
                    'kode_prodi' => 'PAI-S1-D',
                    'nama_prodi' => 'Pendidikan Agama Islam - Daring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'MPI-S1-D',
                    'nama_prodi' => 'Manajemen Pendidikan Islam - Daring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'HES-S1-D',
                    'nama_prodi' => 'Hukum Ekonomi Syariah/Muamalah - Daring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'PGMI-S1-D',
                    'nama_prodi' => 'Pendidikan Guru Madrasah Ibtidaiyah - Daring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],

                // ========================================
                // S2 PROGRAM (Daring only)
                // ========================================
                [
                    'kode_prodi' => 'MPI-S2-D',
                    'nama_prodi' => 'Manajemen Pendidikan Islam - Daring',
                    'jenjang' => 'S2',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],

                // ========================================
                // S3 PROGRAMS (Daring only)
                // ========================================
                [
                    'kode_prodi' => 'PAI-S3-D',
                    'nama_prodi' => 'Pendidikan Agama Islam - Daring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'MPI-S3-D',
                    'nama_prodi' => 'Manajemen Pendidikan Islam - Daring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-S3-D',
                    'nama_prodi' => 'Ilmu Al-Quran dan Tafsir - Daring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
            ];

            foreach ($programs as $program) {
                $existing = ProgramStudi::where('kode_prodi', $program['kode_prodi'])->first();

                if (!$existing) {
                    ProgramStudi::create($program);
                    $this->command->info("✓ Created: {$program['nama_prodi']} ({$program['jenjang']})");
                } else {
                    $this->command->warn("⊗ Skipped: {$program['nama_prodi']} - Already exists");
                }
            }

            DB::commit();
            $this->command->info("\n✓ STAI AL-FATIH Program Studi seeder completed!");
            $this->command->info("Total: 12 programs (8 S1, 1 S2, 3 S3)");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('✗ Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
