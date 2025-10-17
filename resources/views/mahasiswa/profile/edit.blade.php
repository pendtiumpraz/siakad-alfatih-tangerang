@extends('layouts.mahasiswa')

@section('title', 'Edit Profile')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Edit Profile</span>
            </h1>
            <p class="text-gray-600 mt-1">Perbarui informasi profile Anda</p>
        </div>
        <a href="{{ route('mahasiswa.profile.show') }}"
           class="text-gray-600 hover:text-gray-800 px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2 border-2 border-gray-300 hover:border-gray-400">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <div class="islamic-divider"></div>

    <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Profile Photo Upload -->
        <div class="card-islamic p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Foto Profile</span>
            </h3>
            <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-6" x-data="{ photoPreview: null }">
                <!-- Current Photo -->
                <div class="w-32 h-32 rounded-full islamic-border overflow-hidden bg-white p-2">
                    <img :src="photoPreview || 'https://ui-avatars.com/api/?name={{ auth()->user()->name ?? 'Mahasiswa' }}&size=300&background=4A7C59&color=fff'"
                         alt="Profile Photo"
                         class="w-full h-full rounded-full object-cover">
                </div>

                <!-- Upload Area -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Foto Baru</label>
                    <div class="flex items-center space-x-4">
                        <label class="cursor-pointer bg-white px-4 py-2 border-2 border-[#4A7C59] rounded-lg hover:bg-[#4A7C59] hover:text-white transition">
                            <span class="text-sm font-semibold">Pilih File</span>
                            <input
                                type="file"
                                name="photo"
                                accept="image/jpeg,image/png,image/jpg"
                                class="hidden"
                                @change="photoPreview = URL.createObjectURL($event.target.files[0])"
                            >
                        </label>
                        <span class="text-sm text-gray-500">JPG, PNG (Max. 2MB)</span>
                    </div>
                    @error('photo')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Personal Information Form -->
        <div class="card-islamic p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Informasi Personal</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', auth()->user()->name ?? 'Ahmad Fauzi Ramadhan') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition"
                        required
                    >
                    @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Place of Birth -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        name="place_of_birth"
                        value="{{ old('place_of_birth', 'Jakarta') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition"
                        required
                    >
                    @error('place_of_birth')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input
                        type="date"
                        name="date_of_birth"
                        value="{{ old('date_of_birth', '2005-01-15') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition"
                        required
                    >
                    @error('date_of_birth')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select
                        name="gender"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition"
                        required
                    >
                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('gender')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon <span class="text-red-500">*</span></label>
                    <input
                        type="tel"
                        name="phone"
                        value="{{ old('phone', '+62 812-3456-7890') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition"
                        required
                    >
                    @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', auth()->user()->email ?? 'ahmad.fauzi@student.staialfatih.ac.id') }}"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition"
                        required
                    >
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea
                        name="address"
                        rows="3"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition"
                        required
                    >{{ old('address', 'Jl. Pendidikan No. 123, Kelurahan Cibubur, Kecamatan Ciracas, Jakarta Timur, DKI Jakarta 13720') }}</textarea>
                    @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Academic Information (Read Only) -->
        <div class="card-islamic p-6 bg-gray-50">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2 pb-3 border-b-2 border-[#F4E5C3]">
                <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Informasi Akademik (Tidak Dapat Diubah)</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">NIM</label>
                    <input
                        type="text"
                        value="{{ auth()->user()->nim ?? '202301010001' }}"
                        class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-lg cursor-not-allowed"
                        readonly
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Program Studi</label>
                    <input
                        type="text"
                        value="Pendidikan Agama Islam"
                        class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-lg cursor-not-allowed"
                        readonly
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Angkatan</label>
                    <input
                        type="text"
                        value="2023"
                        class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-lg cursor-not-allowed"
                        readonly
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Status</label>
                    <input
                        type="text"
                        value="Aktif"
                        class="w-full px-4 py-3 bg-gray-100 border-2 border-gray-200 rounded-lg cursor-not-allowed"
                        readonly
                    >
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between card-islamic p-6">
            <div class="text-sm text-gray-600">
                <span class="text-red-500">*</span> menunjukkan field yang wajib diisi
            </div>
            <div class="flex space-x-4">
                <a href="{{ route('mahasiswa.profile.show') }}"
                   class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-100 transition">
                    Batal
                </a>
                <button
                    type="submit"
                    class="px-6 py-3 bg-[#4A7C59] hover:bg-[#3d6849] text-white rounded-lg font-semibold transition transform hover:scale-105 flex items-center space-x-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
