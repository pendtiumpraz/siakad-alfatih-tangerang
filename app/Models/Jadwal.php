<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'semester_id', // Nullable, untuk backward compatibility
        'jenis_semester', // 'ganjil' atau 'genap' - MAIN field untuk jadwal
        'mata_kuliah_id',
        'dosen_id',
        'ruangan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kelas',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected $casts = [
        'jenis_semester' => 'string',
    ];

    /**
     * Boot method to auto-set jenis_semester from mata kuliah
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-detect jenis_semester from mata kuliah's semester
        static::creating(function ($jadwal) {
            if (empty($jadwal->jenis_semester) && $jadwal->mata_kuliah_id) {
                $mataKuliah = MataKuliah::find($jadwal->mata_kuliah_id);
                if ($mataKuliah) {
                    $semesterNumber = $mataKuliah->semester;
                    $jadwal->jenis_semester = ($semesterNumber % 2 === 1) ? 'ganjil' : 'genap';
                }
            }
        });

        static::updating(function ($jadwal) {
            // Jika mata_kuliah_id berubah, update jenis_semester
            if ($jadwal->isDirty('mata_kuliah_id')) {
                $mataKuliah = MataKuliah::find($jadwal->mata_kuliah_id);
                if ($mataKuliah) {
                    $semesterNumber = $mataKuliah->semester;
                    $jadwal->jenis_semester = ($semesterNumber % 2 === 1) ? 'ganjil' : 'genap';
                }
            }
        });
    }

    /**
     * Relationships
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }
}
