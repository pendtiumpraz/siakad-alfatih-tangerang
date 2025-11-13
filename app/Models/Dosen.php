<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'nidn',
        'nama_lengkap',
        'gelar_depan',
        'gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_telepon',
        'email',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'jadwals')
            ->withPivot('semester_id', 'ruangan_id', 'hari', 'jam_mulai', 'jam_selesai')
            ->withTimestamps();
    }

    public function mahasiswaBimbingan()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_wali_id');
    }

    public function programStudiAsKetuaProdi()
    {
        return $this->hasOne(ProgramStudi::class, 'ketua_prodi_id');
    }

    public function programStudis()
    {
        return $this->belongsToMany(ProgramStudi::class, 'dosen_program_studi')
            ->withTimestamps();
    }

    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'dosen_mata_kuliah')
            ->withTimestamps();
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_wali_id');
    }
}
