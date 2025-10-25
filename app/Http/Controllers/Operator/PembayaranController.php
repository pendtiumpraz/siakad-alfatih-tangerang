<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\Operator;
use App\Services\GoogleDriveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

class PembayaranController extends Controller
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
     * Get view path prefix based on user role
     */
    protected function getViewPrefix()
    {
        $user = auth()->user();

        if ($user->isSuperAdmin()) {
            return 'admin';
        } elseif ($user->isOperator()) {
            return 'operator';
        }

        return 'operator'; // default
    }

    /**
     * Upload bukti pembayaran to Google Drive or local storage
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param Mahasiswa $mahasiswa
     * @param string $jenisPembayaran
     * @return array ['bukti_path' => path, 'google_drive_file_id' => id, 'google_drive_link' => link]
     */
    protected function uploadBuktiPembayaran($file, $mahasiswa, $jenisPembayaran)
    {
        $result = [
            'bukti_pembayaran' => null,
            'google_drive_file_id' => null,
            'google_drive_link' => null,
        ];

        if ($this->driveService) {
            // Upload to Google Drive
            try {
                $driveResult = $this->driveService->uploadPembayaran(
                    $file,
                    $mahasiswa->nim,
                    $jenisPembayaran
                );

                $result['google_drive_file_id'] = $driveResult['id'];
                $result['google_drive_link'] = $driveResult['webViewLink'];
                $result['bukti_pembayaran'] = $driveResult['webViewLink']; // For backward compatibility

                \Log::info("Uploaded bukti pembayaran to Google Drive: {$driveResult['id']}");
            } catch (Exception $e) {
                \Log::error("Failed to upload to Google Drive: " . $e->getMessage());
                // Fallback to local storage
                $result['bukti_pembayaran'] = $this->uploadToLocalStorage($file, $mahasiswa, $jenisPembayaran);
            }
        } else {
            // Upload to local storage
            $result['bukti_pembayaran'] = $this->uploadToLocalStorage($file, $mahasiswa, $jenisPembayaran);
        }

        return $result;
    }

    /**
     * Upload to local storage (fallback)
     */
    protected function uploadToLocalStorage($file, $mahasiswa, $jenisPembayaran)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = sprintf(
            '%s_%s_%s.%s',
            $mahasiswa->nim,
            $jenisPembayaran,
            date('Ymd_His'),
            $extension
        );

        return $file->storeAs('pembayaran/bukti', $filename, 'public');
    }

    /**
     * Delete bukti pembayaran from Google Drive or local storage
     */
    protected function deleteBuktiPembayaran($pembayaran)
    {
        // Delete from Google Drive if file_id exists
        if ($pembayaran->google_drive_file_id && $this->driveService) {
            try {
                $this->driveService->deleteFile($pembayaran->google_drive_file_id);
            } catch (Exception $e) {
                \Log::error("Failed to delete from Google Drive: " . $e->getMessage());
            }
        }

        // Delete from local storage if path exists
        if ($pembayaran->bukti_pembayaran && !$pembayaran->google_drive_file_id) {
            Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        }
    }

    /**
     * Generate custom filename for bukti pembayaran
     */
    private function generateBuktiFilename($mahasiswa, $semester, $jenisPembayaran, $extension)
    {
        // Sanitize nama: lowercase, remove spaces, keep only alphanumeric
        $nama = Str::slug(strtolower($mahasiswa->nama_lengkap), '');

        // Format: bukti_bayar_{jenis}_{tahun}_{semester}_{nama}_{tanggal}.{ext}
        $filename = sprintf(
            'bukti_bayar_%s_%s_%s_%s_%s.%s',
            $jenisPembayaran,
            $semester->tahun_akademik,
            strtolower($semester->jenis), // ganjil or genap
            $nama,
            date('Ymd'),
            $extension
        );

        return $filename;
    }

    /**
     * Display a listing of payments with filters
     */
    public function index(Request $request)
    {
        $query = Pembayaran::with(['mahasiswa.programStudi', 'semester', 'operator']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by jenis pembayaran
        if ($request->filled('jenis_pembayaran')) {
            $query->where('jenis_pembayaran', $request->jenis_pembayaran);
        }

        // Filter by mahasiswa
        if ($request->filled('mahasiswa_id')) {
            $query->where('mahasiswa_id', $request->mahasiswa_id);
        }

        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        // Search by mahasiswa name or NIM
        if ($request->filled('search')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }

        // Include soft deleted if requested
        if ($request->boolean('include_deleted')) {
            $query->withTrashed();
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        $pembayarans = $query->paginate(20);

        // Get filter options
        $mahasiswas = Mahasiswa::select('id', 'nim', 'nama_lengkap')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $semesters = Semester::orderBy('tahun_akademik', 'desc')
            ->orderBy('jenis', 'asc')
            ->get();

        $jenisPembayaran = [
            'spp',
            'uang_kuliah',
            'ujian',
            'praktikum',
            'wisuda',
            'lainnya',
        ];

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.pembayaran.index", compact(
            'pembayarans',
            'mahasiswas',
            'semesters',
            'jenisPembayaran'
        ));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create()
    {
        $mahasiswas = Mahasiswa::with('programStudi')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $semesters = Semester::orderBy('tahun_akademik', 'desc')
            ->orderBy('jenis', 'asc')
            ->get();

        $jenisPembayaran = [
            'spp' => 'SPP',
            'uang_kuliah' => 'Uang Kuliah',
            'ujian' => 'Ujian',
            'praktikum' => 'Praktikum',
            'wisuda' => 'Wisuda',
            'lainnya' => 'Lainnya',
        ];

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.pembayaran.create", compact(
            'mahasiswas',
            'semesters',
            'jenisPembayaran'
        ));
    }

    /**
     * Store a newly created payment in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mahasiswa_id' => ['required', 'exists:mahasiswas,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'jenis_pembayaran' => ['required', 'in:spp,uang_kuliah,ujian,praktikum,wisuda,lainnya'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'tanggal_jatuh_tempo' => ['required', 'date'],
            'tanggal_bayar' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,lunas,belum_lunas'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'keterangan' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            // Get operator ID from authenticated user
            $operator = auth()->user()->operator;

            // Handle file upload
            $uploadResult = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $mahasiswa = Mahasiswa::findOrFail($validated['mahasiswa_id']);
                $uploadResult = $this->uploadBuktiPembayaran(
                    $request->file('bukti_pembayaran'),
                    $mahasiswa,
                    $validated['jenis_pembayaran']
                );
            }

            // Auto-update status based on file upload
            $status = $validated['status'];
            if ($uploadResult && $uploadResult['bukti_pembayaran'] && $status === 'belum_lunas') {
                $status = 'pending'; // Auto change to pending when file is uploaded
            }

            // Create payment
            $pembayaran = Pembayaran::create([
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'semester_id' => $validated['semester_id'],
                'operator_id' => $operator ? $operator->id : null,
                'jenis_pembayaran' => $validated['jenis_pembayaran'],
                'jumlah' => $validated['jumlah'],
                'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                'tanggal_bayar' => $validated['tanggal_bayar'] ?? null,
                'status' => $status,
                'bukti_pembayaran' => $uploadResult['bukti_pembayaran'] ?? null,
                'google_drive_file_id' => $uploadResult['google_drive_file_id'] ?? null,
                'google_drive_link' => $uploadResult['google_drive_link'] ?? null,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('operator.pembayaran.index')
                ->with('success', 'Payment created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if transaction failed
            if (isset($uploadResult) && $uploadResult) {
                if ($uploadResult['google_drive_file_id'] && $this->driveService) {
                    try {
                        $this->driveService->deleteFile($uploadResult['google_drive_file_id']);
                    } catch (Exception $deleteException) {
                        \Log::error('Failed to delete Google Drive file after rollback: ' . $deleteException->getMessage());
                    }
                } elseif ($uploadResult['bukti_pembayaran']) {
                    Storage::disk('public')->delete($uploadResult['bukti_pembayaran']);
                }
            }

            return back()->withInput()
                ->with('error', 'Failed to create payment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified payment
     */
    public function show($id)
    {
        $pembayaran = Pembayaran::with(['mahasiswa.programStudi', 'semester', 'operator'])
            ->withTrashed()
            ->findOrFail($id);

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.pembayaran.show", compact('pembayaran'));
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit($id)
    {
        $pembayaran = Pembayaran::with(['mahasiswa', 'semester', 'operator'])
            ->withTrashed()
            ->findOrFail($id);

        $mahasiswas = Mahasiswa::with('programStudi')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        $semesters = Semester::orderBy('tahun_akademik', 'desc')
            ->orderBy('jenis', 'asc')
            ->get();

        $jenisPembayaran = [
            'spp' => 'SPP',
            'uang_kuliah' => 'Uang Kuliah',
            'ujian' => 'Ujian',
            'praktikum' => 'Praktikum',
            'wisuda' => 'Wisuda',
            'lainnya' => 'Lainnya',
        ];

        $viewPrefix = $this->getViewPrefix();
        return view("{$viewPrefix}.pembayaran.edit", compact(
            'pembayaran',
            'mahasiswas',
            'semesters',
            'jenisPembayaran'
        ));
    }

    /**
     * Update the specified payment in storage
     */
    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'mahasiswa_id' => ['required', 'exists:mahasiswas,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'jenis_pembayaran' => ['required', 'in:spp,uang_kuliah,ujian,praktikum,wisuda,lainnya'],
            'jumlah' => ['required', 'numeric', 'min:0'],
            'tanggal_jatuh_tempo' => ['required', 'date'],
            'tanggal_bayar' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,lunas,belum_lunas'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'remove_bukti' => ['boolean'],
            'keterangan' => ['nullable', 'string'],
        ]);

        DB::beginTransaction();

        try {
            // Get operator ID from authenticated user
            $operator = auth()->user()->operator;

            $updateData = [
                'mahasiswa_id' => $validated['mahasiswa_id'],
                'semester_id' => $validated['semester_id'],
                'operator_id' => $operator ? $operator->id : $pembayaran->operator_id,
                'jenis_pembayaran' => $validated['jenis_pembayaran'],
                'jumlah' => $validated['jumlah'],
                'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                'tanggal_bayar' => $validated['tanggal_bayar'] ?? null,
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? null,
            ];

            // Handle file upload
            if ($request->hasFile('bukti_pembayaran')) {
                // Delete old file
                $this->deleteBuktiPembayaran($pembayaran);

                // Upload new file
                $mahasiswa = Mahasiswa::findOrFail($validated['mahasiswa_id']);
                $uploadResult = $this->uploadBuktiPembayaran(
                    $request->file('bukti_pembayaran'),
                    $mahasiswa,
                    $validated['jenis_pembayaran']
                );

                $updateData['bukti_pembayaran'] = $uploadResult['bukti_pembayaran'];
                $updateData['google_drive_file_id'] = $uploadResult['google_drive_file_id'];
                $updateData['google_drive_link'] = $uploadResult['google_drive_link'];

                // Auto-update status to pending when file is uploaded and status is belum_lunas
                if ($validated['status'] === 'belum_lunas') {
                    $updateData['status'] = 'pending';
                }
            }

            // Handle remove bukti request
            if ($request->boolean('remove_bukti') && $pembayaran->bukti_pembayaran) {
                $this->deleteBuktiPembayaran($pembayaran);
                $updateData['bukti_pembayaran'] = null;
                $updateData['google_drive_file_id'] = null;
                $updateData['google_drive_link'] = null;

                // Auto-update status to belum_lunas when file is removed and status is pending
                if ($validated['status'] === 'pending') {
                    $updateData['status'] = 'belum_lunas';
                }
            }

            // Update payment
            $pembayaran->update($updateData);

            DB::commit();

            return redirect()->route('operator.pembayaran.index')
                ->with('success', 'Payment updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to update payment: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified payment
     */
    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->delete();

        return redirect()->route('operator.pembayaran.index')
            ->with('success', 'Payment deleted successfully.');
    }

    /**
     * Quick update payment status (e.g., pending to lunas)
     */
    public function updateStatus(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,lunas,belum_lunas'],
            'tanggal_bayar' => ['nullable', 'date'],
            'bukti_pembayaran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        DB::beginTransaction();

        try {
            $updateData = [
                'status' => $validated['status'],
            ];

            // Set tanggal_bayar to now if status is lunas and not already set
            if ($validated['status'] === 'lunas') {
                $updateData['tanggal_bayar'] = $validated['tanggal_bayar'] ?? now();
            }

            // Handle file upload
            if ($request->hasFile('bukti_pembayaran')) {
                // Delete old file
                $this->deleteBuktiPembayaran($pembayaran);

                // Upload new file
                $pembayaran->load('mahasiswa');
                $uploadResult = $this->uploadBuktiPembayaran(
                    $request->file('bukti_pembayaran'),
                    $pembayaran->mahasiswa,
                    $pembayaran->jenis_pembayaran
                );

                $updateData['bukti_pembayaran'] = $uploadResult['bukti_pembayaran'];
                $updateData['google_drive_file_id'] = $uploadResult['google_drive_file_id'];
                $updateData['google_drive_link'] = $uploadResult['google_drive_link'];
            }

            // Update operator
            $operator = auth()->user()->operator;
            if ($operator) {
                $updateData['operator_id'] = $operator->id;
            }

            $pembayaran->update($updateData);

            DB::commit();

            return back()->with('success', 'Payment status updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to update payment status: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment (mark as lunas/verified)
     */
    public function verify(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $pembayaran = Pembayaran::findOrFail($id);

            // Update status to lunas
            $updateData = [
                'status' => 'lunas',
                'tanggal_bayar' => $pembayaran->tanggal_bayar ?? now(),
            ];

            // Update operator who verified
            $operator = auth()->user()->operator;
            if ($operator) {
                $updateData['operator_id'] = $operator->id;
            }

            $pembayaran->update($updateData);

            DB::commit();

            return back()->with('success', 'Pembayaran berhasil diverifikasi sebagai LUNAS.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }
}
