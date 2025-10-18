@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Pembayaran</h1>
            <p class="text-gray-600 mt-1">Perbarui data pembayaran</p>
        </div>
        <a href="{{ route('admin.pembayaran.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form -->
    <x-islamic-card title="Informasi Pembayaran">
        <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Mahasiswa -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mahasiswa <span class="text-red-500">*</span>
                    </label>
                    <select name="mahasiswa_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Mahasiswa</option>
                        @foreach($mahasiswas ?? [] as $mahasiswa)
                        <option value="{{ $mahasiswa->id }}" {{ old('mahasiswa_id', $pembayaran->mahasiswa_id ?? '') == $mahasiswa->id ? 'selected' : '' }}>
                            {{ $mahasiswa->nim }} - {{ $mahasiswa->nama_lengkap }}
                        </option>
                        @endforeach
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
                        <option value="spp" {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'spp' ? 'selected' : '' }}>SPP</option>
                        <option value="uang_kuliah" {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'uang_kuliah' ? 'selected' : '' }}>Uang Kuliah</option>
                        <option value="ujian" {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'ujian' ? 'selected' : '' }}>Ujian</option>
                        <option value="praktikum" {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'praktikum' ? 'selected' : '' }}>Praktikum</option>
                        <option value="wisuda" {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'wisuda' ? 'selected' : '' }}>Wisuda</option>
                        <option value="lainnya" {{ old('jenis_pembayaran', $pembayaran->jenis_pembayaran ?? '') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                        <option value="{{ $semester->id }}" {{ old('semester_id', $pembayaran->semester_id ?? '') == $semester->id ? 'selected' : '' }}>
                            {{ $semester->nama_semester }} - {{ $semester->tahun_akademik }}
                        </option>
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
                        <input type="number" name="jumlah" required min="0" value="{{ old('jumlah', $pembayaran->jumlah ?? '') }}" class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
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
                    <input type="date" name="tanggal_jatuh_tempo" required value="{{ old('tanggal_jatuh_tempo', $pembayaran->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($pembayaran->tanggal_jatuh_tempo)->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('tanggal_jatuh_tempo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Bayar -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Bayar
                    </label>
                    <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('Y-m-d') : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('tanggal_bayar')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="pending" {{ old('status', $pembayaran->status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="lunas" {{ old('status', $pembayaran->status ?? '') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="belum_lunas" {{ old('status', $pembayaran->status ?? '') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
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
                <textarea name="keterangan" rows="4" placeholder="Masukkan keterangan tambahan..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">{{ old('keterangan', $pembayaran->keterangan ?? '') }}</textarea>
                @error('keterangan')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Bukti Pembayaran -->
            <div>
                <x-file-upload
                    name="bukti_pembayaran"
                    label="Bukti Pembayaran"
                    accept="image/*,.pdf"
                    :currentFile="$pembayaran->bukti_pembayaran ?? null"
                />
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
                <a href="{{ route('admin.pembayaran.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md">
                    Update Pembayaran
                </button>
            </div>
        </form>
    </x-islamic-card>
</div>
@endsection
