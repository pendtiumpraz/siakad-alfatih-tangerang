@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Edit Program Studi" />

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.program-studi.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-graduation-cap mr-2"></i>
                Edit Form Program Studi
            </h2>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <form action="{{ route('admin.program-studi.update', $programStudi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kode Program Studi -->
                    <div>
                        <label for="kode_prodi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Program Studi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="kode_prodi"
                            id="kode_prodi"
                            value="{{ old('kode_prodi', $programStudi->kode_prodi) }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('kode_prodi') border-red-500 @enderror"
                            placeholder="Contoh: S1-TI"
                            required
                        >
                        @error('kode_prodi')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Masukkan kode unik untuk program studi</p>
                    </div>

                    <!-- Jenjang -->
                    <div>
                        <label for="jenjang" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenjang <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="jenjang"
                            id="jenjang"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('jenjang') border-red-500 @enderror"
                            required
                        >
                            <option value="">Pilih Jenjang</option>
                            <option value="D3" {{ old('jenjang', $programStudi->jenjang) == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('jenjang', $programStudi->jenjang) == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('jenjang', $programStudi->jenjang) == 'S2' ? 'selected' : '' }}>S2</option>
                            <option value="S3" {{ old('jenjang', $programStudi->jenjang) == 'S3' ? 'selected' : '' }}>S3</option>
                        </select>
                        @error('jenjang')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Nama Program Studi -->
                    <div class="md:col-span-2">
                        <label for="nama_prodi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Program Studi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama_prodi"
                            id="nama_prodi"
                            value="{{ old('nama_prodi', $programStudi->nama_prodi) }}"
                            class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('nama_prodi') border-red-500 @enderror"
                            placeholder="Contoh: Teknik Informatika"
                            required
                        >
                        @error('nama_prodi')
                            <p class="mt-1 text-sm text-red-500">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <div class="flex items-center space-x-4" x-data="{ isActive: {{ old('is_active', $programStudi->is_active ? '1' : '0') }} == 1 }">
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input
                                        type="checkbox"
                                        name="is_active"
                                        value="1"
                                        x-model="isActive"
                                        class="sr-only"
                                        {{ old('is_active', $programStudi->is_active) ? 'checked' : '' }}
                                    >
                                    <div class="w-14 h-8 bg-gray-300 rounded-full shadow-inner transition" :class="isActive ? 'bg-green-500' : 'bg-gray-300'"></div>
                                    <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow transition-transform" :class="isActive ? 'transform translate-x-6' : ''"></div>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-medium" :class="isActive ? 'text-green-700' : 'text-gray-700'" x-text="isActive ? 'Aktif' : 'Tidak Aktif'"></span>
                                </div>
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Aktifkan program studi jika sudah siap digunakan</p>
                    </div>
                </div>

                <!-- Islamic Divider -->
                <div class="islamic-divider my-6"></div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.program-studi.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg hover:from-[#4A7C59] hover:to-[#D4AF37] transition">
                        <i class="fas fa-save mr-2"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Card (Optional) -->
    <div class="bg-gradient-to-br from-[#F4E5C3] to-white rounded-lg shadow-md border border-[#D4AF37] p-6" x-data="previewData()">
        <h3 class="text-lg font-semibold text-[#2D5F3F] mb-4">
            <i class="fas fa-eye mr-2"></i>
            Preview Data
        </h3>
        <div class="space-y-2 text-sm">
            <p><span class="font-semibold">Kode:</span> <span x-text="kode || '-'"></span></p>
            <p><span class="font-semibold">Nama:</span> <span x-text="nama || '-'"></span></p>
            <p><span class="font-semibold">Jenjang:</span> <span x-text="jenjang || '-'"></span></p>
            <p><span class="font-semibold">Status:</span> <span x-text="isActive ? 'Aktif' : 'Tidak Aktif'"></span></p>
        </div>
    </div>
</div>

<script>
function previewData() {
    return {
        kode: '{{ old('kode_prodi', $programStudi->kode_prodi) }}',
        nama: '{{ old('nama_prodi', $programStudi->nama_prodi) }}',
        jenjang: '{{ old('jenjang', $programStudi->jenjang) }}',
        isActive: {{ old('is_active', $programStudi->is_active ? '1' : '0') }},
        init() {
            this.$watch('kode', () => this.updatePreview());
            this.$watch('nama', () => this.updatePreview());
            this.$watch('jenjang', () => this.updatePreview());
        },
        updatePreview() {
            this.kode = document.getElementById('kode_prodi')?.value || '';
            this.nama = document.getElementById('nama_prodi')?.value || '';
            this.jenjang = document.getElementById('jenjang')?.value || '';
        }
    }
}
</script>
@endsection
