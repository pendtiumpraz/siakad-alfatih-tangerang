<?php

namespace App\Http\Middleware;

use App\Models\Pembayaran;
use App\Models\Semester;
use App\Models\SppSetting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPembayaranSpp
{
    /**
     * Handle an incoming request.
     * 
     * Check if mahasiswa has paid SPP for active semester
     * Block access to KHS/KRS if not paid
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to mahasiswa
        if (!Auth::guard('mahasiswa')->check()) {
            return $next($request);
        }

        $mahasiswa = Auth::guard('mahasiswa')->user();
        
        // Get active semester
        $activeSemester = Semester::where('is_active', true)->first();
        
        if (!$activeSemester) {
            // No active semester, allow access
            return $next($request);
        }

        // Check SPP payment for active semester
        $pembayaran = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $activeSemester->id)
            ->where('jenis_pembayaran', 'spp')
            ->first();

        // If no payment record exists, allow access (maybe SPP not generated yet)
        if (!$pembayaran) {
            return $next($request);
        }

        // If payment status is NOT 'lunas', block access
        if ($pembayaran->status !== 'lunas') {
            
            // Get SPP setting for payment info
            $sppSetting = SppSetting::getActiveForProdi($mahasiswa->program_studi_id) 
                ?? SppSetting::getActiveForProdi(null);

            // Redirect back with payment info
            return redirect()->route('mahasiswa.dashboard')->with([
                'error' => 'Anda harus melunasi pembayaran SPP terlebih dahulu untuk mengakses fitur ini.',
                'pembayaran_info' => [
                    'nominal' => $pembayaran->jumlah,
                    'jatuh_tempo' => $pembayaran->tanggal_jatuh_tempo,
                    'semester' => $activeSemester->nama_semester . ' ' . $activeSemester->tahun_akademik,
                    'rekening_nama' => $sppSetting->rekening_nama ?? 'STAI AL-FATIH TANGERANG',
                    'rekening_nomor' => $sppSetting->rekening_nomor ?? '-',
                    'rekening_bank' => $sppSetting->rekening_bank ?? '-',
                    'contact_wa' => $sppSetting->contact_whatsapp ?? '-',
                    'contact_email' => $sppSetting->contact_email ?? '-',
                ],
            ]);
        }

        // Payment is 'lunas', allow access
        return $next($request);
    }
}
