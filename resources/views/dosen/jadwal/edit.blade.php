@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Jadwal</h1>
            <p class="text-gray-600 mt-1">Perbarui jadwal mengajar</p>
        </div>
        <a href="{{ route('dosen.jadwal.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form -->
    <x-islamic-card title="Informasi Jadwal">
        <form action="{{ route('dosen.jadwal.update', $jadwal->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Semester -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Semester <span class="text-red-500">*</span>
                    </label>
                    <select name="semester_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Semester</option>
                        @foreach($semesters as $semester)
                            <option value="{{ $semester->id }}" {{ old('semester_id', $jadwal->semester_id) == $semester->id ? 'selected' : '' }}>
                                {{ $semester->nama_semester }} - {{ $semester->tahun_akademik }} ({{ ucfirst($semester->jenis) }})
                            </option>
                        @endforeach
                    </select>
                    @error('semester_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Mata Kuliah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Mata Kuliah <span class="text-red-500">*</span>
                    </label>
                    <select name="mata_kuliah_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Pilih Mata Kuliah</option>
                        @foreach($mataKuliahs as $mk)
                            <option value="{{ $mk->id }}" {{ old('mata_kuliah_id', $jadwal->mata_kuliah_id) == $mk->id ? 'selected' : '' }}>
                                {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_kuliah_id')
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
                        @foreach($ruangans as $ruangan)
                            <option value="{{ $ruangan->id }}" {{ old('ruangan_id', $jadwal->ruangan_id) == $ruangan->id ? 'selected' : '' }}>
                                {{ $ruangan->nama_ruangan }} (Kapasitas: {{ $ruangan->kapasitas }})
                            </option>
                        @endforeach
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
                        @foreach($hariOptions as $hari)
                            <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
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
                    <input type="time" name="jam_mulai" required value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('jam_mulai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jam Selesai -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="jam_selesai" required value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    @error('jam_selesai')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelas -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kelas" required value="{{ old('kelas', $jadwal->kelas) }}" placeholder="Contoh: 3A, 3B, dll" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
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
                    Update Jadwal
                </button>
            </div>
        </form>
    </x-islamic-card>
</div>
@endsection
