<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kurikulum extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'program_studi_id',
        'nama_kurikulum',
        'tahun_mulai',
        'tahun_selesai',
        'is_active',
        'total_sks',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tahun_mulai' => 'integer',
        'tahun_selesai' => 'integer',
        'total_sks' => 'integer',
    ];

    /**
     * Relationships
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function mataKuliahs()
    {
        return $this->hasMany(MataKuliah::class);
    }
}
