<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of mata kuliah with pagination, search, and filters
     */
    public function index(Request $request)
    {
        $query = MataKuliah::query()->withTrashed();

        // Search logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_mk', 'like', "%{$search}%")
                  ->orWhere('nama_mk', 'like', "%{$search}%");
            });
        }

        // Filter by kurikulum_id
        if ($request->has('kurikulum_id') && $request->kurikulum_id != '') {
            $query->where('kurikulum_id', $request->kurikulum_id);
        }

        // Filter by semester
        if ($request->has('semester') && $request->semester != '') {
            $query->where('semester', $request->semester);
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        // Load relations with counts
        $query->with('kurikulum.programStudi')
            ->withCount(['jadwals', 'nilais']);

        // Pagination
        $mataKuliahs = $query->orderBy('kode_mk', 'asc')->paginate(15);

        // Get all kurikulum for filter dropdown
        $kurikulums = Kurikulum::with('programStudi')
            ->where('is_active', true)
            ->get();

        return view('admin.mata-kuliah.index', compact('mataKuliahs', 'kurikulums'));
    }

    /**
     * Show the form for creating a new mata kuliah
     */
    public function create()
    {
        // Get all active kurikulum with program studi for dropdown
        $kurikulums = Kurikulum::with('programStudi')
            ->where('is_active', true)
            ->orderBy('nama_kurikulum', 'asc')
            ->get();

        return view('admin.mata-kuliah.create', compact('kurikulums'));
    }

    /**
     * Store a newly created mata kuliah in database
     */
    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'kurikulum_id' => 'required|exists:kurikulums,id',
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'jenis' => 'required|in:Wajib,Pilihan',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            MataKuliah::create($validated);

            DB::commit();

            return redirect()->route('admin.mata-kuliah.index')
                ->with('success', 'Mata Kuliah created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create Mata Kuliah: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified mata kuliah
     */
    public function show($id)
    {
        $mataKuliah = MataKuliah::withTrashed()
            ->with(['kurikulum.programStudi', 'jadwals', 'nilais'])
            ->withCount(['jadwals', 'nilais'])
            ->findOrFail($id);

        return view('admin.mata-kuliah.show', compact('mataKuliah'));
    }

    /**
     * Show the form for editing the specified mata kuliah
     */
    public function edit($id)
    {
        $mataKuliah = MataKuliah::withTrashed()->findOrFail($id);

        // Get all active kurikulum with program studi for dropdown
        $kurikulums = Kurikulum::with('programStudi')
            ->where('is_active', true)
            ->orderBy('nama_kurikulum', 'asc')
            ->get();

        return view('admin.mata-kuliah.edit', compact('mataKuliah', 'kurikulums'));
    }

    /**
     * Update the specified mata kuliah in database
     */
    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::withTrashed()->findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'kurikulum_id' => 'required|exists:kurikulums,id',
            'kode_mk' => 'required|string|max:20|unique:mata_kuliahs,kode_mk,' . $id,
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'jenis' => 'required|in:Wajib,Pilihan',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $mataKuliah->update($validated);

            DB::commit();

            return redirect()->route('admin.mata-kuliah.index')
                ->with('success', 'Mata Kuliah updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update Mata Kuliah: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified mata kuliah
     */
    public function destroy($id)
    {
        try {
            $mataKuliah = MataKuliah::findOrFail($id);
            $mataKuliah->delete();

            return redirect()->route('admin.mata-kuliah.index')
                ->with('success', 'Mata Kuliah deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete Mata Kuliah: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified soft-deleted mata kuliah
     */
    public function restore($id)
    {
        try {
            $mataKuliah = MataKuliah::withTrashed()->findOrFail($id);
            $mataKuliah->restore();

            return redirect()->route('admin.mata-kuliah.index')
                ->with('success', 'Mata Kuliah restored successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to restore Mata Kuliah: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified mata kuliah
     */
    public function forceDelete($id)
    {
        try {
            $mataKuliah = MataKuliah::withTrashed()->findOrFail($id);
            $mataKuliah->forceDelete();

            return redirect()->route('admin.mata-kuliah.index')
                ->with('success', 'Mata Kuliah permanently deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete Mata Kuliah: ' . $e->getMessage());
        }
    }
}
