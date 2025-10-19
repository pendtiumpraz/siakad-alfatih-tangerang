<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengumumanRead extends Model
{
    protected $fillable = [
        'pengumuman_id',
        'mahasiswa_id',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    /**
     * Get the pengumuman that was read
     */
    public function pengumuman()
    {
        return $this->belongsTo(Pengumuman::class);
    }

    /**
     * Get the mahasiswa who read this
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
