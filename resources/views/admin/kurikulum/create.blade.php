@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Tambah Kurikulum" />

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.kurikulum.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-book mr-2"></i>
                Form Kurikulum
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form action="{{ route('admin.kurikulum.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Program Studi -->
                    <div class="md:col-span-2">
                        <label for="program_studi_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Program Studi <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="program_studi_id"
                            id="program_studi_id"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('program_studi_id') border-red-500 @enderror"
                            required
                        >
                            <option value="">Pilih Program Studi</option>
                            @foreach($programStudis ?? [] as $prodi)
                                <option value="{{ $prodi->id }}" {{ old('program_studi_id') == $prodi->id ? 'selected' : '' }}>
                                    {{ $prodi->jenjang }} - {{ $prodi->nama }} ({{ $prodi->kode }})
                                </option>
                            @endforeach
                        </select>
                        @error('program_studi_id')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nama Kurikulum -->
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Kurikulum <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            value="{{ old('nama') }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('nama') border-red-500 @enderror"
                            placeholder="Contoh: Kurikulum 2024"
                            required
                        >
                        @error('nama')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Tahun Mulai -->
                    <div>
                        <label for="tahun_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Mulai <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="tahun_mulai"
                            id="tahun_mulai"
                            value="{{ old('tahun_mulai', date('Y')) }}"
                            min="2000"
                            max="2100"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tahun_mulai') border-red-500 @enderror"
                            required
                        >
                        @error('tahun_mulai')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Tahun mulai berlakunya kurikulum</p>
                    </div>

                    <!-- Tahun Selesai -->
                    <div>
                        <label for="tahun_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Selesai <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            name="tahun_selesai"
                            id="tahun_selesai"
                            value="{{ old('tahun_selesai', date('Y') + 4) }}"
                            min="2000"
                            max="2100"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tahun_selesai') border-red-500 @enderror"
                            required
                        >
                        @error('tahun_selesai')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Tahun berakhirnya kurikulum</p>
                    </div>

                    <!-- Total SKS -->
                    <div class="md:col-span-2">
                        <label for="total_sks" class="block text-sm font-semibold text-gray-700 mb-2">
                            Total SKS <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="number"
                                name="total_sks"
                                id="total_sks"
                                value="{{ old('total_sks') }}"
                                min="1"
                                max="200"
                                class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('total_sks') border-red-500 @enderror"
                                placeholder="Contoh: 144"
                                required
                            >
                            <div class="absolute right-4 top-2 text-gray-500">
                                <i class="fas fa-book-open"></i>
                            </div>
                        </div>
                        @error('total_sks')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Total SKS yang harus ditempuh mahasiswa</p>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <div class="flex items-center space-x-4" x-data="{ isActive: {{ old('is_active', '1') }} }">
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        x-model="isActive"
                                        class="sr-only"
                                        {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                                    >
                                    <div class="w-14 h-8 bg-gray-300 rounded-full shadow-inner transition" :class="isActive ? 'bg-green-500' : 'bg-gray-300'"></div>
                                    <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow transition-transform" :class="isActive ? 'transform translate-x-6' : ''"></div>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-medium" :class="isActive ? 'text-green-700' : 'text-gray-700'" x-text="isActive ? 'Aktif' : 'Tidak Aktif'"></span>
                                </div>
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Aktifkan kurikulum jika sudah siap digunakan</p>
                    </div>
                </div>

                <!-- Islamic Divider -->
                <div class="islamic-divider my-6"></div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.kurikulum.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg hover:from-[#4A7C59] hover:to-[#D4AF37] transition">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
