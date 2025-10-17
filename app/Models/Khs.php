<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Khs extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'total_sks',
        'total_sks_lulus',
        'ip_semester',
        'ip_kumulatif',
        'status_semester',
    ];

    protected $casts = [
        'total_sks' => 'integer',
        'total_sks_lulus' => 'integer',
        'ip_semester' => 'decimal:2',
        'ip_kumulatif' => 'decimal:2',
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
}
