<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Mahasiswa;
use App\Models\Semester;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
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
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                // Load relations for filename generation
                $mahasiswa = Mahasiswa::findOrFail($validated['mahasiswa_id']);
                $semester = Semester::findOrFail($validated['semester_id']);

                // Get file extension
                $extension = $request->file('bukti_pembayaran')->getClientOriginalExtension();

                // Generate custom filename
                $filename = $this->generateBuktiFilename(
                    $mahasiswa,
                    $semester,
                    $validated['jenis_pembayaran'],
                    $extension
                );

                // Store with custom filename
                $buktiPath = $request->file('bukti_pembayaran')
                    ->storeAs('pembayaran/bukti', $filename, 'public');
            }

            // Auto-update status based on file upload
            $status = $validated['status'];
            if ($buktiPath && $status === 'belum_lunas') {
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
                'bukti_pembayaran' => $buktiPath,
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('operator.pembayaran.index')
                ->with('success', 'Payment created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded file if transaction failed
            if (isset($buktiPath) && $buktiPath) {
                Storage::disk('public')->delete($buktiPath);
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
                if ($pembayaran->bukti_pembayaran) {
                    Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                }

                // Load relations for filename generation
                $mahasiswa = Mahasiswa::findOrFail($validated['mahasiswa_id']);
                $semester = Semester::findOrFail($validated['semester_id']);

                // Get file extension
                $extension = $request->file('bukti_pembayaran')->getClientOriginalExtension();

                // Generate custom filename
                $filename = $this->generateBuktiFilename(
                    $mahasiswa,
                    $semester,
                    $validated['jenis_pembayaran'],
                    $extension
                );

                // Store with custom filename
                $updateData['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                    ->storeAs('pembayaran/bukti', $filename, 'public');

                // Auto-update status to pending when file is uploaded and status is belum_lunas
                if ($validated['status'] === 'belum_lunas') {
                    $updateData['status'] = 'pending';
                }
            }

            // Handle remove bukti request
            if ($request->boolean('remove_bukti') && $pembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                $updateData['bukti_pembayaran'] = null;

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
                if ($pembayaran->bukti_pembayaran) {
                    Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                }

                // Load relations for filename generation
                $pembayaran->load(['mahasiswa', 'semester']);

                // Get file extension
                $extension = $request->file('bukti_pembayaran')->getClientOriginalExtension();

                // Generate custom filename
                $filename = $this->generateBuktiFilename(
                    $pembayaran->mahasiswa,
                    $pembayaran->semester,
                    $pembayaran->jenis_pembayaran,
                    $extension
                );

                // Store with custom filename
                $updateData['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                    ->storeAs('pembayaran/bukti', $filename, 'public');
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
