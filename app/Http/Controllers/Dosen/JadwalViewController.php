<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Dosen;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalViewController extends Controller
{
    /**
     * Display a listing of jadwal for logged-in dosen (VIEW ONLY)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->with('programStudis')->first();

        if (!$dosen) {
            abort(403, 'Data dosen tidak ditemukan');
        }

        // Get filter data
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();

        // Build query - only show jadwal for this dosen
        $query = Jadwal::where('dosen_id', $dosen->id)
            ->with(['mataKuliah.kurikulum.programStudi', 'ruangan', 'semester']);

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
            ->paginate(15)->withQueryString();

        $hariOptions = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        return view('dosen.jadwal-mengajar.index', compact('jadwals', 'dosen', 'semesters', 'hariOptions'));
    }

    /**
     * Display the specified jadwal (VIEW ONLY)
     */
    public function show($id)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Data dosen tidak ditemukan');
        }

        // Verify jadwal belongs to this dosen
        $jadwal = Jadwal::where('dosen_id', $dosen->id)
            ->with(['mataKuliah.kurikulum.programStudi', 'ruangan', 'semester'])
            ->findOrFail($id);

        return view('dosen.jadwal-mengajar.show', compact('jadwal', 'dosen'));
    }
}
