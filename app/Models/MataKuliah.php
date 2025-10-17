<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliah extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kurikulum_id',
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'jenis',
        'deskripsi',
    ];

    protected $casts = [
        'sks' => 'integer',
        'semester' => 'integer',
    ];

    /**
     * Relationships
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }
}
