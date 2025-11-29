<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use App\Models\SppSetting;
use Illuminate\Http\Request;

class SppSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sppSettings = SppSetting::with('programStudi')->get();
        return view('admin.spp-settings.index', compact('sppSettings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $programStudis = ProgramStudi::all();
        return view('admin.spp-settings.create', compact('programStudis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'nullable|exists:program_studis,id',
            'nominal' => 'required|numeric|min:0',
            'rekening_nama' => 'required|string|max:255',
            'rekening_nomor' => 'nullable|string|max:255',
            'rekening_bank' => 'nullable|string|max:255',
            'contact_whatsapp' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'jatuh_tempo_hari' => 'required|integer|min:1|max:365',
            'is_active' => 'boolean',
        ]);

        SppSetting::create($validated);

        return redirect()->route('admin.spp-settings.index')
            ->with('success', 'Setting SPP berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sppSetting = SppSetting::findOrFail($id);
        $programStudis = ProgramStudi::all();
        return view('admin.spp-settings.edit', compact('sppSetting', 'programStudis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sppSetting = SppSetting::findOrFail($id);

        $validated = $request->validate([
            'program_studi_id' => 'nullable|exists:program_studis,id',
            'nominal' => 'required|numeric|min:0',
            'rekening_nama' => 'required|string|max:255',
            'rekening_nomor' => 'nullable|string|max:255',
            'rekening_bank' => 'nullable|string|max:255',
            'contact_whatsapp' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'jatuh_tempo_hari' => 'required|integer|min:1|max:365',
            'is_active' => 'boolean',
        ]);

        $sppSetting->update($validated);

        return redirect()->route('admin.spp-settings.index')
            ->with('success', 'Setting SPP berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sppSetting = SppSetting::findOrFail($id);
        $sppSetting->delete();

        return redirect()->route('admin.spp-settings.index')
            ->with('success', 'Setting SPP berhasil dihapus');
    }
}
