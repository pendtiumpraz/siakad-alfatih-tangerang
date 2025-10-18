@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Nilai Mahasiswa</h1>
            <p class="text-gray-600 mt-1">Ubah nilai untuk {{ $nilai->mahasiswa->nama_lengkap }}</p>
        </div>
        <a href="{{ route('dosen.nilai.mahasiswa', $nilai->mata_kuliah_id) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Student Info Card -->
    <x-islamic-card title="Informasi Mahasiswa">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-600 mb-1">NIM</p>
                <p class="font-semibold text-gray-900">{{ $nilai->mahasiswa->nim }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Nama Lengkap</p>
                <p class="font-semibold text-gray-900">{{ $nilai->mahasiswa->nama_lengkap }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Mata Kuliah</p>
                <p class="font-semibold text-gray-900">{{ $nilai->mataKuliah->kode_mk }} - {{ $nilai->mataKuliah->nama_mk }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Semester</p>
                <p class="font-semibold text-gray-900">{{ $nilai->semester->tahun_akademik }} - {{ $nilai->semester->periode }}</p>
            </div>
        </div>
    </x-islamic-card>

    <!-- Form Edit -->
    <x-islamic-card title="Form Edit Nilai">
        <form action="{{ route('dosen.nilai.update', $nilai->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Nilai Tugas -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai Tugas (30%) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="nilai_tugas"
                        min="0"
                        max="100"
                        step="0.01"
                        required
                        value="{{ old('nilai_tugas', $nilai->nilai_tugas) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        oninput="if(this.value < 0) this.value = 0; if(this.value > 100) this.value = 100;"
                    >
                    @error('nilai_tugas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nilai UTS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai UTS (30%) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="nilai_uts"
                        min="0"
                        max="100"
                        step="0.01"
                        required
                        value="{{ old('nilai_uts', $nilai->nilai_uts) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        oninput="if(this.value < 0) this.value = 0; if(this.value > 100) this.value = 100;"
                    >
                    @error('nilai_uts')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nilai UAS -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai UAS (40%) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="nilai_uas"
                        min="0"
                        max="100"
                        step="0.01"
                        required
                        value="{{ old('nilai_uas', $nilai->nilai_uas) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        oninput="if(this.value < 0) this.value = 0; if(this.value > 100) this.value = 100;"
                    >
                    @error('nilai_uas')
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

            <!-- Current Grade Info -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 border-2 border-green-200 p-6 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Nilai Saat Ini</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                        <p class="text-sm text-gray-600 mb-1">Nilai Akhir</p>
                        <p class="text-2xl font-bold text-green-600">{{ number_format($nilai->nilai_akhir, 2) }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                        <p class="text-sm text-gray-600 mb-1">Grade</p>
                        <p class="text-2xl font-bold text-green-600">{{ $nilai->grade }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm border border-green-100">
                        <p class="text-sm text-gray-600 mb-1">Status</p>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $nilai->status == 'lulus' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($nilai->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Information -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold mb-1">Catatan:</p>
                        <ul class="list-disc list-inside space-y-1">
                            <li>Nilai akhir akan dihitung ulang secara otomatis</li>
                            <li>Grade: A (85-100), A- (80-84), B+ (75-79), B (70-74), B- (65-69), C+ (60-64), C (55-59), C- (50-54), D (45-49), E (<45)</li>
                            <li>Status kelulusan akan diperbarui otomatis</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('dosen.nilai.mahasiswa', $nilai->mata_kuliah_id) }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Update Nilai</span>
                </button>
            </div>
        </form>
    </x-islamic-card>
</div>
@endsection
