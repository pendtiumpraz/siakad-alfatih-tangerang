<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Pembayaran;
use App\Models\Semester;
use App\Models\SppSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SppAutoGenerateService
{
    /**
     * Generate SPP for all active mahasiswa when semester changes
     * 
     * @param Semester $newSemester The newly activated semester
     * @param Semester|null $previousSemester The previously active semester
     * @return array Result with count and status
     */
    public function generateSppForSemester(Semester $newSemester, ?Semester $previousSemester = null)
    {
        try {
            // Check if this is a valid progression (+1 semester only)
            if ($previousSemester && !$this->isValidProgression($previousSemester, $newSemester)) {
                Log::info('SPP not generated: Invalid semester progression', [
                    'previous' => $previousSemester->nama_semester,
                    'new' => $newSemester->nama_semester,
                ]);
                
                return [
                    'success' => false,
                    'message' => 'SPP tidak digenerate: Perpindahan semester tidak valid (harus +1 semester berurutan)',
                    'count' => 0,
                ];
            }

            DB::beginTransaction();

            // Get all active mahasiswa
            $mahasiswas = Mahasiswa::where('status', 'aktif')->get();
            
            if ($mahasiswas->isEmpty()) {
                DB::rollback();
                return [
                    'success' => false,
                    'message' => 'Tidak ada mahasiswa aktif',
                    'count' => 0,
                ];
            }

            $generated = 0;
            $errors = [];

            foreach ($mahasiswas as $mahasiswa) {
                try {
                    // Check if SPP already exists for this mahasiswa & semester
                    $exists = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
                        ->where('semester_id', $newSemester->id)
                        ->where('jenis_pembayaran', 'spp')
                        ->exists();

                    if ($exists) {
                        continue; // Skip if already exists
                    }

                    // Get SPP setting for this program studi
                    $sppSetting = SppSetting::getActiveForProdi($mahasiswa->program_studi_id);
                    
                    if (!$sppSetting) {
                        // Try to get default setting
                        $sppSetting = SppSetting::getActiveForProdi(null);
                    }

                    if (!$sppSetting) {
                        $errors[] = "No SPP setting found for mahasiswa: {$mahasiswa->nama_lengkap}";
                        continue;
                    }

                    // Calculate due date
                    $tanggalJatuhTempo = Carbon::now()->addDays($sppSetting->jatuh_tempo_hari);

                    // Create pembayaran record
                    Pembayaran::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'semester_id' => $newSemester->id,
                        'operator_id' => null, // Auto-generated, no operator yet
                        'jenis_pembayaran' => 'spp',
                        'jumlah' => $sppSetting->nominal,
                        'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
                        'tanggal_bayar' => null,
                        'status' => 'belum_lunas',
                        'bukti_pembayaran' => null,
                        'keterangan' => "Pembayaran SPP {$newSemester->nama_semester} {$newSemester->tahun_akademik}",
                    ]);

                    $generated++;

                } catch (\Exception $e) {
                    $errors[] = "Error for {$mahasiswa->nama_lengkap}: {$e->getMessage()}";
                    Log::error('SPP generation error for mahasiswa', [
                        'mahasiswa_id' => $mahasiswa->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            DB::commit();

            Log::info('SPP Auto-generated', [
                'semester' => $newSemester->nama_semester,
                'count' => $generated,
                'total_mahasiswa' => $mahasiswas->count(),
                'errors_count' => count($errors),
            ]);

            return [
                'success' => true,
                'message' => "SPP berhasil digenerate untuk {$generated} mahasiswa",
                'count' => $generated,
                'total' => $mahasiswas->count(),
                'errors' => $errors,
            ];

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('SPP Auto-generate failed', [
                'semester_id' => $newSemester->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal generate SPP: ' . $e->getMessage(),
                'count' => 0,
            ];
        }
    }

    /**
     * Check if semester progression is valid (+1 semester only)
     * 
     * Valid progressions:
     * - Ganjil 2024/2025 -> Genap 2024/2025
     * - Genap 2024/2025 -> Ganjil 2025/2026
     * 
     * Invalid:
     * - Skipping semesters
     * - Going backwards
     * 
     * @param Semester $from Previous semester
     * @param Semester $to New semester
     * @return bool
     */
    private function isValidProgression(Semester $from, Semester $to): bool
    {
        // Extract year from tahun_akademik (e.g., "2024/2025" -> 2024)
        $fromYear = (int) substr($from->tahun_akademik, 0, 4);
        $toYear = (int) substr($to->tahun_akademik, 0, 4);

        // Determine jenis semester
        $fromJenis = strpos(strtolower($from->nama_semester), 'ganjil') !== false ? 'ganjil' : 'genap';
        $toJenis = strpos(strtolower($to->nama_semester), 'ganjil') !== false ? 'ganjil' : 'genap';

        // Valid progression cases:
        // Case 1: Ganjil -> Genap (same year)
        if ($fromJenis === 'ganjil' && $toJenis === 'genap' && $fromYear === $toYear) {
            return true;
        }

        // Case 2: Genap -> Ganjil (next year)
        if ($fromJenis === 'genap' && $toJenis === 'ganjil' && $toYear === $fromYear + 1) {
            return true;
        }

        // All other cases are invalid
        return false;
    }

    /**
     * Get payment info for mahasiswa (for dashboard display)
     * 
     * @param int $mahasiswaId
     * @return array|null
     */
    public function getPaymentInfo($mahasiswaId)
    {
        $activeSemester = Semester::where('is_active', true)->first();
        
        if (!$activeSemester) {
            return null;
        }

        $pembayaran = Pembayaran::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $activeSemester->id)
            ->where('jenis_pembayaran', 'spp')
            ->first();

        if (!$pembayaran) {
            return null;
        }

        // Get SPP setting for payment details
        $mahasiswa = Mahasiswa::find($mahasiswaId);
        $sppSetting = SppSetting::getActiveForProdi($mahasiswa->program_studi_id) 
            ?? SppSetting::getActiveForProdi(null);

        return [
            'pembayaran' => $pembayaran,
            'semester' => $activeSemester,
            'spp_setting' => $sppSetting,
        ];
    }
}
