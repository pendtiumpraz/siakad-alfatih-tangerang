@extends('layouts.dosen')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kartu Hasil Studi</h1>
            <p class="text-gray-600 mt-1">Semester 3 - Tahun Akademik 2024/2025</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('dosen.khs.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span>Kembali</span>
            </a>
            <button onclick="window.print()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Cetak KHS</span>
            </button>
        </div>
    </div>

    <!-- KHS Document -->
    <div class="bg-white rounded-lg shadow-lg border-4 border-green-600 p-8">
        <!-- Islamic Header -->
        <div class="text-center mb-8 pb-6 border-b-4 border-double border-yellow-500">
            <div class="flex justify-center mb-4">
                <div class="flex space-x-3">
                    <div class="w-4 h-4 bg-green-600 rounded-full"></div>
                    <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                    <div class="w-4 h-4 bg-green-600 rounded-full"></div>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-green-800 mb-2">STAI AL-FATIH</h1>
            <p class="text-lg text-gray-700 font-semibold">Sekolah Tinggi Agama Islam Al-Fatih</p>
            <p class="text-sm text-gray-600 mt-2">Jl. Pendidikan No. 123, Jakarta Selatan</p>
            <div class="mt-4">
                <h2 class="text-xl font-bold text-gray-800 bg-gradient-to-r from-green-100 to-yellow-100 inline-block px-6 py-2 rounded-lg border-2 border-green-500">
                    KARTU HASIL STUDI (KHS)
                </h2>
            </div>
        </div>

        <!-- Student Info -->
        <x-islamic-card class="mb-6">
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-40 text-sm text-gray-600 font-medium">Nama</span>
                        <span class="text-sm font-semibold text-gray-800">: Ahmad Nur Rahman</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 text-sm text-gray-600 font-medium">NIM</span>
                        <span class="text-sm font-semibold text-gray-800">: 2301001</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 text-sm text-gray-600 font-medium">Program Studi</span>
                        <span class="text-sm font-semibold text-gray-800">: Pendidikan Agama Islam</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex">
                        <span class="w-40 text-sm text-gray-600 font-medium">Semester</span>
                        <span class="text-sm font-semibold text-gray-800">: 3 (Tiga)</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 text-sm text-gray-600 font-medium">Tahun Akademik</span>
                        <span class="text-sm font-semibold text-gray-800">: 2024/2025</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 text-sm text-gray-600 font-medium">Status</span>
                        <x-status-badge status="active" type="status" />
                    </div>
                </div>
            </div>
        </x-islamic-card>

        <!-- Grades Table -->
        <x-islamic-card title="Daftar Nilai">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-green-200 border border-green-200">
                    <thead class="bg-gradient-to-r from-green-600 to-green-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">No</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Kode MK</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase">Mata Kuliah</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase">SKS</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase">Nilai</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase">Grade</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase">Bobot</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $courses = [
                                ['PAI301', 'Aqidah Akhlak', 3, 89, 'A', 4.0],
                                ['PAI302', 'Fiqih Ibadah', 3, 87, 'A', 4.0],
                                ['PAI303', 'Tafsir Al-Quran', 3, 85, 'A', 4.0],
                                ['PAI304', 'Hadist', 2, 82, 'B', 3.0],
                                ['PAI305', 'Ushul Fiqh', 3, 88, 'A', 4.0],
                                ['PAI306', 'Sejarah Islam', 2, 84, 'B', 3.0],
                                ['PAI307', 'Bahasa Arab', 3, 86, 'A', 4.0],
                            ];
                            $totalSks = 0;
                            $totalBobot = 0;
                        @endphp

                        @foreach($courses as $index => $course)
                        @php
                            $totalSks += $course[2];
                            $totalBobot += ($course[2] * $course[5]);
                        @endphp
                        <tr class="hover:bg-green-50 transition-colors">
                            <td class="px-4 py-3 text-sm text-gray-700 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $course[0] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800">{{ $course[1] }}</td>
                            <td class="px-4 py-3 text-sm text-center font-semibold text-gray-800">{{ $course[2] }}</td>
                            <td class="px-4 py-3 text-sm text-center font-bold text-gray-900">{{ $course[3] }}</td>
                            <td class="px-4 py-3 text-center">
                                <x-status-badge :status="$course[4]" type="grade" />
                            </td>
                            <td class="px-4 py-3 text-sm text-center font-semibold text-gray-800">{{ number_format($course[5], 2) }}</td>
                        </tr>
                        @endforeach

                        <!-- Totals -->
                        <tr class="bg-gradient-to-r from-yellow-50 to-yellow-100 border-t-2 border-green-600">
                            <td colspan="3" class="px-4 py-3 text-sm font-bold text-gray-800 text-right">TOTAL</td>
                            <td class="px-4 py-3 text-sm text-center font-bold text-gray-900">{{ $totalSks }}</td>
                            <td colspan="3" class="px-4 py-3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </x-islamic-card>

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <!-- IPS & IPK -->
            <x-islamic-card title="Ringkasan Nilai">
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg border-2 border-green-300">
                        <span class="text-sm font-medium text-gray-700">Total SKS Semester Ini</span>
                        <span class="text-2xl font-bold text-green-700">{{ $totalSks }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border-2 border-blue-300">
                        <span class="text-sm font-medium text-gray-700">IP Semester (IPS)</span>
                        <span class="text-2xl font-bold text-blue-700">{{ number_format($totalBobot / $totalSks, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg border-2 border-yellow-500">
                        <span class="text-sm font-medium text-gray-700">IPK Kumulatif</span>
                        <span class="text-3xl font-bold text-yellow-800">3.67</span>
                    </div>
                    <div class="flex justify-between items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg border-2 border-purple-300">
                        <span class="text-sm font-medium text-gray-700">Predikat</span>
                        <span class="text-xl font-bold text-purple-700">Sangat Baik</span>
                    </div>
                </div>
            </x-islamic-card>

            <!-- Grade Scale -->
            <x-islamic-card title="Skala Penilaian">
                <div class="space-y-2">
                    <div class="flex items-center justify-between p-2 border-b border-gray-200">
                        <span class="text-sm text-gray-700">A (85-100)</span>
                        <span class="text-sm font-semibold">= 4.00</span>
                    </div>
                    <div class="flex items-center justify-between p-2 border-b border-gray-200">
                        <span class="text-sm text-gray-700">B (70-84)</span>
                        <span class="text-sm font-semibold">= 3.00</span>
                    </div>
                    <div class="flex items-center justify-between p-2 border-b border-gray-200">
                        <span class="text-sm text-gray-700">C (60-69)</span>
                        <span class="text-sm font-semibold">= 2.00</span>
                    </div>
                    <div class="flex items-center justify-between p-2 border-b border-gray-200">
                        <span class="text-sm text-gray-700">D (50-59)</span>
                        <span class="text-sm font-semibold">= 1.00</span>
                    </div>
                    <div class="flex items-center justify-between p-2">
                        <span class="text-sm text-gray-700">E (0-49)</span>
                        <span class="text-sm font-semibold">= 0.00</span>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t-2 border-green-200">
                    <p class="text-xs text-gray-600 italic">
                        * KHS ini dicetak secara elektronik dan sah tanpa tanda tangan
                    </p>
                    <p class="text-xs text-gray-600 mt-2">
                        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB
                    </p>
                </div>
            </x-islamic-card>
        </div>

        <!-- Islamic Footer -->
        <div class="mt-8 pt-6 border-t-4 border-double border-yellow-500 text-center">
            <div class="flex justify-center mb-4">
                <div class="flex space-x-3">
                    <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                    <div class="w-3 h-3 bg-green-600 rounded-full"></div>
                </div>
            </div>
            <p class="text-sm text-gray-600 italic">"Barangsiapa yang menghendaki kehidupan akhirat dan berusaha ke arah itu dengan sungguh-sungguh..."</p>
            <p class="text-xs text-gray-500 mt-1">(QS. Al-Isra: 19)</p>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@endsection
