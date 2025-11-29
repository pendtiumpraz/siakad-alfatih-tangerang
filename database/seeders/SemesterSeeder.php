<?php

namespace Database\Seeders;

use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Generate semester from 2022/2023 to 2044/2045
     * Total: 48 semesters (24 academic years × 2 semesters)
     */
    public function run(): void
    {
        $semesters = [];
        
        // Generate from 2022 to 2045
        for ($year = 2022; $year < 2045; $year++) {
            $nextYear = $year + 1;
            $tahunAkademik = "{$year}/{$nextYear}";
            
            // Semester Ganjil (Odd Semester: September - January)
            $semesters[] = [
                'nama_semester' => "Semester Ganjil {$tahunAkademik}",
                'tahun_akademik' => $tahunAkademik,
                'jenis' => 'ganjil',
                'tanggal_mulai' => "{$year}-09-01",
                'tanggal_selesai' => "{$nextYear}-01-31",
                'is_active' => ($tahunAkademik === '2024/2025'), // Set active for current semester
            ];
            
            // Semester Genap (Even Semester: February - July)
            $semesters[] = [
                'nama_semester' => "Semester Genap {$tahunAkademik}",
                'tahun_akademik' => $tahunAkademik,
                'jenis' => 'genap',
                'tanggal_mulai' => "{$nextYear}-02-01",
                'tanggal_selesai' => "{$nextYear}-07-31",
                'is_active' => false,
            ];
        }

        foreach ($semesters as $semester) {
            Semester::updateOrCreate(
                [
                    'tahun_akademik' => $semester['tahun_akademik'],
                    'jenis' => $semester['jenis'],
                ],
                $semester
            );
        }
        
        $this->command->info('✅ Generated ' . count($semesters) . ' semesters from 2022/2023 to 2044/2045');
    }
}
