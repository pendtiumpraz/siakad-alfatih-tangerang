<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the operator dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get operator-specific data
        $operator = auth()->user()->operator;

        // Get statistics for the operator dashboard
        $stats = [
            'total_mahasiswa' => Mahasiswa::count(),
            'total_dosen' => Dosen::count(),
            'active_mahasiswa' => Mahasiswa::whereHas('user', function ($query) {
                $query->where('is_active', true);
            })->count(),
            'active_dosen' => Dosen::whereHas('user', function ($query) {
                $query->where('is_active', true);
            })->count(),
        ];

        // Get recent mahasiswa registrations
        $recentMahasiswa = Mahasiswa::with('user')->latest()->take(5)->get();

        return view('operator.dashboard', compact('operator', 'stats', 'recentMahasiswa'));
    }
}
