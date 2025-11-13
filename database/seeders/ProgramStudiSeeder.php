<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    /**
     * STAI AL-FATIH Tangerang - Program Studi Seeder
     * Data: 4 Program Studi S1 Utama
     */
    public function run(): void
    {
        $programStudis = [
            [
                'kode_prodi' => 'PAI',
                'nama_prodi' => 'Pendidikan Agama Islam',
                'jenjang' => 'S1',
                'akreditasi' => 'B',
                'is_active' => true,
            ],
            [
                'kode_prodi' => 'ES',
                'nama_prodi' => 'Ekonomi Syariah',
                'jenjang' => 'S1',
                'akreditasi' => 'B',
                'is_active' => true,
            ],
            [
                'kode_prodi' => 'PIAUD',
                'nama_prodi' => 'Pendidikan Islam Anak Usia Dini',
                'jenjang' => 'S1',
                'akreditasi' => 'B',
                'is_active' => true,
            ],
            [
                'kode_prodi' => 'MPI',
                'nama_prodi' => 'Manajemen Pendidikan Islam',
                'jenjang' => 'S1',
                'akreditasi' => 'B',
                'is_active' => true,
            ],
        ];

        foreach ($programStudis as $prodi) {
            ProgramStudi::firstOrCreate(
                ['kode_prodi' => $prodi['kode_prodi']], 
                $prodi
            );
        }

        $this->command->info('✓ STAI AL-FATIH Program Studi seeded: 4 programs (PAI, ES, PIAUD, MPI)');
    }
}
