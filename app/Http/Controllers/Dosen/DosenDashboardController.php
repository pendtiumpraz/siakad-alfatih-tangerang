<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenDashboardController extends Controller
{
    public function index()
    {
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            return redirect()->route('login')->with('error', 'Data dosen tidak ditemukan.');
        }

        $totalJadwal = Jadwal::where('dosen_id', $dosen->id)->count();
        $totalMahasiswa = Nilai::where('dosen_id', $dosen->id)
            ->distinct('mahasiswa_id')
            ->count('mahasiswa_id');

        $pendingNilai = Nilai::where('dosen_id', $dosen->id)
            ->where('status', 'pending')
            ->count();

        $todaySchedule = Jadwal::where('dosen_id', $dosen->id)
            ->where('hari', now()->locale('id')->dayName)
            ->with(['mataKuliah', 'ruangan'])
            ->get();

        $recentGrades = Nilai::where('dosen_id', $dosen->id)
            ->with(['mahasiswa', 'mataKuliah'])
            ->latest()
            ->take(5)
            ->get();

        return view('dosen.dashboard', compact(
            'totalJadwal',
            'totalMahasiswa',
            'pendingNilai',
            'todaySchedule',
            'recentGrades'
        ));
    }
}
