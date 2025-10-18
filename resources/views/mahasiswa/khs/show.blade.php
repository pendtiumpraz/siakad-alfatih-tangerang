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
            <p>Grade: A (85-100) | A- (80-84) | B+ (75-79) | B (70-74) | B- (65-69) | C+ (60-64) | C (55-59) | C- (50-54) | D (45-49) | E (0-44)</p>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Hide non-printable elements - ENHANCED */
        aside, .sidebar, header, nav, button, a[href], .no-print, .islamic-divider,
        .flex.items-center.justify-between:first-child,
        [x-data] {
            display: none !important;
            visibility: hidden !important;
        }

        /* Force full width without sidebar */
        body, html {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            overflow: visible !important;
        }

        main {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        /* Page setup - PORTRAIT orientation with optimized margins */
        @page {
            size: A4 portrait;
            margin: 0.8cm 1cm;
        }

        /* Body setup with readable font */
        body {
            font-size: 10pt !important;
            line-height: 1.3 !important;
            color: #000 !important;
        }

        /* Main wrapper - clean and compact */
        #khs-document {
            box-shadow: none !important;
            border: 1px solid #333 !important;
            margin: 0 !important;
            padding: 0.4cm !important;
        }

        .space-y-6, main, .container, .max-w-7xl {
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Header - Compact single line */
        .text-center.mb-6 {
            margin-bottom: 0.3cm !important;
            padding-bottom: 0.2cm !important;
            border-bottom: 2px solid #000 !important;
        }

        .text-center.mb-6 .flex {
            display: none !important; /* Hide decorative SVG */
        }

        h2.text-3xl {
            font-size: 14pt !important;
            margin: 0 0 2px 0 !important;
            font-weight: bold !important;
        }

        h3.text-2xl {
            font-size: 12pt !important;
            margin: 4px 0 2px 0 !important;
            font-weight: bold !important;
        }

        p.text-sm, p.text-gray-600 {
            font-size: 9pt !important;
            margin: 2px 0 !important;
        }

        /* Student info - 2 columns compact */
        .grid.grid-cols-2.gap-6 {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 0.3cm !important;
            margin: 0.2cm 0 !important;
            padding: 0.2cm 0 !important;
            border-bottom: 1px solid #999 !important;
        }

        .grid.grid-cols-2 .space-y-2 {
            margin: 0 !important;
        }

        .grid.grid-cols-2 .flex {
            margin: 2px 0 !important;
        }

        .grid.grid-cols-2 .flex .w-40 {
            width: 120px !important;
            font-size: 9pt !important;
        }

        .grid.grid-cols-2 .flex span {
            font-size: 9pt !important;
        }

        /* Table - Optimized columns */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 0.2cm 0 !important;
            font-size: 9pt !important;
        }

        thead {
            background: #e5e7eb !important;
        }

        th {
            padding: 3px 4px !important;
            font-size: 8pt !important;
            font-weight: bold !important;
            border: 1px solid #666 !important;
            text-align: center !important;
        }

        td {
            padding: 2px 4px !important;
            font-size: 9pt !important;
            border: 1px solid #999 !important;
        }

        /* Narrow columns for numbers */
        th:nth-child(1), td:nth-child(1) { width: 25px !important; } /* No */
        th:nth-child(2), td:nth-child(2) { width: 60px !important; } /* Kode */
        th:nth-child(4), td:nth-child(4) { width: 35px !important; } /* SKS */
        th:nth-child(5), td:nth-child(5) { width: 40px !important; } /* Nilai */
        th:nth-child(6), td:nth-child(6) { width: 40px !important; } /* Grade */
        th:nth-child(7), td:nth-child(7) { width: 45px !important; } /* Bobot */
        th:nth-child(8), td:nth-child(8) { width: 60px !important; } /* SKSxBobot */
        th:nth-child(9), td:nth-child(9) { width: 30px !important; } /* Ket */

        tr.bg-gray-100 td {
            background: #f3f4f6 !important;
            font-weight: bold !important;
        }

        /* Summary cards - Inline grid */
        .grid.grid-cols-1.md\\:grid-cols-2.gap-6 {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 0.3cm !important;
            margin: 0.2cm 0 !important;
        }

        .card-islamic {
            padding: 0.2cm !important;
            border: 1px solid #999 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: white !important;
        }

        .card-islamic h4 {
            font-size: 10pt !important;
            margin: 0 0 4px 0 !important;
            padding: 0 0 3px 0 !important;
            border-bottom: 1px solid #999 !important;
        }

        .card-islamic .space-y-3 > div {
            margin: 3px 0 !important;
            padding: 2px !important;
        }

        .card-islamic .text-sm {
            font-size: 8pt !important;
        }

        .card-islamic .text-lg, .card-islamic .text-3xl {
            font-size: 11pt !important;
        }

        /* Status semester - Compact */
        .bg-green-50 {
            background: white !important;
            border: 1px solid #999 !important;
            border-radius: 0 !important;
            padding: 0.2cm !important;
            margin: 0.2cm 0 !important;
        }

        .bg-green-50 p {
            font-size: 9pt !important;
            margin: 2px 0 !important;
        }

        .bg-green-600 {
            background: white !important;
            color: #000 !important;
            border: 1px solid #000 !important;
            padding: 4px 8px !important;
            font-size: 10pt !important;
        }

        /* Footer - Simplified */
        .mt-8.pt-6 {
            margin-top: 0.3cm !important;
            padding-top: 0.2cm !important;
            border-top: 1px solid #999 !important;
        }

        .mt-8.pt-6 .flex {
            display: flex !important;
            justify-content: space-between !important;
        }

        .mt-8.pt-6 .text-center {
            font-size: 9pt !important;
            margin: 0 !important;
        }

        .mt-8.pt-6 .text-xs {
            font-size: 7pt !important;
            margin-bottom: 30px !important;
        }

        .mt-8.pt-6 .font-bold {
            font-size: 9pt !important;
            border-top: 1px solid #000 !important;
            padding-top: 2px !important;
        }

        .islamic-pattern {
            padding: 0 !important;
            border-radius: 0 !important;
        }

        .islamic-pattern svg {
            width: 20px !important;
            height: 20px !important;
        }

        .islamic-pattern p {
            font-size: 8pt !important;
        }

        /* Keterangan */
        .mt-6.text-xs {
            margin-top: 0.2cm !important;
            font-size: 7pt !important;
            line-height: 1.2 !important;
        }

        .mt-6.text-xs p {
            margin: 1px 0 !important;
            font-size: 7pt !important;
        }

        /* Remove all decorative gradients and colors */
        .bg-gradient-to-r, .bg-gradient-to-br {
            background: white !important;
        }

        .shadow, .shadow-md, .shadow-lg {
            box-shadow: none !important;
        }

        svg {
            width: 16px !important;
            height: 16px !important;
        }

        /* Prevent page breaks in critical sections */
        table, .grid {
            page-break-inside: avoid !important;
        }

        thead {
            display: table-header-group !important;
        }

        tr {
            page-break-inside: avoid !important;
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function printWithWarning() {
    alert('📌 PENTING: Sebelum print, pastikan setting ukuran di Print Dialog diatur ke 80% agar pas 1 lembar!\n\nCara setting:\n1. Di Print Dialog, cari "Scale" atau "Ukuran"\n2. Ubah dari 100% menjadi 80%\n3. Klik Print');
    window.print();
}

function downloadPDF() {
    const element = document.getElementById('khs-document');
    const nim = '{{ auth()->user()->nim ?? "202301010001" }}';
    const semester = '5';
    const filename = `KHS_${nim}_Semester${semester}.pdf`;

    // Show loading
    const btn = event.target.closest('button');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating PDF...';
    btn.disabled = true;

    // Add temporary print class
    document.body.classList.add('generating-pdf');

    // Create temporary style element for print styles
    const printStyles = document.createElement('style');
    printStyles.id = 'temp-print-styles';
    printStyles.textContent = `
        body.generating-pdf {
            background: white !important;
            font-size: 10pt !important;
            line-height: 1.3 !important;
            color: #000 !important;
        }
        body.generating-pdf .sidebar,
        body.generating-pdf header,
        body.generating-pdf button,
        body.generating-pdf a,
        body.generating-pdf nav,
        body.generating-pdf .islamic-divider {
            display: none !important;
        }
        body.generating-pdf #khs-document {
            box-shadow: none !important;
            border: 1px solid #333 !important;
            margin: 0 !important;
            padding: 0.12cm !important;
        }
        body.generating-pdf .text-center.mb-6 {
            margin-bottom: 0.12cm !important;
            padding-bottom: 0.06cm !important;
            border-bottom: 2px solid #000 !important;
        }
        body.generating-pdf .text-center.mb-6 .flex {
            display: none !important;
        }
        body.generating-pdf h2.text-3xl {
            font-size: 14pt !important;
            margin: 0 0 2px 0 !important;
        }
        body.generating-pdf h3.text-2xl {
            font-size: 12pt !important;
            margin: 4px 0 2px 0 !important;
        }
        body.generating-pdf p.text-sm, body.generating-pdf p.text-gray-600 {
            font-size: 9pt !important;
            margin: 2px 0 !important;
        }
        body.generating-pdf .grid.grid-cols-2 {
            gap: 0.12cm !important;
            margin: 0.06cm 0 !important;
            padding: 0.06cm 0 !important;
            border-bottom: 1px solid #999 !important;
        }
        body.generating-pdf .grid.grid-cols-2 .flex {
            margin: 0.5px 0 !important;
        }
        body.generating-pdf .grid.grid-cols-2 .flex .w-40 {
            width: 120px !important;
            font-size: 9pt !important;
        }
        body.generating-pdf .grid.grid-cols-2 .flex span {
            font-size: 9pt !important;
        }
        body.generating-pdf table {
            width: 100% !important;
            font-size: 9pt !important;
            margin: 0.06cm 0 !important;
        }
        body.generating-pdf th {
            padding: 1px 2px !important;
            font-size: 7pt !important;
            border: 1px solid #666 !important;
        }
        body.generating-pdf td {
            padding: 1px 2px !important;
            font-size: 9pt !important;
            border: 1px solid #999 !important;
        }
        body.generating-pdf td span.font-bold,
        body.generating-pdf td span {
            padding: 1px 3px !important;
            font-size: 8pt !important;
        }
        body.generating-pdf .card-islamic {
            padding: 0.08cm !important;
            border: 1px solid #999 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: white !important;
        }
        body.generating-pdf .card-islamic h4 {
            font-size: 8pt !important;
            margin: 0 0 1px 0 !important;
            padding: 0 0 0.5px 0 !important;
            border-bottom: 1px solid #999 !important;
        }
        body.generating-pdf .card-islamic .space-y-3 {
            margin: 0 !important;
        }
        body.generating-pdf .card-islamic .space-y-3 > div {
            margin: 0.5px 0 !important;
            padding: 1.5px !important;
            border-radius: 0 !important;
            background: white !important;
            border: 1px solid #ddd !important;
        }
        body.generating-pdf .card-islamic .text-sm {
            font-size: 7pt !important;
        }
        body.generating-pdf .card-islamic .text-lg {
            font-size: 9pt !important;
        }
        body.generating-pdf .card-islamic .text-3xl {
            font-size: 10pt !important;
        }
        body.generating-pdf .card-islamic .p-3 {
            padding: 3px !important;
        }
        body.generating-pdf .bg-green-50 {
            background: white !important;
            border: 1px solid #999 !important;
            padding: 0.06cm !important;
            margin: 0.06cm 0 !important;
        }
        body.generating-pdf .mt-8.pt-6.border-t-2 {
            margin-top: 0.12cm !important;
            padding-top: 0.06cm !important;
            border-top: 1px solid #999 !important;
        }
        body.generating-pdf .mt-8.pt-6.border-t-4 {
            display: none !important;
        }
        body.generating-pdf .mt-8.pt-6 .text-center {
            font-size: 9pt !important;
        }
        body.generating-pdf .bg-gradient-to-r, body.generating-pdf .bg-gradient-to-br {
            background: white !important;
        }
        body.generating-pdf svg {
            width: 16px !important;
            height: 16px !important;
        }
    `;
    document.head.appendChild(printStyles);

    // Configure PDF options
    const opt = {
        margin: [8, 8, 8, 8],
        filename: filename,
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: {
            scale: 2,
            useCORS: true,
            letterRendering: true,
            logging: false,
            backgroundColor: '#ffffff'
        },
        jsPDF: {
            unit: 'mm',
            format: 'a4',
            orientation: 'portrait'
        }
    };

    // Wait for styles to apply
    setTimeout(() => {
        html2pdf().set(opt).from(element).save().then(() => {
            // Cleanup
            document.body.classList.remove('generating-pdf');
            document.getElementById('temp-print-styles').remove();
            btn.innerHTML = originalText;
            btn.disabled = false;
        }).catch((error) => {
            console.error('Error generating PDF:', error);
            alert('Gagal generate PDF. Silakan coba lagi.');
            document.body.classList.remove('generating-pdf');
            document.getElementById('temp-print-styles').remove();
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }, 200);
}
</script>
@endsection
