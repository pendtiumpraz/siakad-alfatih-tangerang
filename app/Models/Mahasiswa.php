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
        'tanggal_lulus',
        'tanggal_dropout',
        'semester_terakhir',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lulus' => 'date',
        'tanggal_dropout' => 'date',
        'angkatan' => 'integer',
        'semester_aktif' => 'integer',
        'semester_terakhir' => 'integer',
    ];

    /**
     * Boot method to auto-calculate semester_aktif and handle alumni status
     */
    protected static function booted()
    {
        static::saving(function ($mahasiswa) {
            $currentStatus = $mahasiswa->status;

            // Handle status "lulus"
            if ($currentStatus === 'lulus') {
                // Use manual date if provided, otherwise use now()
                if (!$mahasiswa->tanggal_lulus) {
                    $mahasiswa->tanggal_lulus = now();
                }
                $mahasiswa->tanggal_dropout = null; // Clear dropout date if exists

                // Calculate semester_terakhir based on tanggal_lulus, not current date
                $mahasiswa->semester_terakhir = static::calculateSemesterAktif(
                    $mahasiswa->angkatan,
                    $mahasiswa->tanggal_lulus
                );

                // Always sync is_active for lulus
                if ($mahasiswa->user && $mahasiswa->user->is_active) {
                    $mahasiswa->user->update(['is_active' => false]);
                }
            }
            // Handle status "dropout"
            elseif ($currentStatus === 'dropout') {
                // Use manual date if provided, otherwise use now()
                if (!$mahasiswa->tanggal_dropout) {
                    $mahasiswa->tanggal_dropout = now();
                }
                $mahasiswa->tanggal_lulus = null; // Clear graduation date if exists

                // Calculate semester_terakhir based on tanggal_dropout, not current date
                $mahasiswa->semester_terakhir = static::calculateSemesterAktif(
                    $mahasiswa->angkatan,
                    $mahasiswa->tanggal_dropout
                );

                // Always sync is_active for dropout
                if ($mahasiswa->user && $mahasiswa->user->is_active) {
                    $mahasiswa->user->update(['is_active' => false]);
                }
            }
            // Handle status "aktif" or "cuti"
            elseif (in_array($currentStatus, ['aktif', 'cuti'])) {
                $mahasiswa->tanggal_lulus = null;
                $mahasiswa->tanggal_dropout = null;
                $mahasiswa->semester_terakhir = null;

                // Always sync is_active for aktif/cuti
                if ($mahasiswa->user && !$mahasiswa->user->is_active) {
                    $mahasiswa->user->update(['is_active' => true]);
                }
            }

            // Auto-calculate semester_aktif based on status
            if ($mahasiswa->angkatan) {
                if (in_array($currentStatus, ['aktif', 'cuti'])) {
                    // For active students, use current date
                    $mahasiswa->semester_aktif = static::calculateSemesterAktif($mahasiswa->angkatan);
                } elseif ($currentStatus === 'lulus' && $mahasiswa->semester_terakhir) {
                    // For graduates, freeze at semester_terakhir
                    $mahasiswa->semester_aktif = $mahasiswa->semester_terakhir;
                } elseif ($currentStatus === 'dropout' && $mahasiswa->semester_terakhir) {
                    // For dropouts, freeze at semester_terakhir
                    $mahasiswa->semester_aktif = $mahasiswa->semester_terakhir;
                }
            }
        });
    }

    /**
     * Calculate semester aktif based on angkatan and reference date
     *
     * @param int $angkatan Year of enrollment
     * @param Carbon|string|null $referenceDate Date to calculate from (default: now)
     * @return int Calculated semester (max 14)
     */
    public static function calculateSemesterAktif($angkatan, $referenceDate = null)
    {
        // Use provided date or current date
        $date = $referenceDate ? Carbon::parse($referenceDate) : Carbon::now();
        $year = $date->year;
        $month = $date->month;

        // Hitung selisih tahun
        $yearDiff = $year - $angkatan;

        // Tentukan semester berdasarkan bulan
        // Semester ganjil: Agustus - Januari (bulan 8-12, 1)
        // Semester genap: Februari - Juli (bulan 2-7)

        if ($month >= 8) {
            // Sedang semester ganjil tahun akademik baru
            // Contoh: Angkatan 2020, lulus Agustus 2024 = semester 9
            $semester = ($yearDiff * 2) + 1;
        } elseif ($month >= 2) {
            // Sedang semester genap
            // Contoh: Angkatan 2020, lulus Maret 2024 = semester 8
            $semester = ($yearDiff * 2);
        } else {
            // Januari masih semester ganjil tahun akademik sebelumnya
            // Contoh: Angkatan 2020, lulus Januari 2024 = semester 7
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
