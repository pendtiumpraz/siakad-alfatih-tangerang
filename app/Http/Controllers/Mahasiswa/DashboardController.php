<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the mahasiswa dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get mahasiswa-specific data
        $mahasiswa = auth()->user()->mahasiswa;

        // Get statistics for the mahasiswa dashboard
        $stats = [
            'ipk' => $mahasiswa->ipk ?? 0,
            'total_sks' => $mahasiswa->total_sks ?? 0,
            'semester' => $mahasiswa->semester ?? 1,
        ];

        // Get mahasiswa's KRS (Kartu Rencana Studi)
        $krs = $mahasiswa->krs ?? collect();

        // Get mahasiswa's KHS (Kartu Hasil Studi)
        $khs = $mahasiswa->khs ?? collect();

        return view('mahasiswa.dashboard', compact('mahasiswa', 'stats', 'krs', 'khs'));
    }
}
