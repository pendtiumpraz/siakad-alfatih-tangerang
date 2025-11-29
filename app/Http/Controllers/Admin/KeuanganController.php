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
use App\Services\GoogleDriveService;
use Exception;

class KeuanganController extends Controller
{
    protected $driveService;

    public function __construct()
    {
        // Initialize Google Drive service if enabled
        if (config('google-drive.enabled')) {
            try {
                $this->driveService = new GoogleDriveService();
            } catch (Exception $e) {
                \Log::warning('Google Drive service initialization failed: ' . $e->getMessage());
                $this->driveService = null;
            }
        }
    }

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
        
        DB::beginTransaction();
        
        try {
            // Handle file upload to Google Drive ONLY
            $uploadResult = null;
            if ($request->hasFile('bukti_file')) {
                $uploadResult = $this->uploadBuktiTransaksi(
                    $request->file('bukti_file'),
                    $request->jenis,
                    $request->sub_kategori ?? 'Lain-lain'
                );
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
                'bukti_file' => $uploadResult['bukti_file'] ?? null,
                'google_drive_file_id' => $uploadResult['google_drive_file_id'] ?? null,
                'google_drive_link' => $uploadResult['google_drive_link'] ?? null,
                'created_by' => auth()->id(),
            ]);
            
            DB::commit();
            
            return redirect()->route('admin.keuangan.index')
                ->with('success', 'Transaksi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Delete uploaded file from Google Drive if transaction failed
            if (isset($uploadResult) && $uploadResult && $uploadResult['google_drive_file_id'] && $this->driveService) {
                try {
                    $this->driveService->deleteFile($uploadResult['google_drive_file_id']);
                    \Log::info('Deleted Google Drive file after rollback: ' . $uploadResult['google_drive_file_id']);
                } catch (Exception $deleteException) {
                    \Log::error('Failed to delete Google Drive file after rollback: ' . $deleteException->getMessage());
                }
            }
            
            return back()->withInput()
                ->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }
    
    /**
     * Show edit form
     */
    public function edit($id)
    {
        $pembukuan = PembukuanKeuangan::findOrFail($id);
        
        // Only allow edit for manual transactions
        if ($pembukuan->is_otomatis) {
            return redirect()->back()
                ->with('error', 'Transaksi otomatis tidak dapat diedit.');
        }
        
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        $subKategoriPemasukan = PembukuanKeuangan::getSubKategoriPemasukan();
        $subKategoriPengeluaran = PembukuanKeuangan::getSubKategoriPengeluaran();
        
        return view('admin.keuangan.edit', compact(
            'pembukuan',
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
        
        DB::beginTransaction();
        
        try {
            $updateData = [
                'jenis' => $request->jenis,
                'kategori' => 'lain_lain',
                'sub_kategori' => $request->sub_kategori,
                'nominal' => $request->nominal,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'semester_id' => $request->semester_id,
            ];
            
            // Handle file upload to Google Drive ONLY
            if ($request->hasFile('bukti_file')) {
                // Delete old file from Google Drive
                $this->deleteBuktiTransaksi($transaction);
                
                // Upload new file
                $uploadResult = $this->uploadBuktiTransaksi(
                    $request->file('bukti_file'),
                    $request->jenis,
                    $request->sub_kategori ?? 'Lain-lain'
                );
                
                $updateData['bukti_file'] = $uploadResult['bukti_file'];
                $updateData['google_drive_file_id'] = $uploadResult['google_drive_file_id'];
                $updateData['google_drive_link'] = $uploadResult['google_drive_link'];
            }
            
            // Update transaction
            $transaction->update($updateData);
            
            DB::commit();
            
            return redirect()->route('admin.keuangan.index')
                ->with('success', 'Transaksi berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withInput()
                ->with('error', 'Gagal update transaksi: ' . $e->getMessage());
        }
    }
    
    /**
     * Soft delete transaction
     */
    public function destroy($id)
    {
        $transaction = PembukuanKeuangan::findOrFail($id);
        
        // Only allow delete for manual transactions
        if ($transaction->is_otomatis) {
            return redirect()->back()
                ->with('error', 'Transaksi otomatis tidak dapat dihapus.');
        }
        
        // Delete file from Google Drive
        $this->deleteBuktiTransaksi($transaction);
        
        // Soft delete
        $transaction->delete();
        
        return redirect()->back()
            ->with('success', 'Transaksi berhasil dihapus.');
    }
    
    /**
     * Get summary data for semester
     */
    protected function getSummary($semesterId)
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
     * Upload bukti transaksi to Google Drive ONLY
     * Local storage is NOT used - files must go to Google Drive
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $jenis
     * @param string $subKategori
     * @return array ['bukti_file' => link, 'google_drive_file_id' => id, 'google_drive_link' => link]
     * @throws Exception if Google Drive is not available or upload fails
     */
    protected function uploadBuktiTransaksi($file, $jenis, $subKategori)
    {
        if (!$this->driveService) {
            throw new Exception('Google Drive tidak aktif. Hubungi administrator untuk mengaktifkan Google Drive terlebih dahulu.');
        }

        try {
            // Upload to Google Drive (REQUIRED)
            $driveResult = $this->driveService->uploadKeuangan(
                $file,
                $jenis,
                $subKategori
            );

            \Log::info("Uploaded bukti transaksi to Google Drive: {$driveResult['id']}");

            return [
                'bukti_file' => $driveResult['webViewLink'],
                'google_drive_file_id' => $driveResult['id'],
                'google_drive_link' => $driveResult['webViewLink'],
            ];
        } catch (Exception $e) {
            \Log::error("Failed to upload bukti transaksi to Google Drive: " . $e->getMessage());
            throw new Exception("Gagal upload bukti transaksi ke Google Drive: " . $e->getMessage());
        }
    }

    /**
     * Delete bukti transaksi from Google Drive ONLY
     */
    protected function deleteBuktiTransaksi($transaction)
    {
        if (!$this->driveService) {
            return;
        }

        // Delete from Google Drive if file_id exists
        if ($transaction->google_drive_file_id) {
            try {
                $this->driveService->deleteFile($transaction->google_drive_file_id);
                \Log::info("Deleted bukti transaksi from Google Drive: {$transaction->google_drive_file_id}");
            } catch (Exception $e) {
                \Log::error("Failed to delete bukti transaksi from Google Drive: " . $e->getMessage());
            }
        }
    }

    /**
     * Get chart data for last N semesters
     */
    protected function getChartData($limit = 6)
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
