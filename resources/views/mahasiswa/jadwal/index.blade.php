@extends('layouts.mahasiswa')

@section('title', 'Jadwal Kuliah')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Jadwal Kuliah</span>
            </h1>
            <p class="text-gray-600 mt-1">Jadwal perkuliahan semester aktif</p>
        </div>
        <button
            onclick="window.print()"
            class="bg-[#D4AF37] hover:bg-[#c49d2f] text-white px-6 py-3 rounded-lg font-semibold transition transform hover:scale-105 flex items-center space-x-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
            </svg>
            <span>Print</span>
        </button>
    </div>

    <div class="islamic-divider"></div>

    <!-- Semester Filter -->
    <div class="card-islamic p-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4">
                <label class="text-sm font-semibold text-gray-700">Semester:</label>
                <select class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition">
                    <option value="5" selected>Semester 5 - Genap 2024/2025</option>
                    <option value="4">Semester 4 - Ganjil 2024/2025</option>
                    <option value="3">Semester 3 - Genap 2023/2024</option>
                    <option value="2">Semester 2 - Ganjil 2023/2024</option>
                    <option value="1">Semester 1 - Genap 2022/2023</option>
                </select>
            </div>
            <div class="flex items-center space-x-2 text-sm">
                <div class="flex items-center space-x-1">
                    <div class="w-4 h-4 bg-blue-100 border border-blue-300 rounded"></div>
                    <span class="text-gray-600">Teori</span>
                </div>
                <div class="flex items-center space-x-1 ml-4">
                    <div class="w-4 h-4 bg-green-100 border border-green-300 rounded"></div>
                    <span class="text-gray-600">Praktikum</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Schedule Table -->
    <div class="card-islamic p-6 overflow-x-auto">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center space-x-2">
            <svg class="w-6 h-6 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Jadwal Mingguan - Semester 5</span>
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-[#4A7C59] to-[#5a9c6f] text-white">
                        <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Waktu</th>
                        <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Senin</th>
                        <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Selasa</th>
                        <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Rabu</th>
                        <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Kamis</th>
                        <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Jumat</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- 08:00 - 09:40 -->
                    <tr>
                        <td class="border border-gray-300 px-4 py-3 bg-gray-50 font-semibold text-sm">08:00 - 09:40</td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('mahasiswa.jadwal.show', 1) }}" class="block bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 p-3 rounded transition cursor-pointer">
                                <p class="font-semibold text-gray-800 text-sm">Ulumul Qur'an</p>
                                <p class="text-xs text-gray-600 mt-1">A-201</p>
                                <p class="text-xs text-gray-500">Dr. H. Ahmad Fauzi</p>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('mahasiswa.jadwal.show', 2) }}" class="block bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 p-3 rounded transition cursor-pointer">
                                <p class="font-semibold text-gray-800 text-sm">Tafsir Tarbawi</p>
                                <p class="text-xs text-gray-600 mt-1">A-105</p>
                                <p class="text-xs text-gray-500">Prof. Dr. Muhammad Y.</p>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                    </tr>

                    <!-- 10:00 - 11:40 -->
                    <tr>
                        <td class="border border-gray-300 px-4 py-3 bg-gray-50 font-semibold text-sm">10:00 - 11:40</td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('mahasiswa.jadwal.show', 3) }}" class="block bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 p-3 rounded transition cursor-pointer">
                                <p class="font-semibold text-gray-800 text-sm">Fiqih Muamalah</p>
                                <p class="text-xs text-gray-600 mt-1">A-103</p>
                                <p class="text-xs text-gray-500">Dr. Hj. Siti Aminah</p>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('mahasiswa.jadwal.show', 4) }}" class="block bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 p-3 rounded transition cursor-pointer">
                                <p class="font-semibold text-gray-800 text-sm">Ushul Fiqh</p>
                                <p class="text-xs text-gray-600 mt-1">B-201</p>
                                <p class="text-xs text-gray-500">Dr. H. Abdullah M.</p>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                    </tr>

                    <!-- 13:00 - 14:40 -->
                    <tr>
                        <td class="border border-gray-300 px-4 py-3 bg-gray-50 font-semibold text-sm">13:00 - 14:40</td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('mahasiswa.jadwal.show', 5) }}" class="block bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 p-3 rounded transition cursor-pointer">
                                <p class="font-semibold text-gray-800 text-sm">Metodologi Penelitian</p>
                                <p class="text-xs text-gray-600 mt-1">B-105</p>
                                <p class="text-xs text-gray-500">Prof. Dr. M. Yasir</p>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('mahasiswa.jadwal.show', 6) }}" class="block bg-green-50 hover:bg-green-100 border-l-4 border-green-500 p-3 rounded transition cursor-pointer">
                                <p class="font-semibold text-gray-800 text-sm">Praktikum Microteaching</p>
                                <p class="text-xs text-gray-600 mt-1">Lab-301</p>
                                <p class="text-xs text-gray-500">Dr. Hj. Fatimah S.</p>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                    </tr>

                    <!-- 15:00 - 16:40 -->
                    <tr>
                        <td class="border border-gray-300 px-4 py-3 bg-gray-50 font-semibold text-sm">15:00 - 16:40</td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('mahasiswa.jadwal.show', 7) }}" class="block bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 p-3 rounded transition cursor-pointer">
                                <p class="font-semibold text-gray-800 text-sm">Psikologi Pendidikan</p>
                                <p class="text-xs text-gray-600 mt-1">A-202</p>
                                <p class="text-xs text-gray-500">Dr. Ahmad Khoirul</p>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                        <td class="border border-gray-300 p-2">
                            <a href="{{ route('mahasiswa.jadwal.show', 8) }}" class="block bg-blue-50 hover:bg-blue-100 border-l-4 border-blue-500 p-3 rounded transition cursor-pointer">
                                <p class="font-semibold text-gray-800 text-sm">Sejarah Peradaban Islam</p>
                                <p class="text-xs text-gray-600 mt-1">B-103</p>
                                <p class="text-xs text-gray-500">Dr. H. Usman Ali</p>
                            </a>
                        </td>
                        <td class="border border-gray-300 p-2 bg-gray-50"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                <p class="text-sm text-gray-600">Total Mata Kuliah</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">8 MK</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                <p class="text-sm text-gray-600">Total SKS</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">20 SKS</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
                <p class="text-sm text-gray-600">Jam Kuliah / Minggu</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">16 Jam</p>
            </div>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            إِنَّ مَعَ الْعُسْرِ يُسْرًا
        </p>
        <p class="text-gray-600 italic text-sm">
            Sesungguhnya bersama kesulitan ada kemudahan
        </p>
        <p class="text-xs text-gray-500 mt-1">(QS. Al-Insyirah: 6)</p>
    </div>
</div>

<style>
    @media print {
        .sidebar, header, button, .islamic-quote { display: none !important; }
        body { background: white !important; }
    }
</style>
@endsection
