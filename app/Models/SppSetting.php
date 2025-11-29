<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SppSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_studi_id',
        'nominal',
        'rekening_nama',
        'rekening_nomor',
        'rekening_bank',
        'contact_whatsapp',
        'contact_email',
        'is_active',
        'jatuh_tempo_hari',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'is_active' => 'boolean',
        'jatuh_tempo_hari' => 'integer',
    ];

    /**
     * Relationships
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    /**
     * Get active SPP setting for a specific program studi
     */
    public static function getActiveForProdi($prodiId = null)
    {
        if ($prodiId) {
            return self::where('program_studi_id', $prodiId)
                ->where('is_active', true)
                ->first();
        }

        // Get default setting (null program_studi_id = universal)
        return self::whereNull('program_studi_id')
            ->where('is_active', true)
            ->first();
    }
}
