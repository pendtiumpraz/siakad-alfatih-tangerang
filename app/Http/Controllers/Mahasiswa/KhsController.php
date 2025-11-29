<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Khs;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class KhsController extends Controller
{
    /**
     * Display a listing of mahasiswa's own KHS
     */
    public function index()
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $khsList = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->with('semester')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.khs.index', compact('khsList', 'mahasiswa'));
    }

    /**
     * Display the specified KHS
     */
    public function show($id)
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Ensure mahasiswa can only view their own KHS
        $khs = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->where('id', $id)
            ->with([
                'mahasiswa.programStudi',
                'mahasiswa.dosenPa',
                'semester',
                'mahasiswa.nilais' => function($q) use ($id) {
                    $khs = Khs::find($id);
                    if ($khs) {
                        $q->where('semester_id', $khs->semester_id)
                          ->with('mataKuliah');
                    }
                }
            ])
            ->firstOrFail();

        return view('mahasiswa.khs.show', compact('khs'));
    }

    /**
     * Download KHS as PDF
     */
    public function downloadPdf($id)
    {
        $user = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Ensure mahasiswa can only download their own KHS
        $khs = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->where('id', $id)
            ->with([
                'mahasiswa.programStudi.ketuaProdi',
                'mahasiswa.dosenPa',
                'semester',
                'mahasiswa.nilais' => function($q) use ($id) {
                    $khs = Khs::find($id);
                    if ($khs) {
                        $q->where('semester_id', $khs->semester_id)
                          ->with('mataKuliah');
                    }
                }
            ])
            ->firstOrFail();

        $khsService = new \App\Services\KhsService();

        // Generate base64 logo
        $logoPath = public_path('images/logo-alfatih.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        // Generate PDF using DomPDF
        $pdf = \PDF::loadView('mahasiswa.khs.pdf', compact('khs', 'khsService', 'logoBase64'));
        $pdf->setPaper('A4', 'portrait');
        
        // Generate filename
        $filename = 'KHS_' . $mahasiswa->nim . '_' . str_replace('/', '-', $khs->semester->tahun_akademik) . '.pdf';
        
        // Download PDF
        return $pdf->download($filename);
    }
}
