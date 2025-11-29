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
