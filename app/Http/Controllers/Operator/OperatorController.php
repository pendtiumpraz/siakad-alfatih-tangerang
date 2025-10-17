<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OperatorController extends Controller
{
    /**
     * Display operator dashboard with financial summary
     */
    public function index()
    {
        // Get current year and month
        $currentYear = date('Y');
        $currentMonth = date('m');

        // Total payments summary
        $totalPembayaran = Pembayaran::count();
        $totalLunas = Pembayaran::where('status', 'lunas')->count();
        $totalPending = Pembayaran::where('status', 'pending')->count();
        $totalBelumLunas = Pembayaran::where('status', 'belum_lunas')->count();

        // Financial summary
        $totalNominal = Pembayaran::sum('jumlah');
        $totalNominalLunas = Pembayaran::where('status', 'lunas')->sum('jumlah');
        $totalNominalPending = Pembayaran::where('status', 'pending')->sum('jumlah');
        $totalNominalBelumLunas = Pembayaran::where('status', 'belum_lunas')->sum('jumlah');

        // Monthly summary
        $monthlyTotal = Pembayaran::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->sum('jumlah');

        $monthlyLunas = Pembayaran::whereYear('created_at', $currentYear)
            ->whereMonth('created_at', $currentMonth)
            ->where('status', 'lunas')
            ->sum('jumlah');

        // Recent payments (last 10)
        $recentPayments = Pembayaran::with(['mahasiswa.programStudi', 'semester', 'operator'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Payments by type
        $paymentsByType = Pembayaran::select('jenis_pembayaran', DB::raw('count(*) as total'), DB::raw('sum(jumlah) as nominal'))
            ->groupBy('jenis_pembayaran')
            ->get();

        // Overdue payments (past due date but not lunas)
        $overduePayments = Pembayaran::where('tanggal_jatuh_tempo', '<', now())
            ->whereIn('status', ['pending', 'belum_lunas'])
            ->count();

        $overdueNominal = Pembayaran::where('tanggal_jatuh_tempo', '<', now())
            ->whereIn('status', ['pending', 'belum_lunas'])
            ->sum('jumlah');

        // Active students count
        $totalMahasiswaAktif = Mahasiswa::where('status', 'aktif')->count();

        // Chart data for last 6 months
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $monthName = date('M Y', strtotime("-$i months"));

            $lunas = Pembayaran::where('status', 'lunas')
                ->where(DB::raw("DATE_FORMAT(tanggal_bayar, '%Y-%m')"), $month)
                ->sum('jumlah');

            $chartData[] = [
                'month' => $monthName,
                'amount' => $lunas,
            ];
        }

        return view('operator.dashboard', compact(
            'totalPembayaran',
            'totalLunas',
            'totalPending',
            'totalBelumLunas',
            'totalNominal',
            'totalNominalLunas',
            'totalNominalPending',
            'totalNominalBelumLunas',
            'monthlyTotal',
            'monthlyLunas',
            'recentPayments',
            'paymentsByType',
            'overduePayments',
            'overdueNominal',
            'totalMahasiswaAktif',
            'chartData'
        ));
    }
}
