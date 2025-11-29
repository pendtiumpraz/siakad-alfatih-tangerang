<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Admin\KrsApprovalController as AdminKrsApprovalController;

class KrsApprovalController extends AdminKrsApprovalController
{
    /**
     * Display a listing (Operator view)
     */
    public function index()
    {
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();
        
        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }

        $programStudis = \App\Models\ProgramStudi::withCount([
            'mahasiswas as total_mahasiswa' => function($q) {
                $q->where('status', 'aktif');
            },
            'mahasiswas as submitted_krs' => function($q) use ($activeSemester) {
                $q->where('status', 'aktif')
                  ->whereHas('krs', function($krsQ) use ($activeSemester) {
                      $krsQ->where('semester_id', $activeSemester->id)
                           ->where('status', 'submitted');
                  });
            },
            'mahasiswas as approved_krs' => function($q) use ($activeSemester) {
                $q->where('status', 'aktif')
                  ->whereHas('krs', function($krsQ) use ($activeSemester) {
                      $krsQ->where('semester_id', $activeSemester->id)
                           ->where('status', 'approved');
                  });
            }
        ])->get();

        return view('operator.krs-approval.index', compact('programStudis', 'activeSemester'));
    }

    /**
     * Show detail per prodi (Operator view)
     */
    public function detail($prodiId)
    {
        $prodi = \App\Models\ProgramStudi::findOrFail($prodiId);
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $mahasiswas = \App\Models\Mahasiswa::where('program_studi_id', $prodiId)
            ->where('status', 'aktif')
            ->with(['krs' => function($q) use ($activeSemester) {
                $q->where('semester_id', $activeSemester->id);
            }])
            ->paginate(20);

        return view('operator.krs-approval.detail', compact('prodi', 'mahasiswas', 'activeSemester'));
    }

    /**
     * Show detail mahasiswa KRS (Operator view)
     */
    public function show($mahasiswaId)
    {
        $mahasiswa = \App\Models\Mahasiswa::with('programStudi')->findOrFail($mahasiswaId);
        $activeSemester = \App\Models\Semester::where('is_active', true)->first();

        $krsItems = \App\Models\Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $activeSemester->id)
            ->with(['jadwal.mataKuliah', 'jadwal.dosen'])
            ->get();

        $status = $krsItems->first()->status ?? 'draft';
        $totalSks = $krsItems->sum(function($item) {
            return $item->jadwal->mataKuliah->sks ?? 0;
        });

        return view('operator.krs-approval.show', compact('mahasiswa', 'krsItems', 'status', 'totalSks', 'activeSemester'));
    }

    // approve(), reject(), massApprove*() inherited from Admin controller
}
