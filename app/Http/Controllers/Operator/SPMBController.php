<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\JalurSeleksi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class SPMBController extends Controller
{
    /**
     * Display a listing of pendaftars with filters
     */
    public function index(Request $request)
    {
        $query = Pendaftar::with(['jalurSeleksi', 'programStudiPilihan1', 'programStudiPilihan2']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by jalur seleksi
        if ($request->filled('jalur_seleksi_id')) {
            $query->where('jalur_seleksi_id', $request->jalur_seleksi_id);
        }

        // Filter by program studi (either pilihan 1 or 2)
        if ($request->filled('program_studi_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('program_studi_pilihan_1', $request->program_studi_id)
                  ->orWhere('program_studi_pilihan_2', $request->program_studi_id);
            });
        }

        // Search by nama, nomor pendaftaran, or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('nomor_pendaftaran', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        $pendaftars = $query->paginate(15)->withQueryString();

        // Get filter options
        $jalurSeleksis = JalurSeleksi::active()->get();
        $programStudis = ProgramStudi::orderBy('nama_prodi')->get();

        return view('operator.spmb.index', compact('pendaftars', 'jalurSeleksis', 'programStudis'));
    }

    /**
     * Display the specified pendaftar with all details
     */
    public function show($id)
    {
        $pendaftar = Pendaftar::with([
            'jalurSeleksi',
            'programStudiPilihan1',
            'programStudiPilihan2',
            'pembayaranPendaftarans'
        ])->findOrFail($id);

        return view('operator.spmb.show', compact('pendaftar'));
    }

    /**
     * Verify a pendaftar registration (limited action for operator)
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        // Operator can only verify pending registrations
        if ($pendaftar->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Hanya pendaftaran dengan status pending yang dapat diverifikasi');
        }

        $pendaftar->update([
            'status' => 'verified',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('operator.spmb.show', $id)
            ->with('success', 'Pendaftaran berhasil diverifikasi');
    }

    /**
     * Export pendaftars to Excel
     */
    public function export(Request $request)
    {
        // For now, return a simple response
        // TODO: Implement Excel export using Laravel Excel package
        return redirect()->back()
            ->with('info', 'Fitur export akan segera tersedia');
    }
}
