<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudis = [
            [
                'kode_prodi' => 'TI',
                'nama_prodi' => 'Teknik Informatika',
                'jenjang' => 'S1',
                'akreditasi' => 'A',
            ],
            [
                'kode_prodi' => 'SI',
                'nama_prodi' => 'Sistem Informasi',
                'jenjang' => 'S1',
                'akreditasi' => 'A',
            ],
            [
                'kode_prodi' => 'MI',
                'nama_prodi' => 'Manajemen Informatika',
                'jenjang' => 'D3',
                'akreditasi' => 'B',
            ],
        ];

        foreach ($programStudis as $prodi) {
            ProgramStudi::create($prodi);
        }
    }
}
