<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Operator;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

        $users = $query->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $programStudis = ProgramStudi::all();

        return view('admin.users.create', compact('programStudis'));
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        $user = User::withTrashed()->with(['mahasiswa.programStudi', 'dosen', 'operator'])->findOrFail($id);

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
                    Dosen::create([
                        'user_id' => $user->id,
                        'nidn' => $validated['nidn'],
                        'nama_lengkap' => $validated['dosen_nama_lengkap'],
                        'gelar_depan' => $validated['gelar_depan'] ?? null,
                        'gelar_belakang' => $validated['gelar_belakang'] ?? null,
                        'email_dosen' => $validated['email'] ?? null,
                        'no_telepon' => $validated['no_telepon'] ?? null,
                    ]);
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
        $user = User::with(['mahasiswa', 'dosen', 'operator'])
            ->withTrashed()
            ->findOrFail($id);

        $programStudis = ProgramStudi::all();

        return view('admin.users.edit', compact('user', 'programStudis'));
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
                    $user->dosen()->updateOrCreate(
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
}
