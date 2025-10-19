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
                      ->where('semester', '=', $mahasiswa->semester_aktif);
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

        return view('mahasiswa.khs.index', compact('khsList', 'selectedKhs', 'mahasiswa'));
    }

    /**
     * Display detail KHS for specific semester.
     */
    public function khsDetail($id)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        // Get KHS by semester ID
        $khs = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $id)
            ->with('semester')
            ->first();

        if (!$khs) {
            return redirect()->route('mahasiswa.khs.index')
                ->with('error', 'Data KHS tidak ditemukan');
        }

        // Get all nilais for this KHS
        $nilais = \App\Models\Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $id)
            ->with('mataKuliah')
            ->get();

        return view('mahasiswa.khs.show', compact('khs', 'mahasiswa', 'nilais'));
    }

    /**
     * Export KHS to PDF.
     */
    public function khsExport($id)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        // Get KHS by semester ID
        $khs = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $id)
            ->with('semester')
            ->first();

        if (!$khs) {
            return redirect()->route('mahasiswa.khs.index')
                ->with('error', 'Data KHS tidak ditemukan');
        }

        // Get all nilais for this KHS
        $nilais = \App\Models\Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $id)
            ->with('mataKuliah')
            ->get();

        // Load view for PDF
        $pdf = \PDF::loadView('mahasiswa.khs.print', compact('khs', 'mahasiswa', 'nilais'))
            ->setPaper('a4', 'portrait');

        $filename = sprintf(
            'KHS_%s_Semester_%s.pdf',
            $mahasiswa->nim ?? 'mahasiswa',
            str_replace(['/', '\\'], '-', $khs->semester->nama_semester ?? $id)
        );

        return $pdf->download($filename);
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

    /**
     * Display notifications for the authenticated mahasiswa.
     */
    public function notifications(Request $request)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        // Get active pengumumans for mahasiswa
        $pengumumans = \App\Models\Pengumuman::active()
            ->forMahasiswa()
            ->with(['pembuat', 'reads' => function($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform to notifications format
        $notifications = $pengumumans->map(function($pengumuman) use ($mahasiswa) {
            $read = $pengumuman->reads->first();
            return (object)[
                'id' => $pengumuman->id,
                'title' => $pengumuman->judul,
                'message' => $pengumuman->isi,
                'type' => $pengumuman->tipe,
                'read_at' => $read ? $read->read_at : null,
                'created_at' => $pengumuman->created_at,
                'pembuat' => $pengumuman->pembuat->username ?? '-',
                'pembuat_role' => ucfirst(str_replace('_', ' ', $pengumuman->pembuat_role)),
            ];
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $notifications
            ]);
        }

        return view('mahasiswa.notifications.index', compact('notifications', 'mahasiswa'));
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead($pengumumanId)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        $pengumuman = \App\Models\Pengumuman::findOrFail($pengumumanId);

        // Create or update read record
        \App\Models\PengumumanRead::updateOrCreate(
            [
                'pengumuman_id' => $pengumuman->id,
                'mahasiswa_id' => $mahasiswa->id,
            ],
            [
                'read_at' => now(),
            ]
        );

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    /**
     * Display nilai (grades) for the authenticated mahasiswa.
     */
    public function nilai(Request $request)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        // Get semester filter
        $semesterId = $request->input('semester_id');

        $query = \App\Models\Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->with(['mataKuliah', 'semester']);

        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }

        $nilais = $query->orderBy('semester_id', 'desc')->get();
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $nilais
            ]);
        }

        return view('mahasiswa.nilai', compact('nilais', 'semesters', 'mahasiswa'));
    }

    /**
     * Display kurikulum for the authenticated mahasiswa's program studi.
     */
    public function kurikulum(Request $request)
    {
        $mahasiswa = $this->getAuthMahasiswa();
        $mahasiswa->load('programStudi');

        $kurikulum = $mahasiswa->programStudi->kurikulums()
            ->where('is_active', true)
            ->with('mataKuliahs')
            ->first();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $kurikulum
            ]);
        }

        return view('mahasiswa.kurikulum', compact('kurikulum', 'mahasiswa'));
    }

    /**
     * Upload bukti pembayaran by mahasiswa.
     */
    public function uploadBukti(Request $request, $id)
    {
        $mahasiswa = $this->getAuthMahasiswa();

        // Validate the payment belongs to this mahasiswa
        $pembayaran = Pembayaran::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->firstOrFail();

        // Validate the upload
        $validated = $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diupload',
            'bukti_pembayaran.mimes' => 'File harus berformat JPG, JPEG, PNG, atau PDF',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB',
        ]);

        try {
            \DB::beginTransaction();

            // Delete old file if exists
            if ($pembayaran->bukti_pembayaran && Storage::disk('public')->exists($pembayaran->bukti_pembayaran)) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
            }

            // Generate custom filename
            $extension = $request->file('bukti_pembayaran')->getClientOriginalExtension();
            $semester = Semester::find($pembayaran->semester_id);

            $filename = sprintf(
                'bukti_bayar_%s_%s_%s_%s_%s.%s',
                str_replace(' ', '_', strtolower($pembayaran->jenis_pembayaran)),
                $semester ? $semester->tahun_akademik : date('Y'),
                $semester ? strtolower($semester->jenis) : 'reguler',
                \Str::slug($mahasiswa->nama_lengkap),
                date('Ymd_His'),
                $extension
            );

            // Store the file
            $buktiPath = $request->file('bukti_pembayaran')
                ->storeAs('pembayaran/bukti', $filename, 'public');

            // Update pembayaran record
            $pembayaran->update([
                'bukti_pembayaran' => $buktiPath,
                'status' => 'pending', // Change status to pending when bukti uploaded
            ]);

            \DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Bukti pembayaran berhasil diupload. Menunggu verifikasi dari operator.',
                    'data' => $pembayaran
                ]);
            }

            return redirect()->route('mahasiswa.pembayaran.index')
                ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi dari operator.');

        } catch (\Exception $e) {
            \DB::rollBack();

            // Delete uploaded file if transaction fails
            if (isset($buktiPath) && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload bukti pembayaran: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('mahasiswa.pembayaran.index')
                ->with('error', 'Gagal mengupload bukti pembayaran. Silakan coba lagi.');
        }
    }
}
