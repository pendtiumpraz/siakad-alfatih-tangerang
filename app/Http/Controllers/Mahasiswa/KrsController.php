<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Pembayaran;
use App\Models\Kurikulum;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KrsController extends Controller
{
    /**
     * Display KRS form for current semester
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->with('programStudi')->first();

        if (!$mahasiswa) {
            abort(403, 'Data mahasiswa tidak ditemukan');
        }

        // Get active semester
        $semester = Semester::where('is_active', true)->first();

        if (!$semester) {
            return view('mahasiswa.krs.index')->with('error', 'Tidak ada semester aktif saat ini');
        }

        // Check pembayaran SPP
        $pembayaran = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $semester->id)
            ->where('jenis_pembayaran', 'spp')
            ->where('status', 'lunas')
            ->first();

        if (!$pembayaran) {
            return view('mahasiswa.krs.blocked', [
                'mahasiswa' => $mahasiswa,
                'semester' => $semester,
                'reason' => 'Anda belum melunasi pembayaran SPP untuk semester ini'
            ]);
        }

        // Get existing KRS
        $existingKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $semester->id)
            ->with('mataKuliah')
            ->get();

        // Get mata kuliah wajib untuk semester ini (berdasarkan kurikulum)
        $mataKuliahWajib = MataKuliah::whereHas('kurikulum', function ($query) use ($mahasiswa) {
                $query->where('program_studi_id', $mahasiswa->program_studi_id);
            })
            ->where('semester', $semester->semester)
            ->where('jenis', 'wajib')
            ->get();

        // Get mata kuliah yang tidak lulus dari SEMUA semester sebelumnya (untuk mengulang)
        // Mahasiswa bisa mengulang kapan saja sampai semester 14
        $mataKuliahTidakLulus = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'tidak_lulus')
            ->with(['mataKuliah'])
            ->get()
            ->pluck('mataKuliah')
            ->unique('id')
            ->filter(function ($mk) {
                return $mk !== null;
            });

        // Calculate total SKS (informational only, no limit)
        $totalSks = $existingKrs->sum(function ($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });

        // Get status KRS
        $firstKrs = $existingKrs->first();
        $status = $firstKrs->status ?? 'draft';

        return view('mahasiswa.krs.index', compact(
            'mahasiswa',
            'semester',
            'existingKrs',
            'mataKuliahWajib',
            'mataKuliahTidakLulus',
            'totalSks'
        ));
    }

    /**
     * Auto-populate KRS with all wajib mata kuliah + selected mengulang
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $semester = Semester::where('is_active', true)->first();

        if (!$semester) {
            return back()->with('error', 'Tidak ada semester aktif');
        }

        // Check pembayaran
        $pembayaran = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $semester->id)
            ->where('jenis_pembayaran', 'spp')
            ->where('status', 'lunas')
            ->first();

        if (!$pembayaran) {
            return back()->with('error', 'Anda belum melunasi pembayaran SPP');
        }

        // Check if KRS already submitted
        $existingStatus = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $semester->id)
            ->value('status');

        if ($existingStatus && $existingStatus != 'draft') {
            return back()->with('error', 'KRS sudah disubmit dan tidak bisa diubah');
        }

        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
            'is_mengulang' => 'boolean',
        ]);

        try {
            // Check if already exists
            $exists = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $semester->id)
                ->where('mata_kuliah_id', $validated['mata_kuliah_id'])
                ->exists();

            if ($exists) {
                return back()->with('error', 'Mata kuliah sudah ditambahkan ke KRS');
            }

            $mataKuliah = MataKuliah::find($validated['mata_kuliah_id']);

            // Validasi jadwal bentrok (jika mata kuliah mengulang)
            if ($request->boolean('is_mengulang')) {
                $conflict = $this->checkJadwalConflict($mahasiswa->id, $semester->id, $validated['mata_kuliah_id']);
                
                if ($conflict) {
                    return back()->with('error', "Jadwal bentrok! Mata kuliah ini bertabrakan dengan: {$conflict['nama_mk']} ({$conflict['hari']} {$conflict['jam']})");
                }
            }

            // Create KRS
            Krs::create([
                'mahasiswa_id' => $mahasiswa->id,
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $validated['mata_kuliah_id'],
                'is_mengulang' => $request->boolean('is_mengulang'),
                'status' => 'draft',
            ]);

            return back()->with('success', 'Mata kuliah berhasil ditambahkan ke KRS');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan mata kuliah: ' . $e->getMessage());
        }
    }

    /**
     * Remove KRS item (only for mengulang mata kuliah)
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        try {
            $krs = Krs::where('id', $id)
                ->where('mahasiswa_id', $mahasiswa->id)
                ->where('status', 'draft')
                ->firstOrFail();

            // Hanya mata kuliah mengulang yang bisa dihapus
            if (!$krs->is_mengulang) {
                return back()->with('error', 'Mata kuliah wajib tidak dapat dihapus dari KRS');
            }

            $krs->delete();

            return back()->with('success', 'Mata kuliah berhasil dihapus dari KRS');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus mata kuliah');
        }
    }

    /**
     * Submit KRS for approval
     */
    public function submit()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return back()->with('error', 'Data mahasiswa tidak ditemukan');
        }

        $semester = Semester::where('is_active', true)->first();

        if (!$semester) {
            return back()->with('error', 'Tidak ada semester aktif');
        }

        try {
            $krsCount = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $semester->id)
                ->where('status', 'draft')
                ->count();

            if ($krsCount == 0) {
                return back()->with('error', 'Tidak ada mata kuliah dalam KRS untuk disubmit');
            }

            // Update all KRS to submitted
            Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $semester->id)
                ->where('status', 'draft')
                ->update([
                    'status' => 'submitted',
                    'submitted_at' => Carbon::now(),
                ]);

            return back()->with('success', 'KRS berhasil disubmit. Menunggu persetujuan admin.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal submit KRS: ' . $e->getMessage());
        }
    }

    /**
     * Print KRS
     */
    public function print()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->with('programStudi')->first();

        if (!$mahasiswa) {
            abort(403, 'Data mahasiswa tidak ditemukan');
        }

        $semester = Semester::where('is_active', true)->first();

        if (!$semester) {
            return back()->with('error', 'Tidak ada semester aktif');
        }

        $krsItems = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $semester->id)
            ->with(['mataKuliah', 'approvedBy'])
            ->get();

        if ($krsItems->isEmpty()) {
            return back()->with('error', 'Belum ada mata kuliah dalam KRS');
        }

        $totalSks = $krsItems->sum(function ($item) {
            return $item->mataKuliah->sks ?? 0;
        });

        return view('mahasiswa.krs.print', compact('mahasiswa', 'semester', 'krsItems', 'totalSks'));
    }

    /**
     * Check if jadwal bentrok between existing KRS and new mata kuliah
     * 
     * @param int $mahasiswaId
     * @param int $semesterId
     * @param int $newMataKuliahId
     * @return array|false Return conflict info or false if no conflict
     */
    private function checkJadwalConflict($mahasiswaId, $semesterId, $newMataKuliahId)
    {
        // Get jadwal for new mata kuliah
        $newJadwal = Jadwal::where('semester_id', $semesterId)
            ->where('mata_kuliah_id', $newMataKuliahId)
            ->first();

        if (!$newJadwal) {
            // Jika tidak ada jadwal, tidak bisa cek conflict (skip validation)
            return false;
        }

        // Get all existing KRS mata kuliah IDs
        $existingMkIds = Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->pluck('mata_kuliah_id')
            ->toArray();

        if (empty($existingMkIds)) {
            return false;
        }

        // Get jadwal for existing mata kuliah
        $existingJadwals = Jadwal::where('semester_id', $semesterId)
            ->whereIn('mata_kuliah_id', $existingMkIds)
            ->with('mataKuliah')
            ->get();

        // Check for conflicts
        foreach ($existingJadwals as $jadwal) {
            // Same day?
            if ($jadwal->hari != $newJadwal->hari) {
                continue;
            }

            // Time overlap?
            $existingStart = strtotime($jadwal->jam_mulai);
            $existingEnd = strtotime($jadwal->jam_selesai);
            $newStart = strtotime($newJadwal->jam_mulai);
            $newEnd = strtotime($newJadwal->jam_selesai);

            // Check if times overlap
            if (($newStart >= $existingStart && $newStart < $existingEnd) ||
                ($newEnd > $existingStart && $newEnd <= $existingEnd) ||
                ($newStart <= $existingStart && $newEnd >= $existingEnd)) {
                
                return [
                    'nama_mk' => $jadwal->mataKuliah->nama_mk,
                    'hari' => $jadwal->hari,
                    'jam' => date('H:i', $existingStart) . '-' . date('H:i', $existingEnd),
                ];
            }
        }

        return false;
    }
}
