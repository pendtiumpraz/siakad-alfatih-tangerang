<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumumans';

    protected $fillable = [
        'judul',
        'isi',
        'tipe',
        'pembuat_id',
        'pembuat_role',
        'untuk_mahasiswa',
        'is_active',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'untuk_mahasiswa' => 'boolean',
        'is_active' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get the user who created this pengumuman
     */
    public function pembuat()
    {
        return $this->belongsTo(User::class, 'pembuat_id');
    }

    /**
     * Get the reads for this pengumuman
     */
    public function reads()
    {
        return $this->hasMany(PengumumanRead::class);
    }

    /**
     * Scope untuk pengumuman aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('tanggal_mulai')
                  ->orWhere('tanggal_mulai', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('tanggal_selesai')
                  ->orWhere('tanggal_selesai', '>=', now());
            });
    }

    /**
     * Scope untuk pengumuman mahasiswa
     */
    public function scopeForMahasiswa($query)
    {
        return $query->where('untuk_mahasiswa', true);
    }
}
