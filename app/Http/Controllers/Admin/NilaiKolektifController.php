<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProgramStudi;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Semester;
use App\Models\Nilai;
use App\Models\Khs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiKolektifController extends Controller
{
    /**
     * Display filter form
     */
    public function index()
    {
        $programStudis = ProgramStudi::orderBy('nama_prodi')->get();
        
        // Get unique angkatan from mahasiswa
        $angkatans = Mahasiswa::select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');
        
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        
        return view('admin.nilai-kolektif.index', compact('programStudis', 'angkatans', 'semesters'));
    }

    /**
     * Preview mahasiswa and mata kuliah grid
     */
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'angkatan' => 'required|integer',
            'semester_id' => 'required|exists:semesters,id',
        ]);

        // Get mahasiswa
        $mahasiswas = Mahasiswa::where('program_studi_id', $validated['program_studi_id'])
            ->where('angkatan', $validated['angkatan'])
            ->where('status', 'aktif')
            ->orderBy('nim')
            ->get();

        if ($mahasiswas->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada mahasiswa aktif untuk filter yang dipilih.');
        }

        // Get semester number from semester_id
        $semester = Semester::findOrFail($validated['semester_id']);
        
        // Determine semester number (1-8) from semester name
        // Assuming format like "Semester 1", "Semester 2", etc in kurikulum
        // We'll get mata kuliah based on kurikulum
        
        $prodi = ProgramStudi::findOrFail($validated['program_studi_id']);
        
        // Get active kurikulum for this prodi
        $kurikulum = $prodi->kurikulums()->where('is_active', true)->first();
        
        if (!$kurikulum) {
            return redirect()->back()->with('error', 'Kurikulum aktif tidak ditemukan untuk program studi ini.');
        }

        // Calculate semester number based on angkatan and current semester
        $semesterNumber = $this->calculateSemesterNumber($validated['angkatan'], $semester);
        
        // Get mata kuliah wajib for this semester
        $mataKuliahs = MataKuliah::where('kurikulum_id', $kurikulum->id)
            ->where('semester', $semesterNumber)
            ->where('jenis', 'wajib')
            ->orderBy('kode_mk')
            ->get();

        if ($mataKuliahs->isEmpty()) {
            return redirect()->back()->with('error', "Tidak ada mata kuliah wajib untuk semester {$semesterNumber}.");
        }

        // Get existing nilai for preview
        $existingNilai = Nilai::whereIn('mahasiswa_id', $mahasiswas->pluck('id'))
            ->where('semester_id', $validated['semester_id'])
            ->get()
            ->groupBy('mahasiswa_id');

        $totalInput = $mahasiswas->count() * $mataKuliahs->count();

        return view('admin.nilai-kolektif.preview', compact(
            'mahasiswas',
            'mataKuliahs',
            'prodi',
            'semester',
            'semesterNumber',
            'totalInput',
            'existingNilai',
            'validated'
        ));
    }

    /**
     * Store batch nilai
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'angkatan' => 'required|integer',
            'semester_id' => 'required|exists:semesters,id',
            'nilai' => 'required|array',
            'nilai.*.*' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            $semester = Semester::findOrFail($request->semester_id);
            $created = 0;
            $updated = 0;
            $skipped = 0;

            foreach ($request->nilai as $mahasiswaId => $mataKuliahs) {
                foreach ($mataKuliahs as $mataKuliahId => $nilaiAngka) {
                    // Skip if empty
                    if ($nilaiAngka === null || $nilaiAngka === '') {
                        $skipped++;
                        continue;
                    }

                    // Calculate grade and status
                    $gradeData = $this->calculateGrade($nilaiAngka);

                    // Check if already exists
                    $existingNilai = Nilai::where('mahasiswa_id', $mahasiswaId)
                        ->where('semester_id', $request->semester_id)
                        ->where('mata_kuliah_id', $mataKuliahId)
                        ->first();

                    if ($existingNilai) {
                        // Update existing
                        $existingNilai->update([
                            'nilai_akhir' => $nilaiAngka,
                            'grade' => $gradeData['grade'],
                            'bobot' => $gradeData['bobot'],
                            'status' => $gradeData['status'],
                        ]);
                        $updated++;
                    } else {
                        // Get dosen_id from jadwal for this mata kuliah and semester
                        $jadwal = \App\Models\Jadwal::where('semester_id', $request->semester_id)
                            ->where('mata_kuliah_id', $mataKuliahId)
                            ->first();
                        
                        // Create new
                        Nilai::create([
                            'mahasiswa_id' => $mahasiswaId,
                            'semester_id' => $request->semester_id,
                            'mata_kuliah_id' => $mataKuliahId,
                            'dosen_id' => $jadwal ? $jadwal->dosen_id : null,
                            'nilai_akhir' => $nilaiAngka,
                            'grade' => $gradeData['grade'],
                            'bobot' => $gradeData['bobot'],
                            'status' => $gradeData['status'],
                        ]);
                        $created++;
                    }
                }
            }

            // Auto-generate/update KHS for all mahasiswa
            $mahasiswaIds = array_keys($request->nilai);
            foreach ($mahasiswaIds as $mahasiswaId) {
                $this->generateKhs($mahasiswaId, $request->semester_id);
            }

            DB::commit();

            $message = "Berhasil menyimpan nilai! Created: {$created}, Updated: {$updated}, Skipped: {$skipped}";
            
            return redirect()->route('admin.nilai-kolektif.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving batch nilai: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Calculate grade from nilai
     * Based on official STAI AL-FATIH grading system
     * Grade: A+, A, B+, B, C+, C, D+, D, E (NO minus grades)
     */
    private function calculateGrade($nilai)
    {
        if ($nilai >= 98 && $nilai <= 100) {
            return ['grade' => 'A+', 'bobot' => 4.00, 'status' => 'lulus'];
        } elseif ($nilai >= 93 && $nilai <= 97) {
            return ['grade' => 'A', 'bobot' => 3.70, 'status' => 'lulus'];
        } elseif ($nilai >= 88 && $nilai <= 92) {
            return ['grade' => 'B+', 'bobot' => 3.60, 'status' => 'lulus'];
        } elseif ($nilai >= 80 && $nilai <= 87) {
            return ['grade' => 'B', 'bobot' => 2.95, 'status' => 'lulus'];
        } elseif ($nilai >= 70 && $nilai <= 79) {
            return ['grade' => 'C+', 'bobot' => 2.70, 'status' => 'lulus'];
        } elseif ($nilai >= 66 && $nilai <= 69) {
            return ['grade' => 'C', 'bobot' => 2.00, 'status' => 'lulus'];
        } elseif ($nilai >= 58 && $nilai <= 65) {
            return ['grade' => 'D+', 'bobot' => 1.80, 'status' => 'lulus'];
        } elseif ($nilai >= 50 && $nilai <= 57) {
            return ['grade' => 'D', 'bobot' => 1.30, 'status' => 'lulus'];
        } else {
            // 0-49
            return ['grade' => 'E', 'bobot' => 1.00, 'status' => 'tidak_lulus'];
        }
    }

    /**
     * Calculate semester number based on angkatan and current semester
     */
    private function calculateSemesterNumber($angkatan, $semester)
    {
        $tahunAkademik = (int) substr($semester->tahun_akademik, 0, 4);
        $yearDiff = $tahunAkademik - $angkatan;

        if ($semester->jenis === 'ganjil') {
            return ($yearDiff * 2) + 1;
        } else {
            return ($yearDiff * 2) + 2;
        }
    }

    /**
     * Generate or update KHS for mahasiswa
     */
    private function generateKhs($mahasiswaId, $semesterId)
    {
        // Get all nilai for this mahasiswa in this semester
        $nilaiSemester = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->with('mataKuliah')
            ->get();

        if ($nilaiSemester->isEmpty()) {
            return;
        }

        // Calculate IP (Indeks Prestasi semester ini)
        $totalBobot = 0;
        $totalSks = 0;

        foreach ($nilaiSemester as $nilai) {
            $sks = $nilai->mataKuliah->sks;
            $totalBobot += $nilai->bobot * $sks;
            $totalSks += $sks;
        }

        $ip = $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0;

        // Calculate IPK (Indeks Prestasi Kumulatif dari semester 1 sampai sekarang)
        $allNilai = Nilai::where('mahasiswa_id', $mahasiswaId)
            ->whereHas('semester', function($q) use ($semesterId) {
                $currentSemester = Semester::find($semesterId);
                $q->where('tahun_akademik', '<=', $currentSemester->tahun_akademik);
            })
            ->with('mataKuliah')
            ->get();

        $totalBobotKumulatif = 0;
        $totalSksKumulatif = 0;

        foreach ($allNilai as $nilai) {
            $sks = $nilai->mataKuliah->sks;
            $totalBobotKumulatif += $nilai->bobot * $sks;
            $totalSksKumulatif += $sks;
        }

        $ipk = $totalSksKumulatif > 0 ? round($totalBobotKumulatif / $totalSksKumulatif, 2) : 0;

        // Update or create KHS
        Khs::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswaId,
                'semester_id' => $semesterId,
            ],
            [
                'ip' => $ip,
                'ipk' => $ipk,
                'total_sks_semester' => $totalSks,
                'total_sks_kumulatif' => $totalSksKumulatif,
            ]
        );
    }
}
