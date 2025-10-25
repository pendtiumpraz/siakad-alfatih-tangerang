@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <x-admin.page-header title="Edit User" />
        <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] p-6" x-data="userForm()">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information Section -->
            <div>
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                    <i class="fas fa-user mr-2"></i>
                    Informasi Dasar
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">
                            Username <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            value="{{ old('username', $user->username) }}"
                            required
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('username') border-red-500 @enderror"
                        >
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor HP
                        </label>
                        <input
                            type="text"
                            id="phone"
                            name="no_telepon"
                            value="{{ old('no_telepon', $user->phone) }}"
                            placeholder="08xx-xxxx-xxxx"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('no_telepon') border-red-500 @enderror"
                        >
                        @error('no_telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password (Optional) -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                            Password <span class="text-gray-400 text-xs">(Kosongkan jika tidak ingin mengubah)</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('password') border-red-500 @enderror"
                        >
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                            Konfirmasi Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                    </div>

                    <!-- Role -->
                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="role"
                            name="role"
                            x-model="selectedRole"
                            required
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('role') border-red-500 @enderror"
                        >
                            <option value="">Pilih Role</option>
                            <option value="super_admin" {{ old('role', $user->role) == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="operator" {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="dosen" {{ old('role', $user->role) == 'dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="mahasiswa" {{ old('role', $user->role) == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status Akun
                        </label>
                        <div class="flex items-center">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#D4AF37] rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#2D5F3F]"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">Aktif</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role-Specific Fields -->
            <!-- Super Admin Fields -->
            <div x-show="selectedRole === 'super_admin'" x-transition>
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                    <i class="fas fa-user-shield mr-2"></i>
                    Informasi Super Admin
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-600">Super Admin tidak memerlukan data tambahan.</p>
                    </div>
                </div>
            </div>

            <!-- Mahasiswa Fields -->
            <div x-show="selectedRole === 'mahasiswa'" x-transition>
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                    <i class="fas fa-user-graduate mr-2"></i>
                    Informasi Mahasiswa
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NIM -->
                    <div>
                        <label for="nim" class="block text-sm font-semibold text-gray-700 mb-2">
                            NIM <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nim"
                            name="nim"
                            value="{{ old('nim', $user->mahasiswa->nim ?? '') }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="mahasiswa_nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="mahasiswa_nama_lengkap"
                            name="mahasiswa_nama_lengkap"
                            value="{{ old('mahasiswa_nama_lengkap', $user->mahasiswa->nama_lengkap ?? '') }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('mahasiswa_nama_lengkap') border-red-500 @enderror"
                        >
                        @error('mahasiswa_nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Program Studi -->
                    <div>
                        <label for="program_studi_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="program_studi_id"
                            name="program_studi_id"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                            <option value="">Pilih Program Studi</option>
                            <option value="1" {{ old('program_studi_id', $user->mahasiswa->program_studi_id ?? '') == 1 ? 'selected' : '' }}>Pendidikan Agama Islam</option>
                            <option value="2" {{ old('program_studi_id', $user->mahasiswa->program_studi_id ?? '') == 2 ? 'selected' : '' }}>Ekonomi Syariah</option>
                            <option value="3" {{ old('program_studi_id', $user->mahasiswa->program_studi_id ?? '') == 3 ? 'selected' : '' }}>Hukum Keluarga Islam</option>
                        </select>
                    </div>

                    <!-- Angkatan -->
                    <div>
                        <label for="angkatan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Angkatan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="angkatan"
                            name="angkatan"
                            value="{{ old('angkatan', $user->mahasiswa->angkatan ?? '') }}"
                            placeholder="2024"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                    </div>
                </div>
            </div>

            <!-- Dosen Fields -->
            <div x-show="selectedRole === 'dosen'" x-transition>
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                    <i class="fas fa-chalkboard-teacher mr-2"></i>
                    Informasi Dosen
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- NIDN -->
                    <div>
                        <label for="nidn" class="block text-sm font-semibold text-gray-700 mb-2">
                            NIDN <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nidn"
                            name="nidn"
                            value="{{ old('nidn', $user->dosen->nidn ?? '') }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="dosen_nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="dosen_nama_lengkap"
                            name="dosen_nama_lengkap"
                            value="{{ old('dosen_nama_lengkap', $user->dosen->nama_lengkap ?? '') }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('dosen_nama_lengkap') border-red-500 @enderror"
                        >
                        @error('dosen_nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gelar -->
                    <div>
                        <label for="gelar" class="block text-sm font-semibold text-gray-700 mb-2">
                            Gelar
                        </label>
                        <input
                            type="text"
                            id="gelar"
                            name="gelar"
                            value="{{ old('gelar', $user->dosen->gelar ?? '') }}"
                            placeholder="S.Pd.I, M.Pd"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                    </div>
                </div>
            </div>

            <!-- Operator Fields -->
            <div x-show="selectedRole === 'operator'" x-transition>
                <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4 pb-2 border-b-2 border-[#D4AF37]">
                    <i class="fas fa-user-tie mr-2"></i>
                    Informasi Operator
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="operator_nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="operator_nama_lengkap"
                            name="operator_nama_lengkap"
                            value="{{ old('operator_nama_lengkap', $user->operator->nama_lengkap ?? '') }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('operator_nama_lengkap') border-red-500 @enderror"
                        >
                        @error('operator_nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Employee ID -->
                    <div>
                        <label for="employee_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Employee ID <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="employee_id"
                            name="employee_id"
                            value="{{ old('employee_id', $user->operator->employee_id ?? '') }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition"
                        >
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-save mr-2"></i>
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function userForm() {
        return {
            selectedRole: '{{ old('role', $user->role) }}'
        }
    }
</script>
@endsection
