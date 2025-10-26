<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'program_studi_id',
        'dosen_wali_id',
        'nim',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'foto',
        'angkatan',
        'semester_aktif',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'angkatan' => 'integer',
        'semester_aktif' => 'integer',
    ];

    /**
     * Boot method to auto-calculate semester_aktif
     */
    protected static function booted()
    {
        static::saving(function ($mahasiswa) {
            if ($mahasiswa->angkatan) {
                $mahasiswa->semester_aktif = static::calculateSemesterAktif($mahasiswa->angkatan);
            }
        });
    }

    /**
     * Calculate semester aktif based on angkatan and current date
     */
    public static function calculateSemesterAktif($angkatan)
    {
        $now = Carbon::now();
        $currentYear = $now->year;
        $currentMonth = $now->month;

        // Hitung selisih tahun
        $yearDiff = $currentYear - $angkatan;

        // Tentukan semester berdasarkan bulan
        // Semester ganjil: Agustus - Januari (bulan 8-12, 1)
        // Semester genap: Februari - Juli (bulan 2-7)

        if ($currentMonth >= 8) {
            // Sedang semester ganjil tahun akademik baru
            // Contoh: Angkatan 2023, sekarang Agustus 2025 = semester 5
            $semester = ($yearDiff * 2) + 1;
        } elseif ($currentMonth >= 2) {
            // Sedang semester genap
            // Contoh: Angkatan 2023, sekarang Maret 2025 = semester 4
            $semester = ($yearDiff * 2);
        } else {
            // Januari masih semester ganjil tahun akademik sebelumnya
            // Contoh: Angkatan 2023, sekarang Januari 2025 = semester 3
            $semester = (($yearDiff - 1) * 2) + 1;
        }

        // Maksimal 14 semester (S1 normal 8 semester + 6 semester perpanjangan)
        return min($semester, 14);
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function dosenWali()
    {
        return $this->belongsTo(Dosen::class, 'dosen_wali_id');
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function khs()
    {
        return $this->hasMany(Khs::class);
    }
}
