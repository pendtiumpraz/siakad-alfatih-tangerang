<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pembayaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mahasiswa_id',
        'semester_id',
        'operator_id',
        'jenis_pembayaran',
        'jumlah',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'status',
        'bukti_pembayaran',
        'google_drive_file_id',
        'google_drive_link',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
    ];

    /**
     * Boot method for model events
     */
    protected static function booted()
    {
        // Auto-create pembukuan keuangan when pembayaran status becomes 'lunas'
        static::updated(function ($pembayaran) {
            // Check if status changed to 'lunas'
            if ($pembayaran->isDirty('status') && $pembayaran->status === 'lunas') {
                
                // Determine kategori based on jenis_pembayaran
                $kategori = match($pembayaran->jenis_pembayaran) {
                    'spmb', 'daftar_ulang' => 'spmb_daftar_ulang',
                    'spp' => 'spp',
                    default => 'lain_lain'
                };
                
                // Create pembukuan entry
                PembukuanKeuangan::create([
                    'jenis' => 'pemasukan',
                    'kategori' => $kategori,
                    'sub_kategori' => null,
                    'nominal' => $pembayaran->jumlah,
                    'semester_id' => $pembayaran->semester_id,
                    'keterangan' => "Pembayaran {$pembayaran->jenis_pembayaran} - {$pembayaran->mahasiswa->nama_lengkap} (NIM: {$pembayaran->mahasiswa->nim})",
                    'tanggal' => $pembayaran->tanggal_bayar ?? now(),
                    'is_otomatis' => true,
                    'reference_id' => $pembayaran->id,
                    'reference_type' => Pembayaran::class,
                    'created_by' => auth()->id() ?? $pembayaran->operator_id ?? 1,
                ]);
            }
        });
    }

    /**
     * Relationships
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }
}
