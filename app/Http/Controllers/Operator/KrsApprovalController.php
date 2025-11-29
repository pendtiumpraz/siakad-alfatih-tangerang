<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Admin\KrsApprovalController as AdminKrsApprovalController;
use Illuminate\Http\Request;

class KrsApprovalController extends AdminKrsApprovalController
{
    /**
     * Display a listing (Operator view)
     */
    public function index(Request $request)
    {
        $semesterId = $request->semester_id ?? \App\Models\Semester::where('is_active', true)->first()?->id;
        
        if (!$semesterId) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }
        
        $semester = \App\Models\Semester::find($semesterId);
        
        // Get all program studi with statistics
        $programStudis = \App\Models\ProgramStudi::withCount([
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
            $prodi->unpaid_spp_count = \App\Models\Mahasiswa::where('program_studi_id', $prodi->id)
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
        
        $semesters = \App\Models\Semester::orderBy('tahun_akademik', 'desc')->get();
        
        return view('operator.krs-approval.index', compact('programStudis', 'semester', 'semesters'));
    }

    /**
     * Show detail per prodi (Operator view)
     */
    public function detail($prodiId, Request $request)
    {
        $prodi = \App\Models\ProgramStudi::findOrFail($prodiId);
        $semesterId = $request->semester_id ?? \App\Models\Semester::where('is_active', true)->first()?->id;
        $semester = \App\Models\Semester::find($semesterId);
        
        $query = \App\Models\Mahasiswa::where('program_studi_id', $prodiId)
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
            'total' => \App\Models\Mahasiswa::where('program_studi_id', $prodiId)->where('status', 'aktif')->count(),
            'submitted' => \App\Models\Mahasiswa::where('program_studi_id', $prodiId)->where('status', 'aktif')
                ->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)->where('status', 'submitted');
                })->count(),
            'approved' => \App\Models\Mahasiswa::where('program_studi_id', $prodiId)->where('status', 'aktif')
                ->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)->where('status', 'approved');
                })->count(),
            'rejected' => \App\Models\Mahasiswa::where('program_studi_id', $prodiId)->where('status', 'aktif')
                ->whereHas('krs', function($q) use ($semesterId) {
                    $q->where('semester_id', $semesterId)->where('status', 'rejected');
                })->count(),
        ];
        
        $semesters = \App\Models\Semester::orderBy('tahun_akademik', 'desc')->get();
        
        return view('operator.krs-approval.detail', compact('prodi', 'semester', 'mahasiswas', 'summary', 'semesters'));
    }

    /**
     * Show detail mahasiswa KRS (Operator view)
     */
    public function show($mahasiswaId, Request $request)
    {
        $mahasiswa = \App\Models\Mahasiswa::with(['programStudi', 'user'])->findOrFail($mahasiswaId);
        
        $semesterId = $request->semester_id ?? \App\Models\Semester::where('is_active', true)->first()->id;
        
        $krsItems = \App\Models\Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->with(['mataKuliah.jadwals' => function($q) use ($semesterId) {
                $q->where('semester_id', $semesterId)->with(['dosen', 'ruangan']);
            }, 'semester', 'approvedBy'])
            ->get();
        
        $totalSks = $krsItems->sum(function($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });
        
        $semester = \App\Models\Semester::find($semesterId);
        
        // Check SPP payment
        $sppPayment = \App\Models\Pembayaran::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->where('jenis_pembayaran', 'spp')
            ->first();
        
        return view('operator.krs-approval.show', compact('mahasiswa', 'krsItems', 'totalSks', 'semester', 'sppPayment'));
    }

    // approve(), reject(), massApprove*() inherited from Admin controller
}
