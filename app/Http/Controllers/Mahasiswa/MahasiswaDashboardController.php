<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Khs;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaDashboardController extends Controller
{
    public function index()
    {
        $mahasiswa = Auth::user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('login')->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Get latest KHS for IP data
        $latestKhs = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->first();

        $ipSemester = $latestKhs->ip_semester ?? 0;
        $ipk = $latestKhs->ip_kumulatif ?? 0;
        $totalSks = $latestKhs->total_sks_lulus ?? 0;

        // Get today's schedule
        $todaySchedule = Jadwal::whereHas('mataKuliah.kurikulum', function($q) use ($mahasiswa) {
                $q->where('program_studi_id', $mahasiswa->program_studi_id);
            })
            ->where('hari', now()->locale('id')->dayName)
            ->with(['mataKuliah', 'dosen', 'ruangan'])
            ->get();

        // Get pending payments
        $pendingPayments = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'pending')
            ->orWhere('status', 'terlambat')
            ->count();

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'ipSemester',
            'ipk',
            'totalSks',
            'todaySchedule',
            'pendingPayments'
        ));
    }
}
