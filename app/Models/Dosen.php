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
        'foto',
        'nama_bank',
        'nomor_rekening',
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

    public function penggajians()
    {
        return $this->hasMany(PenggajianDosen::class);
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

    /**
     * Get program studi dari jadwal yang dimiliki dosen
     * Program studi didapat dari mata kuliah di jadwal
     */
    public function getProgramStudisFromJadwal()
    {
        return $this->jadwals()
            ->with('mataKuliah.kurikulum.programStudi')
            ->get()
            ->pluck('mataKuliah.kurikulum.programStudi')
            ->filter()
            ->unique('id')
            ->values();
    }

    /**
     * Get mata kuliah dari jadwal yang dimiliki dosen
     */
    public function getMataKuliahsFromJadwal()
    {
        return $this->jadwals()
            ->with('mataKuliah')
            ->get()
            ->pluck('mataKuliah')
            ->filter()
            ->unique('id')
            ->values();
    }
}
