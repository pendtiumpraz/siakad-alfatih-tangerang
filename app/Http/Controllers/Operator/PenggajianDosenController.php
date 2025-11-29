<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Admin\PenggajianDosenController as AdminPenggajianDosenController;
use Illuminate\Http\Request;

class PenggajianDosenController extends AdminPenggajianDosenController
{
    /**
     * Display a listing (Operator view)
     */
    public function index(Request $request)
    {
        $query = \App\Models\PenggajianDosen::with(['dosen', 'semester']);

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

        $penggajians = $query->paginate(15)->withQueryString();

        // Get unique periodes for filter
        $periodes = \App\Models\PenggajianDosen::select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        return view('operator.penggajian-dosen.index', compact('penggajians', 'periodes'));
    }

    /**
     * Display the specified resource (Operator view)
     */
    public function show($id)
    {
        $penggajian = \App\Models\PenggajianDosen::with(['dosen.user', 'semester', 'verifier', 'payer'])
            ->findOrFail($id);
        return view('operator.penggajian-dosen.show', compact('penggajian'));
    }

    /**
     * Show payment form (Operator view)
     */
    public function payment($id)
    {
        $penggajian = \App\Models\PenggajianDosen::with(['dosen', 'semester'])->findOrFail($id);
        return view('operator.penggajian-dosen.payment', compact('penggajian'));
    }

    // verify() and storePayment() inherited from Admin controller
}
