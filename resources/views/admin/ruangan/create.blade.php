@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Tambah Ruangan" />

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.ruangan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-door-open mr-2"></i>
                Form Ruangan
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.ruangan.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Ruangan -->
                    <div>
                        <label for="kode_ruangan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode_ruangan" id="kode_ruangan" value="{{ old('kode_ruangan') }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('kode_ruangan') border-red-500 @enderror" placeholder="Contoh: R101" required>
                        @error('kode_ruangan')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label for="kapasitas" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kapasitas <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="kapasitas" id="kapasitas" value="{{ old('kapasitas') }}" min="1" class="w-full px-4 py-2 pr-20 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('kapasitas') border-red-500 @enderror" placeholder="30" required>
                            <div class="absolute right-3 top-2 flex items-center text-gray-500">
                                <i class="fas fa-users mr-1"></i>
                                <span class="text-sm">Orang</span>
                            </div>
                        </div>
                        @error('kapasitas')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Jumlah maksimal mahasiswa</p>
                    </div>

                    <!-- Nama Ruangan -->
                    <div class="md:col-span-2">
                        <label for="nama_ruangan" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Ruangan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_ruangan" id="nama_ruangan" value="{{ old('nama_ruangan') }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('nama_ruangan') border-red-500 @enderror" placeholder="Contoh: Ruang Kuliah 101" required>
                        @error('nama_ruangan')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fasilitas -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Fasilitas
                        </label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="fasilitas[]" value="Proyektor" class="w-4 h-4 text-[#2D5F3F] border-gray-300 rounded focus:ring-[#D4AF37]" {{ in_array('Proyektor', old('fasilitas', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">
                                    <i class="fas fa-video text-[#2D5F3F] mr-1"></i>
                                    Proyektor
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="fasilitas[]" value="AC" class="w-4 h-4 text-[#2D5F3F] border-gray-300 rounded focus:ring-[#D4AF37]" {{ in_array('AC', old('fasilitas', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">
                                    <i class="fas fa-snowflake text-[#2D5F3F] mr-1"></i>
                                    AC
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="fasilitas[]" value="Papan Tulis" class="w-4 h-4 text-[#2D5F3F] border-gray-300 rounded focus:ring-[#D4AF37]" {{ in_array('Papan Tulis', old('fasilitas', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">
                                    <i class="fas fa-chalkboard text-[#2D5F3F] mr-1"></i>
                                    Papan Tulis
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="fasilitas[]" value="Wifi" class="w-4 h-4 text-[#2D5F3F] border-gray-300 rounded focus:ring-[#D4AF37]" {{ in_array('Wifi', old('fasilitas', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">
                                    <i class="fas fa-wifi text-[#2D5F3F] mr-1"></i>
                                    Wifi
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="fasilitas[]" value="Lab Komputer" class="w-4 h-4 text-[#2D5F3F] border-gray-300 rounded focus:ring-[#D4AF37]" {{ in_array('Lab Komputer', old('fasilitas', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">
                                    <i class="fas fa-desktop text-[#2D5F3F] mr-1"></i>
                                    Lab Komputer
                                </span>
                            </label>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="fasilitas[]" value="Sound System" class="w-4 h-4 text-[#2D5F3F] border-gray-300 rounded focus:ring-[#D4AF37]" {{ in_array('Sound System', old('fasilitas', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-700">
                                    <i class="fas fa-volume-up text-[#2D5F3F] mr-1"></i>
                                    Sound System
                                </span>
                            </label>
                        </div>
                        @error('fasilitas')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">Pilih fasilitas yang tersedia di ruangan</p>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status Ketersediaan
                        </label>
                        <div class="flex items-center space-x-4" x-data="{ isAvailable: {{ old('is_available', '1') }} }">
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="is_available" value="1" x-model="isAvailable" class="sr-only" {{ old('is_available', '1') == '1' ? 'checked' : '' }}>
                                    <div class="w-14 h-8 bg-gray-300 rounded-full shadow-inner transition" :class="isAvailable ? 'bg-green-500' : 'bg-gray-300'"></div>
                                    <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow transition-transform" :class="isAvailable ? 'transform translate-x-6' : ''"></div>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-medium" :class="isAvailable ? 'text-green-700' : 'text-gray-700'" x-text="isAvailable ? 'Tersedia' : 'Tidak Tersedia'"></span>
                                </div>
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Tandai jika ruangan siap digunakan</p>
                    </div>
                </div>

                <div class="islamic-divider my-6"></div>

                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.ruangan.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg hover:from-[#4A7C59] hover:to-[#D4AF37] transition">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
