@extends('layouts.mahasiswa')

@section('title', 'Nilai')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-3">
                <svg class="w-8 h-8 text-[#4A7C59]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Nilai</span>
            </h1>
            <p class="text-gray-600 mt-1">Riwayat nilai akademik mahasiswa</p>
        </div>
    </div>

    <div class="islamic-divider"></div>

    <!-- Semester Filter & Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <div class="card-islamic p-6">
            <label class="block text-sm font-semibold text-gray-700 mb-3">Filter Semester:</label>
            <select class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#4A7C59] focus:ring-2 focus:ring-[#4A7C59]/20 transition">
                <option value="all">Semua Semester</option>
                <option value="5" selected>Semester 5 - Genap 2024/2025</option>
                <option value="4">Semester 4 - Ganjil 2024/2025</option>
                <option value="3">Semester 3 - Genap 2023/2024</option>
                <option value="2">Semester 2 - Ganjil 2023/2024</option>
                <option value="1">Semester 1 - Genap 2022/2023</option>
            </select>
        </div>
        <div class="card-islamic p-6 bg-gradient-to-br from-[#D4AF37] to-[#F4E5C3]">
            <p class="text-sm text-gray-700 mb-1">IPK</p>
            <p class="text-4xl font-bold text-white">3.68</p>
            <p class="text-xs text-gray-700 mt-2">Indeks Prestasi Kumulatif</p>
        </div>
        <div class="card-islamic p-6 bg-gradient-to-br from-[#4A7C59] to-[#5a9c6f] text-white">
            <p class="text-sm opacity-90 mb-1">Total SKS Lulus</p>
            <p class="text-4xl font-bold">92</p>
            <p class="text-xs opacity-90 mt-2">Dari 144 SKS total</p>
        </div>
        <div class="card-islamic p-6 bg-blue-50 border-l-4 border-blue-500">
            <p class="text-sm text-gray-600 mb-1">Status Akademik</p>
            <p class="text-2xl font-bold text-blue-800">Aktif</p>
            <p class="text-xs text-gray-600 mt-2">Semester 5</p>
        </div>
    </div>

    <!-- Semester 5 - Current -->
    <div class="card-islamic p-6">
        <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-[#F4E5C3]">
            <h3 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span>Semester 5 - Genap 2024/2025</span>
            </h3>
            <a href="{{ route('mahasiswa.nilai.show', 5) }}" class="text-sm text-[#4A7C59] hover:text-[#D4AF37] font-semibold">
                Lihat Detail
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-[#4A7C59] to-[#5a9c6f] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Kode MK</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold">Mata Kuliah</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">SKS</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Tugas</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">UTS</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">UAS</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Nilai Akhir</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Grade</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-501</td>
                        <td class="px-4 py-3 text-sm text-gray-800">Ulumul Qur'an</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">85</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">88</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">90</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-800">88</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-700 text-white rounded-full text-sm font-bold">A</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-502</td>
                        <td class="px-4 py-3 text-sm text-gray-800">Fiqih Muamalah</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">82</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">80</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">85</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-800">82</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-600 text-white rounded-full text-sm font-bold">AB</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-503</td>
                        <td class="px-4 py-3 text-sm text-gray-800">Tafsir Tarbawi</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">78</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">75</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">80</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-800">78</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-500 text-white rounded-full text-sm font-bold">B</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-504</td>
                        <td class="px-4 py-3 text-sm text-gray-800">Ushul Fiqh</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">80</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">82</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">84</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-800">82</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-600 text-white rounded-full text-sm font-bold">AB</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-505</td>
                        <td class="px-4 py-3 text-sm text-gray-800">Metodologi Penelitian</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">3</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">85</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">87</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">86</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-800">86</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-700 text-white rounded-full text-sm font-bold">A</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-506</td>
                        <td class="px-4 py-3 text-sm text-gray-800">Psikologi Pendidikan</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">75</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">78</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">80</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-800">78</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-500 text-white rounded-full text-sm font-bold">B</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-507</td>
                        <td class="px-4 py-3 text-sm text-gray-800">Sejarah Peradaban Islam</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">80</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">82</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">85</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-800">82</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-600 text-white rounded-full text-sm font-bold">AB</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">PAI-508</td>
                        <td class="px-4 py-3 text-sm text-gray-800">Praktikum Microteaching</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">2</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">90</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">88</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">92</td>
                        <td class="px-4 py-3 text-center text-sm font-bold text-gray-800">90</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-700 text-white rounded-full text-sm font-bold">A</span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Lulus</span>
                        </td>
                    </tr>
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="2" class="px-4 py-3 text-sm text-gray-800">Total / Rata-rata</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">20</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">82</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">83</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">85</td>
                        <td class="px-4 py-3 text-center text-sm text-gray-800">83</td>
                        <td colspan="2" class="px-4 py-3 text-center">
                            <span class="text-[#D4AF37] font-bold text-lg">IP: 3.75</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grade Legend -->
    <div class="card-islamic p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center space-x-2">
            <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>Keterangan Grade</span>
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="inline-block px-4 py-2 bg-green-700 text-white rounded-full font-bold mb-2">A</div>
                <p class="text-xs text-gray-600">85 - 100</p>
                <p class="text-xs text-gray-500 mt-1">Bobot: 4.0</p>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="inline-block px-4 py-2 bg-green-600 text-white rounded-full font-bold mb-2">AB</div>
                <p class="text-xs text-gray-600">80 - 84</p>
                <p class="text-xs text-gray-500 mt-1">Bobot: 3.5</p>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="inline-block px-4 py-2 bg-green-500 text-white rounded-full font-bold mb-2">B</div>
                <p class="text-xs text-gray-600">75 - 79</p>
                <p class="text-xs text-gray-500 mt-1">Bobot: 3.0</p>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="inline-block px-4 py-2 bg-yellow-500 text-white rounded-full font-bold mb-2">BC</div>
                <p class="text-xs text-gray-600">70 - 74</p>
                <p class="text-xs text-gray-500 mt-1">Bobot: 2.5</p>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="inline-block px-4 py-2 bg-orange-500 text-white rounded-full font-bold mb-2">C</div>
                <p class="text-xs text-gray-600">65 - 69</p>
                <p class="text-xs text-gray-500 mt-1">Bobot: 2.0</p>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="inline-block px-4 py-2 bg-red-600 text-white rounded-full font-bold mb-2">D</div>
                <p class="text-xs text-gray-600">60 - 64</p>
                <p class="text-xs text-gray-500 mt-1">Bobot: 1.0</p>
            </div>
            <div class="text-center p-3 bg-gray-50 rounded-lg">
                <div class="inline-block px-4 py-2 bg-red-700 text-white rounded-full font-bold mb-2">E</div>
                <p class="text-xs text-gray-600">0 - 59</p>
                <p class="text-xs text-gray-500 mt-1">Bobot: 0.0</p>
            </div>
        </div>
    </div>

    <!-- Islamic Quote -->
    <div class="card-islamic p-6 text-center islamic-pattern">
        <svg class="w-10 h-10 text-[#D4AF37] mx-auto mb-3" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
        </svg>
        <p class="text-lg text-[#4A7C59] font-semibold mb-2">
            وَقُل رَّبِّ زِدْنِي عِلْمًا
        </p>
        <p class="text-gray-600 italic text-sm">
            Dan katakanlah, "Ya Tuhanku, tambahkanlah ilmu kepadaku"
        </p>
        <p class="text-xs text-gray-500 mt-1">(QS. Taha: 114)</p>
    </div>
</div>
@endsection
