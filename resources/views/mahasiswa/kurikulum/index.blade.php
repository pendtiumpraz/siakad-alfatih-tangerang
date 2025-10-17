@extends('layouts.mahasiswa')

@section('title', 'Kurikulum')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Kurikulum Program Studi</span>
            </h1>
            <p class="text-gray-600 mt-1">Pendidikan Agama Islam - Angkatan 2023</p>
        </div>
    </div>

    <div class="islamic-divider"></div>

    <!-- Progress Summary -->
    <div class="card-islamic p-6 islamic-pattern">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Progress Studi</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="text-center p-4 bg-white rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Total SKS Kurikulum</p>
                <p class="text-4xl font-bold text-[#4A7C59]">144</p>
            </div>
            <div class="text-center p-4 bg-white rounded-lg">
                <p class="text-sm text-gray-600 mb-2">SKS Lulus</p>
                <p class="text-4xl font-bold text-green-600">92</p>
            </div>
            <div class="text-center p-4 bg-white rounded-lg">
                <p class="text-sm text-gray-600 mb-2">SKS Belum Lulus</p>
                <p class="text-4xl font-bold text-orange-600">52</p>
            </div>
            <div class="text-center p-4 bg-white rounded-lg">
                <p class="text-sm text-gray-600 mb-2">Progress</p>
                <p class="text-4xl font-bold text-[#D4AF37]">64%</p>
            </div>
        </div>
        <div class="space-y-2">
            <div class="flex justify-between text-sm text-gray-600">
                <span>Progress Kelulusan</span>
                <span>92 / 144 SKS</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden border-2 border-[#D4AF37]">
                <div class="bg-gradient-to-r from-[#4A7C59] to-[#5a9c6f] h-full rounded-full flex items-center justify-center text-white text-xs font-bold transition-all duration-500" style="width: 64%;">
                    64%
                </div>
            </div>
        </div>
    </div>

    <!-- Semester Accordion -->
    <div class="space-y-4" x-data="{ openSemester: 1 }">
        <!-- Semester 1 -->
        <div class="card-islamic overflow-hidden">
            <button
                @click="openSemester = openSemester === 1 ? null : 1"
                class="w-full p-6 flex items-center justify-between hover:bg-gray-50 transition"
            >
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#4A7C59] to-[#5a9c6f] text-white px-4 py-2 rounded-lg font-bold">
                        Sem 1
                    </div>
                    <div class="text-left">
                        <h3 class="text-lg font-bold text-gray-800">Semester 1</h3>
                        <p class="text-sm text-gray-600">6 Mata Kuliah - 14 SKS</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold">
                        Lulus Semua
                    </span>
                    <svg class="w-6 h-6 text-gray-600 transition-transform" :class="openSemester === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
            <div x-show="openSemester === 1" x-collapse class="border-t">
                <div class="p-6">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Kode MK</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Mata Kuliah</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">SKS</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Jenis</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-101</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Pendidikan Pancasila</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-102</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Bahasa Indonesia</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-103</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Bahasa Arab I</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-104</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Aqidah Akhlak</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-105</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Ilmu Pendidikan</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-106</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Pengantar Studi Islam</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Semester 2-8 would follow similar pattern, I'll show one more example -->

        <!-- Semester 5 (Current) -->
        <div class="card-islamic overflow-hidden border-2 border-[#D4AF37]">
            <button
                @click="openSemester = openSemester === 5 ? null : 5"
                class="w-full p-6 flex items-center justify-between hover:bg-gray-50 transition"
            >
                <div class="flex items-center space-x-4">
                    <div class="bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3] text-white px-4 py-2 rounded-lg font-bold">
                        Sem 5
                    </div>
                    <div class="text-left">
                        <h3 class="text-lg font-bold text-gray-800">Semester 5 <span class="text-[#D4AF37]">(Aktif)</span></h3>
                        <p class="text-sm text-gray-600">8 Mata Kuliah - 20 SKS</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-semibold">
                        Sedang Berjalan
                    </span>
                    <svg class="w-6 h-6 text-gray-600 transition-transform" :class="openSemester === 5 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
            <div x-show="openSemester === 5" x-collapse class="border-t">
                <div class="p-6">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Kode MK</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Mata Kuliah</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">SKS</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Jenis</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-501</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Ulumul Qur'an</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Sedang Diambil</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-502</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Fiqih Muamalah</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Sedang Diambil</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-503</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Tafsir Tarbawi</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Sedang Diambil</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-504</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Ushul Fiqh</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Sedang Diambil</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-505</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Metodologi Penelitian</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Sedang Diambil</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-506</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Psikologi Pendidikan</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Sedang Diambil</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-507</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Sejarah Peradaban Islam</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Wajib</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Sedang Diambil</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-508</td>
                                <td class="px-4 py-3 text-sm text-gray-800">Praktikum Microteaching</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">Praktikum</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">Sedang Diambil</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Note: Semester 2, 3, 4, 6, 7, 8 would be similar to Semester 1 structure -->
        <div class="text-center py-4">
            <p class="text-sm text-gray-500">Semester 2, 3, 4, 6, 7, dan 8 mengikuti struktur yang sama...</p>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            فَإِذَا عَزَمْتَ فَتَوَكَّلْ عَلَى اللَّهِ
        </p>
        <p class="text-gray-600 italic text-sm">
            Apabila engkau telah membulatkan tekad, maka bertawakallah kepada Allah
        </p>
        <p class="text-xs text-gray-500 mt-1">(QS. Ali Imran: 159)</p>
    </div>
</div>
@endsection
