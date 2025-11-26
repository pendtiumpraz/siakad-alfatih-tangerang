<?php

namespace App\Http\Controllers;

use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SemesterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Semester::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_semester', 'like', "%{$search}%")
                  ->orWhere('tahun_akademik', 'like', "%{$search}%");
            });
        }

        // Filter by active status
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        $semesters = $query->orderBy('tahun_akademik', 'desc')
                          ->orderBy('jenis', 'desc')
                          ->paginate(15)->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $semesters
            ]);
        }

        return view('semester.index', compact('semesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('semester.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_semester' => 'required|string|max:100',
            'tahun_akademik' => 'required|string|max:20',
            'jenis' => 'required|in:Ganjil,Genap,Pendek',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        // Validate only one active semester allowed
        if (isset($validated['is_active']) && $validated['is_active']) {
            $existingActive = Semester::where('is_active', true)->exists();

            if ($existingActive) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sudah ada semester aktif. Nonaktifkan semester yang lain terlebih dahulu.'
                    ], 422);
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Sudah ada semester aktif. Nonaktifkan semester yang lain terlebih dahulu.');
            }
        }

        $semester = Semester::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semester berhasil ditambahkan',
                'data' => $semester
            ], 201);
        }

        return redirect()->route('semester.index')
            ->with('success', 'Semester berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Semester $semester)
    {
        $semester->load(['jadwals', 'nilais', 'khs', 'pembayarans']);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $semester
            ]);
        }

        return view('semester.show', compact('semester'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Semester $semester)
    {
        return view('semester.edit', compact('semester'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Semester $semester)
    {
        $validated = $request->validate([
            'nama_semester' => 'required|string|max:100',
            'tahun_akademik' => 'required|string|max:20',
            'jenis' => 'required|in:Ganjil,Genap,Pendek',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        // Validate only one active semester allowed
        if (isset($validated['is_active']) && $validated['is_active']) {
            $existingActive = Semester::where('is_active', true)
                ->where('id', '!=', $semester->id)
                ->exists();

            if ($existingActive) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Sudah ada semester aktif. Nonaktifkan semester yang lain terlebih dahulu.'
                    ], 422);
                }

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Sudah ada semester aktif. Nonaktifkan semester yang lain terlebih dahulu.');
            }
        }

        $semester->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semester berhasil diperbarui',
                'data' => $semester
            ]);
        }

        return redirect()->route('semester.index')
            ->with('success', 'Semester berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage (NOT soft delete for Semester).
     */
    public function destroy(Request $request, Semester $semester)
    {
        // Authorization check - only super_admin can delete
        if (!Auth::user() || Auth::user()->role !== 'super_admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only super admin can delete master data.'
                ], 403);
            }

            return redirect()->route('semester.index')
                ->with('error', 'Unauthorized. Only super admin can delete master data.');
        }

        // Check if semester has related data
        $hasRelatedData = $semester->jadwals()->exists()
                       || $semester->nilais()->exists()
                       || $semester->khs()->exists()
                       || $semester->pembayarans()->exists();

        if ($hasRelatedData) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semester tidak dapat dihapus karena memiliki data terkait (jadwal, nilai, KHS, atau pembayaran).'
                ], 422);
            }

            return redirect()->route('semester.index')
                ->with('error', 'Semester tidak dapat dihapus karena memiliki data terkait (jadwal, nilai, KHS, atau pembayaran).');
        }

        $semester->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Semester berhasil dihapus'
            ]);
        }

        return redirect()->route('semester.index')
            ->with('success', 'Semester berhasil dihapus');
    }

    /**
     * Set the specified semester as active and deactivate all others.
     */
    public function setActive(Request $request, $id)
    {
        // Authorization check - only super_admin and operator can set active semester
        if (!Auth::user() || !in_array(Auth::user()->role, ['super_admin', 'operator'])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only super admin and operator can set active semester.'
                ], 403);
            }

            return redirect()->route('semester.index')
                ->with('error', 'Unauthorized. Only super admin and operator can set active semester.');
        }

        DB::beginTransaction();

        try {
            // Deactivate all semesters
            Semester::query()->update(['is_active' => false]);

            // Activate the selected semester
            $semester = Semester::findOrFail($id);
            $semester->update(['is_active' => true]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Semester berhasil diaktifkan',
                    'data' => $semester
                ]);
            }

            return redirect()->route('semester.index')
                ->with('success', 'Semester berhasil diaktifkan');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengaktifkan semester'
                ], 500);
            }

            return redirect()->route('semester.index')
                ->with('error', 'Terjadi kesalahan saat mengaktifkan semester');
        }
    }
}
