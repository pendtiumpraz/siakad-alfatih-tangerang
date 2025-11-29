<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Admin\KeuanganController as AdminKeuanganController;
use Illuminate\Http\Request;

class KeuanganController extends AdminKeuanganController
{
    /**
     * Override view path untuk operator
     */
    protected function getViewPath($view)
    {
        return str_replace('admin.keuangan', 'operator.keuangan', $view);
    }

    /**
     * Display a listing (Operator view)
     */
    public function index(Request $request)
    {
        $semesterId = $request->semester_id ?? \App\Models\Semester::where('is_active', true)->first()?->id;
        $semester = \App\Models\Semester::find($semesterId);
        
        if (!$semester) {
            return redirect()->back()->with('error', 'Semester tidak ditemukan.');
        }
        
        // Get summary data using parent method
        $summary = $this->getSummary($semesterId);
        
        // Recent transactions (15 latest)
        $recentTransactions = \App\Models\PembukuanKeuangan::with(['semester', 'creator'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();
        
        // Get all semesters for filter
        $semesters = \App\Models\Semester::orderBy('tahun_akademik', 'desc')->get();
        
        // Chart data for last 6 semesters
        $chartData = $this->getChartData(6);
        
        return view('operator.keuangan.index', compact(
            'semester',
            'summary',
            'recentTransactions',
            'semesters',
            'chartData'
        ));
    }

    /**
     * Show the form for creating a new resource (Operator view)
     */
    public function create(Request $request)
    {
        $semesters = \App\Models\Semester::orderBy('tahun_akademik', 'desc')->get();
        $subKategoriPemasukan = \App\Models\PembukuanKeuangan::getSubKategoriPemasukan();
        $subKategoriPengeluaran = \App\Models\PembukuanKeuangan::getSubKategoriPengeluaran();
        
        return view('operator.keuangan.create', compact(
            'semesters',
            'subKategoriPemasukan',
            'subKategoriPengeluaran'
        ));
    }

    /**
     * Display the specified resource (Operator view)
     */
    public function show($semesterId)
    {
        $semester = \App\Models\Semester::findOrFail($semesterId);
        
        // Get summary for this semester
        $summary = $this->getSummary($semesterId);
        
        // Get all transactions for this semester
        $query = \App\Models\PembukuanKeuangan::with(['creator'])
            ->where('semester_id', $semesterId);
        
        $transactions = $query->orderBy('tanggal', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        return view('operator.keuangan.show', compact(
            'semester',
            'summary',
            'transactions'
        ));
    }

    /**
     * Show the form for editing the specified resource (Operator view)
     */
    public function edit($id)
    {
        $pembukuan = \App\Models\PembukuanKeuangan::findOrFail($id);
        $semesters = \App\Models\Semester::orderBy('tahun_akademik', 'desc')->get();
        $subKategoriPemasukan = \App\Models\PembukuanKeuangan::getSubKategoriPemasukan();
        $subKategoriPengeluaran = \App\Models\PembukuanKeuangan::getSubKategoriPengeluaran();
        
        return view('operator.keuangan.edit', compact(
            'pembukuan',
            'semesters',
            'subKategoriPemasukan',
            'subKategoriPengeluaran'
        ));
    }

    // store(), update(), destroy() inherited from Admin controller
}
