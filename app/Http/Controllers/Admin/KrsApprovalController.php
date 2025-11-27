<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use App\Models\Semester;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KrsApprovalController extends Controller
{
    /**
     * Dashboard Overview: Summary per Program Studi
     */
    public function index(Request $request)
    {
        $semesterId = $request->semester_id ?? Semester::where('is_active', true)->first()?->id;
        
        if (!$semesterId) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }
        
        $semester = Semester::find($semesterId);
        
        // Get all program studi with statistics
        $programStudis = ProgramStudi::withCount([
            // Total mahasiswa aktif
            'mahasiswas as total_mahasiswa' => function($q) {
                $q->where('status', 'aktif');
            },
            // Mahasiswa yang sudah submit KRS
            'mahasiswas as submitted_count' => function($q) use ($semesterId) {
                $q->where('status', 'aktif')
                  ->whereHas('krs', function($krsQuery) use ($semesterId) {
                      $krsQuery->where('semester_id', $semesterId)
                               ->where('status', 'submitted');
                  });
            },
            // Mahasiswa yang sudah approved
            'mahasiswas as approved_count' => function($q) use ($semesterId) {
                $q->where('status', 'aktif')
                  ->whereHas('krs', function($krsQuery) use ($semesterId) {
                      $krsQuery->where('semester_id', $semesterId)
                               ->where('status', 'approved');
                  });
            },
            // Mahasiswa yang rejected
            'mahasiswas as rejected_count' => function($q) use ($semesterId) {
                $q->where('status', 'aktif')
                  ->whereHas('krs', function($krsQuery) use ($semesterId) {
                      $krsQuery->where('semester_id', $semesterId)
                               ->where('status', 'rejected');
                  });
            },
        ])->get();
        
        // Calculate additional statistics for each prodi
        foreach ($programStudis as $prodi) {
            // Mahasiswa belum submit
            $prodi->not_submitted_count = $prodi->total_mahasiswa - $prodi->submitted_count - $prodi->approved_count - $prodi->rejected_count;
            
            // Mahasiswa belum bayar SPP
            $prodi->unpaid_spp_count = Mahasiswa::where('program_studi_id', $prodi->id)
                ->where('status', 'aktif')
                ->whereDoesntHave('pembayarans', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                      ->where('jenis_pembayaran', 'spp')
                      ->where('status', 'lunas');
                })
                ->count();
            
            // Pending approval (submitted but not approved yet)
            $prodi->pending_approval_count = $prodi->submitted_count;
            
            // Percentage
            $prodi->submitted_percentage = $prodi->total_mahasiswa > 0 
                ? round(($prodi->submitted_count / $prodi->total_mahasiswa) * 100, 1) 
                : 0;
        }
        
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        
        return view('admin.krs-approval.index', compact('programStudis', 'semester', 'semesters'));
    }
    
    /**
     * Detail View: List mahasiswa per prodi
     */
    public function detail($prodiId, Request $request)
    {
        $prodi = ProgramStudi::findOrFail($prodiId);
        $semesterId = $request->semester_id ?? Semester::where('is_active', true)->first()?->id;
        $semester = Semester::find($semesterId);
        
        $query = Mahasiswa::where('program_studi_id', $prodiId)
            ->where('status', 'aktif')
            ->with([
                'krs' => function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                      ->with('mataKuliah');
                },
                'pembayarans' => function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                      ->where('jenis_pembayaran', 'spp');
                }
            ]);
        
        // Filter by KRS status
        if ($request->filled('krs_status')) {
            $status = $request->krs_status;
            
            if ($status === 'submitted') {
                $query->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                      ->where('status', 'submitted');
                });
            } elseif ($status === 'approved') {
                $query->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                      ->where('status', 'approved');
                });
            } elseif ($status === 'rejected') {
                $query->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                      ->where('status', 'rejected');
                });
            } elseif ($status === 'not_submitted') {
                $query->whereDoesntHave('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId);
                });
            }
        }
        
        // Filter by payment status
        if ($request->filled('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->whereHas('pembayarans', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                      ->where('jenis_pembayaran', 'spp')
                      ->where('status', 'lunas');
                });
            } elseif ($request->payment_status === 'unpaid') {
                $query->whereDoesntHave('pembayarans', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)
                      ->where('jenis_pembayaran', 'spp')
                      ->where('status', 'lunas');
                });
            }
        }
        
        // Search by NIM or nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }
        
        $mahasiswas = $query->orderBy('nim')->paginate(50)->withQueryString();
        
        // Calculate statistics for each mahasiswa
        foreach ($mahasiswas as $mhs) {
            $krsItems = $mhs->krs->where('semester_id', $semesterId);
            $mhs->total_sks = $krsItems->sum(function($krs) {
                return $krs->mataKuliah->sks ?? 0;
            });
            $mhs->krs_count = $krsItems->count();
            $mhs->krs_status = $krsItems->first()?->status ?? 'not_submitted';
            
            // Check SPP payment
            $sppPayment = $mhs->pembayarans->where('semester_id', $semesterId)
                ->where('jenis_pembayaran', 'spp')
                ->first();
            $mhs->spp_status = $sppPayment?->status ?? 'belum_lunas';
            $mhs->spp_paid = $mhs->spp_status === 'lunas';
            
            // Flag: can be approved?
            $mhs->can_approve = $mhs->krs_status === 'submitted' && $mhs->spp_paid;
        }
        
        // Summary statistics
        $summary = [
            'total' => Mahasiswa::where('program_studi_id', $prodiId)->where('status', 'aktif')->count(),
            'submitted' => Mahasiswa::where('program_studi_id', $prodiId)->where('status', 'aktif')
                ->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)->where('status', 'submitted');
                })->count(),
            'approved' => Mahasiswa::where('program_studi_id', $prodiId)->where('status', 'aktif')
                ->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)->where('status', 'approved');
                })->count(),
            'rejected' => Mahasiswa::where('program_studi_id', $prodiId)->where('status', 'aktif')
                ->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)->where('status', 'rejected');
                })->count(),
        ];
        
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        
        return view('admin.krs-approval.detail', compact('prodi', 'semester', 'mahasiswas', 'summary', 'semesters'));
    }
    
    /**
     * Show individual mahasiswa KRS
     */
    public function show($mahasiswaId, Request $request)
    {
        $mahasiswa = Mahasiswa::with(['programStudi', 'user'])->findOrFail($mahasiswaId);
        
        $semesterId = $request->semester_id ?? Semester::where('is_active', true)->first()->id;
        
        $krsItems = Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->with(['mataKuliah.jadwals' => function($q) use ($semesterId) {
                $q->where('semester_id', $semesterId)->with(['dosen', 'ruangan']);
            }, 'semester', 'approvedBy'])
            ->get();
        
        $totalSks = $krsItems->sum(function($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });
        
        $semester = Semester::find($semesterId);
        
        // Check SPP payment
        $sppPayment = Pembayaran::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->where('jenis_pembayaran', 'spp')
            ->first();
        
        return view('admin.krs-approval.show', compact('mahasiswa', 'krsItems', 'totalSks', 'semester', 'sppPayment'));
    }
    
    /**
     * Mass Approve: Approve all KRS in a program studi
     * HANYA untuk mahasiswa yang SUDAH BAYAR SPP
     */
    public function massApproveProdi(Request $request, $prodiId)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'keterangan' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Get mahasiswa with submitted KRS AND paid SPP
            $mahasiswaIds = Mahasiswa::where('program_studi_id', $prodiId)
                ->where('status', 'aktif')
                ->whereHas('krs', function($q) use ($validated) {
                    $q->where('semester_id', $validated['semester_id'])
                      ->where('status', 'submitted');
                })
                ->whereHas('pembayarans', function($q) use ($validated) {
                    $q->where('semester_id', $validated['semester_id'])
                      ->where('jenis_pembayaran', 'spp')
                      ->where('status', 'lunas');
                })
                ->pluck('id');
            
            if ($mahasiswaIds->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada KRS yang bisa di-approve. Pastikan mahasiswa sudah bayar SPP.');
            }
            
            // Update all KRS to approved
            $updatedCount = Krs::whereIn('mahasiswa_id', $mahasiswaIds)
                ->where('semester_id', $validated['semester_id'])
                ->where('status', 'submitted')
                ->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                    'keterangan' => $validated['keterangan'] ?? 'Approved via mass approval',
                ]);
            
            // Count mahasiswa yang di-skip (belum bayar)
            $totalSubmitted = Mahasiswa::where('program_studi_id', $prodiId)
                ->where('status', 'aktif')
                ->whereHas('krs', function($q) use ($validated) {
                    $q->where('semester_id', $validated['semester_id'])
                      ->where('status', 'submitted');
                })
                ->count();
            
            $skipped = $totalSubmitted - $updatedCount;
            
            DB::commit();
            
            // TODO: Send bulk notification to all mahasiswa
            
            $prodi = ProgramStudi::find($prodiId);
            
            $message = "Berhasil approve {$updatedCount} KRS untuk Program Studi {$prodi->nama_prodi}.";
            if ($skipped > 0) {
                $message .= " {$skipped} mahasiswa di-skip karena belum bayar SPP.";
            }
            
            return redirect()->route('admin.krs-approval.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error mass approve prodi: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal approve KRS: ' . $e->getMessage());
        }
    }
    
    /**
     * Mass Approve: Approve selected mahasiswa (multiple selection)
     */
    public function massApproveSelected(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_ids' => 'required|array',
            'mahasiswa_ids.*' => 'exists:mahasiswas,id',
            'semester_id' => 'required|exists:semesters,id',
            'keterangan' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $updatedCount = Krs::whereIn('mahasiswa_id', $validated['mahasiswa_ids'])
                ->where('semester_id', $validated['semester_id'])
                ->where('status', 'submitted')
                ->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                    'keterangan' => $validated['keterangan'] ?? 'Approved',
                ]);
            
            DB::commit();
            
            return redirect()->back()->with('success', "Berhasil approve {$updatedCount} KRS.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error mass approve selected: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal approve KRS: ' . $e->getMessage());
        }
    }
    
    /**
     * Approve individual KRS
     * Validasi: Mahasiswa HARUS sudah bayar SPP
     * Exception: Admin bisa force approve (untuk kasus khusus: terlambat, dll)
     */
    public function approve($mahasiswaId, Request $request)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'keterangan' => 'nullable|string|max:500',
            'force_approve' => 'nullable|boolean', // Override SPP check
        ]);
        
        try {
            DB::beginTransaction();
            
            $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
            
            // Check SPP payment (unless force approve)
            if (!($validated['force_approve'] ?? false)) {
                $hasPaidSPP = Pembayaran::where('mahasiswa_id', $mahasiswaId)
                    ->where('semester_id', $validated['semester_id'])
                    ->where('jenis_pembayaran', 'spp')
                    ->where('status', 'lunas')
                    ->exists();
                
                if (!$hasPaidSPP) {
                    return redirect()->back()->with('error', 'Mahasiswa belum bayar SPP. Tidak bisa approve KRS.');
                }
            }
            
            $krsItems = Krs::where('mahasiswa_id', $mahasiswaId)
                ->where('semester_id', $validated['semester_id'])
                ->where('status', 'submitted')
                ->get();
            
            if ($krsItems->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada KRS yang perlu di-approve.');
            }
            
            $keterangan = $validated['keterangan'];
            if ($validated['force_approve'] ?? false) {
                $keterangan = '[FORCE APPROVE] ' . ($keterangan ?? 'Approved meskipun belum bayar SPP');
            }
            
            foreach ($krsItems as $krs) {
                $krs->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                    'keterangan' => $keterangan,
                ]);
            }
            
            DB::commit();
            
            // TODO: Send notification to mahasiswa
            
            return redirect()->back()->with('success', 'KRS berhasil di-approve.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal approve KRS: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject KRS
     */
    public function reject($mahasiswaId, Request $request)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'keterangan' => 'required|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $krsItems = Krs::where('mahasiswa_id', $mahasiswaId)
                ->where('semester_id', $validated['semester_id'])
                ->where('status', 'submitted')
                ->get();
            
            if ($krsItems->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada KRS yang bisa di-reject.');
            }
            
            foreach ($krsItems as $krs) {
                $krs->update([
                    'status' => 'rejected',
                    'approved_at' => null,
                    'approved_by' => null,
                    'keterangan' => $validated['keterangan'],
                ]);
            }
            
            DB::commit();
            
            // TODO: Send notification to mahasiswa
            
            return redirect()->back()->with('success', 'KRS berhasil di-reject. Mahasiswa bisa edit dan submit ulang.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal reject KRS: ' . $e->getMessage());
        }
    }
}
