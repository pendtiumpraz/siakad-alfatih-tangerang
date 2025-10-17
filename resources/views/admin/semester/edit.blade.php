@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Edit Semester" />

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.semester.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Warning Alert -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-exclamation-triangle text-yellow-400 text-xl mr-3 mt-1"></i>
            <div>
                <p class="font-semibold text-yellow-800">Perhatian!</p>
                <p class="text-sm text-yellow-700">Hanya satu semester yang dapat aktif pada satu waktu. Mengaktifkan semester ini akan menonaktifkan semester yang sedang aktif.</p>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-calendar-alt mr-2"></i>
                Edit Form Semester
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.semester.update', $semester->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Semester -->
                    <div>
                        <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" id="semester" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('semester') border-red-500 @enderror" required>
                            <option value="">Pilih Semester</option>
                            <option value="Ganjil" {{ old('semester', $semester->semester) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap" {{ old('semester', $semester->semester) == 'Genap' ? 'selected' : '' }}>Genap</option>
                        </select>
                        @error('semester')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Akademik -->
                    <div>
                        <label for="tahun_akademik" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tahun Akademik <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="tahun_akademik" id="tahun_akademik" value="{{ old('tahun_akademik', $semester->tahun_akademik) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tahun_akademik') border-red-500 @enderror" placeholder="2024/2025" required>
                        @error('tahun_akademik')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Format: 2024/2025</p>
                    </div>

                    <!-- Tanggal Mulai -->
                    <div>
                        <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Mulai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $semester->tanggal_mulai) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tanggal_mulai') border-red-500 @enderror" required>
                            <i class="fas fa-calendar-alt absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                        </div>
                        @error('tanggal_mulai')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                            Tanggal Selesai <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $semester->tanggal_selesai) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('tanggal_selesai') border-red-500 @enderror" required>
                            <i class="fas fa-calendar-check absolute right-3 top-3 text-gray-400 pointer-events-none"></i>
                        </div>
                        @error('tanggal_selesai')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Harus lebih besar dari tanggal mulai</p>
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Status
                        </label>
                        <div class="flex items-center space-x-4" x-data="{ isActive: {{ old('is_active', $semester->is_active ? '1' : '0') }} == 1 }">
                            <label class="flex items-center cursor-pointer">
                                <div class="relative">
                                    <input type="checkbox" name="is_active" value="1" x-model="isActive" class="sr-only" {{ old('is_active', $semester->is_active) ? 'checked' : '' }}>
                                    <div class="w-14 h-8 bg-gray-300 rounded-full shadow-inner transition" :class="isActive ? 'bg-green-500' : 'bg-gray-300'"></div>
                                    <div class="absolute left-1 top-1 w-6 h-6 bg-white rounded-full shadow transition-transform" :class="isActive ? 'transform translate-x-6' : ''"></div>
                                </div>
                                <div class="ml-3">
                                    <span class="text-sm font-medium" :class="isActive ? 'text-green-700' : 'text-gray-700'" x-text="isActive ? 'Aktif' : 'Tidak Aktif'"></span>
                                </div>
                            </label>
                        </div>
                        <p class="mt-1 text-xs text-red-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Mengaktifkan semester ini akan menonaktifkan semester lain yang sedang aktif
                        </p>
                    </div>
                </div>

                <div class="islamic-divider my-6"></div>

                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.semester.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition text-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] text-white font-semibold rounded-lg hover:shadow-lg hover:from-[#4A7C59] hover:to-[#D4AF37] transition">
                        <i class="fas fa-save mr-2"></i>Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
