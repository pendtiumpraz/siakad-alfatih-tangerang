<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenggajianDosen extends Model
{
    protected $table = 'penggajian_dosens';
    
    protected $fillable = [
        'dosen_id',
        'periode',
        'semester_id',
        'total_jam_diajukan',
        'link_rps',
        'link_materi_ajar',
        'link_absensi',
        'catatan_dosen',
        'status',
        'total_jam_disetujui',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
        'jumlah_dibayar',
        'bukti_pembayaran',
        'paid_by',
        'paid_at',
    ];

    protected $casts = [
        'total_jam_diajukan' => 'decimal:2',
        'total_jam_disetujui' => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
        'verified_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Boot method for model events
     */
    protected static function booted()
    {
        // Auto-create pembukuan keuangan when penggajian status becomes 'paid'
        static::updated(function ($penggajian) {
            // Check if status changed to 'paid'
            if ($penggajian->isDirty('status') && $penggajian->status === 'paid') {
                
                // Create pembukuan entry
                PembukuanKeuangan::create([
                    'jenis' => 'pengeluaran',
                    'kategori' => 'gaji_dosen',
                    'sub_kategori' => null,
                    'nominal' => $penggajian->jumlah_dibayar,
                    'semester_id' => $penggajian->semester_id,
                    'keterangan' => "Pembayaran gaji dosen - {$penggajian->dosen->nama_lengkap} (NIDN: {$penggajian->dosen->nidn}) - Periode {$penggajian->periode_formatted}",
                    'tanggal' => $penggajian->paid_at ?? now(),
                    'is_otomatis' => true,
                    'reference_id' => $penggajian->id,
                    'reference_type' => PenggajianDosen::class,
                    'created_by' => $penggajian->paid_by ?? auth()->id() ?? 1,
                ]);
            }
        });
    }

    /**
     * Relationship to Dosen
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Relationship to Semester
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * User who verified this payment
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * User who paid this payment
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        $class = match($this->status) {
            'pending' => 'bg-yellow-500 text-white',
            'verified' => 'bg-green-500 text-white',
            'paid' => 'bg-blue-500 text-white',
            'rejected' => 'bg-red-500 text-white',
            default => 'bg-gray-500 text-white',
        };

        $label = $this->status_label;

        return "<span class=\"inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {$class}\">{$label}</span>";
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Disetujui',
            'paid' => 'Sudah Dibayar',
            'rejected' => 'Ditolak',
            default => 'Unknown',
        };
    }

    /**
     * Get formatted periode (2025-01 -> Januari 2025)
     */
    public function getPeriodeFormattedAttribute(): string
    {
        if (!$this->periode) return '-';
        
        $parts = explode('-', $this->periode);
        if (count($parts) !== 2) return $this->periode;
        
        $year = $parts[0];
        $month = $parts[1];
        
        $months = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
        ];
        
        return ($months[$month] ?? $month) . ' ' . $year;
    }

    /**
     * Get Google Drive URL for bukti pembayaran
     */
    public function getBuktiPembayaranUrlAttribute(): ?string
    {
        if (!$this->bukti_pembayaran) {
            return null;
        }

        // If it's already a URL, return as is (backward compatibility)
        if (filter_var($this->bukti_pembayaran, FILTER_VALIDATE_URL)) {
            return $this->bukti_pembayaran;
        }

        // Otherwise, it's a Google Drive file ID - construct view URL
        return "https://drive.google.com/file/d/{$this->bukti_pembayaran}/view";
    }

    /**
     * Check if can be edited
     */
    public function canBeEdited(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if can be verified
     */
    public function canBeVerified(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if can be paid
     */
    public function canBePaid(): bool
    {
        return $this->status === 'verified';
    }
}
