<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembayaranPendaftaran extends Model
{
    protected $fillable = [
        'pendaftar_id',
        'jalur_seleksi_id',
        'jumlah',
        'metode_pembayaran',
        'nomor_referensi',
        'bukti_pembayaran',
        'status',
        'verified_by',
        'verified_at',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'verified_at' => 'datetime',
    ];

    /**
     * Get the pendaftar
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Get the jalur seleksi
     */
    public function jalurSeleksi(): BelongsTo
    {
        return $this->belongsTo(JalurSeleksi::class);
    }

    /**
     * Get the verifier user
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get verified payments
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }
}
