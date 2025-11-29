<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Admin\JalurSeleksiController as AdminJalurSeleksiController;
use Illuminate\Http\Request;

class JalurSeleksiController extends AdminJalurSeleksiController
{
    /**
     * Display a listing of jalur seleksi (Operator view)
     */
    public function index()
    {
        $jalurSeleksis = \App\Models\JalurSeleksi::latest()->paginate(10)->withQueryString();

        return view('operator.jalur-seleksi.index', compact('jalurSeleksis'));
    }

    /**
     * Show the form for creating a new jalur seleksi (Operator view)
     */
    public function create()
    {
        return view('operator.jalur-seleksi.create');
    }

    /**
     * Show the form for editing the specified jalur seleksi (Operator view)
     */
    public function edit(string $id)
    {
        $jalurSeleksi = \App\Models\JalurSeleksi::findOrFail($id);
        return view('operator.jalur-seleksi.edit', compact('jalurSeleksi'));
    }

    // Store, update, destroy methods inherited from Admin controller
}
