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

    /**
     * Show calendar view for dosen's teaching schedule
     */
    public function calendar(Request $request)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Data dosen tidak ditemukan');
        }

        // Get filter parameters (hanya jenis_semester, tidak ada prodi karena ini dosen view)
        $jenis_semester = $request->get('jenis_semester', 'ganjil');

        // Build query - hanya jadwal dosen yang login
        $jadwals = Jadwal::with(['mataKuliah.kurikulum.programStudi', 'dosen', 'ruangan'])
            ->where('dosen_id', $dosen->id)
            ->where('jenis_semester', $jenis_semester)
            ->get();

        // Organize jadwal by day and time slot
        $calendar = [
            'Minggu' => [],
            'Senin' => [],
            'Selasa' => [],
            'Rabu' => [],
            'Kamis' => [],
            'Jumat' => [],
            'Sabtu' => []
        ];

        // Time slots from 06:00 to 22:00 (every hour)
        $timeSlots = [];
        for ($hour = 6; $hour <= 22; $hour++) {
            $timeSlots[] = sprintf('%02d:00', $hour);
        }

        // Populate calendar
        foreach ($jadwals as $jadwal) {
            $hari = $jadwal->hari;
            if ($hari === 'Ahad') {
                $hari = 'Minggu';
            }
            
            if (isset($calendar[$hari])) {
                $calendar[$hari][] = $jadwal;
            }
        }

        return view('dosen.jadwal-mengajar.calendar', compact(
            'calendar',
            'timeSlots',
            'dosen',
            'jenis_semester'
        ));
    }
}
