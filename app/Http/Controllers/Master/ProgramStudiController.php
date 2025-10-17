<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of program studi with pagination, search, and filters
     */
    public function index(Request $request)
    {
        $query = ProgramStudi::query()->withTrashed();

        // Search logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_prodi', 'like', "%{$search}%")
                  ->orWhere('nama_prodi', 'like', "%{$search}%");
            });
        }

        // Filter by jenjang
        if ($request->has('jenjang') && $request->jenjang != '') {
            $query->where('jenjang', $request->jenjang);
        }

        // Filter by is_active
        if ($request->has('is_active') && $request->is_active != '') {
            $query->where('is_active', $request->is_active);
        }

        // Load relations with counts
        $query->withCount(['kurikulums', 'mahasiswas']);

        // Pagination
        $programStudis = $query->orderBy('kode_prodi', 'asc')->paginate(15);

        return view('admin.program-studi.index', compact('programStudis'));
    }

    /**
     * Show the form for creating a new program studi
     */
    public function create()
    {
        return view('admin.program-studi.create');
    }

    /**
     * Store a newly created program studi in database
     */
    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:program_studis,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Set default value for is_active if not provided
            if (!isset($validated['is_active'])) {
                $validated['is_active'] = true;
            }

            ProgramStudi::create($validated);

            DB::commit();

            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program Studi created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create Program Studi: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified program studi
     */
    public function show($id)
    {
        $programStudi = ProgramStudi::withTrashed()
            ->with(['kurikulums', 'mahasiswas'])
            ->withCount(['kurikulums', 'mahasiswas'])
            ->findOrFail($id);

        return view('admin.program-studi.show', compact('programStudi'));
    }

    /**
     * Show the form for editing the specified program studi
     */
    public function edit($id)
    {
        $programStudi = ProgramStudi::withTrashed()->findOrFail($id);

        return view('admin.program-studi.edit', compact('programStudi'));
    }

    /**
     * Update the specified program studi in database
     */
    public function update(Request $request, $id)
    {
        $programStudi = ProgramStudi::withTrashed()->findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'kode_prodi' => 'required|string|max:10|unique:program_studis,kode_prodi,' . $id,
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $programStudi->update($validated);

            DB::commit();

            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program Studi updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update Program Studi: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified program studi
     */
    public function destroy($id)
    {
        try {
            $programStudi = ProgramStudi::findOrFail($id);
            $programStudi->delete();

            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program Studi deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete Program Studi: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified soft-deleted program studi
     */
    public function restore($id)
    {
        try {
            $programStudi = ProgramStudi::withTrashed()->findOrFail($id);
            $programStudi->restore();

            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program Studi restored successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to restore Program Studi: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified program studi
     */
    public function forceDelete($id)
    {
        try {
            $programStudi = ProgramStudi::withTrashed()->findOrFail($id);
            $programStudi->forceDelete();

            return redirect()->route('admin.program-studi.index')
                ->with('success', 'Program Studi permanently deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete Program Studi: ' . $e->getMessage());
        }
    }
}
