<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendaftar extends Model
{
    protected $fillable = [
        'nomor_pendaftaran',
        'nama',
        'email',
        'phone',
        'nik',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'alamat',
        'kelurahan',
        'kecamatan',
        'kota_kabupaten',
        'provinsi',
        'kode_pos',
        'asal_sekolah',
        'tahun_lulus',
        'nilai_rata_rata',
        'nama_ayah',
        'nama_ibu',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'phone_orangtua',
        'jalur_seleksi_id',
        'program_studi_pilihan_1',
        'program_studi_pilihan_2',
        'status',
        'keterangan',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'nilai_rata_rata' => 'decimal:2',
    ];

    /**
     * Generate unique registration number
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pendaftar) {
            if (empty($pendaftar->nomor_pendaftaran)) {
                $pendaftar->nomor_pendaftaran = static::generateNomorPendaftaran();
            }
        });
    }

    /**
     * Generate unique registration number
     */
    public static function generateNomorPendaftaran(): string
    {
        $year = date('Y');
        $lastNumber = static::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastNumber ? ((int) substr($lastNumber->nomor_pendaftaran, -5)) + 1 : 1;

        return 'REG' . $year . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the jalur seleksi
     */
    public function jalurSeleksi(): BelongsTo
    {
        return $this->belongsTo(JalurSeleksi::class);
    }

    /**
     * Get program studi pilihan 1
     */
    public function programStudiPilihan1(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_pilihan_1');
    }

    /**
     * Get program studi pilihan 2
     */
    public function programStudiPilihan2(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_pilihan_2');
    }

    /**
     * Get pembayaran pendaftaran records
     */
    public function pembayaranPendaftarans(): HasMany
    {
        return $this->hasMany(PembayaranPendaftaran::class);
    }

    /**
     * Get daftar ulang record
     */
    public function daftarUlang(): HasOne
    {
        return $this->hasOne(DaftarUlang::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by jalur seleksi
     */
    public function scopeByJalurSeleksi($query, $jalurSeleksiId)
    {
        return $query->where('jalur_seleksi_id', $jalurSeleksiId);
    }

    /**
     * Scope to filter by tahun lulus
     */
    public function scopeByTahunLulus($query, $tahun)
    {
        return $query->where('tahun_lulus', $tahun);
    }
}
