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
        // Foto
        'foto',
        'google_drive_file_id',
        'google_drive_link',
        // Ijazah
        'ijazah',
        'ijazah_google_drive_id',
        'ijazah_google_drive_link',
        // Transkrip
        'transkrip_nilai',
        'transkrip_google_drive_id',
        'transkrip_google_drive_link',
        // KTP
        'ktp',
        'ktp_google_drive_id',
        'ktp_google_drive_link',
        // Kartu Keluarga
        'kartu_keluarga',
        'kk_google_drive_id',
        'kk_google_drive_link',
        // Akta Kelahiran
        'akta_kelahiran',
        'akta_google_drive_id',
        'akta_google_drive_link',
        // SKTM
        'sktm',
        'sktm_google_drive_id',
        'sktm_google_drive_link',
        // Surat Bukti Mengajar (khusus guru)
        'surat_mengajar',
        'surat_mengajar_google_drive_id',
        'surat_mengajar_google_drive_link',
        // Surat Keterangan RT Dhuafa (khusus dhuafa)
        'surat_rt_dhuafa',
        'surat_rt_dhuafa_google_drive_id',
        'surat_rt_dhuafa_google_drive_link',
        // Surat Keterangan Yatim dari RT (khusus yatim)
        'surat_rt_yatim',
        'surat_rt_yatim_google_drive_id',
        'surat_rt_yatim_google_drive_link',
        // Sertifikat Quran (khusus penghafal quran - minimal juz 30)
        'sertifikat_quran',
        'sertifikat_quran_google_drive_id',
        'sertifikat_quran_google_drive_link',
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
     * Generate unique registration number with random suffix for security
     * Format: REG{YEAR}{RANDOM_6_CHARS} e.g., REG2025A1B2C3
     */
    public static function generateNomorPendaftaran(): string
    {
        $year = date('Y');
        $maxAttempts = 10;
        $attempt = 0;

        do {
            // Generate random 6-character alphanumeric string (uppercase)
            $randomSuffix = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 6));
            $nomorPendaftaran = 'REG' . $year . $randomSuffix;

            // Check if this number already exists
            $exists = static::where('nomor_pendaftaran', $nomorPendaftaran)->exists();

            $attempt++;

            // If unique, return it
            if (!$exists) {
                return $nomorPendaftaran;
            }

        } while ($attempt < $maxAttempts);

        // Fallback: if random fails after max attempts, use timestamp-based
        return 'REG' . $year . strtoupper(substr(md5(microtime(true) . rand()), 0, 6));
    }

    /**
     * Accessor for no_telepon (alias for phone field)
     */
    public function getNoTeleponAttribute(): ?string
    {
        return $this->attributes['phone'] ?? null;
    }

    /**
     * Get embeddable photo URL
     */
    public function getFotoUrlAttribute(): ?string
    {
        // Use direct download URL if we have Google Drive file ID
        if ($this->google_drive_file_id) {
            return "https://drive.usercontent.google.com/download?id={$this->google_drive_file_id}&export=view&authuser=0";
        }

        $url = $this->google_drive_link ?? $this->foto;

        if (!$url) {
            return null;
        }

        // Extract file ID from Google Drive link
        if (str_contains($url, 'drive.google.com')) {
            preg_match('/\/d\/([^\/]+)/', $url, $matches);
            if (isset($matches[1])) {
                // Use direct download URL format
                return "https://drive.usercontent.google.com/download?id={$matches[1]}&export=view&authuser=0";
            }
        }

        // Local storage file
        if (!str_starts_with($url, 'http')) {
            return \Storage::url($url);
        }

        return $url;
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
     * Get jurusan (alias for program studi pilihan 1)
     */
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_pilihan_1');
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
