<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Konversi data nilais lama (skala A+, A, A-, AB, B+, B-, BC, C+, C-, D+, D, E)
 * ke skala penilaian baru: A 80-100/4.00, B 70-79/3.00, C 60-69/2.00,
 * D 50-59/1.00 (tidak lulus), E 0-49/0.00 (tidak lulus).
 *
 * Strategi:
 * - Kalau nilai_akhir terisi → recompute grade/bobot/status dari skor (paling akurat).
 * - Kalau nilai_akhir null → fallback dari label grade lama berdasarkan rentang skor lama.
 * - Bobot mahasiswa pada KHS (ip & ipk) di-recompute setelah konversi nilais selesai.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $this->convertNilais();
            $this->recomputeKhs();
        });
    }

    public function down(): void
    {
        // Konversi bersifat collapsing (banyak grade lama dipetakan ke satu grade baru),
        // sehingga rollback tidak bisa merekonstruksi grade asli secara akurat.
        // Dibiarkan no-op agar tidak merusak data hasil konversi.
    }

    private function convertNilais(): void
    {
        $fallback = [
            'A+' => ['A', 4.00, 'lulus'],
            'A'  => ['A', 4.00, 'lulus'],
            'A-' => ['A', 4.00, 'lulus'],
            'AB' => ['A', 4.00, 'lulus'],
            'B+' => ['A', 4.00, 'lulus'], // skor lama 88-92 → skor baru masuk A (>=80)
            'B'  => ['A', 4.00, 'lulus'], // skor lama 80-87 → skor baru masuk A (>=80)
            'B-' => ['B', 3.00, 'lulus'], // skor lama 75-79 → masuk B (70-79)
            'BC' => ['B', 3.00, 'lulus'],
            'C+' => ['B', 3.00, 'lulus'], // skor lama 70-79 → masuk B
            'C'  => ['C', 2.00, 'lulus'], // skor lama 66-69 → masuk C (60-69)
            'C-' => ['C', 2.00, 'lulus'],
            'D+' => ['C', 2.00, 'lulus'], // skor lama 58-65 → mayoritas C
            'D'  => ['D', 1.00, 'tidak_lulus'], // skor lama 50-57 → masuk D
            'E'  => ['E', 0.00, 'tidak_lulus'],
        ];

        DB::table('nilais')->orderBy('id')->chunkById(500, function ($rows) use ($fallback) {
            foreach ($rows as $row) {
                if (in_array($row->grade, ['A', 'B', 'C', 'D', 'E'], true)
                    && $this->bobotMatchesGrade($row->grade, $row->bobot)) {
                    continue; // sudah sesuai skala baru
                }

                if ($row->nilai_akhir !== null) {
                    [$grade, $bobot, $status] = $this->fromScore((float) $row->nilai_akhir);
                } elseif (isset($fallback[$row->grade])) {
                    [$grade, $bobot, $status] = $fallback[$row->grade];
                } else {
                    continue; // grade tidak dikenali & skor kosong → biarkan apa adanya
                }

                DB::table('nilais')->where('id', $row->id)->update([
                    'grade' => $grade,
                    'bobot' => $bobot,
                    'status' => $status,
                ]);
            }
        });
    }

    private function fromScore(float $score): array
    {
        if ($score >= 80) return ['A', 4.00, 'lulus'];
        if ($score >= 70) return ['B', 3.00, 'lulus'];
        if ($score >= 60) return ['C', 2.00, 'lulus'];
        if ($score >= 50) return ['D', 1.00, 'tidak_lulus'];
        return ['E', 0.00, 'tidak_lulus'];
    }

    private function bobotMatchesGrade(string $grade, $bobot): bool
    {
        $expected = ['A' => 4.00, 'B' => 3.00, 'C' => 2.00, 'D' => 1.00, 'E' => 0.00];
        return $bobot !== null && abs(((float) $bobot) - $expected[$grade]) < 0.001;
    }

    private function recomputeKhs(): void
    {
        if (!class_exists(\App\Models\Khs::class)) {
            return;
        }

        $khsRows = DB::table('khs')->select('mahasiswa_id', 'semester_id')->get();

        foreach ($khsRows as $khs) {
            $semesterAgg = DB::table('nilais')
                ->join('mata_kuliahs', 'nilais.mata_kuliah_id', '=', 'mata_kuliahs.id')
                ->where('nilais.mahasiswa_id', $khs->mahasiswa_id)
                ->where('nilais.semester_id', $khs->semester_id)
                ->whereNotNull('nilais.bobot')
                ->selectRaw('SUM(nilais.bobot * mata_kuliahs.sks) AS total_bobot, SUM(mata_kuliahs.sks) AS total_sks')
                ->first();

            $totalSksSemester = (int) ($semesterAgg->total_sks ?? 0);
            $ip = $totalSksSemester > 0
                ? round(((float) $semesterAgg->total_bobot) / $totalSksSemester, 2)
                : 0.00;

            $kumulatifAgg = DB::table('nilais')
                ->join('mata_kuliahs', 'nilais.mata_kuliah_id', '=', 'mata_kuliahs.id')
                ->join('semesters', 'nilais.semester_id', '=', 'semesters.id')
                ->where('nilais.mahasiswa_id', $khs->mahasiswa_id)
                ->whereNotNull('nilais.bobot')
                ->where('semesters.tahun_akademik', '<=', DB::raw(
                    '(SELECT s.tahun_akademik FROM semesters s WHERE s.id = ' . (int) $khs->semester_id . ')'
                ))
                ->selectRaw('SUM(nilais.bobot * mata_kuliahs.sks) AS total_bobot, SUM(mata_kuliahs.sks) AS total_sks')
                ->first();

            $totalSksKumulatif = (int) ($kumulatifAgg->total_sks ?? 0);
            $ipk = $totalSksKumulatif > 0
                ? round(((float) $kumulatifAgg->total_bobot) / $totalSksKumulatif, 2)
                : 0.00;

            DB::table('khs')
                ->where('mahasiswa_id', $khs->mahasiswa_id)
                ->where('semester_id', $khs->semester_id)
                ->update([
                    'ip' => $ip,
                    'ipk' => $ipk,
                    'total_sks_semester' => $totalSksSemester,
                    'total_sks_kumulatif' => $totalSksKumulatif,
                ]);
        }
    }
};
