<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PenggajianDosen;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenggajianController extends Controller
{
    /**
     * Display a listing of penggajian
     */
    public function index()
    {
        $dosen = auth()->user()->dosen;
        
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        $penggajians = PenggajianDosen::where('dosen_id', $dosen->id)
            ->with(['semester', 'verifier', 'payer'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)->withQueryString();

        return view('dosen.penggajian.index', compact('penggajians', 'dosen'));
    }

    /**
     * Show the form for creating a new penggajian
     */
    public function create()
    {
        $dosen = auth()->user()->dosen;
        
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        // Check if rekening sudah diisi
        if (!$dosen->nama_bank || !$dosen->nomor_rekening) {
            return redirect()->route('dosen.profile')
                ->with('error', 'Silakan lengkapi data rekening Anda terlebih dahulu sebelum mengajukan pencairan gaji.');
        }

        $semesters = Semester::orderBy('tahun_akademik', 'desc')
            ->orderBy('jenis', 'desc')
            ->get();

        return view('dosen.penggajian.create', compact('dosen', 'semesters'));
    }

    /**
     * Store a newly created penggajian
     */
    public function store(Request $request)
    {
        $dosen = auth()->user()->dosen;
        
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        // Validate
        $validator = Validator::make($request->all(), [
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'semester_id' => 'nullable|exists:semesters,id',
            'total_jam_diajukan' => 'required|numeric|min:0.5',
            'link_rps' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('Link RPS harus dari Google Drive.');
                }
            }],
            'link_materi_ajar' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('Link Materi Ajar harus dari Google Drive.');
                }
            }],
            'link_absensi' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('Link Absensi harus dari Google Drive.');
                }
            }],
            'catatan_dosen' => 'nullable|string|max:1000',
        ], [
            'periode.required' => 'Periode wajib diisi.',
            'periode.regex' => 'Format periode harus YYYY-MM (contoh: 2025-11).',
            'total_jam_diajukan.required' => 'Total jam mengajar wajib diisi.',
            'total_jam_diajukan.min' => 'Total jam minimal 0.5 jam.',
            'link_rps.required' => 'Link RPS wajib diisi.',
            'link_rps.url' => 'Link RPS harus berupa URL yang valid.',
            'link_materi_ajar.required' => 'Link Materi Ajar wajib diisi.',
            'link_materi_ajar.url' => 'Link Materi Ajar harus berupa URL yang valid.',
            'link_absensi.required' => 'Link Absensi wajib diisi.',
            'link_absensi.url' => 'Link Absensi harus berupa URL yang valid.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check duplicate periode
        $exists = PenggajianDosen::where('dosen_id', $dosen->id)
            ->where('periode', $request->periode)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['periode' => 'Anda sudah mengajukan pencairan untuk periode ini.'])
                ->withInput();
        }

        // Create
        PenggajianDosen::create([
            'dosen_id' => $dosen->id,
            'periode' => $request->periode,
            'semester_id' => $request->semester_id,
            'total_jam_diajukan' => $request->total_jam_diajukan,
            'link_rps' => $request->link_rps,
            'link_materi_ajar' => $request->link_materi_ajar,
            'link_absensi' => $request->link_absensi,
            'catatan_dosen' => $request->catatan_dosen,
            'status' => 'pending',
        ]);

        return redirect()->route('dosen.penggajian.index')
            ->with('success', 'Pengajuan pencairan gaji berhasil diajukan. Menunggu verifikasi dari admin.');
    }

    /**
     * Display the specified penggajian
     */
    public function show($id)
    {
        $dosen = auth()->user()->dosen;
        
        $penggajian = PenggajianDosen::with(['semester', 'verifier', 'payer'])
            ->findOrFail($id);

        // Authorization check
        if ($penggajian->dosen_id !== $dosen->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('dosen.penggajian.show', compact('penggajian', 'dosen'));
    }

    /**
     * Show the form for editing the specified penggajian
     */
    public function edit($id)
    {
        $dosen = auth()->user()->dosen;
        
        $penggajian = PenggajianDosen::findOrFail($id);

        // Authorization check
        if ($penggajian->dosen_id !== $dosen->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only pending can be edited
        if (!$penggajian->canBeEdited()) {
            $message = match($penggajian->status) {
                'verified' => 'Pengajuan yang sudah diverifikasi tidak dapat diedit.',
                'paid' => 'Pengajuan yang sudah dibayar tidak dapat diedit.',
                'rejected' => 'Pengajuan yang ditolak tidak dapat diedit. Silakan buat pengajuan baru.',
                default => 'Pengajuan ini tidak dapat diedit.'
            };
            
            return redirect()->route('dosen.penggajian.show', $id)
                ->with('error', $message);
        }

        $semesters = Semester::orderBy('tahun_akademik', 'desc')
            ->orderBy('jenis', 'desc')
            ->get();

        return view('dosen.penggajian.edit', compact('penggajian', 'dosen', 'semesters'));
    }

    /**
     * Update the specified penggajian
     */
    public function update(Request $request, $id)
    {
        $dosen = auth()->user()->dosen;
        
        $penggajian = PenggajianDosen::findOrFail($id);

        // Authorization check
        if ($penggajian->dosen_id !== $dosen->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only pending can be edited
        if (!$penggajian->canBeEdited()) {
            $message = match($penggajian->status) {
                'verified' => 'Pengajuan yang sudah diverifikasi tidak dapat diedit.',
                'paid' => 'Pengajuan yang sudah dibayar tidak dapat diedit.',
                'rejected' => 'Pengajuan yang ditolak tidak dapat diedit. Silakan buat pengajuan baru.',
                default => 'Pengajuan ini tidak dapat diedit.'
            };
            
            return redirect()->route('dosen.penggajian.show', $id)
                ->with('error', $message);
        }

        // Validate (same as store)
        $validator = Validator::make($request->all(), [
            'periode' => 'required|string|regex:/^\d{4}-\d{2}$/',
            'semester_id' => 'nullable|exists:semesters,id',
            'total_jam_diajukan' => 'required|numeric|min:0.5',
            'link_rps' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('Link RPS harus dari Google Drive.');
                }
            }],
            'link_materi_ajar' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('Link Materi Ajar harus dari Google Drive.');
                }
            }],
            'link_absensi' => ['required', 'url', function ($attribute, $value, $fail) {
                if (!str_contains($value, 'drive.google.com')) {
                    $fail('Link Absensi harus dari Google Drive.');
                }
            }],
            'catatan_dosen' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check duplicate periode (exclude current)
        $exists = PenggajianDosen::where('dosen_id', $dosen->id)
            ->where('periode', $request->periode)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withErrors(['periode' => 'Anda sudah mengajukan pencairan untuk periode ini.'])
                ->withInput();
        }

        // Update
        $penggajian->update([
            'periode' => $request->periode,
            'semester_id' => $request->semester_id,
            'total_jam_diajukan' => $request->total_jam_diajukan,
            'link_rps' => $request->link_rps,
            'link_materi_ajar' => $request->link_materi_ajar,
            'link_absensi' => $request->link_absensi,
            'catatan_dosen' => $request->catatan_dosen,
        ]);

        return redirect()->route('dosen.penggajian.show', $id)
            ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    /**
     * Remove the specified penggajian
     */
    public function destroy($id)
    {
        $dosen = auth()->user()->dosen;
        
        $penggajian = PenggajianDosen::findOrFail($id);

        // Authorization check
        if ($penggajian->dosen_id !== $dosen->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only pending can be deleted
        if (!$penggajian->canBeEdited()) {
            $message = match($penggajian->status) {
                'verified' => 'Pengajuan yang sudah diverifikasi tidak dapat dihapus.',
                'paid' => 'Pengajuan yang sudah dibayar tidak dapat dihapus.',
                'rejected' => 'Pengajuan yang ditolak tidak dapat dihapus.',
                default => 'Pengajuan ini tidak dapat dihapus.'
            };
            
            return redirect()->route('dosen.penggajian.index')
                ->with('error', $message);
        }

        $penggajian->delete();

        return redirect()->route('dosen.penggajian.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }
}
