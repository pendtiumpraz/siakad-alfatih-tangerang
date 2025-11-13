<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramStudi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
        'akreditasi',
        'ketua_prodi_id',
        'is_active',
    ];

    /**
     * Relationships
     */
    public function ketuaProdi()
    {
        return $this->belongsTo(Dosen::class, 'ketua_prodi_id');
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function kurikulums()
    {
        return $this->hasMany(Kurikulum::class);
    }

    public function pendaftarsPilihan1()
    {
        return $this->hasMany(Pendaftar::class, 'program_studi_pilihan_1');
    }

    public function pendaftarsPilihan2()
    {
        return $this->hasMany(Pendaftar::class, 'program_studi_pilihan_2');
    }

    public function nimRanges()
    {
        return $this->hasMany(NimRange::class);
    }

    public function dosens()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_program_studi')
            ->withTimestamps();
    }
}
