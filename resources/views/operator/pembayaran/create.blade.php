@extends('layouts.operator')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Tambah Pembayaran</h1>
            <p class="text-gray-600 mt-1">Buat data pembayaran baru</p>
        </div>
        <a href="{{ route('operator.pembayaran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form -->
    <x-islamic-card title="Informasi Pembayaran">
        <form action="{{ route('operator.pembayaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mahasiswa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mahasiswa <span class="text-red-500">*</span>
                    </label>
                    <select name="mahasiswa_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Mahasiswa</option>
                        <option value="1">2301001 - Ahmad Nur Rahman</option>
                        <option value="2">2301002 - Fatimah Azzahra</option>
                        <option value="3">2301003 - Muhammad Ali</option>
                        <option value="4">2301004 - Khadijah Binti Ahmad</option>
                        <option value="5">2301005 - Umar Faruq</option>
                    </select>
                    @error('mahasiswa_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Pembayaran -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_pembayaran" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Jenis</option>
                        <option value="spp">SPP</option>
                        <option value="uang_kuliah">Uang Kuliah</option>
                        <option value="ujian">Ujian</option>
                        <option value="praktikum">Praktikum</option>
                        <option value="wisuda">Wisuda</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    @error('jenis_pembayaran')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="semester_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Semester</option>
                        @foreach($semesters ?? [] as $semester)
                        <option value="{{ $semester->id }}">{{ $semester->nama_semester }} - {{ $semester->tahun_akademik }}</option>
                        @endforeach
                    </select>
                    @error('semester_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nominal -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nominal <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-gray-600 font-semibold">Rp</span>
                        <input type="number" name="jumlah" required min="0" placeholder="0" class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    </div>
                    @error('jumlah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Jatuh Tempo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_jatuh_tempo" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('tanggal_jatuh_tempo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="belum_lunas" selected>Belum Lunas</option>
                        <option value="pending">Pending (Menunggu Verifikasi)</option>
                        <option value="lunas">Lunas</option>
                    </select>
                    @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Keterangan
                </label>
                <textarea name="keterangan" rows="4" placeholder="Masukkan keterangan tambahan..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                @error('keterangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bukti Pembayaran -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Bukti Pembayaran
                </label>
                <input type="file" name="bukti_pembayaran" accept="image/*,.pdf" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, PDF (Max: 2MB)</p>
                @error('bukti_pembayaran')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
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
                <a href="{{ route('operator.pembayaran.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md">
                    Simpan Pembayaran
                </button>
            </div>
        </form>
    </x-islamic-card>
</div>
@endsection
