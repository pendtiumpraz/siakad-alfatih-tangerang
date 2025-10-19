<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        $mahasiswas = Mahasiswa::all();

        // Define grades with their corresponding letter and numeric values
        $gradeMapping = [
            'A' => ['min' => 90, 'max' => 100, 'angka' => 4.0],
            'A-' => ['min' => 85, 'max' => 89, 'angka' => 3.7],
            'B+' => ['min' => 80, 'max' => 84, 'angka' => 3.3],
            'B' => ['min' => 75, 'max' => 79, 'angka' => 3.0],
            'B-' => ['min' => 70, 'max' => 74, 'angka' => 2.7],
            'C+' => ['min' => 65, 'max' => 69, 'angka' => 2.3],
            'C' => ['min' => 60, 'max' => 64, 'angka' => 2.0],
            'C-' => ['min' => 55, 'max' => 59, 'angka' => 1.7],
            'D' => ['min' => 45, 'max' => 54, 'angka' => 1.0],
            'E' => ['min' => 0, 'max' => 44, 'angka' => 0.0],
        ];

        foreach ($mahasiswas as $mahasiswa) {
            // Get mata kuliah based on program studi
            $mataKuliahs = MataKuliah::whereHas('kurikulum', function ($query) use ($mahasiswa) {
                $query->where('program_studi_id', $mahasiswa->program_studi_id);
            })->take(4)->get(); // Each student takes 4 courses

            foreach ($mataKuliahs as $index => $mk) {
                // Get dosen from jadwal if available
                $dosen = Dosen::inRandomOrder()->first();

                // Generate random scores
                $nilaiTugas = rand(60, 100);
                $nilaiUts = rand(60, 100);
                $nilaiUas = rand(60, 100);

                // Calculate final score (30% tugas, 30% UTS, 40% UAS)
                $nilaiAkhir = ($nilaiTugas * 0.3) + ($nilaiUts * 0.3) + ($nilaiUas * 0.4);

                // Determine grade
                $nilaiHuruf = 'E';
                $nilaiAngka = 0.0;

                foreach ($gradeMapping as $huruf => $range) {
                    if ($nilaiAkhir >= $range['min'] && $nilaiAkhir <= $range['max']) {
                        $nilaiHuruf = $huruf;
                        $nilaiAngka = $range['angka'];
                        break;
                    }
                }

                Nilai::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'mata_kuliah_id' => $mk->id,
                    'dosen_id' => $dosen->id,
                    'semester_id' => $semester->id,
                    'nilai_tugas' => $nilaiTugas,
                    'nilai_uts' => $nilaiUts,
                    'nilai_uas' => $nilaiUas,
                    'nilai_akhir' => round($nilaiAkhir, 2),
                    'grade' => $nilaiHuruf,
                    'status' => $nilaiHuruf !== 'E' ? 'lulus' : 'tidak_lulus',
                ]);
            }
        }
    }
}
