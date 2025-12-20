<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KrsController extends Controller
{
    /**
     * Display KRS for current semester
     */
    public function index(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        // Get active semester
        $activeSemester = Semester::where('is_active', true)->first();
        
        if (!$activeSemester) {
            return view('mahasiswa.krs.blocked', [
                'reason' => 'Tidak ada semester aktif saat ini.',
                'semester' => null,
                'mahasiswa' => $mahasiswa,
                'type' => 'info'
            ]);
        }

        // Check if mahasiswa has paid SPP for this semester
        // Allow both 'lunas' (fully paid) and 'belum_lunas' (negotiated/partial) to access KRS
        // Only block if payment is still 'pending' (not paid at all)
        $sppPayment = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $activeSemester->id)
            ->where('jenis_pembayaran', 'spp')
            ->first();

        $hasPaidOrNegotiated = $sppPayment && in_array($sppPayment->status, ['lunas', 'belum_lunas']);

        if (!$hasPaidOrNegotiated) {
            return view('mahasiswa.krs.blocked', [
                'reason' => 'Anda belum melakukan pembayaran SPP untuk semester ini. Silakan lakukan pembayaran atau hubungi bagian keuangan untuk negosiasi pembayaran.',
                'semester' => $activeSemester,
                'mahasiswa' => $mahasiswa,
                'type' => 'warning'
            ]);
        }

        // Get KRS for current semester
        $krsItems = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $activeSemester->id)
            ->with(['mataKuliah.kurikulum', 'mataKuliah.jadwals' => function($q) use ($activeSemester) {
                $q->where('jenis_semester', $activeSemester->jenis);
            }])
            ->get();

        // Check if KRS already submitted
        $isSubmitted = $krsItems->where('status', '!=', 'draft')->count() > 0;
        $krsStatus = $krsItems->first()?->status ?? 'draft';

        // If no KRS items yet, auto-populate mata kuliah wajib
        if ($krsItems->isEmpty()) {
            $this->autoPopulateMataKuliahWajib($mahasiswa, $activeSemester);
            
            // Reload KRS items
            $krsItems = Krs::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $activeSemester->id)
                ->with(['mataKuliah.kurikulum', 'mataKuliah.jadwals' => function($q) use ($activeSemester) {
                    $q->where('jenis_semester', $activeSemester->jenis);
                }])
                ->get();
        }

        // Get mata kuliah that can be retaken (failed from previous semesters)
        $failedMataKuliahs = Nilai::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'tidak_lulus')
            ->with(['mataKuliah.kurikulum', 'mataKuliah.jadwals' => function($q) use ($activeSemester) {
                $q->where('jenis_semester', $activeSemester->jenis);
            }])
            ->get()
            ->pluck('mataKuliah')
            ->unique('id')
            ->filter(function($mk) use ($krsItems) {
                // Only show if not already in KRS
                return !$krsItems->pluck('mata_kuliah_id')->contains($mk->id);
            })
            ->values();

        // Calculate total SKS
        $totalSks = $krsItems->sum(function($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });

        // Get mata kuliah wajib count
        $mataKuliahWajibCount = $krsItems->filter(function($krs) {
            return !$krs->is_mengulang;
        })->count();

        $mataKuliahMengulangCount = $krsItems->filter(function($krs) {
            return $krs->is_mengulang;
        })->count();

        return view('mahasiswa.krs.index', compact(
            'mahasiswa',
            'activeSemester',
            'krsItems',
            'failedMataKuliahs',
            'totalSks',
            'mataKuliahWajibCount',
            'mataKuliahMengulangCount',
            'isSubmitted',
            'krsStatus'
        ));
    }

    /**
     * Auto-populate mata kuliah wajib for current semester
     */
    private function autoPopulateMataKuliahWajib(Mahasiswa $mahasiswa, Semester $semester)
    {
        try {
            // Get kurikulum from mahasiswa's program studi
            $kurikulum = $mahasiswa->programStudi->kurikulums()
                ->where('is_active', true)
                ->first();

            if (!$kurikulum) {
                Log::warning("No active kurikulum found for mahasiswa {$mahasiswa->id}");
                return;
            }

            // Calculate current semester number for mahasiswa
            // Assuming mahasiswa starts at semester 1
            $currentSemesterNumber = $this->calculateMahasiswaSemester($mahasiswa, $semester);

            // Get mata kuliah wajib for this semester
            $mataKuliahsWajib = MataKuliah::where('kurikulum_id', $kurikulum->id)
                ->where('semester', $currentSemesterNumber)
                ->where('jenis', 'wajib')
                ->get();

            foreach ($mataKuliahsWajib as $mataKuliah) {
                // Check if jadwal exists for this mata kuliah based on jenis_semester (not specific semester_id)
                $jadwalExists = Jadwal::where('mata_kuliah_id', $mataKuliah->id)
                    ->where('jenis_semester', $semester->jenis)
                    ->exists();

                if (!$jadwalExists) {
                    Log::info("No jadwal found for mata kuliah {$mataKuliah->id} for jenis_semester {$semester->jenis}, skipping auto-populate");
                    continue;
                }

                // Create KRS entry
                Krs::create([
                    'mahasiswa_id' => $mahasiswa->id,
                    'semester_id' => $semester->id,
                    'mata_kuliah_id' => $mataKuliah->id,
                    'is_mengulang' => false,
                    'status' => 'draft',
                ]);
            }

            Log::info("Auto-populated {$mataKuliahsWajib->count()} mata kuliah wajib for mahasiswa {$mahasiswa->id}");
        } catch (\Exception $e) {
            Log::error("Error auto-populating mata kuliah wajib: " . $e->getMessage());
        }
    }

    /**
     * Calculate which semester the mahasiswa is currently in
     * 
     * Use mahasiswa->semester_aktif if available (auto-calculated by Mahasiswa model)
     * Fallback to manual calculation if not available
     */
    private function calculateMahasiswaSemester(Mahasiswa $mahasiswa, Semester $currentSemester)
    {
        // PRIORITY 1: Use semester_aktif from mahasiswa (auto-calculated by model)
        if ($mahasiswa->semester_aktif && $mahasiswa->semester_aktif > 0) {
            return $mahasiswa->semester_aktif;
        }
        
        // PRIORITY 2: Fallback to manual calculation using angkatan
        $currentYear = (int) substr($currentSemester->tahun_akademik, 0, 4);
        $yearDiff = $currentYear - $mahasiswa->angkatan; // Use angkatan, not NIM!
        
        // Calculate semester based on year difference and semester type
        // Ganjil = 1, 3, 5, 7
        // Genap = 2, 4, 6, 8
        if ($currentSemester->jenis === 'ganjil') {
            return ($yearDiff * 2) + 1;
        } else {
            return ($yearDiff * 2) + 2;
        }
    }

    /**
     * Add mata kuliah mengulang to KRS
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_kuliah_id' => 'required|exists:mata_kuliahs,id',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }

        // Check if KRS is still in draft
        $existingKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $activeSemester->id)
            ->first();

        if ($existingKrs && $existingKrs->status !== 'draft') {
            return redirect()->back()->with('error', 'KRS sudah disubmit, tidak bisa menambah mata kuliah.');
        }

        // Check if already exists
        $exists = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $activeSemester->id)
            ->where('mata_kuliah_id', $validated['mata_kuliah_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Mata kuliah sudah ada di KRS.');
        }

        // Check for schedule conflict
        $conflict = $this->checkScheduleConflict($mahasiswa->id, $activeSemester->id, $validated['mata_kuliah_id']);
        
        if ($conflict) {
            return redirect()->back()->with('error', "Jadwal bentrok dengan mata kuliah: {$conflict['nama_mk']} pada {$conflict['hari']}, {$conflict['jam_mulai']} - {$conflict['jam_selesai']}");
        }

        try {
            Krs::create([
                'mahasiswa_id' => $mahasiswa->id,
                'semester_id' => $activeSemester->id,
                'mata_kuliah_id' => $validated['mata_kuliah_id'],
                'is_mengulang' => true,
                'status' => 'draft',
            ]);

            return redirect()->back()->with('success', 'Mata kuliah berhasil ditambahkan ke KRS.');
        } catch (\Exception $e) {
            Log::error("Error adding mata kuliah to KRS: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan mata kuliah ke KRS.');
        }
    }

    /**
     * Check for schedule conflicts
     */
    private function checkScheduleConflict($mahasiswaId, $semesterId, $newMataKuliahId)
    {
        // Get semester info to know jenis_semester
        $semester = Semester::findOrFail($semesterId);
        
        // Get jadwal for new mata kuliah based on jenis_semester
        $newJadwal = Jadwal::where('mata_kuliah_id', $newMataKuliahId)
            ->where('jenis_semester', $semester->jenis)
            ->first();

        if (!$newJadwal) {
            return false; // No jadwal, no conflict
        }

        // Get all jadwal for current KRS based on jenis_semester
        $existingKrs = Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->pluck('mata_kuliah_id');

        $existingJadwals = Jadwal::whereIn('mata_kuliah_id', $existingKrs)
            ->where('jenis_semester', $semester->jenis)
            ->with('mataKuliah')
            ->get();

        foreach ($existingJadwals as $jadwal) {
            // Check if same day
            if ($jadwal->hari === $newJadwal->hari) {
                // Check time overlap
                if ($this->isTimeOverlap(
                    $jadwal->jam_mulai, $jadwal->jam_selesai,
                    $newJadwal->jam_mulai, $newJadwal->jam_selesai
                )) {
                    return [
                        'nama_mk' => $jadwal->mataKuliah->nama_mk,
                        'hari' => $jadwal->hari,
                        'jam_mulai' => $jadwal->jam_mulai,
                        'jam_selesai' => $jadwal->jam_selesai,
                    ];
                }
            }
        }

        return false;
    }

    /**
     * Check if two time ranges overlap
     */
    private function isTimeOverlap($start1, $end1, $start2, $end2)
    {
        return ($start1 < $end2) && ($end1 > $start2);
    }

    /**
     * Remove mata kuliah mengulang from KRS
     */
    public function destroy($id)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        $krs = Krs::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->first();

        if (!$krs) {
            return redirect()->back()->with('error', 'Data KRS tidak ditemukan.');
        }

        if ($krs->status !== 'draft') {
            return redirect()->back()->with('error', 'KRS sudah disubmit, tidak bisa menghapus mata kuliah.');
        }

        if (!$krs->is_mengulang) {
            return redirect()->back()->with('error', 'Mata kuliah wajib tidak bisa dihapus.');
        }

        try {
            $krs->delete();
            return redirect()->back()->with('success', 'Mata kuliah berhasil dihapus dari KRS.');
        } catch (\Exception $e) {
            Log::error("Error deleting KRS: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus mata kuliah dari KRS.');
        }
    }

    /**
     * Submit KRS
     */
    public function submit(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        $activeSemester = Semester::where('is_active', true)->first();

        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif.');
        }

        $krsItems = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $activeSemester->id)
            ->where('status', 'draft')
            ->get();

        if ($krsItems->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada KRS yang dapat disubmit.');
        }

        // Validate minimum SKS (optional, adjust as needed)
        $totalSks = $krsItems->sum(function($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });

        if ($totalSks < 1) {
            return redirect()->back()->with('error', 'Minimal harus mengambil 1 SKS.');
        }

        // Maximum SKS check (24 SKS as per requirement)
        if ($totalSks > 24) {
            return redirect()->back()->with('error', 'Maksimal SKS per semester adalah 24 SKS. Total SKS Anda: ' . $totalSks);
        }

        try {
            DB::beginTransaction();

            foreach ($krsItems as $krs) {
                $krs->update([
                    'status' => 'submitted',
                    'submitted_at' => now(),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'KRS berhasil disubmit. Menunggu persetujuan Dosen PA.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error submitting KRS: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal submit KRS.');
        }
    }

    /**
     * Print KRS
     */
    public function print(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }

        $activeSemester = Semester::where('is_active', true)->first();
        
        if (!$activeSemester) {
            return redirect()->back()->with('error', 'Tidak ada semester aktif saat ini.');
        }

        $krsItems = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $activeSemester->id)
            ->with(['mataKuliah.kurikulum', 'mataKuliah.jadwals' => function($q) use ($activeSemester) {
                $q->where('jenis_semester', $activeSemester->jenis)->with(['dosen', 'ruangan']);
            }, 'approvedBy'])
            ->get();

        if ($krsItems->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada KRS untuk dicetak.');
        }

        $totalSks = $krsItems->sum(function($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });

        $krsStatus = $krsItems->first()->status;
        $submittedAt = $krsItems->first()->submitted_at;
        $approvedAt = $krsItems->first()->approved_at;
        $approvedBy = $krsItems->first()->approvedBy;

        return view('mahasiswa.krs.print', compact(
            'mahasiswa',
            'activeSemester',
            'krsItems',
            'totalSks',
            'krsStatus',
            'submittedAt',
            'approvedAt',
            'approvedBy'
        ));
    }
}
