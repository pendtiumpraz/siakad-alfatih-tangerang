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
     * Get route prefix based on user role
     */
    protected function getRoutePrefix()
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
        // Check if showing trashed items
        $showTrashed = $request->has('trashed') && $request->trashed == 1;

        $query = $showTrashed ? Kurikulum::onlyTrashed() : Kurikulum::query();

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

        // Sorting - default by updated_at desc
        $sortColumn = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort column to prevent SQL injection
        $allowedSortColumns = ['nama_kurikulum', 'tahun_mulai', 'tahun_selesai', 'total_sks', 'is_active', 'created_at', 'updated_at'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'updated_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Pagination
        $kurikulums = $query->orderBy($sortColumn, $sortDirection)->paginate(15)->withQueryString();

        // Calculate statistics (only for non-trashed)
        $totalKurikulum = Kurikulum::count();
        $totalAktif = Kurikulum::where('is_active', true)->count();
        $totalTidakAktif = Kurikulum::where('is_active', false)->count();

        // Get all program studi for filter dropdown
        $programStudis = ProgramStudi::where('is_active', true)->get();

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.kurikulum.index", compact(
            'kurikulums',
            'programStudis',
            'totalKurikulum',
            'totalAktif',
            'totalTidakAktif',
            'sortColumn',
            'sortDirection'
        ));
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

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.kurikulum.create", compact('programStudis'));
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.kurikulum.index")
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

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.kurikulum.edit", compact('kurikulum', 'programStudis'));
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.kurikulum.index")
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.kurikulum.index")
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.kurikulum.index")
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.kurikulum.index")
                ->with('success', 'Kurikulum permanently deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete Kurikulum: ' . $e->getMessage());
        }
    }

    /**
     * Batch delete (soft delete) multiple kurikulum
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        try {
            $count = Kurikulum::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} Kurikulum berhasil dihapus.",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Batch restore multiple soft-deleted kurikulum
     */
    public function batchRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        try {
            $count = Kurikulum::onlyTrashed()->whereIn('id', $request->ids)->restore();

            return response()->json([
                'success' => true,
                'message' => "{$count} Kurikulum berhasil di-restore.",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal me-restore data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
