<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'mata_kuliah_id',
        'dosen_id',
        'semester_id',
        'nilai_kehadiran',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'grade',
        'bobot',
        'status',
    ];

    protected $casts = [
        'nilai_kehadiran' => 'decimal:2',
        'nilai_tugas' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    /**
     * Relationships
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Hitung nilai akhir dari komponen.
     * Bobot: Kehadiran 15%, Tugas 15%, UTS 30%, UAS 40%.
     */
    public static function hitungNilaiAkhir($kehadiran, $tugas, $uts, $uas): float
    {
        return round(
            ((float) $kehadiran) * 0.15
            + ((float) $tugas) * 0.15
            + ((float) $uts) * 0.30
            + ((float) $uas) * 0.40,
            2
        );
    }

    /**
     * Konversi nilai akhir 0-100 ke huruf, bobot, dan status kelulusan.
     * Mengikuti kriteria: A 80-100/4.00, B 70-79/3.00, C 60-69/2.00,
     * D 50-59/1.00 (tidak lulus), E 0-49/0.00 (tidak lulus).
     */
    public static function konversiGrade($nilaiAkhir): array
    {
        $nilai = (float) $nilaiAkhir;

        if ($nilai >= 80) {
            return ['grade' => 'A', 'bobot' => 4.00, 'status' => 'lulus', 'keterangan' => 'Sangat Baik'];
        }
        if ($nilai >= 70) {
            return ['grade' => 'B', 'bobot' => 3.00, 'status' => 'lulus', 'keterangan' => 'Baik'];
        }
        if ($nilai >= 60) {
            return ['grade' => 'C', 'bobot' => 2.00, 'status' => 'lulus', 'keterangan' => 'Cukup'];
        }
        if ($nilai >= 50) {
            return ['grade' => 'D', 'bobot' => 1.00, 'status' => 'tidak_lulus', 'keterangan' => 'Tidak Lulus'];
        }

        return ['grade' => 'E', 'bobot' => 0.00, 'status' => 'tidak_lulus', 'keterangan' => 'Tidak Lulus'];
    }
}
