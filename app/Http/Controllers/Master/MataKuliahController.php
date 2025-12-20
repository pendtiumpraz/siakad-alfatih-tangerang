<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MataKuliahController extends Controller
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
     * Display a listing of mata kuliah with pagination, search, and filters
     */
    public function index(Request $request)
    {
        // Check if showing trashed items
        $showTrashed = $request->has('trashed') && $request->trashed == 1;

        $query = $showTrashed ? MataKuliah::onlyTrashed() : MataKuliah::query();

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

        // Sorting - default by updated_at desc
        $sortColumn = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort column to prevent SQL injection
        $allowedSortColumns = ['kode_mk', 'nama_mk', 'sks', 'semester', 'jenis', 'created_at', 'updated_at'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'updated_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Pagination
        $mataKuliahs = $query->orderBy($sortColumn, $sortDirection)->paginate(15)->withQueryString();

        // Calculate statistics (only for non-trashed)
        $totalMataKuliah = MataKuliah::count();
        $totalSKS = MataKuliah::sum('sks');
        $totalWajib = MataKuliah::where('jenis', 'Wajib')->count();
        $totalPilihan = MataKuliah::where('jenis', 'Pilihan')->count();

        // Get all kurikulum for filter dropdown
        $kurikulums = Kurikulum::with('programStudi')
            ->where('is_active', true)
            ->get();

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.mata-kuliah.index", compact(
            'mataKuliahs',
            'kurikulums',
            'totalMataKuliah',
            'totalSKS',
            'totalWajib',
            'totalPilihan',
            'sortColumn',
            'sortDirection'
        ));
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

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.mata-kuliah.create", compact('kurikulums'));
    }

    /**
     * Store a newly created mata kuliah in database
     */
    public function store(Request $request)
    {
        // Validation rules - unique ignores soft deleted records
        $validated = $request->validate([
            'kurikulum_id' => 'required|exists:kurikulums,id',
            'kode_mk' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mata_kuliahs', 'kode_mk')->whereNull('deleted_at'),
            ],
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.mata-kuliah.index")
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

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.mata-kuliah.show", compact('mataKuliah'));
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

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.mata-kuliah.edit", compact('mataKuliah', 'kurikulums'));
    }

    /**
     * Update the specified mata kuliah in database
     */
    public function update(Request $request, $id)
    {
        $mataKuliah = MataKuliah::withTrashed()->findOrFail($id);

        // Validation rules - unique ignores soft deleted records
        $validated = $request->validate([
            'kurikulum_id' => 'required|exists:kurikulums,id',
            'kode_mk' => [
                'required',
                'string',
                'max:20',
                Rule::unique('mata_kuliahs', 'kode_mk')->whereNull('deleted_at')->ignore($id),
            ],
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.mata-kuliah.index")
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.mata-kuliah.index")
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

            // Check if kode_mk already exists in active records
            $existingRecord = MataKuliah::where('kode_mk', $mataKuliah->kode_mk)->first();
            if ($existingRecord) {
                return redirect()->back()
                    ->with('error', "Tidak dapat me-restore: Kode Mata Kuliah '{$mataKuliah->kode_mk}' sudah digunakan oleh data lain.");
            }

            $mataKuliah->restore();

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.mata-kuliah.index")
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

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.mata-kuliah.index")
                ->with('success', 'Mata Kuliah permanently deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete Mata Kuliah: ' . $e->getMessage());
        }
    }

    /**
     * Batch delete (soft delete) multiple mata kuliah
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        try {
            $count = MataKuliah::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} Mata Kuliah berhasil dihapus.",
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
     * Batch restore multiple soft-deleted mata kuliah
     */
    public function batchRestore(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        try {
            $restoredCount = 0;
            $failedCount = 0;
            $failedItems = [];

            $trashedItems = MataKuliah::onlyTrashed()->whereIn('id', $request->ids)->get();

            foreach ($trashedItems as $item) {
                // Check if kode_mk already exists in active records
                $existingRecord = MataKuliah::where('kode_mk', $item->kode_mk)->first();
                if ($existingRecord) {
                    $failedCount++;
                    $failedItems[] = $item->kode_mk;
                } else {
                    $item->restore();
                    $restoredCount++;
                }
            }

            if ($failedCount > 0 && $restoredCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "{$restoredCount} Mata Kuliah berhasil di-restore. {$failedCount} gagal karena kode sudah digunakan: " . implode(', ', $failedItems),
                    'count' => $restoredCount,
                ]);
            } elseif ($failedCount > 0 && $restoredCount == 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Semua gagal di-restore karena kode sudah digunakan: " . implode(', ', $failedItems),
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => "{$restoredCount} Mata Kuliah berhasil di-restore.",
                'count' => $restoredCount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal me-restore data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
