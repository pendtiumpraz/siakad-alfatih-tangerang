<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in correct order respecting foreign key dependencies
        $this->call([
            // 1. Seed roles and permissions first
            RolePermissionSeeder::class,

            // 2. Seed program studi (no dependencies)
            ProgramStudiSeeder::class,

            // 2a. Seed STAI AL-FATIH programs (no dependencies)
            StaiAlfatihProgramStudiSeederCorrect::class,

            // 3. Seed jalur seleksi (no dependencies) - SPMB
            JalurSeleksiSeeder::class,

            // 4. Seed users (no dependencies)
            UserSeeder::class,

            // 5. Seed user-related tables (depends on users)
            OperatorSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,

            // 6. Seed kurikulum (depends on program_studi)
            KurikulumSeeder::class,

            // 7. Seed mata kuliah (depends on kurikulum)
            MataKuliahSeeder::class,

            // 7a. Seed STAI AL-FATIH S1 mata kuliah (depends on kurikulum)
            StaiAlfatihMataKuliahS1Seeder::class,

            // 8. Seed semester (no dependencies)
            SemesterSeeder::class,

            // 9. Seed ruangan (no dependencies)
            RuanganSeeder::class,

            // 10. Seed jadwal (depends on semester, mata_kuliah, dosen, ruangan)
            JadwalSeeder::class,

            // 11. Seed nilai (depends on mahasiswa, mata_kuliah, dosen, semester)
            // NilaiSeeder::class, // Commented - Dosen will input manually

            // 12. Seed KHS (depends on mahasiswa, semester, nilai)
            // KhsSeeder::class, // Commented - Will be generated after nilai input

            // 13. Seed pembayaran (depends on mahasiswa, semester, operator)
            PembayaranSeeder::class,
        ]);

        $this->command->info('All seeders completed successfully!');
    }
}
