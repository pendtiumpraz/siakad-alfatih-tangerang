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
        'jenis', // offline or online
        'fasilitas',
        'is_available',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'is_available' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
