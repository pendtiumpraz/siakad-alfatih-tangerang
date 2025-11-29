<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Admin\KeuanganController as AdminKeuanganController;

class KeuanganController extends AdminKeuanganController
{
    /**
     * Display a listing (Operator view)
     */
    public function index()
    {
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        $semesters = \App\Models\Semester::orderBy('tahun_akademik', 'desc')->get();

        $pembukuans = \App\Models\PembukuanKeuangan::with('semester')
            ->when(request('semester_id'), function ($query, $semesterId) {
                return $query->where('semester_id', $semesterId);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('operator.keuangan.index', compact('pembukuans', 'semesters', 'activeSemester'));
    }

    /**
     * Show the form for creating a new resource (Operator view)
     */
    public function create()
    {
        $semesters = \App\Models\Semester::orderBy('tahun_akademik', 'desc')->get();
        return view('operator.keuangan.create', compact('semesters'));
    }

    /**
     * Display the specified resource (Operator view)
     */
    public function show($semester)
    {
        $semester = \App\Models\Semester::findOrFail($semester);
        
        $pembukuans = \App\Models\PembukuanKeuangan::where('semester_id', $semester->id)
            ->orderBy('tanggal', 'desc')
            ->paginate(50);

        return view('operator.keuangan.show', compact('semester', 'pembukuans'));
    }

    /**
     * Show the form for editing the specified resource (Operator view)
     */
    public function edit(string $id)
    {
        $pembukuan = \App\Models\PembukuanKeuangan::findOrFail($id);
        $semesters = \App\Models\Semester::orderBy('tahun_akademik', 'desc')->get();
        return view('operator.keuangan.edit', compact('pembukuan', 'semesters'));
    }

    // store(), update(), destroy() inherited from Admin controller
}
