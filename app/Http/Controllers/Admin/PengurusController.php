<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengurusController extends Controller
{
    /**
     * Display pengurus management page
     */
    public function index(Request $request)
    {
        // Get all program studi with ketua prodi
        $programStudis = ProgramStudi::with('ketuaProdi')->get();

        // Get dosen wali statistics
        $dosenWaliStats = Dosen::withCount('mahasiswaBimbingan')->get();

        // Get mahasiswa without dosen wali
        $mahasiswaTanpaWali = Mahasiswa::whereNull('dosen_wali_id')->count();

        // Get all dosen for assignment
        $dosens = Dosen::with('user')->orderBy('nama_lengkap')->get();

        return view('admin.pengurus.index', compact(
            'programStudis',
            'dosenWaliStats',
            'mahasiswaTanpaWali',
            'dosens'
        ));
    }

    /**
     * Assign ketua prodi to program studi
     */
    public function assignKetuaProdi(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'dosen_id' => 'required|exists:dosens,id',
        ]);

        try {
            $programStudi = ProgramStudi::findOrFail($validated['program_studi_id']);
            $programStudi->update(['ketua_prodi_id' => $validated['dosen_id']]);

            return redirect()->route('admin.pengurus.index')
                ->with('success', 'Ketua Prodi berhasil ditunjuk');
        } catch (\Exception $e) {
            return redirect()->route('admin.pengurus.index')
                ->with('error', 'Gagal menunjuk Ketua Prodi: ' . $e->getMessage());
        }
    }

    /**
     * Remove ketua prodi from program studi
     */
    public function removeKetuaProdi($programStudiId)
    {
        try {
            $programStudi = ProgramStudi::findOrFail($programStudiId);
            $programStudi->update(['ketua_prodi_id' => null]);

            return redirect()->route('admin.pengurus.index')
                ->with('success', 'Ketua Prodi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('admin.pengurus.index')
                ->with('error', 'Gagal menghapus Ketua Prodi: ' . $e->getMessage());
        }
    }

    /**
     * Show dosen wali management page
     */
    public function dosenWali(Request $request)
    {
        $query = Mahasiswa::with(['dosenWali', 'programStudi']);

        // Filter by program studi
        if ($request->filled('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        // Filter by dosen wali
        if ($request->filled('dosen_wali_id')) {
            $query->where('dosen_wali_id', $request->dosen_wali_id);
        }

        // Filter mahasiswa without wali
        if ($request->filled('tanpa_wali') && $request->tanpa_wali == '1') {
            $query->whereNull('dosen_wali_id');
        }

        $mahasiswas = $query->paginate(20);
        $programStudis = ProgramStudi::all();
        $dosens = Dosen::with('user')->orderBy('nama_lengkap')->get();

        return view('admin.pengurus.dosen-wali', compact(
            'mahasiswas',
            'programStudis',
            'dosens'
        ));
    }

    /**
     * Assign dosen wali to mahasiswa
     */
    public function assignDosenWali(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'dosen_wali_id' => 'required|exists:dosens,id',
        ]);

        try {
            $mahasiswa = Mahasiswa::findOrFail($validated['mahasiswa_id']);
            $mahasiswa->update(['dosen_wali_id' => $validated['dosen_wali_id']]);

            return redirect()->back()
                ->with('success', 'Dosen Wali berhasil ditunjuk');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menunjuk Dosen Wali: ' . $e->getMessage());
        }
    }

    /**
     * Bulk assign dosen wali by program studi
     */
    public function bulkAssignDosenWali(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'dosen_wali_id' => 'required|exists:dosens,id',
        ]);

        try {
            DB::beginTransaction();

            // Assign dosen wali to all mahasiswa in this prodi without wali
            $updated = Mahasiswa::where('program_studi_id', $validated['program_studi_id'])
                ->whereNull('dosen_wali_id')
                ->update(['dosen_wali_id' => $validated['dosen_wali_id']]);

            DB::commit();

            return redirect()->back()
                ->with('success', "Berhasil menunjuk Dosen Wali untuk {$updated} mahasiswa");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menunjuk Dosen Wali: ' . $e->getMessage());
        }
    }

    /**
     * Remove dosen wali from mahasiswa
     */
    public function removeDosenWali($mahasiswaId)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($mahasiswaId);
            $mahasiswa->update(['dosen_wali_id' => null]);

            return redirect()->back()
                ->with('success', 'Dosen Wali berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus Dosen Wali: ' . $e->getMessage());
        }
    }
}
