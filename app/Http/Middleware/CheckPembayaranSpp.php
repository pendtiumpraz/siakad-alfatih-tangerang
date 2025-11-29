<?php

namespace App\Http\Middleware;

use App\Models\Pembayaran;
use App\Models\Semester;
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
        if (!Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return $next($request);
        }

        // Get mahasiswa from authenticated user
        $mahasiswa = Auth::user()->mahasiswa;
        
        // If user doesn't have mahasiswa record, allow access
        if (!$mahasiswa) {
            return $next($request);
        }
        
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
            
            // Redirect back with payment info from keterangan pembayaran
            return redirect()->route('mahasiswa.pembayaran.index')->with([
                'error' => 'Anda harus melunasi pembayaran SPP terlebih dahulu untuk mengakses KHS/KRS.',
                'highlight_pembayaran_id' => $pembayaran->id,
            ]);
        }

        // Payment is 'lunas', allow access
        return $next($request);
    }
}
