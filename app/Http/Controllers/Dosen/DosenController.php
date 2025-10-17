<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    /**
     * Display the dosen dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Get statistics for dashboard
        $totalJadwal = Jadwal::where('dosen_id', $dosen->id)->count();
        $totalMahasiswa = Nilai::where('dosen_id', $dosen->id)
            ->distinct('mahasiswa_id')
            ->count('mahasiswa_id');
        $totalMataKuliah = Jadwal::where('dosen_id', $dosen->id)
            ->distinct('mata_kuliah_id')
            ->count('mata_kuliah_id');

        // Get recent jadwal
        $recentJadwal = Jadwal::where('dosen_id', $dosen->id)
            ->with(['mataKuliah', 'ruangan', 'semester'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();

        return view('dosen.dashboard', compact(
            'dosen',
            'totalJadwal',
            'totalMahasiswa',
            'totalMataKuliah',
            'recentJadwal'
        ));
    }
}
