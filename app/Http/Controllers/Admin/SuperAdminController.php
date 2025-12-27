<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Operator;
use App\Models\ProgramStudi;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
// Temporary: Excel package not fully installed - using CSV instead
// use Maatwebsite\Excel\Facades\Excel;
// use App\Exports\MahasiswaTemplateExport;
// use App\Exports\DosenTemplateExport;
// use App\Exports\ProgramStudiExport;
// use App\Exports\MataKuliahExport;
// use App\Imports\MahasiswaImport;
// use App\Imports\DosenImport;

class SuperAdminController extends Controller
{
    /**
     * Display a listing of users with pagination and filters
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Alumni filter (show inactive users - lulus/dropout mahasiswa)
        if ($request->boolean('show_alumni')) {
            // Show only inactive users (alumni)
            $query->where('is_active', false);
        } else {
            // Default: show only active users
            $query->where('is_active', true);
        }

        // Search by username or email
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Include soft deleted users if requested
        if ($request->boolean('include_deleted')) {
            $query->withTrashed();
        }

        // Load related models
        $query->with(['mahasiswa', 'dosen', 'operator']);

        // Order by latest
        $query->orderBy('created_at', 'desc');

        $users = $query->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Eager load kurikulums and mataKuliahs for each program studi
        $programStudis = ProgramStudi::with(['kurikulums.mataKuliahs' => function($query) {
            $query->orderBy('semester')->orderBy('kode_mk');
        }])->get();
        
        // Load ALL mata kuliah grouped by program studi for dynamic filtering
        $mataKuliahsByProdi = [];
        foreach ($programStudis as $prodi) {
            $kurikulum = $prodi->kurikulums->first();
            if ($kurikulum && $kurikulum->mataKuliahs) {
                $mataKuliahsByProdi[$prodi->id] = $kurikulum->mataKuliahs;
            } else {
                $mataKuliahsByProdi[$prodi->id] = collect();
            }
        }

        return view('admin.users.create', compact('programStudis', 'mataKuliahsByProdi'));
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::withTrashed()->with(['mahasiswa.programStudi', 'dosen', 'operator'])->findOrFail($id);
        
        // Try to load programStudis for dosen if available
        if ($user->dosen) {
            try {
                if (\Schema::hasTable('dosen_program_studi') && method_exists($user->dosen, 'programStudis')) {
                    $user->dosen->load('programStudis');
                }
            } catch (\Throwable $e) {
                \Log::warning('Could not load programStudis in show: ' . $e->getMessage());
            }
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:super_admin,operator,dosen,mahasiswa'],
            'is_active' => ['boolean'],

            // Mahasiswa fields
            'nim' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:255', 'unique:mahasiswas,nim'],
            'program_studi_id' => ['required_if:role,mahasiswa', 'nullable', 'exists:program_studis,id'],
            'mahasiswa_nama_lengkap' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required_if:role,mahasiswa', 'nullable', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'angkatan' => ['required_if:role,mahasiswa', 'nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'semester_aktif' => ['nullable', 'integer', 'min:1', 'max:14'],
            'mahasiswa_status' => ['nullable', 'in:aktif,cuti,lulus,dropout'],
            'tanggal_lulus' => ['nullable', 'date', 'required_if:mahasiswa_status,lulus'],
            'tanggal_dropout' => ['nullable', 'date', 'required_if:mahasiswa_status,dropout'],

            // Dosen fields
            'nidn' => ['required_if:role,dosen', 'nullable', 'string', 'max:255', 'unique:dosens,nidn'],
            'dosen_nama_lengkap' => ['required_if:role,dosen', 'nullable', 'string', 'max:255'],
            'gelar_depan' => ['nullable', 'string', 'max:50'],
            'gelar_belakang' => ['nullable', 'string', 'max:50'],
            'dosen_status' => ['nullable', 'in:aktif,non-aktif'],
            // Program studi and mata kuliah are now auto-synced from jadwal, no manual input needed

            // Operator fields
            'operator_nama_lengkap' => ['required_if:role,operator', 'nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            // Create user
            $userCreateData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ];

            // Only set is_active for non-mahasiswa roles
            // Mahasiswa is_active will be auto-managed by status akademik
            if ($validated['role'] !== 'mahasiswa') {
                $userCreateData['is_active'] = $validated['is_active'] ?? true;
            } else {
                // For mahasiswa, set initial is_active based on status
                $userCreateData['is_active'] = !in_array($validated['mahasiswa_status'] ?? 'aktif', ['lulus', 'dropout']);
            }

            $user = User::create($userCreateData);

            // Create related record based on role
            switch ($validated['role']) {
                case 'mahasiswa':
                    $mahasiswaData = [
                        'user_id' => $user->id,
                        'program_studi_id' => $validated['program_studi_id'],
                        'nim' => $validated['nim'],
                        'nama_lengkap' => $validated['mahasiswa_nama_lengkap'],
                        'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                        'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                        'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                        'alamat' => $validated['alamat'] ?? null,
                        'no_telepon' => $validated['no_telepon'] ?? null,
                        'angkatan' => $validated['angkatan'] ?? date('Y'),
                        // semester_aktif akan auto-calculated by Model
                        'status' => $validated['mahasiswa_status'] ?? 'aktif',
                    ];

                    // Add manual dates if provided
                    if (isset($validated['tanggal_lulus'])) {
                        $mahasiswaData['tanggal_lulus'] = $validated['tanggal_lulus'];
                    }
                    if (isset($validated['tanggal_dropout'])) {
                        $mahasiswaData['tanggal_dropout'] = $validated['tanggal_dropout'];
                    }

                    Mahasiswa::create($mahasiswaData);
                    break;

                case 'dosen':
                    $dosen = Dosen::create([
                        'user_id' => $user->id,
                        'nidn' => $validated['nidn'],
                        'nama_lengkap' => $validated['dosen_nama_lengkap'],
                        'gelar_depan' => $validated['gelar_depan'] ?? null,
                        'gelar_belakang' => $validated['gelar_belakang'] ?? null,
                        'email_dosen' => $validated['email'] ?? null,
                        'no_telepon' => $validated['no_telepon'] ?? null,
                    ]);
                    
                    // Program studi and mata kuliah are now auto-synced from jadwal
                    // No manual assignment needed - they are read-only and derived from jadwal data
                    \Log::info("Dosen created: {$dosen->id}. Program studi & mata kuliah auto-managed from jadwal.");
                    break;

                case 'operator':
                    Operator::create([
                        'user_id' => $user->id,
                        'nama_lengkap' => $validated['operator_nama_lengkap'],
                        'no_telepon' => $validated['no_telepon'] ?? null,
                    ]);
                    break;
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        try {
            // Load user first without any dosen relations
            $user = User::with(['mahasiswa', 'operator'])
                ->withTrashed()
                ->findOrFail($id);
            
            // Load dosen separately and get data from jadwal
            if ($user->role === 'dosen') {
                try {
                    $user->load('dosen');
                    
                    if ($user->dosen) {
                        // Get program studi and mata kuliah from jadwal instead of pivot tables
                        $programStudisFromJadwal = $user->dosen->getProgramStudisFromJadwal();
                        $mataKuliahsFromJadwal = $user->dosen->getMataKuliahsFromJadwal();
                        
                        // Set as relation for view compatibility
                        $user->dosen->setRelation('programStudis', $programStudisFromJadwal);
                        $user->dosen->setRelation('mataKuliahs', $mataKuliahsFromJadwal);
                        
                        \Log::info("Loaded from jadwal - Prodi: {$programStudisFromJadwal->count()}, MK: {$mataKuliahsFromJadwal->count()}");
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Could not load dosen: ' . $e->getMessage());
                }
            }

            // Eager load kurikulums and mataKuliahs for each program studi
            $programStudis = ProgramStudi::with(['kurikulums.mataKuliahs' => function($query) {
                $query->orderBy('semester')->orderBy('kode_mk');
            }])->get();
            
            // Load ALL mata kuliah grouped by program studi for dynamic filtering
            $mataKuliahsByProdi = [];
            foreach ($programStudis as $prodi) {
                $kurikulum = $prodi->kurikulums->first();
                if ($kurikulum && $kurikulum->mataKuliahs) {
                    $mataKuliahsByProdi[$prodi->id] = $kurikulum->mataKuliahs;
                } else {
                    $mataKuliahsByProdi[$prodi->id] = collect();
                }
            }
            
            return view('admin.users.edit', compact('user', 'programStudis', 'mataKuliahsByProdi'));
            
        } catch (\Throwable $e) {
            // Catch all errors including fatal
            \Log::error('Error in user edit: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return redirect()->route('admin.users.index')
                ->with('error', 'Terjadi kesalahan saat membuka halaman edit. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:super_admin,operator,dosen,mahasiswa'],
            'is_active' => ['boolean'],
            'phone' => ['nullable', 'string', 'max:20'], // Direct to User model for helpdesk

            // Mahasiswa fields
            'nim' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:255', Rule::unique('mahasiswas')->ignore($user->mahasiswa->id ?? null)],
            'program_studi_id' => ['required_if:role,mahasiswa', 'nullable', 'exists:program_studis,id'],
            'mahasiswa_nama_lengkap' => ['required_if:role,mahasiswa', 'nullable', 'string', 'max:255'],
            'tempat_lahir' => ['nullable', 'string', 'max:255'],
            'tanggal_lahir' => ['nullable', 'date'],
            'jenis_kelamin' => ['required_if:role,mahasiswa', 'nullable', 'in:L,P'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'angkatan' => ['required_if:role,mahasiswa', 'nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'semester_aktif' => ['nullable', 'integer', 'min:1', 'max:14'],
            'mahasiswa_status' => ['nullable', 'in:aktif,cuti,lulus,dropout'],
            'tanggal_lulus' => ['nullable', 'date', 'required_if:mahasiswa_status,lulus'],
            'tanggal_dropout' => ['nullable', 'date', 'required_if:mahasiswa_status,dropout'],

            // Dosen fields
            'nidn' => ['required_if:role,dosen', 'nullable', 'string', 'max:255', Rule::unique('dosens')->ignore($user->dosen->id ?? null)],
            'dosen_nama_lengkap' => ['required_if:role,dosen', 'nullable', 'string', 'max:255'],
            'gelar_depan' => ['nullable', 'string', 'max:50'],
            'gelar_belakang' => ['nullable', 'string', 'max:50'],
            'dosen_status' => ['nullable', 'in:aktif,non-aktif'],
            // Program studi and mata kuliah are now auto-synced from jadwal, no manual input needed

            // Operator fields
            'operator_nama_lengkap' => ['required_if:role,operator', 'nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();

        try {
            // Update user basic info
            $userUpdateData = [
                'username' => $validated['username'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['phone'] ?? null, // Save phone directly to User
            ];

            // Only update is_active for non-mahasiswa roles
            // Mahasiswa is_active is auto-managed by status akademik
            if ($validated['role'] !== 'mahasiswa') {
                $userUpdateData['is_active'] = $validated['is_active'] ?? true;
            }

            $user->update($userUpdateData);

            // Update password if provided
            if (!empty($validated['password'])) {
                $user->update(['password' => Hash::make($validated['password'])]);
            }

            // Update or create related record based on role
            switch ($validated['role']) {
                case 'mahasiswa':
                    $mahasiswaData = [
                        'program_studi_id' => $validated['program_studi_id'],
                        'nim' => $validated['nim'],
                        'nama_lengkap' => $validated['mahasiswa_nama_lengkap'],
                        'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                        'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                        'jenis_kelamin' => $validated['jenis_kelamin'] ?? null,
                        'alamat' => $validated['alamat'] ?? null,
                        'no_telepon' => $validated['no_telepon'] ?? null,
                        'angkatan' => $validated['angkatan'] ?? date('Y'),
                        // semester_aktif akan auto-calculated by Model
                        'status' => $validated['mahasiswa_status'] ?? 'aktif',
                    ];

                    // Add manual dates if provided
                    if (isset($validated['tanggal_lulus'])) {
                        $mahasiswaData['tanggal_lulus'] = $validated['tanggal_lulus'];
                    }
                    if (isset($validated['tanggal_dropout'])) {
                        $mahasiswaData['tanggal_dropout'] = $validated['tanggal_dropout'];
                    }

                    $user->mahasiswa()->updateOrCreate(
                        ['user_id' => $user->id],
                        $mahasiswaData
                    );
                    break;

                case 'dosen':
                    $dosen = $user->dosen()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'nidn' => $validated['nidn'],
                            'nama_lengkap' => $validated['dosen_nama_lengkap'],
                            'gelar_depan' => $validated['gelar_depan'] ?? null,
                            'gelar_belakang' => $validated['gelar_belakang'] ?? null,
                            'email_dosen' => $validated['email'] ?? null,
                            'no_telepon' => $validated['no_telepon'] ?? null,
                        ]
                    );
                    
                    // Program studi and mata kuliah are now auto-synced from jadwal
                    // No manual sync needed - they are read-only and derived from jadwal data
                    \Log::info("Dosen updated: {$dosen->id}. Program studi & mata kuliah auto-managed from jadwal.");
                    break;

                case 'operator':
                    $user->operator()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'nama_lengkap' => $validated['operator_nama_lengkap'],
                            'no_telepon' => $validated['no_telepon'] ?? null,
                        ]
                    );
                    break;
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Restore a soft deleted user
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $user->restore();

        return redirect()->route('admin.users.index')
            ->with('success', 'User restored successfully.');
    }

    /**
     * Permanently delete a user (force delete)
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        // Delete related records if needed
        if ($user->mahasiswa) {
            $user->mahasiswa->forceDelete();
        }
        if ($user->dosen) {
            $user->dosen->forceDelete();
        }
        if ($user->operator) {
            $user->operator->forceDelete();
        }

        $user->forceDelete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User permanently deleted.');
    }

    /**
     * Show system settings page
     */
    public function settings()
    {
        // Get all settings grouped by category
        $spmbSettings = SystemSetting::where('group', 'spmb')->get();
        $paymentSettings = SystemSetting::where('group', 'payment')->get();
        $pricingSettings = SystemSetting::where('group', 'pricing')->get();
        $generalSettings = SystemSetting::where('group', 'general')->get();

        return view('admin.settings.index', compact(
            'spmbSettings',
            'paymentSettings',
            'pricingSettings',
            'generalSettings'
        ));
    }

    /**
     * Update system settings
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            // SPMB Settings
            'spmb_email' => 'required|email',
            'spmb_phone' => 'required|string|max:20',
            'spmb_whatsapp' => 'required|string|max:20',

            // Payment Settings
            'bank_name' => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name' => 'required|string|max:255',

            // Pricing Settings
            'biaya_uang_gedung' => 'required|numeric|min:0',
            'biaya_spp_semester' => 'required|numeric|min:0',
            'biaya_wisuda' => 'required|numeric|min:0',
            'biaya_daftar_ulang' => 'required|numeric|min:0',

            // General Settings
            'institution_name' => 'required|string|max:255',
            'institution_address' => 'nullable|string',
        ]);

        // Define settings metadata
        $settingsMetadata = [
            'spmb_email' => ['group' => 'spmb', 'type' => 'text', 'description' => 'Email kontak untuk pendaftaran mahasiswa baru'],
            'spmb_phone' => ['group' => 'spmb', 'type' => 'text', 'description' => 'Nomor telepon kontak SPMB'],
            'spmb_whatsapp' => ['group' => 'spmb', 'type' => 'text', 'description' => 'Nomor WhatsApp untuk informasi SPMB'],
            'bank_name' => ['group' => 'payment', 'type' => 'text', 'description' => 'Nama bank untuk pembayaran'],
            'bank_account_number' => ['group' => 'payment', 'type' => 'text', 'description' => 'Nomor rekening bank'],
            'bank_account_name' => ['group' => 'payment', 'type' => 'text', 'description' => 'Nama pemilik rekening'],
            'biaya_uang_gedung' => ['group' => 'pricing', 'type' => 'number', 'description' => 'Biaya uang gedung default'],
            'biaya_spp_semester' => ['group' => 'pricing', 'type' => 'number', 'description' => 'Biaya SPP per semester default'],
            'biaya_wisuda' => ['group' => 'pricing', 'type' => 'number', 'description' => 'Biaya wisuda'],
            'biaya_daftar_ulang' => ['group' => 'pricing', 'type' => 'number', 'description' => 'Biaya daftar ulang setelah diterima'],
            'institution_name' => ['group' => 'general', 'type' => 'text', 'description' => 'Nama institusi'],
            'institution_address' => ['group' => 'general', 'type' => 'text', 'description' => 'Alamat institusi'],
        ];

        try {
            DB::beginTransaction();

            // Update or create all settings
            foreach ($validated as $key => $value) {
                $metadata = $settingsMetadata[$key] ?? ['group' => 'general', 'type' => 'text', 'description' => ''];
                
                SystemSetting::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $value ?? '',
                        'group' => $metadata['group'],
                        'type' => $metadata['type'],
                        'description' => $metadata['description'],
                    ]
                );
            }

            // Clear settings cache
            SystemSetting::clearCache();

            DB::commit();

            return redirect()->route('admin.settings.index')
                ->with('success', 'Pengaturan berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel untuk import mahasiswa
     */
    public function downloadMahasiswaTemplate()
    {
        // Temporary: Generate CSV template
        $csv = "username,email,no_telepon,nim,nama_lengkap,kode_prodi,angkatan,tempat_lahir,tanggal_lahir,jenis_kelamin,alamat,status,tanggal_lulus,tanggal_dropout\n";
        
        // Sample data
        $samples = [
            ['2022001', '2022001@staialfatih.ac.id', '', '2022001', 'Ahmad Zaki Mubarak', 'PAI-S1-L', '2022', '', '', 'L', '', 'aktif', '', ''],
            ['2022002', '2022002@staialfatih.ac.id', '', '2022002', 'Siti Aisyah Nurjanah', 'PAI-S1-L', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022003', '2022003@staialfatih.ac.id', '', '2022003', 'Muhammad Iqbal Ramadhan', 'PAI-S1-L', '2022', '', '', 'L', '', 'aktif', '', ''],
            ['2022004', '2022004@staialfatih.ac.id', '', '2022004', 'Fatimah Az-Zahra', 'MPI-S1-L', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022005', '2022005@staialfatih.ac.id', '', '2022005', 'Umar Abdullah Faruq', 'MPI-S1-L', '2022', '', '', 'L', '', 'aktif', '', ''],
            ['2022006', '2022006@staialfatih.ac.id', '', '2022006', 'Khadijah Husna', 'PGMI-S1-D', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022007', '2022007@staialfatih.ac.id', '', '2022007', 'Ali Hasan Maulana', 'PGMI-S1-D', '2022', '', '', 'L', '', 'aktif', '', ''],
            ['2022008', '2022008@staialfatih.ac.id', '', '2022008', 'Mariam Salma Amalia', 'HES-S1-L', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022009', '2022009@staialfatih.ac.id', '', '2022009', 'Hamzah Yusuf Habibi', 'HES-S1-L', '2022', '', '', 'L', '', 'aktif', '', ''],
            ['2022010', '2022010@staialfatih.ac.id', '', '2022010', 'Zaynab Nadia Putri', 'HES-S1-L', '2022', '', '', 'P', '', 'aktif', '', ''],
            ['2022011', '2022011@staialfatih.ac.id', '', '2022011', 'Ibrahim Malik Firdaus', 'PAI-S1-L', '2022', '', '', 'L', '', 'cuti', '', ''],
            ['2020001', '2020001@staialfatih.ac.id', '', '2020001', 'Hasan Abdullah Alumni', 'PAI-S1-L', '2020', '', '', 'L', '', 'lulus', '2024-08-15', ''],
            ['2021001', '2021001@staialfatih.ac.id', '', '2021001', 'Ahmad Zainuddin', 'MPI-S1-L', '2021', '', '', 'L', '', 'dropout', '', '2023-12-01'],
        ];
        
        foreach ($samples as $row) {
            $csv .= '"' . implode('","', $row) . '"' . "\n";
        }
        
        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="template_import_mahasiswa.csv"');
    }

    /**
     * Download template Excel untuk import dosen
     */
    public function downloadDosenTemplate()
    {
        // Generate CSV template without prodi and mata kuliah columns
        // Program studi and mata kuliah are now auto-managed from jadwal
        $csv = "username,email,no_telepon,nidn,nama_lengkap,gelar_depan,gelar_belakang\n";
        
        // Sample data
        $samples = [
            ['0101018901', '0101018901@staialfatih.ac.id', '081234567890', '0101018901', 'Ahmad Fauzi', 'Dr.', 'M.Pd.I'],
            ['0202019002', '0202019002@staialfatih.ac.id', '081234567891', '0202019002', 'Siti Nurhaliza', 'Dr.', 'M.A'],
            ['0303018703', '0303018703@staialfatih.ac.id', '081234567892', '0303018703', 'Muhammad Yusuf', 'Prof. Dr.', 'M.A'],
            ['0404019104', '0404019104@staialfatih.ac.id', '', '0404019104', 'Abdullah Salim', '', 'S.Pd.I, M.Pd'],
            ['0505019205', '0505019205@staialfatih.ac.id', '', '0505019205', 'Khadijah Azzahra', '', 'S.Ag, M.Pd.I'],
            ['0808019008', '0808019008@staialfatih.ac.id', '', '0808019008', 'Hamzah Ibrahim', '', 'S.Pd, M.Pd'],
            ['0909019409', '0909019409@staialfatih.ac.id', '', '0909019409', 'Aisyah Zahra', '', 'S.Pd.I, M.Pd'],
            ['1010018910', '1010018910@staialfatih.ac.id', '', '1010018910', 'Bilal Mustafa', '', 'S.H.I, M.E.Sy'],
            ['1111019211', '1111019211@staialfatih.ac.id', '', '1111019211', 'Maryam Safiya', '', 'S.Ag, M.E.I'],
            ['1212019512', '1212019512@staialfatih.ac.id', '', '1212019512', 'Zaid Hakim', '', 'S.Pd.I, M.Pd'],
            ['1313019613', '1313019613@staialfatih.ac.id', '', '1313019613', 'Naima Latifah', '', 'S.Th.I, M.A'],
        ];
        
        foreach ($samples as $row) {
            $csv .= '"' . implode('","', $row) . '"' . "\n";
        }
        
        return response($csv, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="template_import_dosen.csv"');
    }

    /**
     * Download master data Program Studi (untuk rujukan import)
     */
    public function downloadMasterDataProdi()
    {
        // Temporary: Excel package not fully installed
        // TODO: Run composer install fully to enable this feature
        $programStudis = \App\Models\ProgramStudi::where('is_active', true)->get();
        
        $csv = "Kode Prodi,Nama Program Studi,Jenjang,Akreditasi,Status\n";
        foreach ($programStudis as $ps) {
            $csv .= sprintf('"%s","%s","%s","%s","%s"' . "\n",
                $ps->kode_prodi,
                $ps->nama_prodi,
                $ps->jenjang,
                $ps->akreditasi ?? '-',
                $ps->is_active ? 'Aktif' : 'Non-Aktif'
            );
        }
        
        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="master_data_program_studi.csv"');
    }

    /**
     * Download master data Mata Kuliah (untuk rujukan import)
     */
    public function downloadMasterDataMataKuliah(Request $request)
    {
        // Temporary: Excel package not fully installed
        // TODO: Run composer install fully to enable this feature
        $programStudiId = $request->get('program_studi_id');
        
        $query = \App\Models\MataKuliah::with('kurikulum.programStudi');
        if ($programStudiId) {
            $query->whereHas('kurikulum', function ($q) use ($programStudiId) {
                $q->where('program_studi_id', $programStudiId);
            });
        }
        $mataKuliahs = $query->orderBy('kode_mk')->get();
        
        $csv = "Kode Mata Kuliah,Nama Mata Kuliah,Program Studi,SKS,Semester,Jenis\n";
        foreach ($mataKuliahs as $mk) {
            $csv .= sprintf('"%s","%s","%s","%s","%s","%s"' . "\n",
                $mk->kode_mk,
                $mk->nama_mk,
                $mk->kurikulum->programStudi->kode_prodi ?? '-',
                $mk->sks,
                $mk->semester,
                $mk->jenis
            );
        }
        
        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="master_data_mata_kuliah.csv"');
    }

    /**
     * Import mahasiswa dari file CSV
     */
    public function importMahasiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'File CSV wajib diupload',
            'file.mimes' => 'File harus berformat CSV (.csv)',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $csv = array_map('str_getcsv', file($path));
            
            // Remove header row
            $header = array_shift($csv);
            
            $successCount = 0;
            $skipCount = 0;
            $errors = [];
            
            foreach ($csv as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2; // +2 because header is row 1
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                try {
                    // Map CSV columns to data array
                    $data = [
                        'username' => $row[0] ?? '',
                        'email' => $row[1] ?? '',
                        'no_telepon' => $row[2] ?? null,
                        'nim' => $row[3] ?? '',
                        'nama_lengkap' => $row[4] ?? '',
                        'kode_prodi' => $row[5] ?? '',
                        'angkatan' => $row[6] ?? '',
                        'tempat_lahir' => $row[7] ?? null,
                        'tanggal_lahir' => $row[8] ?? null,
                        'jenis_kelamin' => $row[9] ?? '',
                        'alamat' => $row[10] ?? null,
                        'status' => $row[11] ?? 'aktif',
                        'tanggal_lulus' => $row[12] ?? null,
                        'tanggal_dropout' => $row[13] ?? null,
                    ];
                    
                    // Validate required fields
                    if (empty($data['username']) || empty($data['nim']) || empty($data['nama_lengkap']) || empty($data['kode_prodi'])) {
                        $errors[] = "Baris {$rowNumber}: Field wajib tidak lengkap";
                        $skipCount++;
                        continue;
                    }
                    
                    // Check if user already exists
                    if (\App\Models\User::where('username', $data['username'])->exists()) {
                        $skipCount++;
                        continue;
                    }
                    
                    // Find program studi
                    $programStudi = \App\Models\ProgramStudi::where('kode_prodi', $data['kode_prodi'])->first();
                    if (!$programStudi) {
                        $errors[] = "Baris {$rowNumber}: Program studi '{$data['kode_prodi']}' tidak ditemukan";
                        $skipCount++;
                        continue;
                    }
                    
                    // Create user
                    $user = \App\Models\User::create([
                        'username' => $data['username'],
                        'email' => $data['email'] ?: $data['username'] . '@staialfatih.ac.id',
                        'password' => Hash::make('mahasiswa_staialfatih'),
                        'role' => 'mahasiswa',
                    ]);
                    
                    // Create mahasiswa profile
                    \App\Models\Mahasiswa::create([
                        'user_id' => $user->id,
                        'nim' => $data['nim'],
                        'nama_lengkap' => $data['nama_lengkap'],
                        'program_studi_id' => $programStudi->id,
                        'angkatan' => $data['angkatan'],
                        'tempat_lahir' => $data['tempat_lahir'],
                        'tanggal_lahir' => $data['tanggal_lahir'] ?: null,
                        'jenis_kelamin' => $data['jenis_kelamin'],
                        'alamat' => $data['alamat'],
                        'no_telepon' => $data['no_telepon'],
                        'status' => $data['status'],
                        'tanggal_lulus' => $data['tanggal_lulus'] ?: null,
                        'tanggal_dropout' => $data['tanggal_dropout'] ?: null,
                    ]);
                    
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    $skipCount++;
                }
            }
            
            $message = "Import selesai! Berhasil: {$successCount}, Dilewati: {$skipCount}";
            
            if (!empty($errors)) {
                $errorMessage = implode('<br>', array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $errorMessage .= '<br>... dan ' . (count($errors) - 10) . ' error lainnya';
                }
                session()->flash('import_errors', $errorMessage);
            }
            
            if ($successCount > 0) {
                return redirect()->route('admin.users.index')->with('success', $message);
            } else {
                return redirect()->route('admin.users.index')->with('error', 'Tidak ada data yang berhasil diimport.');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal import mahasiswa: ' . $e->getMessage());
        }
    }

    /**
     * Import dosen dari file CSV
     * Program studi and mata kuliah are now auto-managed from jadwal, no longer imported
     */
    public function importDosen(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
        ], [
            'file.required' => 'File CSV wajib diupload',
            'file.mimes' => 'File harus berformat CSV (.csv)',
            'file.max' => 'Ukuran file maksimal 5MB',
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();
            $csv = array_map('str_getcsv', file($path));
            
            // Remove header row
            $header = array_shift($csv);
            
            $successCount = 0;
            $skipCount = 0;
            $errors = [];
            
            foreach ($csv as $rowIndex => $row) {
                $rowNumber = $rowIndex + 2;
                
                // Skip empty rows
                if (empty(array_filter($row))) {
                    continue;
                }
                
                try {
                    // Map CSV columns and TRIM all values to remove whitespace
                    // No longer include kode_prodi and kode_mk
                    $data = [
                        'username' => trim($row[0] ?? ''),
                        'email' => trim($row[1] ?? ''),
                        'no_telepon' => !empty(trim($row[2] ?? '')) ? trim($row[2]) : null,
                        'nidn' => trim($row[3] ?? ''),
                        'nama_lengkap' => trim($row[4] ?? ''),
                        'gelar_depan' => !empty(trim($row[5] ?? '')) ? trim($row[5]) : null,
                        'gelar_belakang' => !empty(trim($row[6] ?? '')) ? trim($row[6]) : null,
                    ];
                    
                    // Validate required fields (kode_prodi no longer required)
                    if (empty($data['username']) || empty($data['nidn']) || empty($data['nama_lengkap'])) {
                        $errors[] = "Baris {$rowNumber}: Field wajib tidak lengkap (username={$data['username']}, nidn={$data['nidn']}, nama={$data['nama_lengkap']})";
                        $skipCount++;
                        continue;
                    }
                    
                    // Check if user already exists
                    if (\App\Models\User::where('username', $data['username'])->exists()) {
                        $errors[] = "Baris {$rowNumber}: Username '{$data['username']}' sudah ada, dilewati";
                        $skipCount++;
                        continue;
                    }
                    
                    // Create user
                    $user = \App\Models\User::create([
                        'username' => $data['username'],
                        'email' => $data['email'] ?: $data['username'] . '@staialfatih.ac.id',
                        'password' => Hash::make('dosen_staialfatih'),
                        'role' => 'dosen',
                    ]);
                    
                    // Create dosen profile without program_studi_id
                    $dosen = \App\Models\Dosen::create([
                        'user_id' => $user->id,
                        'nidn' => $data['nidn'],
                        'nama_lengkap' => $data['nama_lengkap'],
                        'gelar_depan' => $data['gelar_depan'],
                        'gelar_belakang' => $data['gelar_belakang'],
                        'no_telepon' => $data['no_telepon'],
                    ]);
                    
                    // Program studi and mata kuliah will be auto-populated from jadwal
                    \Log::info("Dosen imported: {$data['nama_lengkap']} (NIDN: {$data['nidn']}). Program studi & mata kuliah will be auto-managed from jadwal.");
                    
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = "Baris {$rowNumber}: " . $e->getMessage();
                    $skipCount++;
                }
            }
            
            $message = "Import selesai! Berhasil: {$successCount}, Dilewati: {$skipCount}";
            
            if (!empty($errors)) {
                $errorMessage = implode('<br>', array_slice($errors, 0, 10));
                if (count($errors) > 10) {
                    $errorMessage .= '<br>... dan ' . (count($errors) - 10) . ' error lainnya';
                }
                session()->flash('import_errors', $errorMessage);
            }
            
            if ($successCount > 0) {
                return redirect()->route('admin.users.index')->with('success', $message);
            } else {
                return redirect()->route('admin.users.index')->with('error', 'Tidak ada data yang berhasil diimport.');
            }
            
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Gagal import dosen: ' . $e->getMessage());
        }
    }
}
