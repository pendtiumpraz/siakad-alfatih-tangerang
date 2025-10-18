<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kurikulum;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KurikulumController extends Controller
{
    /**
     * Get view path prefix based on user role
     */
    protected function getViewPrefix()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return 'admin';
        } elseif ($user->isOperator()) {
            return 'operator';
        } elseif ($user->isDosen()) {
            return 'dosen';
        }

        return 'admin'; // default
    }

    /**
     * Display a listing of kurikulum with pagination, search, and filters
     */
    public function index(Request $request)
    {
        $query = Kurikulum::query()->withTrashed();

        // Search logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('nama_kurikulum', 'like', "%{$search}%");
        }

        // Filter by program_studi_id
        if ($request->has('program_studi_id') && $request->program_studi_id != '') {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        // Filter by is_active
        if ($request->has('is_active') && $request->is_active != '') {
            $query->where('is_active', $request->is_active);
        }

        // Load relations with counts
        $query->with('programStudi')->withCount('mataKuliahs');

        // Pagination
        $kurikulums = $query->orderBy('tahun_mulai', 'desc')->paginate(15);

        // Get all program studi for filter dropdown
        $programStudis = ProgramStudi::where('is_active', true)->get();

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.kurikulum.index", compact('kurikulums', 'programStudis'));
    }

    /**
     * Show the form for creating a new kurikulum
     */
    public function create()
    {
        // Get all active program studi for dropdown
        $programStudis = ProgramStudi::where('is_active', true)
            ->orderBy('nama_prodi', 'asc')
            ->get();

        return view('admin.kurikulum.create', compact('programStudis'));
    }

    /**
     * Store a newly created kurikulum in database
     */
    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'nama_kurikulum' => 'required|string|max:255',
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
            'tahun_selesai' => 'required|integer|min:2000|max:2100|gte:tahun_mulai',
            'total_sks' => 'required|integer|min:1|max:200',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Set default value for is_active if not provided
            if (!isset($validated['is_active'])) {
                $validated['is_active'] = true;
            }

            Kurikulum::create($validated);

            DB::commit();

            return redirect()->route('admin.kurikulum.index')
                ->with('success', 'Kurikulum created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create Kurikulum: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified kurikulum
     */
    public function show($id)
    {
        $kurikulum = Kurikulum::withTrashed()
            ->with(['programStudi', 'mataKuliahs'])
            ->withCount('mataKuliahs')
            ->findOrFail($id);

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.kurikulum.show", compact('kurikulum'));
    }

    /**
     * Show the form for editing the specified kurikulum
     */
    public function edit($id)
    {
        $kurikulum = Kurikulum::withTrashed()->findOrFail($id);

        // Get all active program studi for dropdown
        $programStudis = ProgramStudi::where('is_active', true)
            ->orderBy('nama_prodi', 'asc')
            ->get();

        return view('admin.kurikulum.edit', compact('kurikulum', 'programStudis'));
    }

    /**
     * Update the specified kurikulum in database
     */
    public function update(Request $request, $id)
    {
        $kurikulum = Kurikulum::withTrashed()->findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'nama_kurikulum' => 'required|string|max:255',
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
            'tahun_selesai' => 'required|integer|min:2000|max:2100|gte:tahun_mulai',
            'total_sks' => 'required|integer|min:1|max:200',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $kurikulum->update($validated);

            DB::commit();

            return redirect()->route('admin.kurikulum.index')
                ->with('success', 'Kurikulum updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update Kurikulum: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified kurikulum
     */
    public function destroy($id)
    {
        try {
            $kurikulum = Kurikulum::findOrFail($id);
            $kurikulum->delete();

            return redirect()->route('admin.kurikulum.index')
                ->with('success', 'Kurikulum deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete Kurikulum: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified soft-deleted kurikulum
     */
    public function restore($id)
    {
        try {
            $kurikulum = Kurikulum::withTrashed()->findOrFail($id);
            $kurikulum->restore();

            return redirect()->route('admin.kurikulum.index')
                ->with('success', 'Kurikulum restored successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to restore Kurikulum: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified kurikulum
     */
    public function forceDelete($id)
    {
        try {
            $kurikulum = Kurikulum::withTrashed()->findOrFail($id);
            $kurikulum->forceDelete();

            return redirect()->route('admin.kurikulum.index')
                ->with('success', 'Kurikulum permanently deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete Kurikulum: ' . $e->getMessage());
        }
    }
}
