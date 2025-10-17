<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Jadwal;
use App\Models\Khs;
use App\Models\Pembayaran;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    /**
     * Get the authenticated mahasiswa instance.
     */
    private function getAuthMahasiswa()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'mahasiswa') {
            abort(403, 'Unauthorized. Only mahasiswa can access this resource.');
        }

        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            abort(404, 'Data mahasiswa tidak ditemukan.');
        }

        return $mahasiswa;
    }

    /**
     * Display the authenticated mahasiswa's profile.
     */
    public function profile(Request $request)
    {
        $mahasiswa = $this->getAuthMahasiswa();
        $mahasiswa->load(['user', 'programStudi']);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $mahasiswa
            ]);
        }

        return view('mahasiswa.profile', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the authenticated mahasiswa's profile.
     */
    public function editProfile()
    {
        $mahasiswa = $this->getAuthMahasiswa();
        $mahasiswa->load(['user', 'programStudi']);

        return view('mahasiswa.edit-profile', compact('mahasiswa'));
    }

    /**
     * Update the authenticated mahasiswa's profile.
     */
    public function updateProfile(Request $request)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // 2MB max
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Delete old foto if exists
            if ($mahasiswa->foto && Storage::exists($mahasiswa->foto)) {
                Storage::delete($mahasiswa->foto);
            }

            // Store new foto
            $fotoPath = $request->file('foto')->store('mahasiswa/foto', 'public');
            $validated['foto'] = $fotoPath;
        }

        $mahasiswa->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => $mahasiswa
            ]);
        }

        return redirect()->route('mahasiswa.profile')
            ->with('success', 'Profil berhasil diperbarui');
    }

    /**
     * Display jadwal for the authenticated mahasiswa for the current semester.
     */
    public function jadwal(Request $request)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        // Get active semester or specified semester
        $semesterId = $request->input('semester_id');

        if ($semesterId) {
            $semester = Semester::findOrFail($semesterId);
        } else {
            $semester = Semester::where('is_active', true)->first();
        }

        if (!$semester) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada semester aktif'
                ], 404);
            }

            return view('mahasiswa.jadwal', [
                'jadwals' => collect(),
                'semester' => null,
                'semesters' => Semester::orderBy('tahun_akademik', 'desc')->get()
            ])->with('error', 'Tidak ada semester aktif');
        }

        // Get jadwal based on mahasiswa's kurikulum
        $kurikulum = $mahasiswa->programStudi->kurikulums()
            ->where('is_active', true)
            ->first();

        if (!$kurikulum) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada kurikulum aktif untuk program studi Anda'
                ], 404);
            }

            return view('mahasiswa.jadwal', [
                'jadwals' => collect(),
                'semester' => $semester,
                'semesters' => Semester::orderBy('tahun_akademik', 'desc')->get()
            ])->with('error', 'Tidak ada kurikulum aktif untuk program studi Anda');
        }

        $jadwals = Jadwal::where('semester_id', $semester->id)
            ->whereHas('mataKuliah', function($query) use ($kurikulum, $mahasiswa) {
                $query->where('kurikulum_id', $kurikulum->id)
                      ->where('semester', '<=', $mahasiswa->semester_aktif);
            })
            ->with(['mataKuliah', 'dosen', 'ruangan'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'semester' => $semester,
                    'jadwals' => $jadwals
                ]
            ]);
        }

        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();

        return view('mahasiswa.jadwal', compact('jadwals', 'semester', 'semesters'));
    }

    /**
     * Display KHS (Kartu Hasil Studi) for the authenticated mahasiswa.
     */
    public function khs(Request $request)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        // Get all KHS for the mahasiswa
        $khsList = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->with('semester')
            ->orderBy('semester_id', 'desc')
            ->get();

        // Get specific KHS if semester_id is provided
        $selectedKhs = null;
        if ($request->has('semester_id')) {
            $selectedKhs = Khs::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $request->semester_id)
                ->with(['semester'])
                ->first();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'khs_list' => $khsList,
                    'selected_khs' => $selectedKhs
                ]
            ]);
        }

        return view('mahasiswa.khs', compact('khsList', 'selectedKhs', 'mahasiswa'));
    }

    /**
     * Display pembayaran history for the authenticated mahasiswa.
     */
    public function pembayaran(Request $request)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        $query = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->with(['semester', 'operator']);

        // Filter by semester if provided
        if ($request->has('semester_id') && $request->semester_id) {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $pembayarans = $query->orderBy('tanggal_jatuh_tempo', 'desc')
                            ->paginate(15);

        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $pembayarans
            ]);
        }

        return view('mahasiswa.pembayaran', compact('pembayarans', 'semesters', 'mahasiswa'));
    }
}
