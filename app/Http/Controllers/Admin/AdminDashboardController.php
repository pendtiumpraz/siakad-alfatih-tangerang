<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Operator;
use App\Models\Semester;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalMahasiswa = Mahasiswa::count();
        $totalDosen = Dosen::count();
        $totalOperator = Operator::count();

        $recentUsers = User::latest()
            ->take(5)
            ->get();

        $activeSemester = Semester::where('is_active', true)->first();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalMahasiswa',
            'totalDosen',
            'totalOperator',
            'recentUsers',
            'activeSemester'
        ));
    }

    public function docs()
    {
        return view('admin.docs');
    }
}
