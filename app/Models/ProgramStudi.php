<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramStudi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'jenjang',
        'akreditasi',
    ];

    /**
     * Relationships
     */
    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function kurikulums()
    {
        return $this->hasMany(Kurikulum::class);
    }
}
