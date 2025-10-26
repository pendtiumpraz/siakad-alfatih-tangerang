<?php

namespace App\Http\Controllers;

use App\Models\Pendaftar;
use App\Models\JalurSeleksi;
use App\Models\ProgramStudi;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Exception;

class PublicController extends Controller
{
    protected $driveService;

    public function __construct()
    {
        // Initialize Google Drive service if enabled
        if (config('google-drive.enabled')) {
            try {
                $this->driveService = new GoogleDriveService();
            } catch (Exception $e) {
                \Log::warning('Google Drive service initialization failed: ' . $e->getMessage());
                $this->driveService = null;
            }
        }
    }
    /**
     * Upload dokumen pendaftar to Google Drive
     * Local storage is only temporary - deleted after successful upload
     */
    protected function uploadDokumenPendaftar($file, $pendaftarId, $category)
    {
        if (!$this->driveService) {
            throw new Exception('Google Drive tidak aktif. Hubungi administrator untuk mengaktifkan Google Drive terlebih dahulu.');
        }

        try {
            // Upload to Google Drive (REQUIRED)
            $driveResult = $this->driveService->uploadDokumenSPMB(
                $file,
                $pendaftarId,
                $category
            );

            \Log::info("Uploaded {$category} to Google Drive: {$driveResult['id']}");

            return [
                'file' => $driveResult['webViewLink'],
                'google_drive_id' => $driveResult['id'],
                'google_drive_link' => $driveResult['webViewLink'],
            ];
        } catch (Exception $e) {
            \Log::error("Failed to upload {$category} to Google Drive: " . $e->getMessage());
            throw new Exception("Gagal upload {$category} ke Google Drive: " . $e->getMessage());
        }
    }

    /**
     * Delete all dokumen pendaftar from Google Drive
     */
    protected function deleteDokumenPendaftar($pendaftar)
    {
        if (!$this->driveService) {
            return;
        }

        $documents = [
            'google_drive_file_id' => 'Foto',
            'ijazah_google_drive_id' => 'Ijazah',
            'transkrip_google_drive_id' => 'Transkrip',
            'ktp_google_drive_id' => 'KTP',
            'kk_google_drive_id' => 'Kartu Keluarga',
            'akta_google_drive_id' => 'Akta',
            'sktm_google_drive_id' => 'SKTM',
        ];

        foreach ($documents as $field => $label) {
            if ($pendaftar->$field) {
                try {
                    $this->driveService->deleteFile($pendaftar->$field);
                    \Log::info("Deleted {$label} from Google Drive: {$pendaftar->$field}");
                } catch (Exception $e) {
                    \Log::error("Failed to delete {$label} from Google Drive: " . $e->getMessage());
                }
            }
        }
    }

    /**
     * Show SPMB landing page with info jalur seleksi
     */
    public function showSPMB()
    {
        $jalurSeleksis = JalurSeleksi::active()->get();

        return view('public.spmb.index', compact('jalurSeleksis'));
    }

    /**
     * Show multi-step registration form
     */
    public function showRegistrationForm(Request $request)
    {
        $jalurSeleksis = JalurSeleksi::active()->get();
        $programStudis = ProgramStudi::whereNull('deleted_at')->get();

        // Check if continuing from draft
        $draft = null;
        if ($request->has('email') && $request->has('draft')) {
            $draft = Pendaftar::where('email', $request->email)
                ->where('status', 'draft')
                ->first();
        }

        return view('public.spmb.register', compact('jalurSeleksis', 'programStudis', 'draft'));
    }

    /**
     * Process registration with validation
     */
    public function storeRegistration(Request $request)
    {
        // Determine if this is a draft save or final submission
        $isDraft = $request->input('save_as_draft', false);

        // Build validation rules
        $rules = [
            'jalur_seleksi_id' => 'required|exists:jalur_seleksis,id',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pendaftars,email,' . ($request->input('id') ?? 'NULL'),
            'phone' => 'required|string|max:20',
            'nik' => 'required|string|size:16|unique:pendaftars,nik,' . ($request->input('id') ?? 'NULL'),
            'jenis_kelamin' => 'required|in:L,P',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'alamat' => 'required|string',
            'kelurahan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kota_kabupaten' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kode_pos' => 'nullable|string|max:10',
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'required|string|max:255',
            'pekerjaan_ayah' => 'required|string|max:255',
            'pekerjaan_ibu' => 'required|string|max:255',
            'phone_orangtua' => 'required|string|max:20',
            'asal_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'nilai_rata_rata' => 'required|numeric|min:0|max:100',
            'program_studi_pilihan_1' => 'required|exists:program_studis,id',
            'program_studi_pilihan_2' => 'nullable|exists:program_studis,id|different:program_studi_pilihan_1',
        ];

        // Document rules - required for final submission, optional for draft
        if (!$isDraft) {
            $rules['foto'] = 'required|image|mimes:jpg,jpeg,png|max:500';
            $rules['ijazah'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            $rules['transkrip_nilai'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:2048';
            $rules['ktp'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:1024';
            $rules['kartu_keluarga'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:1024';
            $rules['akta_kelahiran'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:1024';
            // SKTM only required for jalur beasiswa
            if ($request->input('jalur_seleksi_id')) {
                $jalur = \App\Models\JalurSeleksi::find($request->input('jalur_seleksi_id'));
                if ($jalur && stripos($jalur->nama, 'beasiswa') !== false) {
                    $rules['sktm'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:1024';
                }
            }
        } else {
            $rules['foto'] = 'nullable|image|mimes:jpg,jpeg,png|max:500';
            $rules['ijazah'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
            $rules['transkrip_nilai'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048';
            $rules['ktp'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024';
            $rules['kartu_keluarga'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024';
            $rules['akta_kelahiran'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024';
            $rules['sktm'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:1024';
        }

        $validator = Validator::make($request->all(), $rules, [
            'foto.required' => 'Foto 4x6 harus diupload sebelum submit pendaftaran.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 500KB.',
            'ijazah.required' => 'Ijazah/SKL harus diupload.',
            'ijazah.mimes' => 'Ijazah harus berformat PDF, JPG, JPEG, atau PNG.',
            'ijazah.max' => 'Ukuran ijazah maksimal 2MB.',
            'transkrip_nilai.required' => 'Transkrip nilai/raport harus diupload.',
            'transkrip_nilai.mimes' => 'Transkrip harus berformat PDF, JPG, JPEG, atau PNG.',
            'transkrip_nilai.max' => 'Ukuran transkrip maksimal 2MB.',
            'ktp.required' => 'KTP harus diupload.',
            'ktp.mimes' => 'KTP harus berformat PDF, JPG, JPEG, atau PNG.',
            'ktp.max' => 'Ukuran KTP maksimal 1MB.',
            'kartu_keluarga.required' => 'Kartu Keluarga harus diupload.',
            'kartu_keluarga.mimes' => 'Kartu Keluarga harus berformat PDF, JPG, JPEG, atau PNG.',
            'kartu_keluarga.max' => 'Ukuran Kartu Keluarga maksimal 1MB.',
            'akta_kelahiran.required' => 'Akta Kelahiran harus diupload.',
            'akta_kelahiran.mimes' => 'Akta Kelahiran harus berformat PDF, JPG, JPEG, atau PNG.',
            'akta_kelahiran.max' => 'Ukuran Akta Kelahiran maksimal 1MB.',
            'sktm.required' => 'SKTM harus diupload untuk jalur beasiswa.',
            'sktm.mimes' => 'SKTM harus berformat PDF, JPG, JPEG, atau PNG.',
            'sktm.max' => 'Ukuran SKTM maksimal 1MB.',
            'nik.size' => 'NIK harus 16 digit.',
            'program_studi_pilihan_2.different' => 'Pilihan 2 harus berbeda dengan Pilihan 1.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate temporary ID for new pendaftar
        $pendaftarId = $request->input('id')
            ? Pendaftar::find($request->input('id'))->nomor_pendaftaran
            : 'TEMP-' . date('Ymd') . '-' . rand(1000, 9999);

        // Delete old documents if updating
        if ($request->input('id')) {
            $existingPendaftar = Pendaftar::find($request->input('id'));
            if ($existingPendaftar) {
                $this->deleteDokumenPendaftar($existingPendaftar);
            }
        }

        // Prepare data
        $data = $request->except([
            'foto', 'ijazah', 'transkrip_nilai', 'ktp', 'kartu_keluarga', 'akta_kelahiran', 'sktm',
            'save_as_draft', 'id'
        ]);

        // Handle all document uploads
        $documents = [
            'foto' => 'Foto',
            'ijazah' => 'Ijazah',
            'transkrip_nilai' => 'Transkrip',
            'ktp' => 'KTP',
            'kartu_keluarga' => 'KK',
            'akta_kelahiran' => 'Akta',
            'sktm' => 'SKTM',
        ];

        foreach ($documents as $field => $category) {
            if ($request->hasFile($field)) {
                try {
                    // Validate foto aspect ratio
                    if ($field === 'foto') {
                        $foto = $request->file($field);
                        $dimensions = getimagesize($foto->getPathname());
                        $ratio = $dimensions[0] / $dimensions[1];
                        if ($ratio < 0.62 || $ratio > 0.72) {
                            return redirect()->back()
                                ->withErrors(['foto' => 'Foto harus memiliki rasio 4x6 (portrait).'])
                                ->withInput();
                        }
                    }

                    // Upload to Google Drive
                    $uploadResult = $this->uploadDokumenPendaftar(
                        $request->file($field),
                        $pendaftarId,
                        $category
                    );

                    // Save to database
                    $data[$field] = $uploadResult['file'];
                    $data[$field . '_google_drive_id'] = $uploadResult['google_drive_id'];
                    $data[$field . '_google_drive_link'] = $uploadResult['google_drive_link'];
                } catch (Exception $e) {
                    return redirect()->back()
                        ->withErrors([$field => $e->getMessage()])
                        ->withInput();
                }
            }
        }

        $data['status'] = $isDraft ? 'draft' : 'pending';

        // Create or update pendaftar
        if ($request->input('id')) {
            $pendaftar = Pendaftar::findOrFail($request->input('id'));
            $pendaftar->update($data);
        } else {
            $pendaftar = Pendaftar::create($data);
        }

        if ($isDraft) {
            return redirect()->back()
                ->with('success', 'Pendaftaran berhasil disimpan sebagai draft. Anda dapat melanjutkan nanti menggunakan email: ' . $pendaftar->email);
        }

        return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
            ->with('success', 'Pendaftaran berhasil! Nomor pendaftaran Anda: ' . $pendaftar->nomor_pendaftaran);
    }

    /**
     * Show check registration status page
     */
    public function checkRegistration()
    {
        return view('public.spmb.check');
    }

    /**
     * Process check registration status
     */
    public function checkRegistrationPost(Request $request)
    {
        $request->validate([
            'nomor_pendaftaran' => 'required|string',
        ]);

        $pendaftar = Pendaftar::where('nomor_pendaftaran', $request->nomor_pendaftaran)
            ->with(['jalurSeleksi', 'programStudiPilihan1', 'programStudiPilihan2'])
            ->first();

        if (!$pendaftar) {
            return redirect()->back()
                ->withErrors(['nomor_pendaftaran' => 'Nomor pendaftaran tidak ditemukan.'])
                ->withInput();
        }

        return view('public.spmb.result', compact('pendaftar'));
    }

    /**
     * Show registration result/status
     */
    public function showResult(Request $request)
    {
        $nomorPendaftaran = $request->query('nomor_pendaftaran');

        if (!$nomorPendaftaran) {
            return redirect()->route('public.spmb.check');
        }

        $pendaftar = Pendaftar::where('nomor_pendaftaran', $nomorPendaftaran)
            ->with(['jalurSeleksi', 'programStudiPilihan1', 'programStudiPilihan2'])
            ->first();

        if (!$pendaftar) {
            return redirect()->route('public.spmb.check')
                ->withErrors(['nomor_pendaftaran' => 'Nomor pendaftaran tidak ditemukan.']);
        }

        return view('public.spmb.result', compact('pendaftar'));
    }
}
