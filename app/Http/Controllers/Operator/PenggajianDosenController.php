<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Admin\PenggajianDosenController as AdminPenggajianDosenController;

class PenggajianDosenController extends AdminPenggajianDosenController
{
    /**
     * Display a listing (Operator view)
     */
    public function index()
    {
        $penggajians = \App\Models\PenggajianDosen::with(['dosen', 'semester'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('operator.penggajian-dosen.index', compact('penggajians'));
    }

    /**
     * Display the specified resource (Operator view)
     */
    public function show(string $id)
    {
        $penggajian = \App\Models\PenggajianDosen::with(['dosen', 'semester', 'jadwals'])->findOrFail($id);
        return view('operator.penggajian-dosen.show', compact('penggajian'));
    }

    /**
     * Show payment form (Operator view)
     */
    public function payment(string $id)
    {
        $penggajian = \App\Models\PenggajianDosen::with(['dosen', 'semester'])->findOrFail($id);
        return view('operator.penggajian-dosen.payment', compact('penggajian'));
    }

    // verify() and storePayment() inherited from Admin controller
}
