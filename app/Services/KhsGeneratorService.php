<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\Nilai;
use App\Models\Khs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KhsGeneratorService
{
    /**
     * Get bobot nilai from nilai_huruf
     * A = 4.0, A- = 3.7, B+ = 3.3, B = 3.0, B- = 2.7, C+ = 2.3, C = 2.0, D = 1.0, E = 0.0
     */
    public function getBobot(string $nilaiHuruf): float
    {
        return match(strtoupper($nilaiHuruf)) {
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'D' => 1.0,
            'E' => 0.0,
            default => 0.0,
        };
    }

    /**
     * Calculate IP (Indeks Prestasi) for a semester
     * IP = (Σ(Bobot × SKS)) / Σ SKS
     */
    public function calculateIP(Mahasiswa $mahasiswa, Semester $semester): array
    {
        $nilais = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $semester->id)
            ->whereNotNull('nilai_huruf')
            ->with('mataKuliah')
            ->get();

        if ($nilais->isEmpty()) {
            return [
                'ip' => 0.0,
                'total_sks' => 0,
                'total_bobot_x_sks' => 0.0,
            ];
        }

        $totalBobotXSks = 0.0;
        $totalSks = 0;

        foreach ($nilais as $nilai) {
            $bobot = $this->getBobot($nilai->nilai_huruf);
            $sks = $nilai->mataKuliah->sks ?? 0;
            
            $totalBobotXSks += ($bobot * $sks);
            $totalSks += $sks;
        }

        $ip = $totalSks > 0 ? round($totalBobotXSks / $totalSks, 2) : 0.0;

        return [
            'ip' => $ip,
            'total_sks' => $totalSks,
            'total_bobot_x_sks' => $totalBobotXSks,
        ];
    }

    /**
     * Calculate IPK (Indeks Prestasi Kumulatif) for all semesters up to and including current semester
     * IPK = (Σ(Bobot × SKS from all semesters)) / Σ SKS from all semesters
     */
    public function calculateIPK(Mahasiswa $mahasiswa, Semester $currentSemester): array
    {
        // Get all semesters up to current semester
        $semesters = Semester::where('tahun_akademik', '<=', $currentSemester->tahun_akademik)
            ->orderBy('tahun_akademik')
            ->get();

        $totalBobotXSks = 0.0;
        $totalSks = 0;

        foreach ($semesters as $semester) {
            $nilais = Nilai::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $semester->id)
                ->whereNotNull('nilai_huruf')
                ->with('mataKuliah')
                ->get();

            foreach ($nilais as $nilai) {
                $bobot = $this->getBobot($nilai->nilai_huruf);
                $sks = $nilai->mataKuliah->sks ?? 0;
                
                $totalBobotXSks += ($bobot * $sks);
                $totalSks += $sks;
            }
        }

        $ipk = $totalSks > 0 ? round($totalBobotXSks / $totalSks, 2) : 0.0;

        return [
            'ipk' => $ipk,
            'total_sks' => $totalSks,
            'total_bobot_x_sks' => $totalBobotXSks,
        ];
    }

    /**
     * Generate KHS for a single mahasiswa in a semester
     */
    public function generateForMahasiswa(Mahasiswa $mahasiswa, Semester $semester): ?Khs
    {
        try {
            // Calculate IP and IPK
            $ipData = $this->calculateIP($mahasiswa, $semester);
            $ipkData = $this->calculateIPK($mahasiswa, $semester);

            // Create or update KHS
            $khs = Khs::updateOrCreate(
                [
                    'mahasiswa_id' => $mahasiswa->id,
                    'semester_id' => $semester->id,
                ],
                [
                    'ip' => $ipData['ip'],
                    'ipk' => $ipkData['ipk'],
                    'total_sks_semester' => $ipData['total_sks'],
                    'total_sks_kumulatif' => $ipkData['total_sks'],
                ]
            );

            Log::info("KHS generated for mahasiswa {$mahasiswa->nim} - IP: {$ipData['ip']}, IPK: {$ipkData['ipk']}");

            return $khs;

        } catch (\Exception $e) {
            Log::error("Failed to generate KHS for mahasiswa {$mahasiswa->nim}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate KHS for all mahasiswa in a semester
     * Returns array with statistics
     */
    public function generateForSemester(Semester $semester, ?int $programStudiId = null): array
    {
        $query = Mahasiswa::where('status', 'aktif');

        // Filter by program studi if provided
        if ($programStudiId) {
            $query->where('program_studi_id', $programStudiId);
        }

        $mahasiswas = $query->get();
        
        $stats = [
            'total' => $mahasiswas->count(),
            'success' => 0,
            'failed' => 0,
            'skipped' => 0,
        ];

        foreach ($mahasiswas as $mahasiswa) {
            // Check if mahasiswa has nilai for this semester
            $hasNilai = Nilai::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $semester->id)
                ->whereNotNull('nilai_huruf')
                ->exists();

            if (!$hasNilai) {
                $stats['skipped']++;
                Log::info("Skipped mahasiswa {$mahasiswa->nim} - no nilai for semester {$semester->tahun_akademik}");
                continue;
            }

            $khs = $this->generateForMahasiswa($mahasiswa, $semester);

            if ($khs) {
                $stats['success']++;
            } else {
                $stats['failed']++;
            }
        }

        // Update semester status
        $semester->update(['khs_status' => 'generated']);

        Log::info("KHS generation completed for semester {$semester->tahun_akademik}", $stats);

        return $stats;
    }

    /**
     * Check if KHS can be generated for a semester
     */
    public function canGenerate(Semester $semester): array
    {
        $mahasiswasWithNilai = Mahasiswa::where('status', 'aktif')
            ->whereHas('nilais', function($q) use ($semester) {
                $q->where('semester_id', $semester->id)
                  ->whereNotNull('nilai_huruf');
            })
            ->count();

        $totalMahasiswa = Mahasiswa::where('status', 'aktif')->count();

        return [
            'can_generate' => $mahasiswasWithNilai > 0,
            'mahasiswas_with_nilai' => $mahasiswasWithNilai,
            'total_mahasiswa' => $totalMahasiswa,
            'percentage' => $totalMahasiswa > 0 ? round(($mahasiswasWithNilai / $totalMahasiswa) * 100, 2) : 0,
        ];
    }
}
