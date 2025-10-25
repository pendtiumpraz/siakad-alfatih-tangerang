<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaiAlfatihProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Seeder for STAI AL-FATIH Tangerang Programs
     * Source: https://staialfatih.or.id/
     *
     * Programs offered:
     * - Ilmu Al-Qur'an dan Tafsir (S1, S2, S3) - Daring & Luring
     * - Hukum Ekonomi Syariah (S1) - Daring & Luring
     *
     * @return void
     */
    public function run(): void
    {
        // Use transaction for data integrity
        DB::beginTransaction();

        try {
            $programStudis = [
                // ===================================
                // PROGRAM DARING (ONLINE)
                // ===================================

                // Ilmu Al-Qur'an dan Tafsir - DARING
                [
                    'kode_prodi' => 'IQT-D-S1',
                    'nama_prodi' => 'Ilmu Al-Qur\'an dan Tafsir - Daring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-D-S2',
                    'nama_prodi' => 'Ilmu Al-Qur\'an dan Tafsir - Daring',
                    'jenjang' => 'S2',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-D-S3',
                    'nama_prodi' => 'Ilmu Al-Qur\'an dan Tafsir - Daring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],

                // Hukum Ekonomi Syariah - DARING
                [
                    'kode_prodi' => 'HES-D-S1',
                    'nama_prodi' => 'Hukum Ekonomi Syariah - Daring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B', // SK Ban-PT: No. 8926/SK/BAN-PT/Akred/S/VI/2021
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

                // ===================================
                // PROGRAM LURING (OFFLINE)
                // ===================================

                // Ilmu Al-Qur'an dan Tafsir - LURING
                [
                    'kode_prodi' => 'IQT-L-S1',
                    'nama_prodi' => 'Ilmu Al-Qur\'an dan Tafsir - Luring',
                    'jenjang' => 'S1',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-L-S2',
                    'nama_prodi' => 'Ilmu Al-Qur\'an dan Tafsir - Luring',
                    'jenjang' => 'S2',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],
                [
                    'kode_prodi' => 'IQT-L-S3',
                    'nama_prodi' => 'Ilmu Al-Qur\'an dan Tafsir - Luring',
                    'jenjang' => 'S3',
                    'akreditasi' => 'B',
                    'is_active' => true,
                ],

                // Hukum Ekonomi Syariah - LURING
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

            foreach ($programStudis as $prodi) {
                // Check if program already exists to ensure idempotency
                $existing = ProgramStudi::where('kode_prodi', $prodi['kode_prodi'])->first();

                if (!$existing) {
                    ProgramStudi::create($prodi);
                    $this->command->info("Created: {$prodi['nama_prodi']} ({$prodi['jenjang']})");
                } else {
                    $this->command->warn("Skipped: {$prodi['nama_prodi']} ({$prodi['jenjang']}) - Already exists");
                }
            }

            DB::commit();
            $this->command->info('STAI AL-FATIH Program Studi seeder completed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error seeding STAI AL-FATIH programs: ' . $e->getMessage());
            throw $e;
        }
    }
}
