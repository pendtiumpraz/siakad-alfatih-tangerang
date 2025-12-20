<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JalurSeleksi extends Model
{
    protected $fillable = [
        'kode_jalur',
        'nama',
        'deskripsi',
        'biaya_pendaftaran',
        'is_active',
        'kuota_total',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'biaya_pendaftaran' => 'decimal:2',
        'is_active' => 'boolean',
        'kuota_total' => 'integer',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get all pendaftars for this jalur seleksi
     */
    public function pendaftars(): HasMany
    {
        return $this->hasMany(Pendaftar::class);
    }

    /**
     * Get all pembayaran pendaftarans for this jalur seleksi
     */
    public function pembayaranPendaftarans(): HasMany
    {
        return $this->hasMany(PembayaranPendaftaran::class);
    }

    /**
     * Scope to get only active jalur seleksi
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
