<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Khs;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use App\Models\Semester;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KHSController extends Controller
{
    /**
     * Display a listing of KHS
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->with('programStudis')->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Get program studi IDs assigned to dosen
        $prodiIds = $dosen->programStudis->pluck('id')->toArray();

        if (empty($prodiIds)) {
            abort(403, 'Anda belum di-assign ke program studi manapun. Silakan hubungi admin.');
        }

        // Get all mahasiswa from assigned program studi that this dosen teaches
        $mahasiswas = Mahasiswa::whereIn('program_studi_id', $prodiIds)
            ->whereHas('nilais', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->orderBy('nama_lengkap')
            ->get();

        // Get all semesters
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();

        // Get all KHS for mahasiswa from assigned program studi that this dosen teaches
        $query = Khs::whereHas('mahasiswa', function ($query) use ($prodiIds, $dosen) {
                $query->whereIn('program_studi_id', $prodiIds)
                    ->whereHas('nilais', function ($q) use ($dosen) {
                        $q->where('dosen_id', $dosen->id);
                    });
            })
            ->with(['mahasiswa.programStudi', 'semester']);

        // Apply filters
        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        $khsList = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        return view('dosen.khs.index', compact('khsList', 'dosen', 'mahasiswas', 'semesters'));
    }

    /**
     * Display the specified KHS detail
     */
    public function show($id)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->with('programStudis')->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Get program studi IDs assigned to dosen
        $prodiIds = $dosen->programStudis->pluck('id')->toArray();

        if (empty($prodiIds)) {
            abort(403, 'Anda belum di-assign ke program studi manapun. Silakan hubungi admin.');
        }

        $khs = Khs::with(['mahasiswa.programStudi', 'semester'])->findOrFail($id);

        // Verify mahasiswa is from assigned program studi and dosen has taught them
        if (!in_array($khs->mahasiswa->program_studi_id, $prodiIds)) {
            abort(403, 'Mahasiswa tidak termasuk dalam program studi Anda');
        }

        $hasAccess = Nilai::where('mahasiswa_id', $khs->mahasiswa_id)
            ->where('dosen_id', $dosen->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access');
        }

        // Get all nilai for this semester
        $nilais = Nilai::where('mahasiswa_id', $khs->mahasiswa_id)
            ->where('semester_id', $khs->semester_id)
            ->with(['mataKuliah', 'dosen'])
            ->get();

        return view('dosen.khs.show', compact('khs', 'nilais', 'dosen'));
    }

    /**
     * Generate KHS for mahasiswa in semester
     */
    public function generate(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->with('programStudis')->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Get program studi IDs assigned to dosen
        $prodiIds = $dosen->programStudis->pluck('id')->toArray();

        if (empty($prodiIds)) {
            abort(403, 'Anda belum di-assign ke program studi manapun. Silakan hubungi admin.');
        }

        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        // Verify mahasiswa is from assigned program studi
        $mahasiswa = Mahasiswa::findOrFail($validated['mahasiswa_id']);
        if (!in_array($mahasiswa->program_studi_id, $prodiIds)) {
            abort(403, 'Mahasiswa tidak termasuk dalam program studi Anda');
        }

        // Verify dosen has taught this mahasiswa
        $hasAccess = Nilai::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('dosen_id', $dosen->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this mahasiswa');
        }

        // Get all nilai for mahasiswa in this semester
        $nilais = Nilai::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->where('semester_id', $validated['semester_id'])
            ->with('mataKuliah')
            ->get();

        if ($nilais->isEmpty()) {
            return back()->with('error', 'Tidak ada nilai untuk mahasiswa pada semester ini');
        }

        // Calculate total SKS and total SKS lulus
        $totalSks = 0;
        $totalSksLulus = 0;
        $totalNilaiSks = 0;

        foreach ($nilais as $nilai) {
            $sks = $nilai->mataKuliah->sks;
            $totalSks += $sks;

            if ($nilai->status == 'lulus') {
                $totalSksLulus += $sks;
            }

            // Calculate weighted grade points
            $gradeNumeric = $this->getGradeNumeric($nilai->grade);
            $totalNilaiSks += ($gradeNumeric * $sks);
        }

        // Calculate IP Semester
        $ipSemester = $totalSks > 0 ? round($totalNilaiSks / $totalSks, 2) : 0;

        // Calculate IP Kumulatif from all semesters
        $allNilais = Nilai::where('mahasiswa_id', $validated['mahasiswa_id'])
            ->with('mataKuliah')
            ->get();

        $totalSksKumulatif = 0;
        $totalNilaiSksKumulatif = 0;

        foreach ($allNilais as $nilai) {
            $sks = $nilai->mataKuliah->sks;
            $totalSksKumulatif += $sks;

            $gradeNumeric = $this->getGradeNumeric($nilai->grade);
            $totalNilaiSksKumulatif += ($gradeNumeric * $sks);
        }

        $ipKumulatif = $totalSksKumulatif > 0 ? round($totalNilaiSksKumulatif / $totalSksKumulatif, 2) : 0;

        // Create or update KHS
        $khs = Khs::updateOrCreate(
            [
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'semester_id' => $validated['semester_id'],
            ],
            [
                'total_sks_semester' => $totalSks,
                'total_sks_kumulatif' => $totalSksKumulatif,
                'ip' => $ipSemester,
                'ipk' => $ipKumulatif,
            ]
        );

        return redirect()->route('dosen.khs.show', $khs->id)
            ->with('success', 'KHS berhasil digenerate');
    }

    /**
     * Get numeric value for grade
     * Grade numeric: A=4.0, A-=3.7, B+=3.3, B=3.0, B-=2.7, C+=2.3, C=2.0, C-=1.7, D=1.0, E=0
     */
    private function getGradeNumeric($grade)
    {
        $gradeMap = [
            'A' => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B' => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C' => 2.0,
            'C-' => 1.7,
            'D' => 1.0,
            'E' => 0.0,
        ];

        return $gradeMap[$grade] ?? 0.0;
    }
}
