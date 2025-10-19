<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pendaftar;
use App\Models\JalurSeleksi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        return view('admin.spmb.index', compact('pendaftars', 'jalurSeleksis', 'programStudis'));
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

        return view('admin.spmb.show', compact('pendaftar'));
    }

    /**
     * Verify a pendaftar registration
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        $pendaftar->update([
            'status' => 'verified',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.spmb.show', $id)
            ->with('success', 'Pendaftaran berhasil diverifikasi');
    }

    /**
     * Reject a pendaftar registration with reason
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:500',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        $pendaftar->update([
            'status' => 'rejected',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.spmb.show', $id)
            ->with('success', 'Pendaftaran berhasil ditolak');
    }

    /**
     * Accept a pendaftar and assign to program studi
     */
    public function accept(Request $request, $id)
    {
        $request->validate([
            'program_studi_id' => 'required|exists:program_studis,id',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        // Verify that selected prodi is one of the choices
        if ($request->program_studi_id != $pendaftar->program_studi_pilihan_1 &&
            $request->program_studi_id != $pendaftar->program_studi_pilihan_2) {
            return redirect()->back()
                ->with('error', 'Program studi yang dipilih harus sesuai dengan pilihan pendaftar');
        }

        $pendaftar->update([
            'status' => 'accepted',
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.spmb.show', $id)
            ->with('success', 'Pendaftaran berhasil diterima');
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

    /**
     * Bulk verify pendaftars
     */
    public function bulkVerify(Request $request)
    {
        $request->validate([
            'pendaftar_ids' => 'required|array',
            'pendaftar_ids.*' => 'exists:pendaftars,id',
            'keterangan' => 'nullable|string|max:500',
        ]);

        Pendaftar::whereIn('id', $request->pendaftar_ids)
            ->update([
                'status' => 'verified',
                'keterangan' => $request->keterangan,
            ]);

        return redirect()->route('admin.spmb.index')
            ->with('success', count($request->pendaftar_ids) . ' pendaftaran berhasil diverifikasi');
    }

    /**
     * Bulk reject pendaftars
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'pendaftar_ids' => 'required|array',
            'pendaftar_ids.*' => 'exists:pendaftars,id',
            'keterangan' => 'required|string|max:500',
        ]);

        Pendaftar::whereIn('id', $request->pendaftar_ids)
            ->update([
                'status' => 'rejected',
                'keterangan' => $request->keterangan,
            ]);

        return redirect()->route('admin.spmb.index')
            ->with('success', count($request->pendaftar_ids) . ' pendaftaran berhasil ditolak');
    }
}
