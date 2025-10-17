<?php

namespace Database\Seeders;

use App\Models\Khs;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Semester;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KhsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semester = Semester::where('is_active', true)->first();
        $mahasiswas = Mahasiswa::all();

        // Grade to numeric mapping
        $gradeToNumeric = [
            'A' => 4.0,
            'AB' => 3.5,
            'B' => 3.0,
            'BC' => 2.5,
            'C' => 2.0,
            'D' => 1.0,
            'E' => 0.0,
        ];

        foreach ($mahasiswas as $mahasiswa) {
            // Get all nilai for this mahasiswa in this semester
            $nilais = Nilai::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $semester->id)
                ->get();

            if ($nilais->count() > 0) {
                $totalSks = 0;
                $totalSksLulus = 0;
                $totalNilai = 0;

                foreach ($nilais as $nilai) {
                    $sks = $nilai->mataKuliah->sks;
                    $totalSks += $sks;

                    // Count as passed if grade is not E
                    if ($nilai->grade !== 'E') {
                        $totalSksLulus += $sks;
                    }

                    // Calculate weighted grade
                    $nilaiAngka = $gradeToNumeric[$nilai->grade] ?? 0.0;
                    $totalNilai += ($nilaiAngka * $sks);
                }

                // Calculate IPS (Indeks Prestasi Semester)
                $ips = $totalSks > 0 ? round($totalNilai / $totalSks, 2) : 0;

                // For simplicity, IPK = IPS in first semester
                $ipk = $ips;

                // Determine status_semester
                $statusSemester = 'naik';
                if ($ips < 2.0) {
                    $statusSemester = 'mengulang';
                }

                Khs::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'semester_id' => $semester->id,
                    'total_sks' => $totalSks,
                    'total_sks_lulus' => $totalSksLulus,
                    'ip_semester' => $ips,
                    'ip_kumulatif' => $ipk,
                    'status_semester' => $statusSemester,
                ]);
            }
        }
    }
}
