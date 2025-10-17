@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Input Nilai</h1>
            <p class="text-gray-600 mt-1">Kelola nilai mahasiswa</p>
        </div>
    </div>

    <!-- Filter Section -->
    <x-islamic-card title="Filter">
        <form method="GET" action="{{ route('dosen.nilai.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                <select name="semester" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Pilih Semester</option>
                    <option value="3" selected>Semester 3</option>
                    <option value="4">Semester 4</option>
                    <option value="5">Semester 5</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mata Kuliah</label>
                <select name="matakuliah" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Pilih Mata Kuliah</option>
                    <option value="1" selected>Aqidah Akhlak</option>
                    <option value="2">Fiqih Ibadah</option>
                    <option value="3">Tafsir Al-Quran</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                <select name="kelas" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                    <option value="">Pilih Kelas</option>
                    <option value="3A" selected>3A</option>
                    <option value="3B">3B</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                    Terapkan
                </button>
            </div>
        </form>
    </x-islamic-card>

    <!-- Grades Table -->
    <x-islamic-card title="Daftar Nilai - Aqidah Akhlak (Kelas 3A)">
        <form method="POST" action="{{ route('dosen.nilai.store') }}" class="space-y-6">
            @csrf

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-green-200 border border-green-200 rounded-lg">
                    <thead class="bg-gradient-to-r from-green-600 to-green-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase w-20">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">NIM</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Nama</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">Tugas</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">UTS</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">UAS</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-24">Nilai Akhir</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase w-20">Grade</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $students = [
                                ['2301001', 'Ahmad Nur Rahman', 85, 88, 90],
                                ['2301002', 'Fatimah Azzahra', 90, 92, 95],
                                ['2301003', 'Muhammad Ali', 78, 82, 85],
                                ['2301004', 'Khadijah Binti Ahmad', 88, 86, 90],
                                ['2301005', 'Umar Faruq', 82, 85, 88],
                                ['2301006', 'Aisyah Ramadhani', 92, 90, 93],
                                ['2301007', 'Bilal Ibnu Said', 75, 78, 80],
                                ['2301008', 'Sumayah Khairani', 87, 89, 91],
                            ];

                            function calculateGrade($finalScore) {
                                if ($finalScore >= 85) return 'A';
                                if ($finalScore >= 70) return 'B';
                                if ($finalScore >= 60) return 'C';
                                if ($finalScore >= 50) return 'D';
                                return 'E';
                            }
                        @endphp

                        @foreach($students as $index => $student)
                        @php
                            $nilaiAkhir = ($student[2] * 0.3) + ($student[3] * 0.3) + ($student[4] * 0.4);
                            $grade = calculateGrade($nilaiAkhir);
                        @endphp
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-700 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $student[0] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $student[1] }}</td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" name="tugas[{{ $index }}]" value="{{ $student[2] }}" min="0" max="100" class="w-20 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" name="uts[{{ $index }}]" value="{{ $student[3] }}" min="0" max="100" class="w-20 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <input type="number" name="uas[{{ $index }}]" value="{{ $student[4] }}" min="0" max="100" class="w-20 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="font-bold text-gray-800">{{ number_format($nilaiAkhir, 2) }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <x-status-badge :status="$grade" type="grade" />
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Islamic Divider -->
            <div class="flex items-center justify-center py-4">
                <div class="flex space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold shadow-md flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    <span>Simpan Semua Nilai</span>
                </button>
            </div>
        </form>
    </x-islamic-card>

    <!-- Grade Distribution -->
    <x-islamic-card title="Distribusi Nilai">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center p-4 bg-gradient-to-br from-green-700 to-green-800 rounded-lg border border-green-600 text-white">
                <p class="text-3xl font-bold">3</p>
                <p class="text-sm mt-1">Grade A</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-lg border border-green-400 text-white">
                <p class="text-3xl font-bold">4</p>
                <p class="text-sm mt-1">Grade B</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg border border-yellow-400 text-white">
                <p class="text-3xl font-bold">1</p>
                <p class="text-sm mt-1">Grade C</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg border border-orange-400 text-white">
                <p class="text-3xl font-bold">0</p>
                <p class="text-sm mt-1">Grade D</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-red-500 to-red-600 rounded-lg border border-red-400 text-white">
                <p class="text-3xl font-bold">0</p>
                <p class="text-sm mt-1">Grade E</p>
            </div>
        </div>
    </x-islamic-card>
</div>
@endsection
