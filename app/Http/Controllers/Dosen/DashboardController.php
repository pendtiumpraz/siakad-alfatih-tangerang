<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dosen dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get dosen-specific data
        $dosen = auth()->user()->dosen;

        // Get statistics for the dosen dashboard
        $stats = [
            'total_mata_kuliah' => $dosen->mataKuliah()->count() ?? 0,
            'total_kelas' => $dosen->kelas()->count() ?? 0,
        ];

        // Get dosen's mata kuliah
        $mataKuliah = $dosen->mataKuliah ?? collect();

        // Get dosen's kelas
        $kelas = $dosen->kelas ?? collect();

        return view('dosen.dashboard', compact('dosen', 'stats', 'mataKuliah', 'kelas'));
    }
}
