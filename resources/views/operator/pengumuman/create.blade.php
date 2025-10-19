@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Buat Pengumuman</h1>
            <p class="text-gray-600 mt-1">Tambah pengumuman baru untuk mahasiswa</p>
        </div>
        <a href="{{ route('operator.pengumuman.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-bold text-white">Form Pengumuman</h2>
        </div>

        <form action="{{ route('operator.pengumuman.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Judul -->
            <div>
                <label for="judul" class="block text-sm font-semibold text-gray-700 mb-2">
                    Judul Pengumuman <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="judul"
                       name="judul"
                       value="{{ old('judul') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent @error('judul') border-red-500 @enderror"
                       placeholder="Masukkan judul pengumuman"
                       required>
                @error('judul')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe -->
            <div>
                <label for="tipe" class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipe Pengumuman <span class="text-red-500">*</span>
                </label>
                <select id="tipe"
                        name="tipe"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent @error('tipe') border-red-500 @enderror">
                    <option value="info" {{ old('tipe') == 'info' ? 'selected' : '' }}>
                        <i class="fas fa-info-circle"></i> Info
                    </option>
                    <option value="penting" {{ old('tipe') == 'penting' ? 'selected' : '' }}>
                        <i class="fas fa-exclamation-triangle"></i> Penting
                    </option>
                    <option value="pengingat" {{ old('tipe') == 'pengingat' ? 'selected' : '' }}>
                        <i class="fas fa-clock"></i> Pengingat
                    </option>
                    <option value="kegiatan" {{ old('tipe') == 'kegiatan' ? 'selected' : '' }}>
                        <i class="fas fa-calendar"></i> Kegiatan
                    </option>
                </select>
                @error('tipe')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Isi -->
            <div>
                <label for="isi" class="block text-sm font-semibold text-gray-700 mb-2">
                    Isi Pengumuman <span class="text-red-500">*</span>
                </label>
                <textarea id="isi"
                          name="isi"
                          rows="8"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent @error('isi') border-red-500 @enderror"
                          placeholder="Tuliskan isi pengumuman..."
                          required>{{ old('isi') }}</textarea>
                @error('isi')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Mulai & Selesai -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Mulai
                    </label>
                    <input type="date"
                           id="tanggal_mulai"
                           name="tanggal_mulai"
                           value="{{ old('tanggal_mulai') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika mulai sekarang</p>
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal Selesai
                    </label>
                    <input type="date"
                           id="tanggal_selesai"
                           name="tanggal_selesai"
                           value="{{ old('tanggal_selesai') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2D5F3F] focus:border-transparent @error('tanggal_selesai') border-red-500 @enderror">
                    @error('tanggal_selesai')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada batas waktu</p>
                </div>
            </div>

            <!-- Checkboxes -->
            <div class="space-y-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex items-center">
                    <input type="checkbox"
                           id="untuk_mahasiswa"
                           name="untuk_mahasiswa"
                           value="1"
                           {{ old('untuk_mahasiswa', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-[#2D5F3F] border-gray-300 rounded focus:ring-2 focus:ring-[#2D5F3F]">
                    <label for="untuk_mahasiswa" class="ml-3 text-sm font-medium text-gray-700">
                        Untuk Mahasiswa
                    </label>
                </div>

                <div class="flex items-center">
                    <input type="checkbox"
                           id="is_active"
                           name="is_active"
                           value="1"
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-5 h-5 text-[#2D5F3F] border-gray-300 rounded focus:ring-2 focus:ring-[#2D5F3F]">
                    <label for="is_active" class="ml-3 text-sm font-medium text-gray-700">
                        Aktifkan Pengumuman
                    </label>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                <a href="{{ route('operator.pengumuman.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg transition">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Pengumuman
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
