<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DaftarUlang;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\MahasiswaAccountCreated;

class DaftarUlangController extends Controller
{
    /**
     * Display a listing of daftar ulang submissions
     */
    public function index(Request $request)
    {
        $query = DaftarUlang::with(['pendaftar', 'verifier', 'mahasiswaUser'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $daftarUlangs = $query->paginate(20)->withQueryString();
        $stats = [
            'total' => DaftarUlang::count(),
            'pending' => DaftarUlang::where('status', 'pending')->count(),
            'verified' => DaftarUlang::where('status', 'verified')->count(),
            'rejected' => DaftarUlang::where('status', 'rejected')->count(),
        ];

        return view('admin.daftar-ulang.index', compact('daftarUlangs', 'stats'));
    }

    /**
     * Display the specified daftar ulang
     */
    public function show($id)
    {
        $daftarUlang = DaftarUlang::with(['pendaftar.jurusan', 'pendaftar.jalurSeleksi', 'verifier', 'mahasiswaUser'])
            ->findOrFail($id);

        return view('admin.daftar-ulang.show', compact('daftarUlang'));
    }

    /**
     * Verify daftar ulang and auto-create mahasiswa user
     */
    public function verify(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'nullable|string|max:500',
        ]);

        $daftarUlang = DaftarUlang::with('pendaftar')->findOrFail($id);

        if ($daftarUlang->status !== 'pending') {
            return back()->with('error', 'Daftar ulang ini sudah diverifikasi sebelumnya.');
        }

        try {
            DB::beginTransaction();

            // Create user account for mahasiswa
            $password = Str::random(8);
            $user = User::create([
                'name' => $daftarUlang->pendaftar->nama,
                'email' => $daftarUlang->pendaftar->email,
                'username' => $daftarUlang->nim_sementara,
                'password' => Hash::make($password),
                'role' => 'mahasiswa',
            ]);

            // Create mahasiswa record
            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $daftarUlang->nim_sementara, // Temporary NIM
                'nama_lengkap' => $daftarUlang->pendaftar->nama,
                'tempat_lahir' => $daftarUlang->pendaftar->tempat_lahir,
                'tanggal_lahir' => $daftarUlang->pendaftar->tanggal_lahir,
                'jenis_kelamin' => $daftarUlang->pendaftar->jenis_kelamin,
                'alamat' => $daftarUlang->pendaftar->alamat,
                'no_telepon' => $daftarUlang->pendaftar->phone,
                'program_studi_id' => $daftarUlang->pendaftar->program_studi_pilihan_1, // Use program studi pilihan 1
                'angkatan' => date('Y'),
                'status' => 'aktif',
            ]);

            // Update daftar ulang status
            $daftarUlang->update([
                'status' => 'verified',
                'tanggal_verifikasi' => now(),
                'verified_by' => auth()->id(),
                'mahasiswa_user_id' => $user->id,
                'keterangan' => $request->keterangan,
            ]);

            DB::commit();

            // Send email with login credentials
            try {
                Mail::to($daftarUlang->pendaftar->email)
                    ->send(new MahasiswaAccountCreated($daftarUlang, $daftarUlang->nim_sementara, $password));

                $emailStatus = " Email dengan informasi login telah dikirim ke {$daftarUlang->pendaftar->email}";
            } catch (\Exception $emailError) {
                \Log::error('Failed to send email: ' . $emailError->getMessage());
                $emailStatus = " (Email gagal dikirim, mohon informasikan secara manual)";
            }

            return redirect()->route('admin.daftar-ulang.index')
                ->with('success', "Daftar ulang berhasil diverifikasi! Akun mahasiswa telah dibuat dengan username: {$daftarUlang->nim_sementara} dan password: {$password}.{$emailStatus}");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to verify daftar ulang: ' . $e->getMessage());

            return back()->with('error', 'Gagal memverifikasi daftar ulang: ' . $e->getMessage());
        }
    }

    /**
     * Reject daftar ulang
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string|max:500',
        ]);

        $daftarUlang = DaftarUlang::findOrFail($id);

        if ($daftarUlang->status !== 'pending') {
            return back()->with('error', 'Daftar ulang ini sudah diverifikasi sebelumnya.');
        }

        $daftarUlang->update([
            'status' => 'rejected',
            'tanggal_verifikasi' => now(),
            'verified_by' => auth()->id(),
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.daftar-ulang.index')
            ->with('success', 'Daftar ulang berhasil ditolak.');
    }
}
