<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembukuanKeuangan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'semester_id',
        'jenis',
        'kategori',
        'sub_kategori',
        'nominal',
        'keterangan',
        'tanggal',
        'is_otomatis',
        'reference_id',
        'reference_type',
        'bukti_file',
        'google_drive_file_id',
        'google_drive_link',
        'created_by',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal' => 'date',
        'is_otomatis' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Accessors
     */
    public function getJenisLabelAttribute(): string
    {
        return match($this->jenis) {
            'pemasukan' => 'Pemasukan',
            'pengeluaran' => 'Pengeluaran',
            default => '-'
        };
    }

    public function getKategoriLabelAttribute(): string
    {
        return match($this->kategori) {
            'spmb_daftar_ulang' => 'SPMB & Daftar Ulang',
            'spp' => 'SPP',
            'gaji_dosen' => 'Gaji Dosen',
            'lain_lain' => 'Lain-lain',
            default => '-'
        };
    }

    public function getNominalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->nominal, 0, ',', '.');
    }

    /**
     * Scopes
     */
    public function scopePemasukan($query)
    {
        return $query->where('jenis', 'pemasukan');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('jenis', 'pengeluaran');
    }

    public function scopeSemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    public function scopeOtomatis($query)
    {
        return $query->where('is_otomatis', true);
    }

    public function scopeManual($query)
    {
        return $query->where('is_otomatis', false);
    }

    /**
     * Static helper untuk sub-kategori options
     */
    public static function getSubKategoriPemasukan(): array
    {
        return [
            'donasi' => 'Donasi',
            'investor' => 'Investor',
            'hibah' => 'Hibah',
            'sponsorship' => 'Sponsorship',
            'sewa_rental' => 'Sewa/Rental',
            'lainnya' => 'Lainnya',
        ];
    }

    public static function getSubKategoriPengeluaran(): array
    {
        return [
            'pembangunan' => 'Pembangunan',
            'operasional' => 'Operasional',
            'pemeliharaan' => 'Pemeliharaan',
            'utilitas' => 'Utilitas (Listrik/Air/Internet)',
            'atk_perlengkapan' => 'ATK & Perlengkapan',
            'transport_dinas' => 'Transport & Perjalanan Dinas',
            'kegiatan_kampus' => 'Kegiatan Mahasiswa/Kampus',
            'lainnya' => 'Lainnya',
        ];
    }
}
