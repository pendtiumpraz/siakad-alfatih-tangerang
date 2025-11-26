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

    /**
     * Show the edit username form.
     */
    public function editUsername()
    {
        return view('dosen.profile.edit-username');
    }

    /**
     * Update username (only allowed once).
     */
    public function updateUsername(Request $request)
    {
        $user = Auth::user();

        // Check if username has already been changed
        if ($user->username_changed_at) {
            return redirect()->back()->with('error', 'Username sudah pernah diubah. Tidak dapat diubah lagi.');
        }

        // Validate input
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:255',
                'regex:/^[a-z0-9_.]+$/',
                'unique:users,username,' . $user->id
            ],
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.min' => 'Username minimal 3 karakter',
            'username.max' => 'Username maksimal 255 karakter',
            'username.regex' => 'Username hanya boleh berisi huruf kecil, angka, underscore (_), dan titik (.)',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi untuk konfirmasi',
        ]);

        // Verify password
        if (!\Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->withErrors(['password' => 'Password salah'])
                ->withInput($request->except('password'));
        }

        try {
            \DB::beginTransaction();

            // Update username and set timestamp
            $user->update([
                'username' => $validated['username'],
                'username_changed_at' => now(),
            ]);

            \DB::commit();

            \Log::info("Dosen {$user->id} changed username to: {$validated['username']}");

            return redirect()->route('dosen.dashboard')
                ->with('success', 'Username berhasil diubah! Login selanjutnya gunakan username baru Anda.');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error("Failed to update username for dosen {$user->id}: " . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengubah username: ' . $e->getMessage())
                ->withInput($request->except('password'));
        }
    }

    /**
     * Show the edit password form.
     */
    public function editPassword()
    {
        return view('dosen.profile.edit-password');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password baru minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        // Verify current password
        if (!\Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->withErrors(['current_password' => 'Password lama salah'])
                ->withInput();
        }

        try {
            // Update password
            $user->update([
                'password' => \Hash::make($validated['password']),
            ]);

            return redirect()->route('dosen.profile')
                ->with('success', 'Password berhasil diubah');

        } catch (\Exception $e) {
            \Log::error("Failed to update password for user {$user->id}: " . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal mengubah password: ' . $e->getMessage());
        }
    }
}
