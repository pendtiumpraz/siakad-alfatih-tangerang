<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NimRange;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NimRangeController extends Controller
{
    /**
     * Display a listing of the NIM ranges
     */
    public function index(Request $request)
    {
        $query = NimRange::with('programStudi');

        // Filter by tahun_masuk
        if ($request->filled('tahun_masuk')) {
            $query->where('tahun_masuk', $request->tahun_masuk);
        }

        // Filter by prodi
        if ($request->filled('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        // Get all data grouped by year
        $nimRanges = $query->orderBy('tahun_masuk', 'desc')
            ->orderBy('program_studi_id')
            ->get()
            ->groupBy('tahun_masuk');

        // Get unique years for filter
        $years = NimRange::select('tahun_masuk')
            ->distinct()
            ->orderBy('tahun_masuk', 'desc')
            ->pluck('tahun_masuk');

        // Get all program studi for filter
        $programStudis = ProgramStudi::orderBy('nama_prodi')->get();

        return view('admin.nim-range.index', compact('nimRanges', 'years', 'programStudis'));
    }

    /**
     * Show the form for creating a new NIM range
     */
    public function create()
    {
        $programStudis = ProgramStudi::orderBy('nama_prodi')->get();
        $currentYear = date('Y');

        return view('admin.nim-range.create', compact('programStudis', 'currentYear'));
    }

    /**
     * Store a newly created NIM range
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'tahun_masuk' => 'required|integer|min:2000|max:2100',
            'max_number' => 'required|integer|min:1|max:9999',
        ]);

        // Check if already exists
        $exists = NimRange::where('program_studi_id', $validated['program_studi_id'])
            ->where('tahun_masuk', $validated['tahun_masuk'])
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['program_studi_id' => 'NIM Range untuk Program Studi dan Tahun ini sudah ada.']);
        }

        // Get program studi to generate prefix
        $prodi = ProgramStudi::findOrFail($validated['program_studi_id']);

        // Generate prefix: YY + Kode Prodi (3 digits)
        $yearPrefix = substr($validated['tahun_masuk'], 2, 2); // Last 2 digits of year
        $prodiCode = str_pad($prodi->kode_prodi, 3, '0', STR_PAD_LEFT); // Pad to 3 digits
        $prefix = $yearPrefix . $prodiCode;

        // Create NIM range
        NimRange::create([
            'program_studi_id' => $validated['program_studi_id'],
            'tahun_masuk' => $validated['tahun_masuk'],
            'prefix' => $prefix,
            'current_number' => 0,
            'max_number' => $validated['max_number'],
        ]);

        return redirect()
            ->route('admin.nim-ranges.index')
            ->with('success', 'NIM Range berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified NIM range
     */
    public function edit($id)
    {
        $nimRange = NimRange::with('programStudi')->findOrFail($id);

        return view('admin.nim-range.edit', compact('nimRange'));
    }

    /**
     * Update the specified NIM range
     */
    public function update(Request $request, $id)
    {
        $nimRange = NimRange::findOrFail($id);

        $validated = $request->validate([
            'max_number' => 'required|integer|min:' . $nimRange->current_number,
        ], [
            'max_number.min' => 'Maksimal nomor tidak boleh kurang dari nomor saat ini (' . $nimRange->current_number . ').',
        ]);

        $nimRange->update([
            'max_number' => $validated['max_number'],
        ]);

        return redirect()
            ->route('admin.nim-ranges.index')
            ->with('success', 'NIM Range berhasil diperbarui.');
    }

    /**
     * Remove the specified NIM range
     */
    public function destroy($id)
    {
        $nimRange = NimRange::findOrFail($id);

        // Only allow deletion if current_number is 0
        if ($nimRange->current_number > 0) {
            return back()->withErrors(['delete' => 'NIM Range tidak dapat dihapus karena sudah digunakan.']);
        }

        $nimRange->delete();

        return redirect()
            ->route('admin.nim-ranges.index')
            ->with('success', 'NIM Range berhasil dihapus.');
    }

    /**
     * Bulk create NIM ranges for all prodi in a year
     */
    public function bulkCreate(Request $request)
    {
        $validated = $request->validate([
            'tahun_masuk' => 'required|integer|min:2000|max:2100',
            'max_number' => 'required|integer|min:1|max:9999',
        ]);

        try {
            DB::beginTransaction();

            $programStudis = ProgramStudi::all();
            $created = 0;
            $skipped = 0;

            foreach ($programStudis as $prodi) {
                // Check if already exists
                $exists = NimRange::where('program_studi_id', $prodi->id)
                    ->where('tahun_masuk', $validated['tahun_masuk'])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                // Generate prefix
                $yearPrefix = substr($validated['tahun_masuk'], 2, 2);
                $prodiCode = str_pad($prodi->kode_prodi, 3, '0', STR_PAD_LEFT);
                $prefix = $yearPrefix . $prodiCode;

                // Create NIM range
                NimRange::create([
                    'program_studi_id' => $prodi->id,
                    'tahun_masuk' => $validated['tahun_masuk'],
                    'prefix' => $prefix,
                    'current_number' => 0,
                    'max_number' => $validated['max_number'],
                ]);

                $created++;
            }

            DB::commit();

            $message = "Berhasil membuat $created NIM Range.";
            if ($skipped > 0) {
                $message .= " $skipped NIM Range dilewati karena sudah ada.";
            }

            return redirect()
                ->route('admin.nim-ranges.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->withErrors(['bulk' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Batch delete multiple NIM ranges
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        try {
            // Check if any of the selected NIM ranges are in use
            $usedRanges = NimRange::whereIn('id', $request->ids)->where('current_number', '>', 0)->count();
            if ($usedRanges > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus NIM Range yang sudah digunakan.',
                ], 400);
            }

            $count = NimRange::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} NIM Range berhasil dihapus.",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
