<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class JadwalController extends Controller
{
    /**
     * Display a listing of jadwal for logged-in dosen
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

        // Get filter data
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();

        // Build query with filters - only show jadwal for assigned program studi
        $query = Jadwal::where('dosen_id', $dosen->id)
            ->with(['mataKuliah.kurikulum.programStudi', 'ruangan', 'semester'])
            ->whereHas('mataKuliah.kurikulum', function($q) use ($prodiIds) {
                $q->whereIn('program_studi_id', $prodiIds);
            });

        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by hari
        if ($request->filled('hari')) {
            $query->where('hari', $request->hari);
        }

        $jadwals = $query->orderBy('semester_id', 'desc')
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->paginate(15);

        return view('dosen.jadwal.index', compact('jadwals', 'dosen', 'semesters'));
    }

    /**
     * Show the form for creating a new jadwal
     */
    public function create()
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

        // Only show mata kuliah from assigned program studi
        $mataKuliahs = MataKuliah::whereHas('kurikulum', function($q) use ($prodiIds) {
                $q->whereIn('program_studi_id', $prodiIds);
            })
            ->with('kurikulum.programStudi')
            ->orderBy('nama_mk')
            ->get();

        // Only show ruangan that are used by assigned program studi (or all available if no specific assignment)
        $ruangans = Ruangan::where('is_available', true)->orderBy('nama_ruangan')->get();
        
        $semesters = Semester::where('is_active', true)->orderBy('tahun_akademik', 'desc')->get();

        $hariOptions = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];

        return view('dosen.jadwal.create', compact(
            'dosen',
            'mataKuliahs',
            'ruangans',
            'semesters',
            'hariOptions'
        ));
    }

    /**
     * Store a newly created jadwal in storage
     */
    public function store(Request $request)
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
            'semester_id' => 'required|exists:semesters,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'ruangan_id' => 'required|exists:ruangans,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kelas' => 'required|string|max:10',
        ]);

        // Verify mata kuliah is from assigned program studi
        $mataKuliah = MataKuliah::whereHas('kurikulum', function($q) use ($prodiIds) {
                $q->whereIn('program_studi_id', $prodiIds);
            })
            ->find($validated['mata_kuliah_id']);

        if (!$mataKuliah) {
            throw ValidationException::withMessages([
                'mata_kuliah_id' => ['Mata kuliah tidak termasuk dalam program studi Anda']
            ]);
        }

        // Validate time slots (must be within operational hours)
        $jamMulai = strtotime($validated['jam_mulai']);
        $jamSelesai = strtotime($validated['jam_selesai']);

        if ($jamMulai < strtotime('07:00') || $jamSelesai > strtotime('21:00')) {
            throw ValidationException::withMessages([
                'jam_mulai' => ['Jadwal harus berada dalam jam operasional (07:00 - 21:00)']
            ]);
        }

        // Check ruangan availability (no conflict same day/time)
        $ruanganConflict = Jadwal::where('ruangan_id', $validated['ruangan_id'])
            ->where('semester_id', $validated['semester_id'])
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
                'ruangan_id' => ['Ruangan tidak tersedia pada hari dan waktu yang dipilih']
            ]);
        }

        // Check dosen schedule (no conflict)
        $dosenConflict = Jadwal::where('dosen_id', $dosen->id)
            ->where('semester_id', $validated['semester_id'])
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
                'jam_mulai' => ['Anda sudah memiliki jadwal pada hari dan waktu yang sama']
            ]);
        }

        // Create jadwal
        $validated['dosen_id'] = $dosen->id;
        Jadwal::create($validated);

        return redirect()->route('dosen.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified jadwal
     */
    public function edit($id)
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

        // Verify jadwal belongs to dosen and is from assigned program studi
        $jadwal = Jadwal::where('dosen_id', $dosen->id)
            ->whereHas('mataKuliah.kurikulum', function($q) use ($prodiIds) {
                $q->whereIn('program_studi_id', $prodiIds);
            })
            ->findOrFail($id);

        // Only show mata kuliah from assigned program studi
        $mataKuliahs = MataKuliah::whereHas('kurikulum', function($q) use ($prodiIds) {
                $q->whereIn('program_studi_id', $prodiIds);
            })
            ->with('kurikulum.programStudi')
            ->orderBy('nama_mk')
            ->get();
            
        $ruangans = Ruangan::where('is_available', true)->orderBy('nama_ruangan')->get();
        $semesters = Semester::where('is_active', true)->orderBy('tahun_akademik', 'desc')->get();

        $hariOptions = [
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        ];

        return view('dosen.jadwal.edit', compact(
            'jadwal',
            'dosen',
            'mataKuliahs',
            'ruangans',
            'semesters',
            'hariOptions'
        ));
    }

    /**
     * Update the specified jadwal in storage
     */
    public function update(Request $request, $id)
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

        // Verify jadwal belongs to dosen and is from assigned program studi
        $jadwal = Jadwal::where('dosen_id', $dosen->id)
            ->whereHas('mataKuliah.kurikulum', function($q) use ($prodiIds) {
                $q->whereIn('program_studi_id', $prodiIds);
            })
            ->findOrFail($id);

        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
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

        // Check ruangan availability (no conflict same day/time, excluding current jadwal)
        $ruanganConflict = Jadwal::where('ruangan_id', $validated['ruangan_id'])
            ->where('semester_id', $validated['semester_id'])
            ->where('hari', $validated['hari'])
            ->where('id', '!=', $id)
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
                'ruangan_id' => ['Ruangan tidak tersedia pada hari dan waktu yang dipilih']
            ]);
        }

        // Check dosen schedule (no conflict, excluding current jadwal)
        $dosenConflict = Jadwal::where('dosen_id', $dosen->id)
            ->where('semester_id', $validated['semester_id'])
            ->where('hari', $validated['hari'])
            ->where('id', '!=', $id)
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
                'jam_mulai' => ['Anda sudah memiliki jadwal pada hari dan waktu yang sama']
            ]);
        }

        // Update jadwal
        $jadwal->update($validated);

        return redirect()->route('dosen.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    /**
     * Remove the specified jadwal from storage (soft delete)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        $jadwal = Jadwal::where('dosen_id', $dosen->id)->findOrFail($id);
        $jadwal->delete();

        return redirect()->route('dosen.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    /**
     * AJAX endpoint for real-time conflict check
     */
    public function checkConflict(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'ruangan_id' => 'required|exists:ruangans,id',
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
        $dosenConflictQuery = Jadwal::where('dosen_id', $dosen->id)
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
                'message' => 'Anda sudah memiliki jadwal pada waktu yang sama'
            ];
        }

        return response()->json([
            'has_conflict' => count($conflicts) > 0,
            'conflicts' => $conflicts
        ]);
    }
}
