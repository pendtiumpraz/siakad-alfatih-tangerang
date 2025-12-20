<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'kapasitas',
        'jenis', // offline or online (legacy)
        'tipe', // daring or luring
        'url', // for daring rooms
        'fasilitas',
        'is_available',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * Check if room is online/daring
     */
    public function isDaring(): bool
    {
        return $this->tipe === 'daring';
    }

    /**
     * Check if room is offline/luring
     */
    public function isLuring(): bool
    {
        return $this->tipe === 'luring';
    }

    /**
     * Relationships
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
