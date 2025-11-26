<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PenggajianDosen;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenggajianDosenController extends Controller
{
    /**
     * Display a listing of all penggajian from all dosens
     */
    public function index(Request $request)
    {
        $query = PenggajianDosen::with(['dosen', 'semester']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by periode
        if ($request->filled('periode')) {
            $query->where('periode', $request->periode);
        }

        // Search by dosen name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('dosen', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nidn', 'like', "%{$search}%");
            });
        }

        // Order: pending first, then by created_at desc
        $query->orderByRaw("FIELD(status, 'pending', 'verified', 'paid', 'rejected')")
              ->orderBy('created_at', 'desc');

        $penggajians = $query->paginate(15);

        // Get unique periodes for filter
        $periodes = PenggajianDosen::select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        return view('admin.penggajian.index', compact('penggajians', 'periodes'));
    }

    /**
     * Display the specified penggajian for verification
     */
    public function show($id)
    {
        $penggajian = PenggajianDosen::with(['dosen.user', 'semester', 'verifier', 'payer'])
            ->findOrFail($id);

        return view('admin.penggajian.show', compact('penggajian'));
    }

    /**
     * Verify (approve or reject) the penggajian
     */
    public function verify(Request $request, $id)
    {
        $penggajian = PenggajianDosen::findOrFail($id);

        // Check if can be verified
        if (!$penggajian->canBeVerified()) {
            return redirect()->back()
                ->with('error', 'Pengajuan ini sudah diverifikasi sebelumnya.');
        }

        // Validate
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:approve,reject',
            'total_jam_disetujui' => 'required_if:action,approve|nullable|numeric|min:0',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ], [
            'action.required' => 'Aksi verifikasi harus dipilih.',
            'action.in' => 'Aksi verifikasi tidak valid.',
            'total_jam_disetujui.required_if' => 'Total jam yang disetujui wajib diisi saat menyetujui.',
            'total_jam_disetujui.min' => 'Total jam minimal 0.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Process verification
        if ($request->action === 'approve') {
            $penggajian->update([
                'status' => 'verified',
                'total_jam_disetujui' => $request->total_jam_disetujui,
                'catatan_verifikasi' => $request->catatan_verifikasi,
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $message = 'Pengajuan berhasil disetujui.';
        } else {
            $penggajian->update([
                'status' => 'rejected',
                'catatan_verifikasi' => $request->catatan_verifikasi ?? 'Ditolak oleh admin.',
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);

            $message = 'Pengajuan ditolak.';
        }

        return redirect()->route('admin.penggajian.index')
            ->with('success', $message);
    }

    /**
     * Show payment form
     */
    public function payment($id)
    {
        $penggajian = PenggajianDosen::with(['dosen', 'semester'])
            ->findOrFail($id);

        // Check if can be paid
        if (!$penggajian->canBePaid()) {
            return redirect()->route('admin.penggajian.index')
                ->with('error', 'Pengajuan harus diverifikasi terlebih dahulu sebelum dibayar.');
        }

        return view('admin.penggajian.payment', compact('penggajian'));
    }

    /**
     * Store payment information
     */
    public function storePayment(Request $request, $id)
    {
        $penggajian = PenggajianDosen::findOrFail($id);

        // Check if can be paid
        if (!$penggajian->canBePaid()) {
            return redirect()->route('admin.penggajian.index')
                ->with('error', 'Pengajuan harus diverifikasi terlebih dahulu sebelum dibayar.');
        }

        // Validate
        $validator = Validator::make($request->all(), [
            'jumlah_dibayar' => 'required|numeric|min:0',
            'bukti_pembayaran' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('Link bukti pembayaran harus dari Google Drive.');
                }
            }],
        ], [
            'jumlah_dibayar.required' => 'Jumlah yang dibayar wajib diisi.',
            'jumlah_dibayar.numeric' => 'Jumlah harus berupa angka.',
            'jumlah_dibayar.min' => 'Jumlah minimal 0.',
            'bukti_pembayaran.required' => 'Link bukti pembayaran wajib diisi.',
            'bukti_pembayaran.url' => 'Link bukti pembayaran harus berupa URL yang valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update payment
        $penggajian->update([
            'status' => 'paid',
            'jumlah_dibayar' => $request->jumlah_dibayar,
            'bukti_pembayaran' => $request->bukti_pembayaran,
            'paid_by' => auth()->id(),
            'paid_at' => now(),
        ]);

        return redirect()->route('admin.penggajian.show', $id)
            ->with('success', 'Pembayaran berhasil dicatat. Status pengajuan diubah menjadi "Sudah Dibayar".');
    }
}
