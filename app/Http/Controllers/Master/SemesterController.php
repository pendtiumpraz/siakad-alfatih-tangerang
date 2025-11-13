<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SemesterController extends Controller
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
     * Display a listing of semester with pagination, search, and filters
     */
    public function index(Request $request)
    {
        $query = Semester::query();

        // Search logic
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('tahun_akademik', 'like', "%{$search}%");
        }

        // Filter by jenis (ganjil/genap/pendek)
        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by is_active
        if ($request->has('is_active') && $request->is_active != '') {
            $query->where('is_active', $request->is_active);
        }

        // Load relations with counts
        $query->withCount(['jadwals', 'khs']);

        // Pagination
        $semesters = $query->orderBy('tahun_akademik', 'desc')
            ->orderBy('jenis', 'asc')
            ->paginate(15);

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.semester.index", compact('semesters'));
    }

    /**
     * Show the form for creating a new semester
     */
    public function create()
    {
        return view('admin.semester.create');
    }

    /**
     * Store a newly created semester in database
     */
    public function store(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'nama_semester' => 'required|string|max:255',
            'tahun_akademik' => 'required|string|regex:/^\d{4}\/\d{4}$/',
            'jenis' => 'required|in:ganjil,genap,pendek',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            // Set default value for is_active if not provided
            if (!isset($validated['is_active'])) {
                $validated['is_active'] = false;
            }

            // If is_active is true, set all other semesters to inactive
            if ($validated['is_active']) {
                Semester::where('is_active', true)->update(['is_active' => false]);
            }

            Semester::create($validated);

            DB::commit();

            return redirect()->route('admin.semester.index')
                ->with('success', 'Semester created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create Semester: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified semester
     */
    public function show($id)
    {
        $semester = Semester::with(['jadwals', 'khs'])
            ->withCount(['jadwals', 'khs'])
            ->findOrFail($id);

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.semester.show", compact('semester'));
    }

    /**
     * Trigger manual KHS generation for a semester
     */
    public function generateKhs($id)
    {
        try {
            $semester = Semester::findOrFail($id);
            
            // Call artisan command
            \Artisan::call('khs:generate', [
                'semester_id' => $id,
                '--force' => true,
            ]);
            
            $output = \Artisan::output();
            
            return redirect()->back()
                ->with('success', "KHS generation triggered for {$semester->tahun_akademik}. Check logs for details.");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to generate KHS: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified semester
     */
    public function edit($id)
    {
        $semester = Semester::findOrFail($id);

        return view('admin.semester.edit', compact('semester'));
    }

    /**
     * Update the specified semester in database
     */
    public function update(Request $request, $id)
    {
        $semester = Semester::findOrFail($id);

        // Validation rules
        $validated = $request->validate([
            'nama_semester' => 'required|string|max:255',
            'tahun_akademik' => 'required|string|regex:/^\d{4}\/\d{4}$/',
            'jenis' => 'required|in:ganjil,genap,pendek',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean',
            'khs_generate_date' => 'nullable|date',
            'khs_auto_generate' => 'boolean',
            'khs_show_ketua_prodi_signature' => 'boolean',
            'khs_show_dosen_pa_signature' => 'boolean',
            'khs_status' => 'nullable|in:draft,generated,approved,published',
        ]);

        try {
            DB::beginTransaction();

            // If is_active is true, set all other semesters to inactive
            if (isset($validated['is_active']) && $validated['is_active']) {
                Semester::where('id', '!=', $id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);
            }

            $semester->update($validated);

            DB::commit();

            return redirect()->route('admin.semester.index')
                ->with('success', 'Semester updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update Semester: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified semester (NO soft delete for Semester)
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $semester = Semester::findOrFail($id);

            // Check if this is the active semester
            if ($semester->is_active) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Cannot delete active semester. Please set another semester as active first.');
            }

            $semester->delete();

            DB::commit();

            return redirect()->route('admin.semester.index')
                ->with('success', 'Semester deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete Semester: ' . $e->getMessage());
        }
    }
}
