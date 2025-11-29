<?php

namespace App\Services;

class KhsService
{
    /**
     * Get bobot (nilai angka) from grade huruf
     * Based on official STAI AL-FATIH grading system
     * 
     * @param string $grade
     * @return float
     */
    public function getBobot($grade)
    {
        $gradeMap = [
            'A+' => 4.00,
            'A'  => 3.70,
            'B+' => 3.60,
            'B'  => 2.95,
            'C+' => 2.70,
            'C'  => 2.00,
            'D+' => 1.80,
            'D'  => 1.30,
            'E'  => 1.00,
        ];

        return $gradeMap[$grade] ?? 0.00;
    }

    /**
     * Calculate IP (Indeks Prestasi) for one semester
     * 
     * @param int $mahasiswaId
     * @param int $semesterId
     * @return float
     */
    public function calculateIP($mahasiswaId, $semesterId)
    {
        $nilais = \App\Models\Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->with('mataKuliah')
            ->get();

        if ($nilais->isEmpty()) {
            return 0.00;
        }

        $totalSks = 0;
        $totalBobotXSks = 0;

        foreach ($nilais as $nilai) {
            $sks = $nilai->mataKuliah->sks ?? 0;
            $bobot = $nilai->bobot ?? $this->getBobot($nilai->grade ?? 'E');

            $totalSks += $sks;
            $totalBobotXSks += ($bobot * $sks);
        }

        return $totalSks > 0 ? round($totalBobotXSks / $totalSks, 2) : 0.00;
    }

    /**
     * Calculate IPK (Indeks Prestasi Kumulatif) up to current semester
     * 
     * @param int $mahasiswaId
     * @param int $semesterId
     * @return float
     */
    public function calculateIPK($mahasiswaId, $semesterId)
    {
        $nilais = \App\Models\Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', '<=', $semesterId)
            ->with('mataKuliah')
            ->get();

        if ($nilais->isEmpty()) {
            return 0.00;
        }

        $totalSks = 0;
        $totalBobotXSks = 0;

        foreach ($nilais as $nilai) {
            $sks = $nilai->mataKuliah->sks ?? 0;
            $bobot = $nilai->bobot ?? $this->getBobot($nilai->grade ?? 'E');

            $totalSks += $sks;
            $totalBobotXSks += ($bobot * $sks);
        }

        return $totalSks > 0 ? round($totalBobotXSks / $totalSks, 2) : 0.00;
    }

    /**
     * Get total SKS for one semester
     * 
     * @param int $mahasiswaId
     * @param int $semesterId
     * @return int
     */
    public function getTotalSksSemester($mahasiswaId, $semesterId)
    {
        return \App\Models\Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->with('mataKuliah')
            ->get()
            ->sum(function($nilai) {
                return $nilai->mataKuliah->sks ?? 0;
            });
    }

    /**
     * Get total SKS kumulatif up to current semester
     * 
     * @param int $mahasiswaId
     * @param int $semesterId
     * @return int
     */
    public function getTotalSksKumulatif($mahasiswaId, $semesterId)
    {
        return \App\Models\Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', '<=', $semesterId)
            ->with('mataKuliah')
            ->get()
            ->sum(function($nilai) {
                return $nilai->mataKuliah->sks ?? 0;
            });
    }

    /**
     * Generate or update KHS for mahasiswa
     * 
     * @param int $mahasiswaId
     * @param int $semesterId
     * @return \App\Models\Khs
     */
    public function generateKhs($mahasiswaId, $semesterId)
    {
        $ip = $this->calculateIP($mahasiswaId, $semesterId);
        $ipk = $this->calculateIPK($mahasiswaId, $semesterId);
        $totalSksSemester = $this->getTotalSksSemester($mahasiswaId, $semesterId);
        $totalSksKumulatif = $this->getTotalSksKumulatif($mahasiswaId, $semesterId);

        return \App\Models\Khs::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'semester_id' => $semesterId,
            ],
            [
                'ip' => $ip,
                'ipk' => $ipk,
                'total_sks_semester' => $totalSksSemester,
                'total_sks_kumulatif' => $totalSksKumulatif,
            ]
        );
    }

    /**
     * Get grade color for display
     * 
     * @param string $grade
     * @return string
     */
    public function getGradeColor($grade)
    {
        $colorMap = [
            'A+' => 'bg-green-100 text-green-800',
            'A'  => 'bg-green-100 text-green-800',
            'B+' => 'bg-blue-100 text-blue-800',
            'B'  => 'bg-blue-100 text-blue-800',
            'C+' => 'bg-yellow-100 text-yellow-800',
            'C'  => 'bg-yellow-100 text-yellow-800',
            'D+' => 'bg-orange-100 text-orange-800',
            'D'  => 'bg-orange-100 text-orange-800',
            'E'  => 'bg-red-100 text-red-800',
        ];

        return $colorMap[$grade] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get status text
     * 
     * @param string $status
     * @return string
     */
    public function getStatusText($status)
    {
        $statusMap = [
            'lulus' => 'Lulus',
            'tidak_lulus' => 'Tidak Lulus',
            'pending' => 'Pending',
        ];

        return $statusMap[$status] ?? 'Unknown';
    }

    /**
     * Check if mahasiswa passed the semester
     * 
     * @param float $ip
     * @return bool
     */
    public function isPassedSemester($ip)
    {
        return $ip >= 2.00; // Minimum IP to pass
    }

    /**
     * Get predikat based on IPK
     * 
     * @param float $ipk
     * @return string
     */
    public function getPredikat($ipk)
    {
        if ($ipk >= 3.70) {
            return 'Cumlaude';
        } elseif ($ipk >= 3.40) {
            return 'Sangat Memuaskan';
        } elseif ($ipk >= 3.00) {
            return 'Memuaskan';
        } elseif ($ipk >= 2.00) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }
}
