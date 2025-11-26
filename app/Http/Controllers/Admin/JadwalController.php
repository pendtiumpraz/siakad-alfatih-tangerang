<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JadwalController extends Controller
{
    /**
     * Display a listing of all jadwal (Admin can see all)
     */
    public function index(Request $request)
    {
        // Build query - Admin can see ALL jadwal
        $query = Jadwal::with(['mataKuliah.kurikulum.programStudi', 'ruangan', 'dosen']);

        // Filter by jenis_semester (ganjil/genap)
        if ($request->filled('jenis_semester')) {
            $query->where('jenis_semester', $request->jenis_semester);
        }

        // Filter by hari
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        // Search by mata kuliah name
        if ($request->filled('search')) {
            $query->whereHas('mataKuliah', function($q) use ($request) {
                $q->where('nama_mk', 'like', '%' . $request->search . '%')
                  ->orWhere('kode_mk', 'like', '%' . $request->search . '%');
            });
        }

        $jadwals = $query->orderBy('jenis_semester', 'asc') // ganjil first, genap second
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(20)->withQueryString();

        return view('admin.jadwal.index', compact('jadwals'));
    }

    /**
     * Show the form for creating a new jadwal
     */
    public function create()
    {
        // Admin can access ALL data - sorted for better UX
        $mataKuliahs = MataKuliah::with('kurikulum.programStudi')
            ->orderBy('kode_mk', 'asc')
            ->get();
        $dosens = Dosen::with('user')->orderBy('nama_lengkap')->get();
        $ruangans = Ruangan::where('is_available', true)->orderBy('nama_ruangan')->get();

        $hariOptions = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];

        return view('admin.jadwal.create', compact(
            'mataKuliahs',
            'dosens',
            'ruangans',
            'hariOptions'
        ));
    }

    /**
     * Store a newly created jadwal in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_semester' => 'required|in:ganjil,genap',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kelas' => 'required|string|max:10',
        ]);

        // Validate time slots (must be within operational hours)
        $jamMulai = strtotime($validated['jam_mulai']);
        $jamSelesai = strtotime($validated['jam_selesai']);

        if ($jamMulai < strtotime('07:00') || $jamSelesai > strtotime('21:00')) {
            throw ValidationException::withMessages([
                'jam_mulai' => ['Jadwal harus berada dalam jam operasional (07:00 - 21:00)']
            ]);
        }

        // NEW VALIDATION LOGIC (Option 2)
        
        // 1. VALIDASI RUANGAN OFFLINE (Tidak peduli prodi, hanya cek jenis_semester sama)
        $ruangan = Ruangan::find($validated['ruangan_id']);
        
        if ($ruangan->jenis === 'offline') {
            // Ruangan offline: Cek bentrok jika jenis_semester sama, hari sama, jam overlap
            $ruanganConflict = Jadwal::where('ruangan_id', $validated['ruangan_id'])
                ->where('jenis_semester', $validated['jenis_semester']) // Ganjil vs ganjil ATAU genap vs genap
                ->where('hari', $validated['hari'])
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                              ->where('jam_selesai', '>=', $validated['jam_selesai']);
                        });
                })
                ->exists();

            if ($ruanganConflict) {
                throw ValidationException::withMessages([
                    'ruangan_id' => ['Ruangan offline sudah digunakan pada semester ' . $validated['jenis_semester'] . ', hari ' . $validated['hari'] . ' di waktu yang sama']
                ]);
            }
        }
        // Ruangan online: TIDAK ADA VALIDASI (bisa overlap tanpa limit)
        
        // 2. VALIDASI KONFLIK MAHASISWA (Prodi sama + Semester MK sama + Jenis semester sama)
        $mataKuliah = MataKuliah::with('kurikulum')->findOrFail($validated['mata_kuliah_id']);
        $prodiId = $mataKuliah->kurikulum->program_studi_id ?? null;
        $semesterMK = $mataKuliah->semester; // 1, 2, 3, dst
        
        if ($prodiId && $semesterMK) {
            $mahasiswaConflict = Jadwal::where('jenis_semester', $validated['jenis_semester'])
                ->where('hari', $validated['hari'])
                ->where('id', '!=', 0) // For create, check all
                ->whereHas('mataKuliah', function($q) use ($prodiId, $semesterMK) {
                    $q->where('semester', $semesterMK) // Semester mata kuliah sama
                      ->whereHas('kurikulum', function($q2) use ($prodiId) {
                          $q2->where('program_studi_id', $prodiId); // Prodi sama
                      });
                })
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                              ->where('jam_selesai', '>=', $validated['jam_selesai']);
                        });
                })
                ->exists();

            if ($mahasiswaConflict) {
                throw ValidationException::withMessages([
                    'jam_mulai' => ['Jadwal bentrok dengan mata kuliah lain di prodi yang sama untuk mahasiswa semester ' . $semesterMK . ' pada semester ' . $validated['jenis_semester']]
                ]);
            }
        }
        
        // 3. VALIDASI KONFLIK DOSEN (Dosen tidak bisa mengajar di 2 tempat bersamaan)
        $dosenConflict = Jadwal::where('dosen_id', $validated['dosen_id'])
            ->where('jenis_semester', $validated['jenis_semester'])
            ->where('hari', $validated['hari'])
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                          ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })
            ->exists();

        if ($dosenConflict) {
            throw ValidationException::withMessages([
                'dosen_id' => ['Dosen sudah memiliki jadwal mengajar pada semester ' . $validated['jenis_semester'] . ', hari ' . $validated['hari'] . ' di waktu yang sama']
            ]);
        }

        // Create jadwal
        $jadwal = Jadwal::create($validated);

        // AUTO-ASSIGN DOSEN to Mata Kuliah & Program Studi
        $this->autoAssignDosen($validated['dosen_id'], $validated['mata_kuliah_id']);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan dan dosen berhasil di-assign');
    }

    /**
     * Show the form for editing the specified jadwal
     */
    public function edit($id)
    {
        $jadwal = Jadwal::with(['mataKuliah', 'dosen', 'ruangan'])->findOrFail($id);

        // Sorted for better UX
        $mataKuliahs = MataKuliah::with('kurikulum.programStudi')
            ->orderBy('kode_mk', 'asc')
            ->get();
        $dosens = Dosen::with('user')->orderBy('nama_lengkap')->get();
        $ruangans = Ruangan::where('is_available', true)->orderBy('nama_ruangan')->get();

        $hariOptions = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];

        return view('admin.jadwal.edit', compact(
            'jadwal',
            'mataKuliahs',
            'dosens',
            'ruangans',
            'hariOptions'
        ));
    }

    /**
     * Update the specified jadwal in storage
     */
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validated = $request->validate([
            'jenis_semester' => 'required|in:ganjil,genap',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'dosen_id' => 'required|exists:dosens,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kelas' => 'required|string|max:10',
        ]);

        // Validate time slots
        $jamMulai = strtotime($validated['jam_mulai']);
        $jamSelesai = strtotime($validated['jam_selesai']);

        if ($jamMulai < strtotime('07:00') || $jamSelesai > strtotime('21:00')) {
            throw ValidationException::withMessages([
                'jam_mulai' => ['Jadwal harus berada dalam jam operasional (07:00 - 21:00)']
            ]);
        }

        // NEW VALIDATION LOGIC (Option 2) - Excluding current jadwal
        
        // 1. VALIDASI RUANGAN OFFLINE
        $ruangan = Ruangan::find($validated['ruangan_id']);
        
        if ($ruangan->jenis === 'offline') {
            $ruanganConflict = Jadwal::where('ruangan_id', $validated['ruangan_id'])
                ->where('jenis_semester', $validated['jenis_semester'])
                ->where('hari', $validated['hari'])
                ->where('id', '!=', $id) // Exclude current jadwal
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                              ->where('jam_selesai', '>=', $validated['jam_selesai']);
                        });
                })
                ->exists();

            if ($ruanganConflict) {
                throw ValidationException::withMessages([
                    'ruangan_id' => ['Ruangan offline sudah digunakan pada semester ' . $validated['jenis_semester'] . ', hari ' . $validated['hari'] . ' di waktu yang sama']
                ]);
            }
        }
        // Ruangan online: TIDAK ADA VALIDASI
        
        // 2. VALIDASI KONFLIK MAHASISWA
        $mataKuliah = MataKuliah::with('kurikulum')->findOrFail($validated['mata_kuliah_id']);
        $prodiId = $mataKuliah->kurikulum->program_studi_id ?? null;
        $semesterMK = $mataKuliah->semester;
        
        if ($prodiId && $semesterMK) {
            $mahasiswaConflict = Jadwal::where('jenis_semester', $validated['jenis_semester'])
                ->where('hari', $validated['hari'])
                ->where('id', '!=', $id) // Exclude current jadwal
                ->whereHas('mataKuliah', function($q) use ($prodiId, $semesterMK) {
                    $q->where('semester', $semesterMK)
                      ->whereHas('kurikulum', function($q2) use ($prodiId) {
                          $q2->where('program_studi_id', $prodiId);
                      });
                })
                ->where(function ($query) use ($validated) {
                    $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                        ->orWhere(function ($q) use ($validated) {
                            $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                              ->where('jam_selesai', '>=', $validated['jam_selesai']);
                        });
                })
                ->exists();

            if ($mahasiswaConflict) {
                throw ValidationException::withMessages([
                    'jam_mulai' => ['Jadwal bentrok dengan mata kuliah lain di prodi yang sama untuk mahasiswa semester ' . $semesterMK . ' pada semester ' . $validated['jenis_semester']]
                ]);
            }
        }
        
        // 3. VALIDASI KONFLIK DOSEN
        $dosenConflict = Jadwal::where('dosen_id', $validated['dosen_id'])
            ->where('jenis_semester', $validated['jenis_semester'])
            ->where('hari', $validated['hari'])
            ->where('id', '!=', $id) // Exclude current jadwal
            ->where(function ($query) use ($validated) {
                $query->whereBetween('jam_mulai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhereBetween('jam_selesai', [$validated['jam_mulai'], $validated['jam_selesai']])
                    ->orWhere(function ($q) use ($validated) {
                        $q->where('jam_mulai', '<=', $validated['jam_mulai'])
                          ->where('jam_selesai', '>=', $validated['jam_selesai']);
                    });
            })
            ->exists();

        if ($dosenConflict) {
            throw ValidationException::withMessages([
                'dosen_id' => ['Dosen sudah memiliki jadwal mengajar pada semester ' . $validated['jenis_semester'] . ', hari ' . $validated['hari'] . ' di waktu yang sama']
            ]);
        }

        // Update jadwal
        $jadwal->update($validated);

        // AUTO-ASSIGN DOSEN to Mata Kuliah & Program Studi
        $this->autoAssignDosen($validated['dosen_id'], $validated['mata_kuliah_id']);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui dan dosen berhasil di-assign');
    }

    /**
     * Remove the specified jadwal from storage
     */
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    /**
     * AUTO-ASSIGN DOSEN to Mata Kuliah & Program Studi
     * This method is called after creating or updating jadwal
     */
    private function autoAssignDosen($dosenId, $mataKuliahId)
    {
        $dosen = Dosen::findOrFail($dosenId);
        $mataKuliah = MataKuliah::with('kurikulum')->findOrFail($mataKuliahId);
        
        // 1. Auto-assign to Mata Kuliah (if not already assigned)
        if (!$dosen->mataKuliahs()->where('mata_kuliah_id', $mataKuliahId)->exists()) {
            $dosen->mataKuliahs()->attach($mataKuliahId);
        }
        
        // 2. Auto-assign to Program Studi (if not already assigned)
        if ($mataKuliah->kurikulum && $mataKuliah->kurikulum->program_studi_id) {
            $prodiId = $mataKuliah->kurikulum->program_studi_id;
            
            if (!$dosen->programStudis()->where('program_studi_id', $prodiId)->exists()) {
                $dosen->programStudis()->attach($prodiId);
            }
        }
    }

    /**
     * AJAX endpoint for real-time conflict check
     */
    public function checkConflict(Request $request)
    {
        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'dosen_id' => 'required|exists:dosens,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'jadwal_id' => 'nullable|exists:jadwals,id',
        ]);

        $conflicts = [];

        // Check ruangan conflict
        $ruanganConflictQuery = Jadwal::where('ruangan_id', $request->ruangan_id)
            ->where('semester_id', $request->semester_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                          ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            });

        if ($request->jadwal_id) {
            $ruanganConflictQuery->where('id', '!=', $request->jadwal_id);
        }

        if ($ruanganConflictQuery->exists()) {
            $conflicts[] = [
                'type' => 'ruangan',
                'message' => 'Ruangan tidak tersedia pada waktu yang dipilih'
            ];
        }

        // Check dosen conflict
        $dosenConflictQuery = Jadwal::where('dosen_id', $request->dosen_id)
            ->where('semester_id', $request->semester_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                          ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            });

        if ($request->jadwal_id) {
            $dosenConflictQuery->where('id', '!=', $request->jadwal_id);
        }

        if ($dosenConflictQuery->exists()) {
            $conflicts[] = [
                'type' => 'dosen',
                'message' => 'Dosen sudah memiliki jadwal pada waktu yang sama'
            ];
        }

        return response()->json([
            'has_conflict' => count($conflicts) > 0,
            'conflicts' => $conflicts
        ]);
    }
}
