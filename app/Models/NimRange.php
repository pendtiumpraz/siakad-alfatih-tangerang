<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NimRange extends Model
{
    protected $fillable = [
        'program_studi_id',
        'tahun_masuk',
        'prefix',
        'current_number',
        'max_number',
    ];

    protected $casts = [
        'current_number' => 'integer',
        'max_number' => 'integer',
    ];

    /**
     * Get the program studi
     */
    public function programStudi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    /**
     * Generate next NIM
     */
    public function generateNextNim(): string
    {
        $this->increment('current_number');
        $this->refresh();

        return $this->prefix . str_pad($this->current_number, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if quota is available
     */
    public function hasQuota(): bool
    {
        if (is_null($this->max_number)) {
            return true;
        }

        return $this->current_number < $this->max_number;
    }

    /**
     * Get remaining quota
     */
    public function getRemainingQuotaAttribute(): ?int
    {
        if (is_null($this->max_number)) {
            return null;
        }

        return max(0, $this->max_number - $this->current_number);
    }

    /**
     * Scope to filter by program studi and tahun
     */
    public function scopeForProgramStudiAndYear($query, $programStudiId, $tahun)
    {
        return $query->where('program_studi_id', $programStudiId)
            ->where('tahun_masuk', $tahun);
    }
}
