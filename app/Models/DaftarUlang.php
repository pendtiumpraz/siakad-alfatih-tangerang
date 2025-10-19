<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DaftarUlang extends Model
{
    protected $fillable = [
        'pendaftar_id',
        'nim',
        'biaya_daftar_ulang',
        'pembayaran_status',
        'bukti_pembayaran',
        'verified_by',
        'verified_at',
        'berkas_uploaded',
        'status',
        'completed_at',
        'keterangan',
    ];

    protected $casts = [
        'biaya_daftar_ulang' => 'decimal:2',
        'verified_at' => 'datetime',
        'completed_at' => 'datetime',
        'berkas_uploaded' => 'array',
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
     * Scope to filter by pembayaran status
     */
    public function scopeByPembayaranStatus($query, $status)
    {
        return $query->where('pembayaran_status', $status);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get completed re-registrations
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get pending re-registrations
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments(): bool
    {
        $requiredDocuments = ['ijazah', 'foto', 'ktp', 'kk'];
        $uploadedDocuments = array_keys($this->berkas_uploaded ?? []);

        foreach ($requiredDocuments as $doc) {
            if (!in_array($doc, $uploadedDocuments)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get missing documents
     */
    public function getMissingDocuments(): array
    {
        $requiredDocuments = ['ijazah', 'foto', 'ktp', 'kk'];
        $uploadedDocuments = array_keys($this->berkas_uploaded ?? []);

        return array_diff($requiredDocuments, $uploadedDocuments);
    }
}
