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
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'grade',
        'bobot',
        'status',
    ];

    protected $casts = [
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
}
