@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tambah Jadwal</h1>
            <p class="text-gray-600 mt-1">Buat jadwal mengajar baru</p>
        </div>
        <a href="{{ route('dosen.jadwal.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Conflict Warning (if any) -->
    <div x-data="{ showWarning: false }" x-show="showWarning" x-cloak class="p-4 bg-red-100 border-2 border-red-500 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <p class="font-bold text-red-800">Konflik Jadwal Terdeteksi!</p>
                <p class="text-sm text-red-700">Ruangan R101 sudah digunakan pada waktu yang sama.</p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <x-islamic-card title="Informasi Jadwal">
        <form action="{{ route('dosen.jadwal.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="semester" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Semester</option>
                        <option value="1">Semester 1</option>
                        <option value="2">Semester 2</option>
                        <option value="3">Semester 3</option>
                        <option value="4">Semester 4</option>
                        <option value="5">Semester 5</option>
                        <option value="6">Semester 6</option>
                    </select>
                    @error('semester')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mata Kuliah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <select name="matakuliah_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Mata Kuliah</option>
                        <option value="1">Aqidah Akhlak</option>
                        <option value="2">Fiqih Ibadah</option>
                        <option value="3">Tafsir Al-Quran</option>
                        <option value="4">Hadist</option>
                        <option value="5">Ushul Fiqh</option>
                        <option value="6">Sejarah Islam</option>
                    </select>
                    @error('matakuliah_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ruangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select name="ruangan_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Ruangan</option>
                        <option value="1">R101 (Kapasitas: 40)</option>
                        <option value="2">R102 (Kapasitas: 35)</option>
                        <option value="3">R201 (Kapasitas: 45)</option>
                        <option value="4">R202 (Kapasitas: 40)</option>
                        <option value="5">R103 (Kapasitas: 30)</option>
                    </select>
                    @error('ruangan_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Hari -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hari <span class="text-red-500">*</span>
                    </label>
                    <select name="hari" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Hari</option>
                        <option value="senin">Senin</option>
                        <option value="selasa">Selasa</option>
                        <option value="rabu">Rabu</option>
                        <option value="kamis">Kamis</option>
                        <option value="jumat">Jumat</option>
                    </select>
                    @error('hari')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Mulai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_mulai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('jam_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_selesai" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('jam_selesai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kelas" required placeholder="Contoh: 3A, 3B, dll" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('kelas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Islamic Divider -->
            <div class="flex items-center justify-center py-4">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('dosen.jadwal.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md">
                    Simpan Jadwal
                </button>
            </div>
        </form>
    </x-islamic-card>
</div>
@endsection
