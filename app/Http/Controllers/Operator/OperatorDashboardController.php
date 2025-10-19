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
        $pembayaranTerlambat = Pembayaran::where('status', 'terlambat')->count();

        $recentPembayaran = Pembayaran::with('mahasiswa')
            ->latest()
            ->take(10)
            ->get();

        return view('operator.dashboard', compact(
            'totalPembayaran',
            'pembayaranPending',
            'pembayaranLunas',
            'pembayaranTerlambat',
            'recentPembayaran'
        ));
    }

    public function docs()
    {
        return view('operator.docs');
    }
}
