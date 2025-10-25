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
     * Upload foto pendaftar to Google Drive or local storage
     */
    protected function uploadFotoPendaftar($file, $pendaftarId)
    {
        $result = [
            'foto' => null,
            'google_drive_file_id' => null,
            'google_drive_link' => null,
        ];

        if ($this->driveService) {
            // Upload to Google Drive
            try {
                $driveResult = $this->driveService->uploadDokumenSPMB(
                    $file,
                    $pendaftarId,
                    'Foto'
                );

                $result['google_drive_file_id'] = $driveResult['id'];
                $result['google_drive_link'] = $driveResult['webViewLink'];
                $result['foto'] = $driveResult['webViewLink'];

                \Log::info("Uploaded foto pendaftar to Google Drive: {$driveResult['id']}");
            } catch (Exception $e) {
                \Log::error("Failed to upload to Google Drive: " . $e->getMessage());
                // Fallback to local storage
                $result['foto'] = $file->store('pendaftar/foto', 'public');
            }
        } else {
            // Upload to local storage
            $result['foto'] = $file->store('pendaftar/foto', 'public');
        }

        return $result;
    }

    /**
     * Delete foto pendaftar from Google Drive or local storage
     */
    protected function deleteFotoPendaftar($pendaftar)
    {
        // Delete from Google Drive if file_id exists
        if ($pendaftar->google_drive_file_id && $this->driveService) {
            try {
                $this->driveService->deleteFile($pendaftar->google_drive_file_id);
            } catch (Exception $e) {
                \Log::error("Failed to delete from Google Drive: " . $e->getMessage());
            }
        }

        // Delete from local storage if path exists
        if ($pendaftar->foto && !$pendaftar->google_drive_file_id) {
            Storage::disk('public')->delete($pendaftar->foto);
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

        // Only require photo for final submission
        if (!$isDraft) {
            $rules['foto'] = 'required|image|mimes:jpg,jpeg,png|max:500';
        } else {
            $rules['foto'] = 'nullable|image|mimes:jpg,jpeg,png|max:500';
        }

        $validator = Validator::make($request->all(), $rules, [
            'foto.required' => 'Foto harus diupload sebelum submit pendaftaran.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 500KB.',
            'nik.size' => 'NIK harus 16 digit.',
            'program_studi_pilihan_2.different' => 'Pilihan 2 harus berbeda dengan Pilihan 1.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Handle photo upload
        $uploadResult = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');

            // Validate aspect ratio (4x6 approximately 2:3)
            $dimensions = getimagesize($foto->getPathname());
            $ratio = $dimensions[0] / $dimensions[1];

            // Allow some tolerance for 2:3 ratio (0.66 to 0.68)
            if ($ratio < 0.62 || $ratio > 0.72) {
                return redirect()->back()
                    ->withErrors(['foto' => 'Foto harus memiliki rasio 4x6 (portrait).'])
                    ->withInput();
            }

            // Delete old photo if updating
            if ($request->input('id')) {
                $existingPendaftar = Pendaftar::find($request->input('id'));
                if ($existingPendaftar) {
                    $this->deleteFotoPendaftar($existingPendaftar);
                }
            }

            // Generate temporary ID for new pendaftar (will use nomor_pendaftaran later)
            $pendaftarId = $request->input('id') ?: 'SPMB' . date('Ymd') . rand(1000, 9999);

            // Upload photo
            $uploadResult = $this->uploadFotoPendaftar($foto, $pendaftarId);
        }

        // Prepare data
        $data = $request->except(['foto', 'save_as_draft', 'old_foto', 'id']);
        if ($uploadResult) {
            $data['foto'] = $uploadResult['foto'];
            $data['google_drive_file_id'] = $uploadResult['google_drive_file_id'];
            $data['google_drive_link'] = $uploadResult['google_drive_link'];
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
