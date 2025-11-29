<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembukuanKeuangan;
use App\Models\Semester;
use App\Models\Pembayaran;
use App\Models\PenggajianDosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class KeuanganController extends Controller
{
    /**
     * Dashboard keuangan dengan summary
     */
    public function index(Request $request)
    {
        $semesterId = $request->semester_id ?? Semester::where('is_active', true)->first()?->id;
        $semester = Semester::find($semesterId);
        
        if (!$semester) {
            return redirect()->back()->with('error', 'Semester tidak ditemukan.');
        }
        
        // Get summary data
        $summary = $this->getSummary($semesterId);
        
        // Recent transactions (15 latest)
        $recentTransactions = PembukuanKeuangan::with(['semester', 'creator'])
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();
        
        // Get all semesters for filter
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        
        // Chart data for last 6 semesters
        $chartData = $this->getChartData(6);
        
        return view('admin.keuangan.index', compact(
            'semester',
            'summary',
            'recentTransactions',
            'semesters',
            'chartData'
        ));
    }
    
    /**
     * Detail per semester
     */
    public function show($semesterId)
    {
        $semester = Semester::findOrFail($semesterId);
        
        // Get summary for this semester
        $summary = $this->getSummary($semesterId);
        
        // Get all transactions for this semester
        $query = PembukuanKeuangan::with(['creator'])
            ->where('semester_id', $semesterId);
        
        $transactions = $query->orderBy('tanggal', 'desc')
            ->paginate(20)
            ->withQueryString();
        
        return view('admin.keuangan.show', compact(
            'semester',
            'summary',
            'transactions'
        ));
    }
    
    /**
     * Show form for manual input
     */
    public function create(Request $request)
    {
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        $subKategoriPemasukan = PembukuanKeuangan::getSubKategoriPemasukan();
        $subKategoriPengeluaran = PembukuanKeuangan::getSubKategoriPengeluaran();
        
        return view('admin.keuangan.create', compact(
            'semesters',
            'subKategoriPemasukan',
            'subKategoriPengeluaran'
        ));
    }
    
    /**
     * Store manual transaction
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'sub_kategori' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:1000',
            'semester_id' => 'nullable|exists:semesters,id',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle file upload
        $buktiFile = null;
        if ($request->hasFile('bukti_file')) {
            $file = $request->file('bukti_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $buktiFile = $file->storeAs('pembukuan', $fileName, 'public');
        }
        
        // Create transaction
        PembukuanKeuangan::create([
            'jenis' => $request->jenis,
            'kategori' => 'lain_lain',
            'sub_kategori' => $request->sub_kategori,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'semester_id' => $request->semester_id,
            'is_otomatis' => false,
            'bukti_file' => $buktiFile,
            'created_by' => auth()->id(),
        ]);
        
        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }
    
    /**
     * Show edit form
     */
    public function edit($id)
    {
        $transaction = PembukuanKeuangan::findOrFail($id);
        
        // Only allow edit for manual transactions
        if ($transaction->is_otomatis) {
            return redirect()->back()
                ->with('error', 'Transaksi otomatis tidak dapat diedit.');
        }
        
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        $subKategoriPemasukan = PembukuanKeuangan::getSubKategoriPemasukan();
        $subKategoriPengeluaran = PembukuanKeuangan::getSubKategoriPengeluaran();
        
        return view('admin.keuangan.edit', compact(
            'transaction',
            'semesters',
            'subKategoriPemasukan',
            'subKategoriPengeluaran'
        ));
    }
    
    /**
     * Update transaction
     */
    public function update(Request $request, $id)
    {
        $transaction = PembukuanKeuangan::findOrFail($id);
        
        // Only allow update for manual transactions
        if ($transaction->is_otomatis) {
            return redirect()->back()
                ->with('error', 'Transaksi otomatis tidak dapat diedit.');
        }
        
        $validator = Validator::make($request->all(), [
            'jenis' => 'required|in:pemasukan,pengeluaran',
            'sub_kategori' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:1000',
            'semester_id' => 'nullable|exists:semesters,id',
            'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Handle file upload
        if ($request->hasFile('bukti_file')) {
            // Delete old file
            if ($transaction->bukti_file) {
                Storage::disk('public')->delete($transaction->bukti_file);
            }
            
            $file = $request->file('bukti_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $buktiFile = $file->storeAs('pembukuan', $fileName, 'public');
            $transaction->bukti_file = $buktiFile;
        }
        
        // Update transaction
        $transaction->update([
            'jenis' => $request->jenis,
            'sub_kategori' => $request->sub_kategori,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
            'semester_id' => $request->semester_id,
        ]);
        
        return redirect()->route('admin.keuangan.index')
            ->with('success', 'Transaksi berhasil diupdate.');
    }
    
    /**
     * Delete transaction
     */
    public function destroy($id)
    {
        $transaction = PembukuanKeuangan::findOrFail($id);
        
        // Only allow delete for manual transactions
        if ($transaction->is_otomatis) {
            return redirect()->back()
                ->with('error', 'Transaksi otomatis tidak dapat dihapus.');
        }
        
        // Delete file if exists
        if ($transaction->bukti_file) {
            Storage::disk('public')->delete($transaction->bukti_file);
        }
        
        $transaction->delete();
        
        return redirect()->back()
            ->with('success', 'Transaksi berhasil dihapus.');
    }
    
    /**
     * Get summary data for semester
     */
    private function getSummary($semesterId)
    {
        // Saldo awal (could be from previous semester or manual input)
        // For now, calculate from all previous semesters
        $semester = Semester::findOrFail($semesterId);
        
        // Get all semesters before this one
        $previousSemesters = Semester::where('tahun_akademik', '<', $semester->tahun_akademik)
            ->orWhere(function($q) use ($semester) {
                $q->where('tahun_akademik', '=', $semester->tahun_akademik)
                  ->where('jenis', '<', $semester->jenis);
            })
            ->pluck('id');
        
        $saldoAwal = 0;
        if ($previousSemesters->isNotEmpty()) {
            $totalPemasukanSebelumnya = PembukuanKeuangan::whereIn('semester_id', $previousSemesters)
                ->where('jenis', 'pemasukan')
                ->sum('nominal');
            
            $totalPengeluaranSebelumnya = PembukuanKeuangan::whereIn('semester_id', $previousSemesters)
                ->where('jenis', 'pengeluaran')
                ->sum('nominal');
            
            $saldoAwal = $totalPemasukanSebelumnya - $totalPengeluaranSebelumnya;
        }
        
        // Current semester data
        $pemasukanSPMB = PembukuanKeuangan::where('semester_id', $semesterId)
            ->where('kategori', 'spmb_daftar_ulang')
            ->sum('nominal');
        
        $pemasukanSPP = PembukuanKeuangan::where('semester_id', $semesterId)
            ->where('kategori', 'spp')
            ->sum('nominal');
        
        $pemasukanLainLain = PembukuanKeuangan::where('semester_id', $semesterId)
            ->where('kategori', 'lain_lain')
            ->where('jenis', 'pemasukan')
            ->sum('nominal');
        
        $totalPemasukan = $pemasukanSPMB + $pemasukanSPP + $pemasukanLainLain;
        
        $pengeluaranGaji = PembukuanKeuangan::where('semester_id', $semesterId)
            ->where('kategori', 'gaji_dosen')
            ->sum('nominal');
        
        $pengeluaranLainLain = PembukuanKeuangan::where('semester_id', $semesterId)
            ->where('kategori', 'lain_lain')
            ->where('jenis', 'pengeluaran')
            ->sum('nominal');
        
        $totalPengeluaran = $pengeluaranGaji + $pengeluaranLainLain;
        
        $saldoAkhir = $saldoAwal + $totalPemasukan - $totalPengeluaran;
        
        // Get breakdown lain-lain
        $breakdownPemasukanLainLain = PembukuanKeuangan::where('semester_id', $semesterId)
            ->where('kategori', 'lain_lain')
            ->where('jenis', 'pemasukan')
            ->select('sub_kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('sub_kategori')
            ->get();
        
        $breakdownPengeluaranLainLain = PembukuanKeuangan::where('semester_id', $semesterId)
            ->where('kategori', 'lain_lain')
            ->where('jenis', 'pengeluaran')
            ->select('sub_kategori', DB::raw('SUM(nominal) as total'))
            ->groupBy('sub_kategori')
            ->get();
        
        return [
            'saldo_awal' => $saldoAwal,
            'pemasukan_spmb' => $pemasukanSPMB,
            'pemasukan_spp' => $pemasukanSPP,
            'pemasukan_lain_lain' => $pemasukanLainLain,
            'total_pemasukan' => $totalPemasukan,
            'pengeluaran_gaji' => $pengeluaranGaji,
            'pengeluaran_lain_lain' => $pengeluaranLainLain,
            'total_pengeluaran' => $totalPengeluaran,
            'saldo_akhir' => $saldoAkhir,
            'breakdown_pemasukan_lain_lain' => $breakdownPemasukanLainLain,
            'breakdown_pengeluaran_lain_lain' => $breakdownPengeluaranLainLain,
        ];
    }
    
    /**
     * Get chart data for last N semesters
     */
    private function getChartData($limit = 6)
    {
        $semesters = Semester::orderBy('tahun_akademik', 'desc')
            ->orderBy('jenis', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();
        
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];
        $saldo = [];
        
        $runningBalance = 0;
        
        foreach ($semesters as $semester) {
            $labels[] = $semester->nama_semester;
            
            $totalPemasukan = PembukuanKeuangan::where('semester_id', $semester->id)
                ->where('jenis', 'pemasukan')
                ->sum('nominal');
            
            $totalPengeluaran = PembukuanKeuangan::where('semester_id', $semester->id)
                ->where('jenis', 'pengeluaran')
                ->sum('nominal');
            
            $runningBalance += ($totalPemasukan - $totalPengeluaran);
            
            $pemasukan[] = $totalPemasukan;
            $pengeluaran[] = $totalPengeluaran;
            $saldo[] = $runningBalance;
        }
        
        return [
            'labels' => $labels,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran,
            'saldo' => $saldo,
        ];
    }
}
