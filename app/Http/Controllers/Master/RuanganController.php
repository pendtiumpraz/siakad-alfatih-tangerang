<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Display a listing of ruangan with pagination, search, and filters
     */
    public function index(Request $request)
    {
        $query = Ruangan::query()->withTrashed();

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

        // Pagination
        $ruangans = $query->orderBy('kode_ruangan', 'asc')->paginate(15);

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.ruangan.index", compact('ruangans'));
    }

    /**
     * Show the form for creating a new ruangan
     */
    public function create()
    {
        return view('admin.ruangan.create');
    }

    /**
     * Store a newly created ruangan in database
     */
    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:20|unique:ruangans,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1|max:500',
            'fasilitas' => 'nullable|string',
            'is_available' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Set default value for is_available if not provided
            if (!isset($validated['is_available'])) {
                $validated['is_available'] = true;
            }

            Ruangan::create($validated);

            DB::commit();

            return redirect()->route('admin.ruangan.index')
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

        return view('admin.ruangan.edit', compact('ruangan'));
    }

    /**
     * Update the specified ruangan in database
     */
    public function update(Request $request, $id)
    {
        $ruangan = Ruangan::withTrashed()->findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'kode_ruangan' => 'required|string|max:20|unique:ruangans,kode_ruangan,' . $id,
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1|max:500',
            'fasilitas' => 'nullable|string',
            'is_available' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $ruangan->update($validated);

            DB::commit();

            return redirect()->route('admin.ruangan.index')
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

            return redirect()->route('admin.ruangan.index')
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
            $ruangan->restore();

            return redirect()->route('admin.ruangan.index')
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

            return redirect()->route('admin.ruangan.index')
                ->with('success', 'Ruangan permanently deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to permanently delete Ruangan: ' . $e->getMessage());
        }
    }
}
