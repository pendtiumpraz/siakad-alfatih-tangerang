<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    /**
     * Display a listing of mata kuliah that dosen teaches
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Get filter data
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        $programStudis = \App\Models\ProgramStudi::orderBy('nama_prodi')->get();

        // Get mata kuliah from jadwal with filters
        $query = Jadwal::where('dosen_id', $dosen->id)
            ->with(['mataKuliah.kurikulum.programStudi', 'semester'])
            ->select('mata_kuliah_id', 'semester_id')
            ->distinct();

        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by program studi
        if ($request->filled('program_studi_id')) {
            $query->whereHas('mataKuliah.kurikulum', function($q) use ($request) {
                $q->where('program_studi_id', $request->program_studi_id);
            });
        }

        $mataKuliahs = $query->get()
            ->map(function ($jadwal) use ($dosen) {
                $mahasiswaCount = Nilai::where('mata_kuliah_id', $jadwal->mata_kuliah_id)
                    ->where('semester_id', $jadwal->semester_id)
                    ->where('dosen_id', $dosen->id)
                    ->count();

                return [
                    'mata_kuliah' => $jadwal->mataKuliah,
                    'semester' => $jadwal->semester,
                    'mahasiswa_count' => $mahasiswaCount,
                ];
            });

        return view('dosen.nilai.index', compact('mataKuliahs', 'dosen', 'semesters', 'programStudis'));
    }

    /**
     * Display a listing of mahasiswa for specific mata kuliah
     */
    public function mahasiswa($mataKuliahId)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Verify dosen teaches this mata kuliah
        $jadwal = Jadwal::where('dosen_id', $dosen->id)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->firstOrFail();

        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);

        $nilais = Nilai::where('mata_kuliah_id', $mataKuliahId)
            ->where('dosen_id', $dosen->id)
            ->with(['mahasiswa', 'semester'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dosen.nilai.mahasiswa', compact('mataKuliah', 'nilais', 'dosen'));
    }

    /**
     * Show the form for creating nilai for all mahasiswa in mata kuliah
     */
    public function create($mataKuliahId)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Verify dosen teaches this mata kuliah
        $jadwal = Jadwal::where('dosen_id', $dosen->id)
            ->where('mata_kuliah_id', $mataKuliahId)
            ->firstOrFail();

        $mataKuliah = MataKuliah::findOrFail($mataKuliahId);
        $semesters = Semester::where('is_active', true)->orderBy('tahun_akademik', 'desc')->get();
        $mahasiswas = Mahasiswa::where('status', 'aktif')->orderBy('nama_lengkap')->get();

        return view('dosen.nilai.create', compact('mataKuliah', 'mahasiswas', 'semesters', 'dosen'));
    }

    /**
     * Store newly created nilai in storage (batch save)
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'semester_id' => 'required|exists:semesters,id',
            'nilai' => 'required|array',
            'nilai.*.mahasiswa_id' => 'required|exists:mahasiswas,id',
            'nilai.*.nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai.*.nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai.*.nilai_uas' => 'required|numeric|min:0|max:100',
        ]);

        // Verify dosen teaches this mata kuliah
        $jadwal = Jadwal::where('dosen_id', $dosen->id)
            ->where('mata_kuliah_id', $validated['mata_kuliah_id'])
            ->firstOrFail();

        DB::transaction(function () use ($validated, $dosen) {
            foreach ($validated['nilai'] as $nilaiData) {
                // Calculate nilai_akhir
                $nilaiAkhir = ($nilaiData['nilai_tugas'] * 0.3) +
                             ($nilaiData['nilai_uts'] * 0.3) +
                             ($nilaiData['nilai_uas'] * 0.4);

                // Calculate grade
                $grade = $this->calculateGrade($nilaiAkhir);

                // Determine status
                $status = $grade != 'E' ? 'lulus' : 'tidak_lulus';

                // Create or update nilai
                Nilai::updateOrCreate(
                    [
                        'mahasiswa_id' => $nilaiData['mahasiswa_id'],
                        'mata_kuliah_id' => $validated['mata_kuliah_id'],
                        'semester_id' => $validated['semester_id'],
                    ],
                    [
                        'dosen_id' => $dosen->id,
                        'nilai_tugas' => $nilaiData['nilai_tugas'],
                        'nilai_uts' => $nilaiData['nilai_uts'],
                        'nilai_uas' => $nilaiData['nilai_uas'],
                        'nilai_akhir' => $nilaiAkhir,
                        'grade' => $grade,
                        'status' => $status,
                    ]
                );
            }
        });

        return redirect()->route('dosen.nilai.mahasiswa', $validated['mata_kuliah_id'])
            ->with('success', 'Nilai berhasil disimpan');
    }

    /**
     * Show the form for editing the specified nilai
     */
    public function edit($nilaiId)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        $nilai = Nilai::where('dosen_id', $dosen->id)->findOrFail($nilaiId);
        $nilai->load(['mahasiswa', 'mataKuliah', 'semester']);

        return view('dosen.nilai.edit', compact('nilai', 'dosen'));
    }

    /**
     * Update the specified nilai in storage with recalculation
     */
    public function update(Request $request, $nilaiId)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        $nilai = Nilai::where('dosen_id', $dosen->id)->findOrFail($nilaiId);

        $validated = $request->validate([
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
        ]);

        // Calculate nilai_akhir
        $nilaiAkhir = ($validated['nilai_tugas'] * 0.3) +
                     ($validated['nilai_uts'] * 0.3) +
                     ($validated['nilai_uas'] * 0.4);

        // Calculate grade
        $grade = $this->calculateGrade($nilaiAkhir);

        // Determine status
        $status = $grade != 'E' ? 'lulus' : 'tidak_lulus';

        // Update nilai
        $nilai->update([
            'nilai_tugas' => $validated['nilai_tugas'],
            'nilai_uts' => $validated['nilai_uts'],
            'nilai_uas' => $validated['nilai_uas'],
            'nilai_akhir' => $nilaiAkhir,
            'grade' => $grade,
            'status' => $status,
        ]);

        return redirect()->route('dosen.nilai.mahasiswa', $nilai->mata_kuliah_id)
            ->with('success', 'Nilai berhasil diperbarui');
    }

    /**
     * Remove the specified nilai from storage (soft delete)
     */
    public function destroy($nilaiId)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        $nilai = Nilai::where('dosen_id', $dosen->id)->findOrFail($nilaiId);
        $mataKuliahId = $nilai->mata_kuliah_id;
        $nilai->delete();

        return redirect()->route('dosen.nilai.mahasiswa', $mataKuliahId)
            ->with('success', 'Nilai berhasil dihapus');
    }

    /**
     * Calculate grade based on nilai_akhir
     * A (85-100), A- (80-84), B+ (75-79), B (70-74), B- (65-69), C+ (60-64), C (55-59), C- (50-54), D (45-49), E (<45)
     */
    private function calculateGrade($nilaiAkhir)
    {
        if ($nilaiAkhir >= 85) {
            return 'A';
        } elseif ($nilaiAkhir >= 80) {
            return 'A-';
        } elseif ($nilaiAkhir >= 75) {
            return 'B+';
        } elseif ($nilaiAkhir >= 70) {
            return 'B';
        } elseif ($nilaiAkhir >= 65) {
            return 'B-';
        } elseif ($nilaiAkhir >= 60) {
            return 'C+';
        } elseif ($nilaiAkhir >= 55) {
            return 'C';
        } elseif ($nilaiAkhir >= 50) {
            return 'C-';
        } elseif ($nilaiAkhir >= 45) {
            return 'D';
        } else {
            return 'E';
        }
    }
}
