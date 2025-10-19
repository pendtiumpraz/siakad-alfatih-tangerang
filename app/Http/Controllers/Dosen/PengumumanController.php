<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengumumans = Pengumuman::with('pembuat')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dosen.pengumuman.index', compact('pengumumans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dosen.pengumuman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:info,penting,pengingat,kegiatan',
            'untuk_mahasiswa' => 'boolean',
            'is_active' => 'boolean',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $user = Auth::user();

        Pengumuman::create([
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'tipe' => $validated['tipe'],
            'pembuat_id' => $user->id,
            'pembuat_role' => $user->role,
            'untuk_mahasiswa' => $request->boolean('untuk_mahasiswa', true),
            'is_active' => $request->boolean('is_active', true),
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
        ]);

        return redirect()->route('dosen.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengumuman $pengumuman)
    {
        $pengumuman->load('pembuat');
        return view('dosen.pengumuman.show', compact('pengumuman'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengumuman $pengumuman)
    {
        return view('dosen.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'tipe' => 'required|in:info,penting,pengingat,kegiatan',
            'untuk_mahasiswa' => 'boolean',
            'is_active' => 'boolean',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);

        $pengumuman->update([
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
            'tipe' => $validated['tipe'],
            'untuk_mahasiswa' => $request->boolean('untuk_mahasiswa', true),
            'is_active' => $request->boolean('is_active', true),
            'tanggal_mulai' => $validated['tanggal_mulai'] ?? null,
            'tanggal_selesai' => $validated['tanggal_selesai'] ?? null,
        ]);

        return redirect()->route('dosen.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();

        return redirect()->route('dosen.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }
}
