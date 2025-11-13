<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_semester',
        'tahun_akademik',
        'jenis',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'khs_generate_date',
        'khs_auto_generate',
        'khs_show_ketua_prodi_signature',
        'khs_show_dosen_pa_signature',
        'khs_status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
        'khs_generate_date' => 'date',
        'khs_auto_generate' => 'boolean',
        'khs_show_ketua_prodi_signature' => 'boolean',
        'khs_show_dosen_pa_signature' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function khs()
    {
        return $this->hasMany(Khs::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(Pembayaran::class);
    }
}
