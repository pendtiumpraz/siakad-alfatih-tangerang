<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JalurSeleksi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JalurSeleksiController extends Controller
{
    /**
     * Display a listing of jalur seleksi
     */
    public function index()
    {
        $jalurSeleksis = JalurSeleksi::latest()->paginate(10)->withQueryString();

        return view('admin.jalur-seleksi.index', compact('jalurSeleksis'));
    }

    /**
     * Show the form for creating a new jalur seleksi
     */
    public function create()
    {
        return view('admin.jalur-seleksi.create');
    }

    /**
     * Store a newly created jalur seleksi in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_jalur' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jalur_seleksis', 'kode_jalur'),
            ],
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'kuota_total' => 'nullable|integer|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        JalurSeleksi::create($validated);

        return redirect()->route('admin.jalur-seleksi.index')
            ->with('success', 'Jalur seleksi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified jalur seleksi
     */
    public function edit(JalurSeleksi $jalurSeleksi)
    {
        return view('admin.jalur-seleksi.edit', compact('jalurSeleksi'));
    }

    /**
     * Update the specified jalur seleksi in storage
     */
    public function update(Request $request, JalurSeleksi $jalurSeleksi)
    {
        $validated = $request->validate([
            'kode_jalur' => [
                'required',
                'string',
                'max:20',
                Rule::unique('jalur_seleksis', 'kode_jalur')->ignore($jalurSeleksi->id),
            ],
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'biaya_pendaftaran' => 'required|numeric|min:0',
            'kuota_total' => 'nullable|integer|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $jalurSeleksi->update($validated);

        return redirect()->route('admin.jalur-seleksi.index')
            ->with('success', 'Jalur seleksi berhasil diperbarui.');
    }

    /**
     * Remove the specified jalur seleksi from storage
     */
    public function destroy(JalurSeleksi $jalurSeleksi)
    {
        $jalurSeleksi->delete();

        return redirect()->route('admin.jalur-seleksi.index')
            ->with('success', 'Jalur seleksi berhasil dihapus.');
    }

    /**
     * Batch delete multiple jalur seleksi
     */
    public function batchDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        try {
            $count = JalurSeleksi::whereIn('id', $request->ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "{$count} Jalur Seleksi berhasil dihapus.",
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
