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

                    <!-- Is Active (Hidden for mahasiswa - auto-managed by status) -->
                    <div x-show="selectedRole !== 'mahasiswa'">
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
                        <p class="mt-1 text-xs text-gray-500">Status akun untuk mahasiswa dikelola otomatis berdasarkan status akademik</p>
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
                            :required="selectedRole === 'mahasiswa'"
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
                            :required="selectedRole === 'mahasiswa'"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('nim') border-red-500 @enderror"
                        >
                        @error('nim')
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
                            :required="selectedRole === 'mahasiswa'"
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
                            :required="selectedRole === 'mahasiswa'"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('angkatan') border-red-500 @enderror"
                        >
                        @error('angkatan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Semester Aktif (Auto-calculated, Read-only) -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Semester Aktif
                        </label>
                        <div class="w-full px-4 py-2 border-2 border-gray-300 bg-gray-50 rounded-lg text-gray-700 font-semibold" x-text="calculatedSemester()">
                            -
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            <span x-show="mahasiswaStatus === 'lulus' || mahasiswaStatus === 'dropout'">
                                Dihitung dari angkatan s/d tanggal <span x-text="mahasiswaStatus === 'lulus' ? 'lulus' : 'dropout'"></span>
                            </span>
                            <span x-show="mahasiswaStatus === 'aktif' || mahasiswaStatus === 'cuti'">
                                Dihitung dari angkatan s/d hari ini
                            </span>
                        </p>
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
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            :required="selectedRole === 'mahasiswa'"
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
                            x-model="mahasiswaStatus"
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
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle text-blue-500"></i>
                            Status "Lulus" dan "Dropout" akan otomatis menonaktifkan akun login
                        </p>
                    </div>

                    <!-- Tanggal Lulus (Conditional) -->
                    <div x-show="mahasiswaStatus === 'lulus'" x-transition>
                        <label for="tanggal_lulus" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Lulus
                        </label>
                        <div class="flex gap-2">
                            <input
                                type="date"
                                id="tanggal_lulus"
                                name="tanggal_lulus"
                                value="{{ old('tanggal_lulus', $user->mahasiswa->tanggal_lulus ? $user->mahasiswa->tanggal_lulus->format('Y-m-d') : '') }}"
                                x-bind:disabled="useCurrentDateLulus"
                                class="flex-1 px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('tanggal_lulus') border-red-500 @enderror"
                            >
                            <label class="flex items-center px-4 py-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                                <input type="checkbox" x-model="useCurrentDateLulus" class="mr-2">
                                <span class="text-sm font-medium text-gray-700">Hari Ini</span>
                            </label>
                        </div>
                        @error('tanggal_lulus')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Centang "Hari Ini" untuk menggunakan tanggal saat ini</p>
                    </div>

                    <!-- Tanggal Dropout (Conditional) -->
                    <div x-show="mahasiswaStatus === 'dropout'" x-transition>
                        <label for="tanggal_dropout" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Dropout
                        </label>
                        <div class="flex gap-2">
                            <input
                                type="date"
                                id="tanggal_dropout"
                                name="tanggal_dropout"
                                value="{{ old('tanggal_dropout', $user->mahasiswa->tanggal_dropout ? $user->mahasiswa->tanggal_dropout->format('Y-m-d') : '') }}"
                                x-bind:disabled="useCurrentDateDropout"
                                class="flex-1 px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('tanggal_dropout') border-red-500 @enderror"
                            >
                            <label class="flex items-center px-4 py-2 bg-gray-100 rounded-lg cursor-pointer hover:bg-gray-200 transition">
                                <input type="checkbox" x-model="useCurrentDateDropout" class="mr-2">
                                <span class="text-sm font-medium text-gray-700">Hari Ini</span>
                            </label>
                        </div>
                        @error('tanggal_dropout')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Centang "Hari Ini" untuk menggunakan tanggal saat ini</p>
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
                            :required="selectedRole === 'dosen'"
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
                            :required="selectedRole === 'dosen'"
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

                    <!-- Program Studi Assignment (Multi-Select) -->
                    <div class="md:col-span-2">
                        <label for="program_studi_ids" class="block text-sm font-semibold text-gray-700 mb-2">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="program_studi_ids"
                            name="program_studi_ids[]"
                            multiple
                            size="5"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('program_studi_ids') border-red-500 @enderror"
                        >
                            @foreach($programStudis as $prodi)
                                @php
                                    $isSelected = false;
                                    if (old('program_studi_ids')) {
                                        $isSelected = in_array($prodi->id, old('program_studi_ids'));
                                    } elseif (isset($user->dosen) && method_exists($user->dosen, 'programStudis')) {
                                        try {
                                            $programStudisCollection = $user->dosen->programStudis;
                                            if ($programStudisCollection) {
                                                $isSelected = $programStudisCollection->contains($prodi->id);
                                            }
                                        } catch (\Exception $e) {
                                            // Ignore if programStudis relation doesn't exist yet
                                        }
                                    }
                                @endphp
                                <option value="{{ $prodi->id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_ids')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Tahan Ctrl (Windows) atau Command (Mac) untuk memilih multiple program studi. Dosen hanya bisa mengelola jadwal, nilai, dan KHS dari program studi yang di-assign.
                        </p>
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
                            :required="selectedRole === 'operator'"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:border-[#D4AF37] transition @error('operator_nama_lengkap') border-red-500 @enderror"
                        >
                        @error('operator_nama_lengkap')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
            mahasiswaStatus: '{{ old('mahasiswa_status', $user->mahasiswa->status ?? 'aktif') }}',
            useCurrentDateLulus: {{ old('tanggal_lulus', $user->mahasiswa->tanggal_lulus ?? null) ? 'false' : 'true' }},
            useCurrentDateDropout: {{ old('tanggal_dropout', $user->mahasiswa->tanggal_dropout ?? null) ? 'false' : 'true' }},

            calculatedSemester() {
                const angkatanInput = document.getElementById('angkatan');
                if (!angkatanInput || !angkatanInput.value) return '-';

                const angkatan = parseInt(angkatanInput.value);
                let referenceDate = new Date();

                // Untuk status lulus/dropout, gunakan tanggal yang dipilih
                if (this.mahasiswaStatus === 'lulus') {
                    if (this.useCurrentDateLulus) {
                        referenceDate = new Date(); // Hari ini
                    } else {
                        const tanggalLulusInput = document.getElementById('tanggal_lulus');
                        if (tanggalLulusInput && tanggalLulusInput.value) {
                            referenceDate = new Date(tanggalLulusInput.value);
                        }
                    }
                } else if (this.mahasiswaStatus === 'dropout') {
                    if (this.useCurrentDateDropout) {
                        referenceDate = new Date(); // Hari ini
                    } else {
                        const tanggalDropoutInput = document.getElementById('tanggal_dropout');
                        if (tanggalDropoutInput && tanggalDropoutInput.value) {
                            referenceDate = new Date(tanggalDropoutInput.value);
                        }
                    }
                }
                // Untuk aktif/cuti, gunakan hari ini (default)

                const year = referenceDate.getFullYear();
                const month = referenceDate.getMonth() + 1; // JavaScript months are 0-indexed

                // Hitung selisih tahun
                const yearDiff = year - angkatan;

                let semester;
                if (month >= 8) {
                    // Semester ganjil (Agustus-Desember)
                    semester = (yearDiff * 2) + 1;
                } else if (month >= 2) {
                    // Semester genap (Februari-Juli)
                    semester = (yearDiff * 2);
                } else {
                    // Januari masih semester ganjil tahun sebelumnya
                    semester = ((yearDiff - 1) * 2) + 1;
                }

                // Maksimal 14 semester
                semester = Math.min(Math.max(semester, 1), 14);

                return semester;
            }
        }
    }
</script>
@endsection
