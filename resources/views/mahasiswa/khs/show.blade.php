@extends('layouts.mahasiswa')

@section('title', 'KHS Semester 5')

@section('content')
<div class="space-y-6">
    <!-- Page Title -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Kartu Hasil Studi</h1>
            <p class="text-gray-600 mt-1">Semester 5 - Genap 2024/2025</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('mahasiswa.khs.index') }}"
               class="text-gray-600 hover:text-gray-800 px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2 border-2 border-gray-300 hover:border-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali</span>
            </a>
            <button
                onclick="window.print()"
                class="bg-[#D4AF37] hover:bg-[#c49d2f] text-white px-6 py-3 rounded-lg font-semibold transition flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Print KHS</span>
            </button>
        </div>
    </div>

    <div class="islamic-divider"></div>

    <!-- Official KHS Document -->
    <div class="card-islamic p-8" id="khs-document">
        <!-- Header -->
        <div class="text-center mb-6 pb-6 border-b-2 border-[#D4AF37]">
            <div class="flex items-center justify-center space-x-4 mb-4">
                <svg class="w-16 h-16 text-[#D4AF37]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                </svg>
                <div>
                    <h2 class="text-3xl font-bold text-[#4A7C59]">STAI AL-FATIH</h2>
                    <p class="text-sm text-gray-600">Sekolah Tinggi Agama Islam Al-Fatih</p>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-gray-800 mt-4">KARTU HASIL STUDI (KHS)</h3>
            <p class="text-gray-600 mt-2">Semester Genap Tahun Akademik 2024/2025</p>
        </div>

        <!-- Student Identity -->
        <div class="grid grid-cols-2 gap-6 mb-6 pb-6 border-b border-gray-300">
            <div class="space-y-2">
                <div class="flex">
                    <span class="w-40 text-sm text-gray-600">NIM</span>
                    <span class="text-sm">: <strong>{{ auth()->user()->nim ?? '202301010001' }}</strong></span>
                </div>
                <div class="flex">
                    <span class="w-40 text-sm text-gray-600">Nama</span>
                    <span class="text-sm">: <strong>{{ auth()->user()->name ?? 'Ahmad Fauzi Ramadhan' }}</strong></span>
                </div>
                <div class="flex">
                    <span class="w-40 text-sm text-gray-600">Program Studi</span>
                    <span class="text-sm">: <strong>Pendidikan Agama Islam</strong></span>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex">
                    <span class="w-40 text-sm text-gray-600">Semester</span>
                    <span class="text-sm">: <strong>5 (Lima)</strong></span>
                </div>
                <div class="flex">
                    <span class="w-40 text-sm text-gray-600">Tahun Akademik</span>
                    <span class="text-sm">: <strong>2024/2025 Genap</strong></span>
                </div>
                <div class="flex">
                    <span class="w-40 text-sm text-gray-600">Status</span>
                    <span class="text-sm">: <strong class="text-green-600">Aktif</strong></span>
                </div>
            </div>
        </div>

        <!-- Grades Table -->
        <div class="mb-6">
            <table class="w-full border-collapse border-2 border-gray-300">
                <thead>
                    <tr class="bg-gradient-to-r from-[#4A7C59] to-[#5a9c6f] text-white">
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-center">No</th>
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-left">Kode MK</th>
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-left">Mata Kuliah</th>
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-center">SKS</th>
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-center">Nilai</th>
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-center">Grade</th>
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-center">Bobot</th>
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-center">SKS x Bobot</th>
                        <th class="border border-gray-300 px-3 py-2 text-sm font-semibold text-center">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">1</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">PAI-501</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">Ulumul Qur'an</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">3</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">88</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="font-bold text-green-700">A</span></td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">4.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">12.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="text-green-600 text-xs">L</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">2</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">PAI-502</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">Fiqih Muamalah</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">3</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">82</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="font-bold text-green-600">AB</span></td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">3.5</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">10.5</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="text-green-600 text-xs">L</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">3</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">PAI-503</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">Tafsir Tarbawi</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">2</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">78</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="font-bold text-green-500">B</span></td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">3.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">6.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="text-green-600 text-xs">L</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">4</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">PAI-504</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">Ushul Fiqh</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">3</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">82</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="font-bold text-green-600">AB</span></td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">3.5</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">10.5</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="text-green-600 text-xs">L</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">5</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">PAI-505</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">Metodologi Penelitian</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">3</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">86</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="font-bold text-green-700">A</span></td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">4.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">12.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="text-green-600 text-xs">L</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">6</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">PAI-506</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">Psikologi Pendidikan</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">2</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">78</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="font-bold text-green-500">B</span></td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">3.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">6.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="text-green-600 text-xs">L</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">7</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">PAI-507</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">Sejarah Peradaban Islam</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">2</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">82</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="font-bold text-green-600">AB</span></td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">3.5</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">7.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="text-green-600 text-xs">L</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">8</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">PAI-508</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm">Praktikum Microteaching</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">2</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">90</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="font-bold text-green-700">A</span></td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">4.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center font-semibold">8.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center"><span class="text-green-600 text-xs">L</span></td>
                    </tr>
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="3" class="border border-gray-300 px-3 py-2 text-sm text-right">JUMLAH</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">20</td>
                        <td colspan="3" class="border border-gray-300 px-3 py-2 text-sm text-center">-</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">72.0</td>
                        <td class="border border-gray-300 px-3 py-2 text-sm text-center">-</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="card-islamic p-6 border-2 border-[#F4E5C3]">
                <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b-2 border-[#D4AF37]">Prestasi Semester Ini</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total SKS Semester</span>
                        <span class="font-bold text-gray-800 text-lg">20 SKS</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total SKS Lulus</span>
                        <span class="font-bold text-green-600 text-lg">20 SKS</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gradient-to-r from-[#D4AF37] to-[#F4E5C3] rounded-lg">
                        <span class="text-sm text-gray-700 font-semibold">IP Semester</span>
                        <span class="font-bold text-white text-3xl">3.75</span>
                    </div>
                </div>
            </div>

            <div class="card-islamic p-6 border-2 border-[#F4E5C3]">
                <h4 class="font-bold text-gray-800 mb-4 pb-2 border-b-2 border-[#D4AF37]">Prestasi Kumulatif</h4>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total SKS Tempuh</span>
                        <span class="font-bold text-gray-800 text-lg">98 SKS</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Total SKS Lulus</span>
                        <span class="font-bold text-green-600 text-lg">92 SKS</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gradient-to-r from-[#4A7C59] to-[#5a9c6f] rounded-lg">
                        <span class="text-sm text-white font-semibold">IPK</span>
                        <span class="font-bold text-white text-3xl">3.68</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Semester -->
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="font-bold text-gray-800">Status Semester:</p>
                    <p class="text-sm text-gray-600 mt-1">Berdasarkan IP Semester 3.75, mahasiswa dinyatakan:</p>
                </div>
                <span class="inline-block px-6 py-3 bg-green-600 text-white rounded-lg font-bold text-xl">
                    LULUS
                </span>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t-2 border-[#D4AF37]">
            <div class="flex justify-between items-end">
                <div class="text-center">
                    <p class="text-xs text-gray-600 mb-16">Mengetahui,</p>
                    <p class="font-bold text-gray-800 border-t-2 border-gray-800 pt-2">Ketua Program Studi</p>
                </div>
                <div class="text-center islamic-pattern p-6 rounded-lg">
                    <svg class="w-12 h-12 text-[#D4AF37] mx-auto mb-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                    </svg>
                    <p class="text-[#4A7C59] font-bold">STAI AL-FATIH</p>
                    <p class="text-xs text-gray-600">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-600 mb-1">Jakarta, {{ date('d F Y') }}</p>
                    <p class="text-xs text-gray-600 mb-16">Dosen Wali</p>
                    <p class="font-bold text-gray-800 border-t-2 border-gray-800 pt-2">Dr. H. Abdullah, M.Pd.I</p>
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="mt-6 text-xs text-gray-600 space-y-1">
            <p><strong>Keterangan:</strong></p>
            <p>L = Lulus | TL = Tidak Lulus | K = Kosong</p>
            <p>Grade: A (85-100) | AB (80-84) | B (75-79) | BC (70-74) | C (65-69) | D (60-64) | E (0-59)</p>
        </div>
    </div>
</div>

<style>
    @media print {
        .sidebar, header, button, a { display: none !important; }
        body { background: white !important; }
        #khs-document { box-shadow: none !important; border: none !important; }
    }
</style>
@endsection
