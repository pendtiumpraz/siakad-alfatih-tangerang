<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RuanganController extends Controller
{
    /**
     * Get the view prefix based on user role
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
     * Display a listing of ruangan with pagination, search, and filters
     */
    public function index(Request $request)
    {
        // Check if showing trashed items
        $showTrashed = $request->has('trashed') && $request->trashed == 1;

        $query = $showTrashed ? Ruangan::onlyTrashed() : Ruangan::query();

        // Search logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_ruangan', 'like', "%{$search}%")
                  ->orWhere('nama_ruangan', 'like', "%{$search}%");
            });
        }

        // Filter by is_available
        if ($request->has('is_available') && $request->is_available != '') {
            $query->where('is_available', $request->is_available);
        }

        // Filter by kapasitas (minimum)
        if ($request->has('kapasitas_min') && $request->kapasitas_min != '') {
            $query->where('kapasitas', '>=', $request->kapasitas_min);
        }

        // Filter by kapasitas (maximum)
        if ($request->has('kapasitas_max') && $request->kapasitas_max != '') {
            $query->where('kapasitas', '<=', $request->kapasitas_max);
        }

        // Load relations with counts
        $query->withCount('jadwals');

        // Sorting - default by updated_at desc
        $sortColumn = $request->get('sort', 'updated_at');
        $sortDirection = $request->get('direction', 'desc');
        
        // Validate sort column to prevent SQL injection
        $allowedSortColumns = ['kode_ruangan', 'nama_ruangan', 'kapasitas', 'tipe', 'is_available', 'created_at', 'updated_at'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'updated_at';
        }
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        // Pagination
        $ruangans = $query->orderBy($sortColumn, $sortDirection)->paginate(15)->withQueryString();

        // Calculate statistics (only for non-trashed)
        $totalRuangan = Ruangan::count();
        $totalTersedia = Ruangan::where('is_available', true)->count();
        $totalTidakTersedia = Ruangan::where('is_available', false)->count();
        $totalKapasitas = Ruangan::sum('kapasitas');

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.ruangan.index", compact(
            'ruangans',
            'totalRuangan',
            'totalTersedia',
            'totalTidakTersedia',
            'totalKapasitas',
            'sortColumn',
            'sortDirection'
        ));
    }

    /**
     * Show the form for creating a new ruangan
     */
    public function create()
    {
        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.ruangan.create");
    }

    /**
     * Store a newly created ruangan in database
     */
    public function store(Request $request)
    {
        // Validation rules - unique ignores soft deleted records
        $validated = $request->validate([
            'kode_ruangan' => [
                'required',
                'string',
                'max:20',
                Rule::unique('ruangans', 'kode_ruangan')->whereNull('deleted_at'),
            ],
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1|max:500',
            'tipe' => 'required|in:daring,luring',
            'url' => 'nullable|url|max:500|required_if:tipe,daring',
            'fasilitas' => 'nullable|string',
            'is_available' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Set default value for is_available if not provided
            if (!isset($validated['is_available'])) {
                $validated['is_available'] = true;
            }

            // Clear URL if not daring
            if ($validated['tipe'] !== 'daring') {
                $validated['url'] = null;
            }

            Ruangan::create($validated);

            DB::commit();

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.ruangan.index")
                ->with('success', 'Ruangan created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create Ruangan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified ruangan
     */
    public function show($id)
    {
        $ruangan = Ruangan::withTrashed()
            ->with('jadwals')
            ->withCount('jadwals')
            ->findOrFail($id);

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.ruangan.show", compact('ruangan'));
    }

    /**
     * Show the form for editing the specified ruangan
     */
    public function edit($id)
    {
        $ruangan = Ruangan::withTrashed()->findOrFail($id);

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.ruangan.edit", compact('ruangan'));
    }

    /**
     * Update the specified ruangan in database
     */
    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::withTrashed()->findOrFail($id);

        // Validation rules - unique ignores soft deleted records
        $validated = $request->validate([
            'kode_ruangan' => [
                'required',
                'string',
                'max:20',
                Rule::unique('ruangans', 'kode_ruangan')->whereNull('deleted_at')->ignore($id),
            ],
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1|max:500',
            'tipe' => 'required|in:daring,luring',
            'url' => 'nullable|url|max:500|required_if:tipe,daring',
            'fasilitas' => 'nullable|string',
            'is_available' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Clear URL if not daring
            if ($validated['tipe'] !== 'daring') {
                $validated['url'] = null;
            }

            $ruangan->update($validated);

            DB::commit();

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.ruangan.index")
                ->with('success', 'Ruangan updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update Ruangan: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified ruangan
     */
    public function destroy($id)
    {
        try {
            $ruangan = Ruangan::findOrFail($id);
            $ruangan->delete();

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.ruangan.index")
                ->with('success', 'Ruangan deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete Ruangan: ' . $e->getMessage());
        }
    }

    /**
     * Restore the specified soft-deleted ruangan
     */
    public function restore($id)
    {
        try {
            $ruangan = Ruangan::withTrashed()->findOrFail($id);

            // Check if kode_ruangan already exists in active records
            $existingRecord = Ruangan::where('kode_ruangan', $ruangan->kode_ruangan)->first();
            if ($existingRecord) {
                return redirect()->back()
                    ->with('error', "Tidak dapat me-restore: Kode Ruangan '{$ruangan->kode_ruangan}' sudah digunakan oleh data lain.");
            }

            $ruangan->restore();

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.ruangan.index")
                ->with('success', 'Ruangan restored successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to restore Ruangan: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified ruangan
     */
    public function forceDelete($id)
    {
        try {
            $ruangan = Ruangan::withTrashed()->findOrFail($id);
            $ruangan->forceDelete();

            $routePrefix = $this->getRoutePrefix();
            return redirect()->route("{$routePrefix}.ruangan.index")
                ->with('success', 'Ruangan permanently deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete Ruangan: ' . $e->getMessage());
        }
    }

    /**
     * Batch delete (soft delete) multiple ruangan
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        try {
            $count = Ruangan::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} Ruangan berhasil dihapus.",
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
     * Batch restore multiple soft-deleted ruangan
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

            $trashedItems = Ruangan::onlyTrashed()->whereIn('id', $request->ids)->get();

            foreach ($trashedItems as $item) {
                // Check if kode_ruangan already exists in active records
                $existingRecord = Ruangan::where('kode_ruangan', $item->kode_ruangan)->first();
                if ($existingRecord) {
                    $failedCount++;
                    $failedItems[] = $item->kode_ruangan;
                } else {
                    $item->restore();
                    $restoredCount++;
                }
            }

            if ($failedCount > 0 && $restoredCount > 0) {
                return response()->json([
                    'success' => true,
                    'message' => "{$restoredCount} Ruangan berhasil di-restore. {$failedCount} gagal karena kode sudah digunakan: " . implode(', ', $failedItems),
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
                'message' => "{$restoredCount} Ruangan berhasil di-restore.",
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
