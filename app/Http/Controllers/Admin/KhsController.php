<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Khs;
use App\Models\Semester;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class KhsController extends Controller
{
    /**
     * Display a listing of KHS with filters
     */
    public function index(Request $request)
    {
        $query = Khs::with(['mahasiswa.programStudi', 'semester']);

        // Filter by semester
        if ($request->has('semester_id') && $request->semester_id != '') {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by program studi
        if ($request->has('program_studi_id') && $request->program_studi_id != '') {
            $query->whereHas('mahasiswa', function($q) use ($request) {
                $q->where('program_studi_id', $request->program_studi_id);
            });
        }

        // Search by NIM or nama
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('mahasiswa', function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }

        // Filter by IP range
        if ($request->has('ip_min') && $request->ip_min != '') {
            $query->where('ip', '>=', $request->ip_min);
        }
        if ($request->has('ip_max') && $request->ip_max != '') {
            $query->where('ip', '<=', $request->ip_max);
        }

        $khsList = $query->orderBy('created_at', 'desc')
            ->paginate(20)->withQueryString();

        // Get data for filters
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        $programStudis = ProgramStudi::all();

        return view('admin.khs.index', compact('khsList', 'semesters', 'programStudis'));
    }

    /**
     * Display the specified KHS
     */
    public function show($id)
    {
        $khs = Khs::with([
            'mahasiswa.programStudi', 
            'mahasiswa.dosenPa',
            'semester',
            'mahasiswa.nilais' => function($q) use ($id) {
                $khs = Khs::find($id);
                if ($khs) {
                    $q->where('semester_id', $khs->semester_id)
                      ->with('mataKuliah');
                }
            }
        ])->findOrFail($id);

        return view('admin.khs.show', compact('khs'));
    }
}
