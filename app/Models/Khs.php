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
        'ip',
        'ipk',
        'total_sks_semester',
        'total_sks_kumulatif',
    ];

    protected $casts = [
        'ip' => 'decimal:2',
        'ipk' => 'decimal:2',
        'total_sks_semester' => 'integer',
        'total_sks_kumulatif' => 'integer',
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
