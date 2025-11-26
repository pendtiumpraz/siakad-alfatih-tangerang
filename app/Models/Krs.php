<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';

    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'mata_kuliah_id',
        'is_mengulang',
        'status',
        'keterangan',
        'submitted_at',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'is_mengulang' => 'boolean',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scopes
     */
    public function scopeByMahasiswa($query, $mahasiswaId)
    {
        return $query->where('mahasiswa_id', $mahasiswaId);
    }

    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeMengulang($query)
    {
        return $query->where('is_mengulang', true);
    }

    /**
     * Get total SKS for KRS
     */
    public static function getTotalSks($mahasiswaId, $semesterId)
    {
        return self::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->with('mataKuliah')
            ->get()
            ->sum(function ($krs) {
                return $krs->mataKuliah->sks ?? 0;
            });
    }
}
