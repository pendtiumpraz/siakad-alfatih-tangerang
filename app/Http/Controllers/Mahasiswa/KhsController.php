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
     * Download KHS as PDF using TCPDF (NO GD extension required!)
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

        // Generate base64 logo (no GD extension required!)
        $logoPath = public_path('images/logo-alfatih.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        // Generate PDF using TCPDF (NO GD REQUIRED!)
        $pdf = \App::make('tcpdf');
        
        // Set document info
        $pdf->SetCreator('SIAKAD STAI Al-Fatih Tangerang');
        $pdf->SetAuthor('STAI Al-Fatih Tangerang');
        $pdf->SetTitle('Kartu Hasil Studi (KHS)');
        $pdf->SetSubject('KHS Mahasiswa');
        
        // Set margins (left, top, right)
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Add page
        $pdf->AddPage('P', 'A4'); // Portrait, A4
        
        // Set font
        $pdf->SetFont('times', '', 10);
        
        // Generate HTML view
        $html = view('mahasiswa.khs.pdf', compact('khs', 'khsService', 'logoBase64'))->render();
        
        // Write HTML to PDF
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Generate filename
        $filename = 'KHS_' . $mahasiswa->nim . '_' . str_replace('/', '-', $khs->semester->tahun_akademik) . '.pdf';
        
        // Download PDF
        return response($pdf->Output($filename, 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
