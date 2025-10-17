<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semesters = [
            [
                'nama_semester' => 'Semester Ganjil 2024/2025',
                'tahun_akademik' => '2024/2025',
                'jenis' => 'ganjil',
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => '2025-01-31',
                'is_active' => true,
            ],
            [
                'nama_semester' => 'Semester Genap 2023/2024',
                'tahun_akademik' => '2023/2024',
                'jenis' => 'genap',
                'tanggal_mulai' => '2024-02-01',
                'tanggal_selesai' => '2024-07-31',
                'is_active' => false,
            ],
        ];

        foreach ($semesters as $semester) {
            Semester::create($semester);
        }
    }
}
