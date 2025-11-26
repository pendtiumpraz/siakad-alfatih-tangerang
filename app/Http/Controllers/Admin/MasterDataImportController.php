<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CsvImportService;
use App\Models\ProgramStudi;
use App\Models\Kurikulum;
use App\Models\MataKuliah;
use App\Models\Ruangan;
use App\Models\Semester;
use App\Models\Jadwal;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MasterDataImportController extends Controller
{
    protected $csvService;
    
    public function __construct(CsvImportService $csvService)
    {
        $this->csvService = $csvService;
    }
    
    /**
     * Show import page
     */
    public function index()
    {
        return view('admin.master-data.import');
    }
    
    /**
     * Import Program Studi
     */
    public function importProgramStudi(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        
        $validationRules = [
            'kode_prodi' => 'required|string|max:20|unique:program_studis,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|in:D3,S1,S2,S3',
            'akreditasi' => 'nullable|string|max:10',
            'is_active' => 'required|in:1,0,Ya,Tidak,TRUE,FALSE'
        ];
        
        $result = $this->csvService->import(
            $request->file('file'),
            ProgramStudi::class,
            $validationRules,
            function($row) {
                return [
                    'kode_prodi' => $row['kode_prodi'],
                    'nama_prodi' => $row['nama_prodi'],
                    'jenjang' => $row['jenjang'],
                    'akreditasi' => $row['akreditasi'] ?? null,
                    'is_active' => in_array(strtolower($row['is_active']), ['1', 'ya', 'true']) ? 1 : 0,
                ];
            }
        );
        
        if ($result['success']) {
            return redirect()->back()->with('import_result', $result);
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
    
    /**
     * Import Kurikulum
     */
    public function importKurikulum(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        
        $validationRules = [
            'kode_prodi' => 'required|exists:program_studis,kode_prodi',
            'nama_kurikulum' => 'required|string|max:255',
            'tahun_mulai' => 'required|integer|min:2000|max:2100',
            'tahun_selesai' => 'nullable|integer|min:2000|max:2100',
            'total_sks' => 'required|integer|min:1',
            'is_active' => 'required|in:1,0,Ya,Tidak,TRUE,FALSE'
        ];
        
        $result = $this->csvService->import(
            $request->file('file'),
            Kurikulum::class,
            $validationRules,
            function($row) {
                $programStudi = ProgramStudi::where('kode_prodi', $row['kode_prodi'])->first();
                
                return [
                    'program_studi_id' => $programStudi->id,
                    'nama_kurikulum' => $row['nama_kurikulum'],
                    'tahun_mulai' => $row['tahun_mulai'],
                    'tahun_selesai' => $row['tahun_selesai'] ?? null,
                    'total_sks' => $row['total_sks'],
                    'is_active' => in_array(strtolower($row['is_active']), ['1', 'ya', 'true']) ? 1 : 0,
                ];
            }
        );
        
        if ($result['success']) {
            return redirect()->back()->with('import_result', $result);
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
    
    /**
     * Import Mata Kuliah
     */
    public function importMataKuliah(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        
        $validationRules = [
            'kurikulum_nama' => 'required|exists:kurikulums,nama_kurikulum',
            'kode_mk' => 'required|string|max:20',
            'nama_mk' => 'required|string|max:255',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:14',
            'jenis' => 'required|in:wajib,pilihan,Wajib,Pilihan',
            'deskripsi' => 'nullable|string'
        ];
        
        $result = $this->csvService->import(
            $request->file('file'),
            MataKuliah::class,
            $validationRules,
            function($row) {
                $kurikulum = Kurikulum::where('nama_kurikulum', $row['kurikulum_nama'])->first();
                
                return [
                    'kurikulum_id' => $kurikulum->id,
                    'kode_mk' => $row['kode_mk'],
                    'nama_mk' => $row['nama_mk'],
                    'sks' => $row['sks'],
                    'semester' => $row['semester'],
                    'jenis' => strtolower($row['jenis']), // Normalize to lowercase
                    'deskripsi' => $row['deskripsi'] ?? null,
                ];
            }
        );
        
        if ($result['success']) {
            return redirect()->back()->with('import_result', $result);
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
    
    /**
     * Import Ruangan
     */
    public function importRuangan(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        
        $validationRules = [
            'kode_ruangan' => 'required|string|max:20|unique:ruangans,kode_ruangan',
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'jenis' => 'required|in:offline,online',
            'fasilitas' => 'nullable|string',
            'is_available' => 'required|in:1,0,Ya,Tidak,TRUE,FALSE'
        ];
        
        $result = $this->csvService->import(
            $request->file('file'),
            Ruangan::class,
            $validationRules,
            function($row) {
                return [
                    'kode_ruangan' => $row['kode_ruangan'],
                    'nama_ruangan' => $row['nama_ruangan'],
                    'kapasitas' => $row['kapasitas'],
                    'jenis' => strtolower($row['jenis']),
                    'fasilitas' => $row['fasilitas'] ?? null,
                    'is_available' => in_array(strtolower($row['is_available']), ['1', 'ya', 'true']) ? 1 : 0,
                ];
            }
        );
        
        if ($result['success']) {
            return redirect()->back()->with('import_result', $result);
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
    
    /**
     * Import Semester
     */
    public function importSemester(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        
        $validationRules = [
            'nama_semester' => 'required|string|max:255',
            'tahun_akademik' => 'required|string|max:20',
            'jenis' => 'required|in:ganjil,genap,pendek',
            'tanggal_mulai' => 'required|date_format:Y-m-d',
            'tanggal_selesai' => 'required|date_format:Y-m-d|after:tanggal_mulai',
            'is_active' => 'required|in:1,0,Ya,Tidak,TRUE,FALSE'
        ];
        
        $result = $this->csvService->import(
            $request->file('file'),
            Semester::class,
            $validationRules,
            function($row) {
                return [
                    'nama_semester' => $row['nama_semester'],
                    'tahun_akademik' => $row['tahun_akademik'],
                    'jenis' => strtolower($row['jenis']),
                    'tanggal_mulai' => $row['tanggal_mulai'],
                    'tanggal_selesai' => $row['tanggal_selesai'],
                    'is_active' => in_array(strtolower($row['is_active']), ['1', 'ya', 'true']) ? 1 : 0,
                    'khs_auto_generate' => false,
                    'khs_show_ketua_prodi_signature' => true,
                    'khs_show_dosen_pa_signature' => true,
                ];
            }
        );
        
        if ($result['success']) {
            return redirect()->back()->with('import_result', $result);
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
    
    /**
     * Import Jadwal
     */
    public function importJadwal(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048'
        ]);
        
        $validationRules = [
            'jenis_semester' => 'required|in:ganjil,genap',
            'kode_mk' => 'required|exists:mata_kuliahs,kode_mk',
            'nidn_dosen' => 'required|exists:dosens,nidn',
            'kode_ruangan' => 'required|exists:ruangans,kode_ruangan',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'kelas' => 'required|string|max:10'
        ];
        
        $result = $this->csvService->import(
            $request->file('file'),
            Jadwal::class,
            $validationRules,
            function($row) {
                $mataKuliah = MataKuliah::where('kode_mk', $row['kode_mk'])->first();
                $dosen = Dosen::where('nidn', $row['nidn_dosen'])->first();
                $ruangan = Ruangan::where('kode_ruangan', $row['kode_ruangan'])->first();
                
                return [
                    'jenis_semester' => strtolower($row['jenis_semester']),
                    'mata_kuliah_id' => $mataKuliah->id,
                    'dosen_id' => $dosen->id,
                    'ruangan_id' => $ruangan->id,
                    'hari' => $row['hari'],
                    'jam_mulai' => $row['jam_mulai'],
                    'jam_selesai' => $row['jam_selesai'],
                    'kelas' => $row['kelas'],
                ];
            }
        );
        
        if ($result['success']) {
            return redirect()->back()->with('import_result', $result);
        }
        
        return redirect()->back()->with('error', $result['message']);
    }
    
    /**
     * Download Template Program Studi
     */
    public function downloadTemplateProgramStudi()
    {
        $headers = ['kode_prodi', 'nama_prodi', 'jenjang', 'akreditasi', 'is_active'];
        
        // Get sample data from database
        $sample = ProgramStudi::first();
        $exampleRow = $sample ? [
            $sample->kode_prodi,
            $sample->nama_prodi,
            $sample->jenjang,
            $sample->akreditasi ?? '',
            $sample->is_active ? '1' : '0'
        ] : ['PAI-S1-L', 'Pendidikan Agama Islam', 'S1', 'B', '1'];
        
        return $this->csvService->generateTemplateWithExample($headers, $exampleRow, 'template_program_studi.csv');
    }
    
    /**
     * Download Template Kurikulum
     */
    public function downloadTemplateKurikulum()
    {
        $headers = ['kode_prodi', 'nama_kurikulum', 'tahun_mulai', 'tahun_selesai', 'total_sks', 'is_active'];
        
        // Get sample data
        $sample = Kurikulum::with('programStudi')->first();
        $exampleRow = $sample ? [
            $sample->programStudi->kode_prodi,
            $sample->nama_kurikulum,
            $sample->tahun_mulai,
            $sample->tahun_selesai ?? '',
            $sample->total_sks,
            $sample->is_active ? '1' : '0'
        ] : ['PAI-S1-L', 'Kurikulum Pendidikan Agama Islam 2024', '2024', '', '148', '1'];
        
        return $this->csvService->generateTemplateWithExample($headers, $exampleRow, 'template_kurikulum.csv');
    }
    
    /**
     * Download Template Mata Kuliah
     */
    public function downloadTemplateMataKuliah()
    {
        $headers = ['kurikulum_nama', 'kode_mk', 'nama_mk', 'sks', 'semester', 'jenis', 'deskripsi'];
        
        // Get sample data
        $sample = MataKuliah::with('kurikulum')->first();
        $exampleRow = $sample ? [
            $sample->kurikulum->nama_kurikulum,
            $sample->kode_mk,
            $sample->nama_mk,
            $sample->sks,
            $sample->semester,
            $sample->jenis,
            $sample->deskripsi ?? ''
        ] : ['Kurikulum Pendidikan Agama Islam 2024', 'PAI-1-001-L', 'Pendidikan Pancasila dan Kewarganegaraan', '2', '1', 'wajib', 'Pendidikan Pancasila dan Kewarganegaraan'];
        
        return $this->csvService->generateTemplateWithExample($headers, $exampleRow, 'template_mata_kuliah.csv');
    }
    
    /**
     * Download Template Ruangan
     */
    public function downloadTemplateRuangan()
    {
        $headers = ['kode_ruangan', 'nama_ruangan', 'kapasitas', 'jenis', 'fasilitas', 'is_available'];
        
        // Get sample data
        $sample = Ruangan::first();
        $exampleRow = $sample ? [
            $sample->kode_ruangan,
            $sample->nama_ruangan,
            $sample->kapasitas,
            $sample->jenis,
            $sample->fasilitas ?? '',
            $sample->is_available ? '1' : '0'
        ] : ['R101', 'Ruang Kuliah 101', '40', 'offline', 'Proyektor, AC, Whiteboard', '1'];
        
        return $this->csvService->generateTemplateWithExample($headers, $exampleRow, 'template_ruangan.csv');
    }
    
    /**
     * Download Template Semester
     */
    public function downloadTemplateSemester()
    {
        $headers = ['nama_semester', 'tahun_akademik', 'jenis', 'tanggal_mulai', 'tanggal_selesai', 'is_active'];
        
        // Get sample data
        $sample = Semester::first();
        $exampleRow = $sample ? [
            $sample->nama_semester,
            $sample->tahun_akademik,
            $sample->jenis,
            $sample->tanggal_mulai->format('Y-m-d'),
            $sample->tanggal_selesai->format('Y-m-d'),
            $sample->is_active ? '1' : '0'
        ] : ['Semester Ganjil 2024/2025', '2024/2025', 'ganjil', '2024-09-01', '2025-01-31', '1'];
        
        return $this->csvService->generateTemplateWithExample($headers, $exampleRow, 'template_semester.csv');
    }
    
    /**
     * Download Template Jadwal
     */
    public function downloadTemplateJadwal()
    {
        $headers = ['jenis_semester', 'kode_mk', 'nidn_dosen', 'kode_ruangan', 'hari', 'jam_mulai', 'jam_selesai', 'kelas'];
        
        // Get sample data
        $sample = Jadwal::with(['mataKuliah', 'dosen', 'ruangan'])->first();
        $exampleRow = $sample ? [
            $sample->jenis_semester,
            $sample->mataKuliah->kode_mk,
            $sample->dosen->nidn,
            $sample->ruangan->kode_ruangan,
            $sample->hari,
            substr($sample->jam_mulai, 0, 5), // HH:MM format
            substr($sample->jam_selesai, 0, 5), // HH:MM format
            $sample->kelas
        ] : ['genap', 'PGMI-2-004-L', '0301108901', 'ONLINE-5', 'Senin', '11:00', '12:00', 'A'];
        
        return $this->csvService->generateTemplateWithExample($headers, $exampleRow, 'template_jadwal.csv');
    }
}
