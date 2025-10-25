<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaiAlfatihProgramStudiSeederFixed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $programs = [
                // IQT DARING
                [
                    'kode_prodi' => 'IQT-D-S1',
                    'nama_prodi' => 'Ilmu Al-Quran dan Tafsir - Daring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-D-S2',
                    'nama_prodi' => 'Ilmu Al-Quran dan Tafsir - Daring',
                    'jenjang' => 'S2',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-D-S3',
                    'nama_prodi' => 'Ilmu Al-Quran dan Tafsir - Daring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],

                // HES DARING
                [
                    'kode_prodi' => 'HES-D-S1',
                    'nama_prodi' => 'Hukum Ekonomi Syariah - Daring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'HES-D-S2',
                    'nama_prodi' => 'Hukum Ekonomi Syariah - Daring',
                    'jenjang' => 'S2',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'HES-D-S3',
                    'nama_prodi' => 'Hukum Ekonomi Syariah - Daring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],

                // IQT LURING
                [
                    'kode_prodi' => 'IQT-L-S1',
                    'nama_prodi' => 'Ilmu Al-Quran dan Tafsir - Luring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-L-S2',
                    'nama_prodi' => 'Ilmu Al-Quran dan Tafsir - Luring',
                    'jenjang' => 'S2',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-L-S3',
                    'nama_prodi' => 'Ilmu Al-Quran dan Tafsir - Luring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],

                // HES LURING
                [
                    'kode_prodi' => 'HES-L-S1',
                    'nama_prodi' => 'Hukum Ekonomi Syariah - Luring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'HES-L-S2',
                    'nama_prodi' => 'Hukum Ekonomi Syariah - Luring',
                    'jenjang' => 'S2',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'HES-L-S3',
                    'nama_prodi' => 'Hukum Ekonomi Syariah - Luring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
            ];

            foreach ($programs as $program) {
                $existing = ProgramStudi::where('kode_prodi', $program['kode_prodi'])->first();

                if (!$existing) {
                    ProgramStudi::create($program);
                    $this->command->info("Created: {$program['nama_prodi']} ({$program['jenjang']})");
                } else {
                    $this->command->warn("Skipped: {$program['nama_prodi']} ({$program['jenjang']}) - Already exists");
                }
            }

            DB::commit();
            $this->command->info('STAI AL-FATIH Program Studi seeder completed!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
