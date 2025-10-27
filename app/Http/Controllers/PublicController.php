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
     * Rollback uploaded files on error
     */
    protected function rollbackUploadedFiles(array $fileIds)
    {
        if (!$this->driveService || empty($fileIds)) {
            return;
        }

        \Log::warning("Rolling back " . count($fileIds) . " uploaded files...");

        foreach ($fileIds as $fileId) {
            try {
                $this->driveService->deleteFile($fileId);
                \Log::info("Rolled back file: {$fileId}");
            } catch (Exception $e) {
                \Log::error("Failed to rollback file {$fileId}: " . $e->getMessage());
            }
        }
    }

    /**
     * Show SPMB landing page with info jalur seleksi
     */
    public function showSPMB()
    {
        $jalurSeleksis = JalurSeleksi::active()->get();

        // Load SPMB contact settings
        $spmbPhone = \App\Models\SystemSetting::get('spmb_phone', '021-12345678');
        $spmbEmail = \App\Models\SystemSetting::get('spmb_email', 'info@staialfatih.ac.id');
        $spmbWhatsapp = \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890');

        return view('public.spmb.index', compact('jalurSeleksis', 'spmbPhone', 'spmbEmail', 'spmbWhatsapp'));
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
        // Increase execution time for large file uploads to Google Drive
        set_time_limit(300); // 5 minutes for uploading multiple documents
        ini_set('memory_limit', '256M'); // Increase memory limit

        \Log::info("====== SPMB REGISTRATION STARTED ======");
        \Log::info("Request method: " . $request->method());
        \Log::info("Has files: " . ($request->hasFile('foto') ? 'YES' : 'NO'));
        \Log::info("Google Drive service: " . ($this->driveService ? 'ACTIVE' : 'NOT ACTIVE'));

        // Determine if this is a draft save or final submission
        $isDraft = $request->input('save_as_draft', false);
        \Log::info("Is draft: " . ($isDraft ? 'YES' : 'NO'));

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
            \Log::error("====== VALIDATION FAILED ======");
            \Log::error("Errors: " . json_encode($validator->errors()->toArray()));
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        \Log::info("✅ Validation passed");

        // Generate temporary ID for new pendaftar
        $pendaftarId = $request->input('id')
            ? Pendaftar::find($request->input('id'))->nomor_pendaftaran
            : 'TEMP-' . date('Ymd') . '-' . rand(1000, 9999);

        // Pre-create folders in Google Drive to avoid timeout during upload
        if ($this->driveService && !$isDraft) {
            try {
                \Log::info("📁 Pre-creating Google Drive folders for pendaftar: {$pendaftarId}");

                // Create SPMB folder if not exists
                $spmbFolder = $this->driveService->findFolder(config('google-drive.folders.spmb'))
                    ?? $this->driveService->createFolder(config('google-drive.folders.spmb'));

                \Log::info("📁 SPMB folder ID: {$spmbFolder}");

                // Create pendaftar subfolder
                $pendaftarFolder = $this->driveService->findFolder($pendaftarId, $spmbFolder)
                    ?? $this->driveService->createFolder($pendaftarId, $spmbFolder);

                \Log::info("✅ Google Drive folders ready - Pendaftar folder ID: {$pendaftarFolder}");
            } catch (Exception $e) {
                \Log::error("❌ Failed to pre-create Google Drive folders: " . $e->getMessage());
                \Log::error("Exception trace: " . $e->getTraceAsString());
                return redirect()->back()
                    ->withErrors(['_error' => 'Gagal menyiapkan folder Google Drive: ' . $e->getMessage()])
                    ->withInput();
            }
        } elseif (!$this->driveService && !$isDraft) {
            \Log::error("❌ Google Drive service is NULL - cannot upload documents");
            return redirect()->back()
                ->withErrors(['_error' => 'Google Drive tidak aktif. Hubungi administrator untuk mengaktifkan Google Drive terlebih dahulu.'])
                ->withInput();
        }

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

        // Handle all document uploads with rollback on failure
        // Map form field names to database field names
        $documents = [
            'foto' => ['category' => 'Foto', 'db_field' => 'google_drive'],
            'ijazah' => ['category' => 'Ijazah', 'db_field' => 'ijazah_google_drive'],
            'transkrip_nilai' => ['category' => 'Transkrip', 'db_field' => 'transkrip_google_drive'],
            'ktp' => ['category' => 'KTP', 'db_field' => 'ktp_google_drive'],
            'kartu_keluarga' => ['category' => 'KK', 'db_field' => 'kk_google_drive'],
            'akta_kelahiran' => ['category' => 'Akta', 'db_field' => 'akta_google_drive'],
            'sktm' => ['category' => 'SKTM', 'db_field' => 'sktm_google_drive'],
        ];

        $uploadedFiles = []; // Track uploaded files for rollback

        foreach ($documents as $field => $config) {
            if ($request->hasFile($field)) {
                try {
                    $category = $config['category'];
                    $dbField = $config['db_field'];

                    // Validate foto aspect ratio
                    if ($field === 'foto') {
                        $foto = $request->file($field);
                        $dimensions = getimagesize($foto->getPathname());
                        $ratio = $dimensions[0] / $dimensions[1];
                        if ($ratio < 0.62 || $ratio > 0.72) {
                            // Rollback uploaded files
                            $this->rollbackUploadedFiles($uploadedFiles);
                            return redirect()->back()
                                ->withErrors(['foto' => 'Foto harus memiliki rasio 4x6 (portrait).'])
                                ->withInput();
                        }
                    }

                    \Log::info("Starting upload for {$category}...");

                    // Upload to Google Drive
                    $uploadResult = $this->uploadDokumenPendaftar(
                        $request->file($field),
                        $pendaftarId,
                        $category
                    );

                    // Track uploaded file for potential rollback
                    $uploadedFiles[] = $uploadResult['google_drive_id'];

                    // Save to database with correct field names
                    $data[$field] = $uploadResult['file'];
                    $data[$dbField . '_file_id'] = $uploadResult['google_drive_id'];
                    $data[$dbField . '_link'] = $uploadResult['google_drive_link'];

                    \Log::info("Successfully uploaded {$category}: {$uploadResult['google_drive_id']} → {$dbField}_file_id");

                } catch (Exception $e) {
                    \Log::error("Failed to upload {$category}: " . $e->getMessage());

                    // Rollback all uploaded files
                    $this->rollbackUploadedFiles($uploadedFiles);

                    return redirect()->back()
                        ->withErrors([$field => "Gagal upload {$category}: " . $e->getMessage()])
                        ->withInput();
                }
            }
        }

        $data['status'] = $isDraft ? 'draft' : 'pending';

        \Log::info("📝 Saving pendaftar to database...");

        // Create or update pendaftar with error handling
        try {
            if ($request->input('id')) {
                $pendaftar = Pendaftar::findOrFail($request->input('id'));
                $pendaftar->update($data);
                \Log::info("✅ Updated pendaftar: " . $pendaftar->nomor_pendaftaran);
            } else {
                $pendaftar = Pendaftar::create($data);
                \Log::info("✅ Created pendaftar: " . $pendaftar->nomor_pendaftaran);
            }

        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            \Log::error("❌ Duplicate entry error: " . $e->getMessage());

            // Rollback uploaded files
            $this->rollbackUploadedFiles($uploadedFiles);

            // Check which field is duplicate
            if (strpos($e->getMessage(), 'email') !== false) {
                return redirect()->back()
                    ->withErrors(['email' => 'Email sudah terdaftar. Gunakan email lain atau cek status pendaftaran Anda.'])
                    ->withInput();
            } elseif (strpos($e->getMessage(), 'nik') !== false) {
                return redirect()->back()
                    ->withErrors(['nik' => 'NIK sudah terdaftar. Gunakan NIK lain atau cek status pendaftaran Anda.'])
                    ->withInput();
            } else {
                return redirect()->back()
                    ->withErrors(['_error' => 'Data sudah terdaftar sebelumnya. Silakan cek status pendaftaran Anda.'])
                    ->withInput();
            }

        } catch (Exception $e) {
            \Log::error("❌ Failed to save pendaftar: " . $e->getMessage());
            \Log::error("Exception trace: " . $e->getTraceAsString());

            // Rollback uploaded files
            $this->rollbackUploadedFiles($uploadedFiles);

            return redirect()->back()
                ->withErrors(['_error' => 'Gagal menyimpan pendaftaran: ' . $e->getMessage()])
                ->withInput();
        }

        if ($isDraft) {
            \Log::info("📤 Redirecting with draft success");
            return redirect()->back()
                ->with('success', 'Pendaftaran berhasil disimpan sebagai draft. Anda dapat melanjutkan nanti menggunakan email: ' . $pendaftar->email);
        }

        // Final submission - redirect to result page
        \Log::info("📤 Redirecting to result page");
        \Log::info("Pendaftar ID: " . $pendaftar->id);
        \Log::info("Nomor Pendaftaran: " . $pendaftar->nomor_pendaftaran);
        \Log::info("Status: " . $pendaftar->status);

        $resultUrl = route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran]);
        \Log::info("Result URL: " . $resultUrl);

        return redirect($resultUrl)
            ->with('success', 'Pendaftaran berhasil! Nomor pendaftaran Anda: ' . $pendaftar->nomor_pendaftaran)
            ->with('pendaftar_id', $pendaftar->id);
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

    /**
     * Upload bukti pembayaran
     */
    public function uploadPayment(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'metode_pembayaran' => 'required|string',
            'nomor_referensi' => 'nullable|string|max:100',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        // Cek apakah sudah ada pembayaran yang verified
        if ($pendaftar->pembayaranPendaftarans()->where('status', 'verified')->exists()) {
            return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
                ->with('error', 'Pembayaran Anda sudah diverifikasi.');
        }

        // Cek apakah sudah ada pembayaran pending
        if ($pendaftar->pembayaranPendaftarans()->where('status', 'pending')->exists()) {
            return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
                ->with('error', 'Anda sudah upload bukti pembayaran sebelumnya. Mohon tunggu verifikasi admin.');
        }

        try {
            $file = $request->file('bukti_pembayaran');
            $fileName = 'pembayaran_' . $pendaftar->nomor_pendaftaran . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Upload ke Google Drive
            $driveService = new \App\Services\GoogleDriveService();
            $uploadResult = $driveService->uploadPembayaran($file, $pendaftar->nomor_pendaftaran, 'pembayaran_pendaftaran');

            // Simpan ke database
            \App\Models\PembayaranPendaftaran::create([
                'pendaftar_id' => $pendaftar->id,
                'jalur_seleksi_id' => $pendaftar->jalur_seleksi_id,
                'jumlah' => $pendaftar->jalurSeleksi->biaya_pendaftaran ?? 0,
                'metode_pembayaran' => $request->metode_pembayaran,
                'nomor_referensi' => $request->nomor_referensi,
                'bukti_pembayaran' => $uploadResult['webViewLink'],
                'status' => 'pending',
                'keterangan' => 'Upload oleh pendaftar via sistem',
            ]);

            return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
                ->with('success', 'Bukti pembayaran berhasil diupload! Mohon tunggu verifikasi dari admin maksimal 2x24 jam.');

        } catch (\Exception $e) {
            \Log::error('Failed to upload payment proof: ' . $e->getMessage());

            return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
                ->with('error', 'Gagal upload bukti pembayaran. Silakan coba lagi atau hubungi admin.');
        }
    }

    /**
     * Download registration card as PDF
     */
    public function downloadPDF($nomorPendaftaran)
    {
        $pendaftar = Pendaftar::where('nomor_pendaftaran', $nomorPendaftaran)
            ->with(['jurusan', 'jalurSeleksi'])
            ->firstOrFail();

        // Load SPMB settings for PDF
        $spmbPhone = \App\Models\SystemSetting::get('spmb_phone', '021-12345678');
        $spmbEmail = \App\Models\SystemSetting::get('spmb_email', 'info@staialfatih.ac.id');

        // Convert foto to base64 for PDF (DomPDF can't load external images)
        $fotoBase64 = null;
        if ($pendaftar->foto_url) {
            try {
                // Download image from Google Drive
                $imageContent = @file_get_contents($pendaftar->foto_url);
                if ($imageContent) {
                    // Convert to base64
                    $base64 = base64_encode($imageContent);

                    // Detect image type (fallback if fileinfo not available)
                    $mimeType = 'image/jpeg'; // Default

                    if (class_exists('finfo')) {
                        try {
                            $finfo = new \finfo(FILEINFO_MIME_TYPE);
                            $mimeType = $finfo->buffer($imageContent);
                        } catch (\Exception $e) {
                            \Log::warning('finfo detection failed, using default: ' . $e->getMessage());
                        }
                    } else {
                        // Fallback: detect from image content signature
                        $signature = substr($imageContent, 0, 4);
                        if ($signature === "\xFF\xD8\xFF") {
                            $mimeType = 'image/jpeg';
                        } elseif ($signature === "\x89PNG") {
                            $mimeType = 'image/png';
                        } elseif (substr($signature, 0, 3) === 'GIF') {
                            $mimeType = 'image/gif';
                        }
                    }

                    // Create data URI
                    $fotoBase64 = "data:{$mimeType};base64,{$base64}";
                }
            } catch (\Exception $e) {
                \Log::warning('Failed to load photo for PDF: ' . $e->getMessage());
            }
        }

        // Create PDF from view
        $pdf = \PDF::loadView('public.spmb.pdf', compact('pendaftar', 'spmbPhone', 'spmbEmail', 'fotoBase64'));

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Download with filename
        $filename = 'Kartu_Pendaftaran_' . $pendaftar->nomor_pendaftaran . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Submit daftar ulang
     */
    public function submitDaftarUlang(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'metode_pembayaran' => 'required|string',
            'nomor_referensi' => 'nullable|string|max:100',
        ]);

        $pendaftar = Pendaftar::findOrFail($id);

        // Check if status is accepted
        if ($pendaftar->status !== 'accepted') {
            return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
                ->with('error', 'Anda harus diterima terlebih dahulu sebelum bisa daftar ulang.');
        }

        // Check if already submitted
        if ($pendaftar->daftarUlang) {
            return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
                ->with('error', 'Anda sudah submit daftar ulang sebelumnya.');
        }

        try {
            $file = $request->file('bukti_pembayaran');

            // Upload to Google Drive
            $driveService = new \App\Services\GoogleDriveService();
            $uploadResult = $driveService->uploadPembayaran($file, $pendaftar->nomor_pendaftaran, 'daftar_ulang');

            // Get biaya daftar ulang from settings
            $biayaDaftarUlang = \App\Models\SystemSetting::get('biaya_daftar_ulang', 500000);

            // Create daftar ulang record
            \App\Models\DaftarUlang::create([
                'pendaftar_id' => $pendaftar->id,
                'status' => 'pending',
                'nim_sementara' => $pendaftar->nomor_pendaftaran, // Use registration number as temporary NIM
                'biaya_daftar_ulang' => $biayaDaftarUlang,
                'metode_pembayaran' => $request->metode_pembayaran,
                'nomor_referensi' => $request->nomor_referensi,
                'bukti_pembayaran' => $uploadResult['webViewLink'],
            ]);

            return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
                ->with('success', 'Daftar ulang berhasil disubmit! Mohon tunggu verifikasi dari admin maksimal 1x24 jam.');

        } catch (\Exception $e) {
            \Log::error('Failed to submit daftar ulang: ' . $e->getMessage());

            return redirect()->route('public.spmb.result', ['nomor_pendaftaran' => $pendaftar->nomor_pendaftaran])
                ->with('error', 'Gagal submit daftar ulang. Silakan coba lagi atau hubungi admin.');
        }
    }
}
