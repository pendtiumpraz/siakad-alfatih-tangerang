<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use App\Models\Jadwal;
use App\Models\Khs;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MahasiswaDashboardController extends Controller
{
    /**
     * Display the mahasiswa dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get mahasiswa data from authenticated user
        $mahasiswa = auth()->user()->mahasiswa;

        if (!$mahasiswa) {
            return redirect()->route('login')
                ->with('error', 'Data mahasiswa tidak ditemukan. Silakan hubungi administrator.');
        }

        // Get semester aktif
        $semesterAktif = Semester::where('is_active', true)->first();

        // Get KHS terakhir untuk IP Semester dan IPK
        $khsTerakhir = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->with('semester')
            ->orderBy('semester_id', 'desc')
            ->first();

        // Get total SKS kumulatif dari KHS terbaru
        $latestKhs = Khs::where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('semester_id', 'desc')
            ->first();
        $totalSksLulus = $latestKhs ? $latestKhs->total_sks_kumulatif : 0;

        // Get jadwal hari ini
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, dst
        // Mapping hari Indonesia ke format database
        $hariMapping = [
            'Senin' => 'Senin',
            'Selasa' => 'Selasa',
            'Rabu' => 'Rabu',
            'Kamis' => 'Kamis',
            'Jumat' => 'Jumat',
            'Sabtu' => 'Sabtu',
            'Minggu' => 'Minggu'
        ];
        $hari = $hariMapping[$hariIni] ?? 'Senin';

        // Get jadwal untuk hari ini (dari jadwal mahasiswa yang terdaftar)
        // Note: Ini mengasumsikan ada relasi melalui semester aktif
        $jadwalHariIni = collect(); // Empty collection for now, karena belum ada tabel KRS
        if ($semesterAktif) {
            $jadwalHariIni = Jadwal::where('hari', $hari)
                ->where('semester_id', $semesterAktif->id)
                ->with(['mataKuliah', 'dosen', 'ruangan'])
                ->orderBy('jam_mulai')
                ->limit(3)
                ->get();
        }

        // Get pembayaran yang belum lunas dan masih dalam jatuh tempo
        $pembayaranPending = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->where('status', 'pending')
            ->where('tanggal_jatuh_tempo', '>=', now())
            ->orderBy('tanggal_jatuh_tempo')
            ->limit(2)
            ->get();

        // Pengumuman dummy (karena tabel belum ada)
        $pengumuman = collect([
            (object)[
                'id' => 1,
                'judul' => 'Pembayaran UTS Semester Genap',
                'isi' => 'Batas waktu pembayaran UTS adalah 30 Oktober 2024. Segera lakukan pembayaran untuk menghindari denda.',
                'created_at' => now()->subDays(3),
            ],
            (object)[
                'id' => 2,
                'judul' => 'Perubahan Jadwal Kuliah',
                'isi' => 'Jadwal kuliah Fiqih Muamalah minggu depan dipindah ke Ruang C-204.',
                'created_at' => now()->subDays(5),
            ],
            (object)[
                'id' => 3,
                'judul' => 'Nilai UAS Telah Keluar',
                'isi' => 'Nilai UAS Semester Ganjil sudah dapat dilihat di menu KHS.',
                'created_at' => now()->subWeek(),
            ],
        ]);

        // Compile stats
        $stats = [
            'ip_semester' => $khsTerakhir->ip ?? 0,
            'ipk' => $khsTerakhir->ipk ?? 0,
            'total_sks' => $totalSksLulus,
            'semester_aktif' => $mahasiswa->semester_aktif ?? 1,
            'status' => $mahasiswa->status ?? 'aktif',
        ];

        return view('mahasiswa.dashboard', compact(
            'mahasiswa',
            'stats',
            'semesterAktif',
            'jadwalHariIni',
            'pembayaranPending',
            'pengumuman',
            'khsTerakhir'
        ));
    }

    public function docs()
    {
        return view('mahasiswa.docs');
    }
}
