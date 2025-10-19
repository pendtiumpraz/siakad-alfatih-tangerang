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

            // 3. Seed users (no dependencies)
            UserSeeder::class,

            // 4. Seed user-related tables (depends on users)
            OperatorSeeder::class,
            DosenSeeder::class,
            MahasiswaSeeder::class,

            // 5. Seed kurikulum (depends on program_studi)
            KurikulumSeeder::class,

            // 6. Seed mata kuliah (depends on kurikulum)
            MataKuliahSeeder::class,

            // 7. Seed semester (no dependencies)
            SemesterSeeder::class,

            // 8. Seed ruangan (no dependencies)
            RuanganSeeder::class,

            // 9. Seed jadwal (depends on semester, mata_kuliah, dosen, ruangan)
            JadwalSeeder::class,

            // 10. Seed nilai (depends on mahasiswa, mata_kuliah, dosen, semester)
            // NilaiSeeder::class, // Commented - Dosen will input manually

            // 11. Seed KHS (depends on mahasiswa, semester, nilai)
            // KhsSeeder::class, // Commented - Will be generated after nilai input

            // 12. Seed pembayaran (depends on mahasiswa, semester, operator)
            PembayaranSeeder::class,
        ]);

        $this->command->info('All seeders completed successfully!');
    }
}
