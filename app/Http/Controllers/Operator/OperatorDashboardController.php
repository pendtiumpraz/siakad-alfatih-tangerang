<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class OperatorDashboardController extends Controller
{
    public function index()
    {
        $totalPembayaran = Pembayaran::count();
        $pembayaranPending = Pembayaran::where('status', 'pending')->count();
        $pembayaranLunas = Pembayaran::where('status', 'lunas')->count();
        $pembayaranTerlambat = Pembayaran::where('status', 'terlambat')
            ->orWhere(function($query) {
                $query->where('status', 'belum_lunas')
                      ->where('tanggal_jatuh_tempo', '<', now());
            })
            ->count();

        $pendingPembayaran = Pembayaran::with('mahasiswa')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentPembayaran = Pembayaran::with('mahasiswa')
            ->where('status', 'lunas')
            ->latest('tanggal_bayar')
            ->take(5)
            ->get();

        $totalPendapatan = Pembayaran::where('status', 'lunas')
            ->sum('jumlah');

        $currentYear = now()->year;
        $ganjilSemester = \App\Models\Semester::where('jenis', 'ganjil')
            ->whereYear('tanggal_mulai', $currentYear)
            ->first();
        $genapSemester = \App\Models\Semester::where('jenis', 'genap')
            ->whereYear('tanggal_mulai', $currentYear)
            ->first();

        $pendapatanGanjil = $ganjilSemester 
            ? Pembayaran::where('status', 'lunas')
                ->where('semester_id', $ganjilSemester->id)
                ->sum('jumlah')
            : 0;

        $pendapatanGenap = $genapSemester
            ? Pembayaran::where('status', 'lunas')
                ->where('semester_id', $genapSemester->id)
                ->sum('jumlah')
            : 0;

        $currentMonth = now()->month;
        $currentYearForStats = now()->year;
        
        $pembayaranBulanIni = [
            'spp' => Pembayaran::where('status', 'lunas')
                ->where('jenis_pembayaran', 'spp')
                ->whereMonth('tanggal_bayar', $currentMonth)
                ->whereYear('tanggal_bayar', $currentYearForStats)
                ->count(),
            'ukt' => Pembayaran::where('status', 'lunas')
                ->where('jenis_pembayaran', 'ukt')
                ->whereMonth('tanggal_bayar', $currentMonth)
                ->whereYear('tanggal_bayar', $currentYearForStats)
                ->count(),
            'daftar_ulang' => Pembayaran::where('status', 'lunas')
                ->where('jenis_pembayaran', 'daftar_ulang')
                ->whereMonth('tanggal_bayar', $currentMonth)
                ->whereYear('tanggal_bayar', $currentYearForStats)
                ->count(),
            'wisuda' => Pembayaran::where('status', 'lunas')
                ->where('jenis_pembayaran', 'wisuda')
                ->whereMonth('tanggal_bayar', $currentMonth)
                ->whereYear('tanggal_bayar', $currentYearForStats)
                ->count(),
        ];

        return view('operator.dashboard', compact(
            'totalPembayaran',
            'pembayaranPending',
            'pembayaranLunas',
            'pembayaranTerlambat',
            'pendingPembayaran',
            'recentPembayaran',
            'totalPendapatan',
            'pendapatanGanjil',
            'pendapatanGenap',
            'pembayaranBulanIni'
        ));
    }

    public function docs()
    {
        return view('operator.docs');
    }
}
