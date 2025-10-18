@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Input Nilai Mahasiswa</h1>
            <p class="text-gray-600 mt-1">Input nilai untuk mata kuliah {{ $mataKuliah->nama_mk }}</p>
        </div>
        <a href="{{ route('dosen.nilai.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Form -->
    <x-islamic-card title="Form Input Nilai - {{ $mataKuliah->kode_mk }} - {{ $mataKuliah->nama_mk }}">
        <form action="{{ route('dosen.nilai.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="mata_kuliah_id" value="{{ $mataKuliah->id }}">

            <!-- Semester Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Semester Akademik <span class="text-red-500">*</span>
                </label>
                <select name="semester_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Pilih Semester Akademik</option>
                    @foreach($semesters as $semester)
                    <option value="{{ $semester->id }}" {{ old('semester_id') == $semester->id ? 'selected' : '' }}>
                        {{ $semester->nama_semester }} - {{ $semester->tahun_akademik }} ({{ ucfirst($semester->jenis) }})
                    </option>
                    @endforeach
                </select>
                @error('semester_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-semibold">Total Mahasiswa: {{ $mahasiswas->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-green-200 border border-green-200 rounded-lg">
                    <thead class="bg-gradient-to-r from-green-600 to-green-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase w-16">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Nama Mahasiswa</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">Tugas (30%)</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">UTS (30%)</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">UAS (40%)</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($mahasiswas as $index => $mahasiswa)
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-700 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $mahasiswa->nim }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $mahasiswa->nama_lengkap }}</td>
                            <td class="px-4 py-3 text-center">
                                <input type="hidden" name="nilai[{{ $index }}][mahasiswa_id]" value="{{ $mahasiswa->id }}">
                                <input
                                    type="number"
                                    name="nilai[{{ $index }}][nilai_tugas]"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    placeholder="0-100"
                                    class="w-24 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    value="{{ old('nilai.'.$index.'.nilai_tugas') }}"
                                    oninput="if(this.value < 0) this.value = 0; if(this.value > 100) this.value = 100;"
                                >
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input
                                    type="number"
                                    name="nilai[{{ $index }}][nilai_uts]"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    placeholder="0-100"
                                    class="w-24 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    value="{{ old('nilai.'.$index.'.nilai_uts') }}"
                                    oninput="if(this.value < 0) this.value = 0; if(this.value > 100) this.value = 100;"
                                >
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input
                                    type="number"
                                    name="nilai[{{ $index }}][nilai_uas]"
                                    min="0"
                                    max="100"
                                    step="0.01"
                                    required
                                    placeholder="0-100"
                                    class="w-24 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    value="{{ old('nilai.'.$index.'.nilai_uas') }}"
                                    oninput="if(this.value < 0) this.value = 0; if(this.value > 100) this.value = 100;"
                                >
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-lg font-medium">Tidak ada mahasiswa aktif</p>
                                    <p class="text-sm">Silakan tambahkan mahasiswa terlebih dahulu</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($mahasiswas->count() > 0)
            <!-- Islamic Divider -->
            <div class="flex items-center justify-center py-4">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
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
                            <li>Nilai Tugas: 30% dari nilai akhir</li>
                            <li>Nilai UTS: 30% dari nilai akhir</li>
                            <li>Nilai UAS: 40% dari nilai akhir</li>
                            <li>Nilai akhir dan grade akan dihitung otomatis</li>
                            <li>Rentang nilai: 0-100</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('dosen.nilai.index') }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    <span>Simpan Semua Nilai</span>
                </button>
            </div>
            @endif
        </form>
    </x-islamic-card>
</div>
@endsection
