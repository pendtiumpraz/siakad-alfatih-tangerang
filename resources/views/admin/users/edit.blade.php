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
                        <label for="no_telepon" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nomor HP
                        </label>
                        <input
                            type="text"
                            id="no_telepon"
                            name="no_telepon"
                            value="{{ old('no_telepon', $user->mahasiswa->no_telepon ?? $user->dosen->no_telepon ?? $user->operator->no_telepon ?? '') }}"
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
                    <!-- Nama Lengkap Mahasiswa -->
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

                    <!-- Program Studi -->
                    <div>
                        <label for="program_studi_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="program_studi_id"
                            name="program_studi_id"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('program_studi_id') border-red-500 @enderror"
                        >
                            <option value="">Pilih Program Studi</option>
                            @foreach($programStudis as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('program_studi_id', $user->mahasiswa->program_studi_id ?? '') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('angkatan') border-red-500 @enderror"
                        >
                        @error('angkatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Semester Aktif -->
                    <div>
                        <label for="semester_aktif" class="block text-sm font-semibold text-gray-700 mb-2">
                            Semester Aktif <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="semester_aktif"
                            name="semester_aktif"
                            value="{{ old('semester_aktif', $user->mahasiswa->semester_aktif ?? '') }}"
                            min="1"
                            max="14"
                            placeholder="Auto-calculated"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('semester_aktif') border-red-500 @enderror"
                        >
                        @error('semester_aktif')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Otomatis dihitung dari angkatan, bisa diubah manual</p>
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir
                        </label>
                        <input
                            type="text"
                            id="tempat_lahir"
                            name="tempat_lahir"
                            value="{{ old('tempat_lahir', $user->mahasiswa->tempat_lahir ?? '') }}"
                            placeholder="Jakarta"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('tempat_lahir') border-red-500 @enderror"
                        >
                        @error('tempat_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir
                        </label>
                        @php
                            $tanggalLahirMahasiswa = optional($user->mahasiswa)->tanggal_lahir;
                            $formattedTanggalMahasiswa = $tanggalLahirMahasiswa ? \Carbon\Carbon::parse($tanggalLahirMahasiswa)->format('Y-m-d') : '';
                        @endphp
                        <input
                            type="date"
                            id="tanggal_lahir"
                            name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $formattedTanggalMahasiswa) }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('tanggal_lahir') border-red-500 @enderror"
                        >
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Kelamin
                        </label>
                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('jenis_kelamin') border-red-500 @enderror"
                        >
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $user->mahasiswa->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->mahasiswa->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Alamat lengkap"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('alamat') border-red-500 @enderror"
                        >{{ old('alamat', $user->mahasiswa->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Mahasiswa -->
                    <div>
                        <label for="mahasiswa_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <select
                            id="mahasiswa_status"
                            name="mahasiswa_status"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('mahasiswa_status') border-red-500 @enderror"
                        >
                            <option value="aktif" {{ old('mahasiswa_status', $user->mahasiswa->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="cuti" {{ old('mahasiswa_status', $user->mahasiswa->status ?? '') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="lulus" {{ old('mahasiswa_status', $user->mahasiswa->status ?? '') == 'lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="dropout" {{ old('mahasiswa_status', $user->mahasiswa->status ?? '') == 'dropout' ? 'selected' : '' }}>Dropout</option>
                        </select>
                        @error('mahasiswa_status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('nidn') border-red-500 @enderror"
                        >
                        @error('nidn')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Lengkap Dosen -->
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

                    <!-- Gelar Depan -->
                    <div>
                        <label for="gelar_depan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Gelar Depan
                        </label>
                        <input
                            type="text"
                            id="gelar_depan"
                            name="gelar_depan"
                            value="{{ old('gelar_depan', $user->dosen->gelar_depan ?? '') }}"
                            placeholder="Dr., Prof."
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('gelar_depan') border-red-500 @enderror"
                        >
                        @error('gelar_depan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gelar Belakang -->
                    <div>
                        <label for="gelar_belakang" class="block text-sm font-semibold text-gray-700 mb-2">
                            Gelar Belakang
                        </label>
                        <input
                            type="text"
                            id="gelar_belakang"
                            name="gelar_belakang"
                            value="{{ old('gelar_belakang', $user->dosen->gelar_belakang ?? '') }}"
                            placeholder="S.Pd.I, M.Pd"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('gelar_belakang') border-red-500 @enderror"
                        >
                        @error('gelar_belakang')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tempat Lahir
                        </label>
                        <input
                            type="text"
                            id="tempat_lahir"
                            name="tempat_lahir"
                            value="{{ old('tempat_lahir', $user->dosen->tempat_lahir ?? '') }}"
                            placeholder="Jakarta"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('tempat_lahir') border-red-500 @enderror"
                        >
                        @error('tempat_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lahir
                        </label>
                        @php
                            $tanggalLahirDosen = optional($user->dosen)->tanggal_lahir;
                            $formattedTanggalDosen = $tanggalLahirDosen ? \Carbon\Carbon::parse($tanggalLahirDosen)->format('Y-m-d') : '';
                        @endphp
                        <input
                            type="date"
                            id="tanggal_lahir"
                            name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $formattedTanggalDosen) }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('tanggal_lahir') border-red-500 @enderror"
                        >
                        @error('tanggal_lahir')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis Kelamin
                        </label>
                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('jenis_kelamin') border-red-500 @enderror"
                        >
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $user->dosen->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $user->dosen->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Alamat lengkap"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('alamat') border-red-500 @enderror"
                        >{{ old('alamat', $user->dosen->alamat ?? '') }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status Dosen -->
                    <div>
                        <label for="dosen_status" class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <select
                            id="dosen_status"
                            name="dosen_status"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('dosen_status') border-red-500 @enderror"
                        >
                            <option value="aktif" {{ old('dosen_status', $user->dosen->status ?? 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="non-aktif" {{ old('dosen_status', $user->dosen->status ?? '') == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('dosen_status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
            selectedRole: '{{ old('role', $user->role) }}',
            calculateSemester() {
                const angkatan = document.getElementById('angkatan')?.value;
                const semesterField = document.getElementById('semester_aktif');

                if (angkatan && semesterField) {
                    const currentYear = new Date().getFullYear();
                    const currentMonth = new Date().getMonth() + 1;

                    // Determine if we're in odd (ganjil) or even (genap) semester
                    // Odd semester: Aug-Dec (month 8-12), Even semester: Jan-Jul (month 1-7)
                    const isOddSemester = currentMonth >= 8;

                    // Calculate years since enrollment
                    const yearsSince = currentYear - parseInt(angkatan);

                    // Calculate semester: (years * 2) + current semester type
                    const calculatedSemester = (yearsSince * 2) + (isOddSemester ? 1 : 2);

                    // Only auto-fill if field is empty or has placeholder
                    if (!semesterField.value || semesterField.value == '') {
                        semesterField.value = Math.max(1, Math.min(14, calculatedSemester));
                    }
                }
            }
        }
    }

    // Add event listener for angkatan field
    document.addEventListener('DOMContentLoaded', function() {
        const angkatanField = document.getElementById('angkatan');
        if (angkatanField) {
            angkatanField.addEventListener('blur', function() {
                const app = Alpine.$data(document.querySelector('[x-data]'));
                if (app && app.selectedRole === 'mahasiswa') {
                    app.calculateSemester();
                }
            });
        }
    });
</script>
@endsection
