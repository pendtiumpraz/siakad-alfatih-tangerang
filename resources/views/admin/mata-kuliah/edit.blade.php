@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <x-admin.page-header title="Edit Mata Kuliah" />

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.mata-kuliah.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg shadow-md border-2 border-[#D4AF37] overflow-hidden">
        <div class="bg-gradient-to-r from-[#2D5F3F] to-[#4A7C59] px-6 py-4">
            <h2 class="text-xl font-semibold text-white">
                <i class="fas fa-book-open mr-2"></i>
                Edit Form Mata Kuliah
            </h2>
        </div>

        <div class="p-6">
            <form action="{{ route('admin.mata-kuliah.update', $mataKuliah->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kurikulum -->
                    <div class="md:col-span-2">
                        <label for="kurikulum_id" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kurikulum <span class="text-red-500">*</span>
                        </label>
                        <select name="kurikulum_id" id="kurikulum_id" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('kurikulum_id') border-red-500 @enderror" required>
                            <option value="">Pilih Kurikulum</option>
                            @foreach($kurikulums ?? [] as $kurikulum)
                                <option value="{{ $kurikulum->id }}" {{ old('kurikulum_id', $mataKuliah->kurikulum_id) == $kurikulum->id ? 'selected' : '' }}>
                                    {{ $kurikulum->nama }} - {{ $kurikulum->programStudi->nama ?? '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('kurikulum_id')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode MK -->
                    <div>
                        <label for="kode" class="block text-sm font-semibold text-gray-700 mb-2">
                            Kode Mata Kuliah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode', $mataKuliah->kode_mk) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('kode') border-red-500 @enderror" placeholder="Contoh: MK001" required>
                        @error('kode')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Kode unik untuk mata kuliah</p>
                    </div>

                    <!-- SKS -->
                    <div>
                        <label for="sks" class="block text-sm font-semibold text-gray-700 mb-2">
                            SKS <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="sks" id="sks" value="{{ old('sks', $mataKuliah->sks) }}" min="1" max="6" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('sks') border-red-500 @enderror" required>
                        @error('sks')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">1-6 SKS</p>
                    </div>

                    <!-- Nama MK -->
                    <div class="md:col-span-2">
                        <label for="nama" class="block text-sm font-semibold text-gray-700 mb-2">
                            Nama Mata Kuliah <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama', $mataKuliah->nama_mk) }}" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('nama') border-red-500 @enderror" placeholder="Contoh: Pemrograman Web" required>
                        @error('nama')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Semester -->
                    <div>
                        <label for="semester" class="block text-sm font-semibold text-gray-700 mb-2">
                            Semester <span class="text-red-500">*</span>
                        </label>
                        <select name="semester" id="semester" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('semester') border-red-500 @enderror" required>
                            <option value="">Pilih Semester</option>
                            @for($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('semester', $mataKuliah->semester) == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                        @error('semester')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis -->
                    <div>
                        <label for="jenis" class="block text-sm font-semibold text-gray-700 mb-2">
                            Jenis <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis" id="jenis" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('jenis') border-red-500 @enderror" required>
                            <option value="">Pilih Jenis</option>
                            <option value="Wajib" {{ old('jenis', $mataKuliah->jenis) == 'Wajib' ? 'selected' : '' }}>Wajib</option>
                            <option value="Pilihan" {{ old('jenis', $mataKuliah->jenis) == 'Pilihan' ? 'selected' : '' }}>Pilihan</option>
                        </select>
                        @error('jenis')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full px-4 py-2 border-2 border-[#2D5F3F] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D4AF37] transition @error('deskripsi') border-red-500 @enderror" placeholder="Deskripsi mata kuliah...">{{ old('deskripsi', $mataKuliah->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="mt-1 text-sm text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="islamic-divider my-6"></div>

                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <a href="{{ route('admin.mata-kuliah.index') }}" class="px-6 py-2 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition text-center">
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
