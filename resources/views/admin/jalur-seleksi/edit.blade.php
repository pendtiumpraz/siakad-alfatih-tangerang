@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Jalur Seleksi</h1>
            <p class="text-gray-600 mt-1">Perbarui data jalur seleksi</p>
        </div>
        <a href="{{ route('admin.jalur-seleksi.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200">
        <form action="{{ route('admin.jalur-seleksi.update', $jalurSeleksi) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kode Jalur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Jalur <span class="text-red-600">*</span></label>
                    <input type="text" name="kode_jalur" value="{{ old('kode_jalur', $jalurSeleksi->kode_jalur) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('kode_jalur') border-red-500 @enderror"
                           placeholder="Contoh: REG, PRESTASI">
                    @error('kode_jalur')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Jalur -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Jalur <span class="text-red-600">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $jalurSeleksi->nama) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('nama') border-red-500 @enderror"
                           placeholder="Contoh: Jalur Reguler">
                    @error('nama')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Biaya Pendaftaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Biaya Pendaftaran <span class="text-red-600">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                        <input type="number" name="biaya_pendaftaran" value="{{ old('biaya_pendaftaran', $jalurSeleksi->biaya_pendaftaran) }}" required min="0" step="1000"
                               class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('biaya_pendaftaran') border-red-500 @enderror"
                               placeholder="0">
                    </div>
                    @error('biaya_pendaftaran')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kuota -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kuota (Opsional)</label>
                    <input type="number" name="kuota" value="{{ old('kuota', $jalurSeleksi->kuota) }}" min="0"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                           placeholder="Kosongkan untuk unlimited">
                    <p class="text-gray-500 text-xs mt-1">Kosongkan jika tidak ada batasan kuota</p>
                </div>

                <!-- Tanggal Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai <span class="text-red-600">*</span></label>
                    <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $jalurSeleksi->tanggal_mulai?->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Selesai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai <span class="text-red-600">*</span></label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $jalurSeleksi->tanggal_selesai?->format('Y-m-d')) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 @error('tanggal_selesai') border-red-500 @enderror">
                    @error('tanggal_selesai')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Deskripsi -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                          placeholder="Deskripsi jalur seleksi...">{{ old('deskripsi', $jalurSeleksi->deskripsi) }}</textarea>
            </div>

            <!-- Status Aktif -->
            <div class="mt-6">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $jalurSeleksi->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                    <span class="ml-2 text-sm font-medium text-gray-700">Aktifkan jalur seleksi ini</span>
                </label>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.jalur-seleksi.index') }}" class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
