<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DaftarUlang extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pendaftar_id',
        'status',
        'nim_sementara',
        'biaya_daftar_ulang',
        'metode_pembayaran',
        'nomor_referensi',
        'bukti_pembayaran',
        'dokumen_tambahan',
        'keterangan',
        'tanggal_verifikasi',
        'verified_by',
        'mahasiswa_user_id',
    ];

    protected $casts = [
        'biaya_daftar_ulang' => 'decimal:2',
        'dokumen_tambahan' => 'array',
        'tanggal_verifikasi' => 'datetime',
    ];

    /**
     * Get the pendaftar
     */
    public function pendaftar(): BelongsTo
    {
        return $this->belongsTo(Pendaftar::class);
    }

    /**
     * Get the verifier user
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get the mahasiswa user (created after verification)
     */
    public function mahasiswaUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_user_id');
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get pending daftar ulang
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get verified daftar ulang
     */
    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    /**
     * Check if payment proof is uploaded
     */
    public function hasPaymentProof(): bool
    {
        return !empty($this->bukti_pembayaran);
    }

    /**
     * Check if verified
     */
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    /**
     * Check if user account has been created
     */
    public function hasUserAccount(): bool
    {
        return !empty($this->mahasiswa_user_id);
    }
}
