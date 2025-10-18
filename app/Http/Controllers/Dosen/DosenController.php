<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Nilai;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    /**
     * Display the dosen dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(403, 'Unauthorized access');
        }

        // Get statistics for dashboard
        $totalJadwal = Jadwal::where('dosen_id', $dosen->id)->count();
        $totalMahasiswa = Nilai::where('dosen_id', $dosen->id)
            ->distinct('mahasiswa_id')
            ->count('mahasiswa_id');
        $totalMataKuliah = Jadwal::where('dosen_id', $dosen->id)
            ->distinct('mata_kuliah_id')
            ->count('mata_kuliah_id');

        // Get recent jadwal
        $recentJadwal = Jadwal::where('dosen_id', $dosen->id)
            ->with(['mataKuliah', 'ruangan', 'semester'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->limit(5)
            ->get();

        return view('dosen.dashboard', compact(
            'dosen',
            'totalJadwal',
            'totalMahasiswa',
            'totalMataKuliah',
            'recentJadwal'
        ));
    }

    /**
     * Get the authenticated dosen instance.
     */
    private function getAuthDosen()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'dosen') {
            abort(403, 'Unauthorized. Only dosen can access this resource.');
        }

        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            abort(404, 'Data dosen tidak ditemukan.');
        }

        return $dosen;
    }

    /**
     * Display the authenticated dosen's profile.
     */
    public function profile(Request $request)
    {
        $dosen = $this->getAuthDosen();
        $dosen->load('user');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $dosen
            ]);
        }

        return view('dosen.profile', compact('dosen'));
    }

    /**
     * Update the authenticated dosen's profile.
     */
    public function updateProfile(Request $request)
    {
        $dosen = $this->getAuthDosen();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'gelar_depan' => 'nullable|string|max:50',
            'gelar_belakang' => 'nullable|string|max:50',
            'email_dosen' => 'nullable|email|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($dosen->foto && Storage::exists($dosen->foto)) {
                Storage::delete($dosen->foto);
            }

            // Store new foto
            $fotoPath = $request->file('foto')->store('dosen/foto', 'public');
            $validated['foto'] = $fotoPath;
        }

        $dosen->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => $dosen
            ]);
        }

        return redirect()->route('dosen.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
