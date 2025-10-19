<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JalurSeleksi extends Model
{
    protected $fillable = [
        'nama',
        'deskripsi',
        'biaya_pendaftaran',
        'is_active',
        'kuota_total',
    ];

    protected $casts = [
        'biaya_pendaftaran' => 'decimal:2',
        'is_active' => 'boolean',
        'kuota_total' => 'integer',
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
