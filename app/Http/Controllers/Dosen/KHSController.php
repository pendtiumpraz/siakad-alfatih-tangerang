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
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Get all KHS for mahasiswa that this dosen teaches
        $khs = Khs::whereHas('mahasiswa.nilais', function ($query) use ($dosen) {
                $query->where('dosen_id', $dosen->id);
            })
            ->with(['mahasiswa', 'semester'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dosen.khs.index', compact('khs', 'dosen'));
    }

    /**
     * Display the specified KHS detail
     */
    public function show($id)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        $khs = Khs::with(['mahasiswa', 'semester'])->findOrFail($id);

        // Verify dosen has access to this mahasiswa
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
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'semester_id' => 'required|exists:semesters,id',
        ]);

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

        // Determine status semester
        $statusSemester = $ipSemester >= 2.0 ? 'naik' : 'mengulang';

        // Create or update KHS
        $khs = Khs::updateOrCreate(
            [
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'semester_id' => $validated['semester_id'],
            ],
            [
                'total_sks' => $totalSks,
                'total_sks_lulus' => $totalSksLulus,
                'ip_semester' => $ipSemester,
                'ip_kumulatif' => $ipKumulatif,
                'status_semester' => $statusSemester,
            ]
        );

        return redirect()->route('dosen.khs.show', $khs->id)
            ->with('success', 'KHS berhasil digenerate');
    }

    /**
     * Get numeric value for grade
     * Grade numeric: A=4.0, AB=3.5, B=3.0, BC=2.5, C=2.0, D=1.0, E=0
     */
    private function getGradeNumeric($grade)
    {
        $gradeMap = [
            'A' => 4.0,
            'AB' => 3.5,
            'B' => 3.0,
            'BC' => 2.5,
            'C' => 2.0,
            'D' => 1.0,
            'E' => 0.0,
        ];

        return $gradeMap[$grade] ?? 0.0;
    }
}
