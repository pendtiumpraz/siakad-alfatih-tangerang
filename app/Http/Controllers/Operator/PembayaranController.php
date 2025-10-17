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

class PembayaranController extends Controller
{
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

        $semesters = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('periode', 'desc')
            ->get();

        $jenisPembayaran = [
            'spp',
            'uang_kuliah',
            'ujian',
            'praktikum',
            'wisuda',
            'lainnya',
        ];

        return view('operator.pembayaran.index', compact(
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

        $semesters = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('periode', 'desc')
            ->get();

        $jenisPembayaran = [
            'spp' => 'SPP',
            'uang_kuliah' => 'Uang Kuliah',
            'ujian' => 'Ujian',
            'praktikum' => 'Praktikum',
            'wisuda' => 'Wisuda',
            'lainnya' => 'Lainnya',
        ];

        return view('operator.pembayaran.create', compact(
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
                $buktiPath = $request->file('bukti_pembayaran')
                    ->store('pembayaran/bukti', 'public');
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
                'status' => $validated['status'],
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

        $semesters = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('periode', 'desc')
            ->get();

        $jenisPembayaran = [
            'spp' => 'SPP',
            'uang_kuliah' => 'Uang Kuliah',
            'ujian' => 'Ujian',
            'praktikum' => 'Praktikum',
            'wisuda' => 'Wisuda',
            'lainnya' => 'Lainnya',
        ];

        return view('operator.pembayaran.edit', compact(
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

                // Upload new file
                $updateData['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                    ->store('pembayaran/bukti', 'public');
            }

            // Handle remove bukti request
            if ($request->boolean('remove_bukti') && $pembayaran->bukti_pembayaran) {
                Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
                $updateData['bukti_pembayaran'] = null;
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

                // Upload new file
                $updateData['bukti_pembayaran'] = $request->file('bukti_pembayaran')
                    ->store('pembayaran/bukti', 'public');
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
}
