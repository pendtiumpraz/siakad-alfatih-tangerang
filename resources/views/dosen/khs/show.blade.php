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
            <button onclick="downloadPDF()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Download PDF</span>
            </button>
            <button onclick="printWithWarning()" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors font-semibold flex items-center space-x-2">
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
                <div class="space-y-1">
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">A (85-100)</span>
                        <span class="text-xs font-semibold">= 4.0</span>
                    </div>
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">A- (80-84)</span>
                        <span class="text-xs font-semibold">= 3.7</span>
                    </div>
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">B+ (75-79)</span>
                        <span class="text-xs font-semibold">= 3.3</span>
                    </div>
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">B (70-74)</span>
                        <span class="text-xs font-semibold">= 3.0</span>
                    </div>
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">B- (65-69)</span>
                        <span class="text-xs font-semibold">= 2.7</span>
                    </div>
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">C+ (60-64)</span>
                        <span class="text-xs font-semibold">= 2.3</span>
                    </div>
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">C (55-59)</span>
                        <span class="text-xs font-semibold">= 2.0</span>
                    </div>
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">C- (50-54)</span>
                        <span class="text-xs font-semibold">= 1.7</span>
                    </div>
                    <div class="flex items-center justify-between p-1 border-b border-gray-200">
                        <span class="text-xs text-gray-700">D (45-49)</span>
                        <span class="text-xs font-semibold">= 1.0</span>
                    </div>
                    <div class="flex items-center justify-between p-1">
                        <span class="text-xs text-gray-700">E (0-44)</span>
                        <span class="text-xs font-semibold">= 0.0</span>
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
        /* Hide non-printable elements */
        .sidebar, header, button, a, nav, .no-print, .islamic-divider {
            display: none !important;
        }

        /* Page setup - PORTRAIT orientation with optimized margins */
        @page {
            size: A4 portrait;
            margin: 0.8cm 1cm;
        }

        /* Body setup with readable font */
        body {
            background: white !important;
            margin: 0;
            padding: 0;
            font-size: 10pt !important;
            line-height: 1.3 !important;
            color: #000 !important;
        }

        /* Main wrapper - clean and compact */
        .bg-white.rounded-lg {
            box-shadow: none !important;
            border: 1px solid #333 !important;
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0.12cm !important;
        }

        .space-y-6, main, .container, .max-w-7xl {
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Header - Compact */
        .text-center.mb-8 {
            margin-bottom: 0.12cm !important;
            padding-bottom: 0.06cm !important;
            border-bottom: 2px solid #000 !important;
        }

        .text-center.mb-8 .flex {
            display: none !important; /* Hide decorative elements */
        }

        h1.text-3xl {
            font-size: 14pt !important;
            margin: 0 0 2px 0 !important;
            font-weight: bold !important;
        }

        h2.text-xl {
            font-size: 11pt !important;
            margin: 4px 0 2px 0 !important;
            font-weight: bold !important;
        }

        .text-center p.text-lg {
            font-size: 9pt !important;
            margin: 2px 0 !important;
        }

        .text-center p.text-sm {
            font-size: 8pt !important;
            margin: 1px 0 !important;
        }

        /* Student info card - 2 columns */
        .grid.grid-cols-2.gap-6 {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 0.12cm !important;
            margin: 0.06cm 0 !important;
            padding: 0.06cm !important;
            border: 1px solid #999 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: white !important;
        }

        .grid.grid-cols-2 .space-y-3 {
            margin: 0 !important;
        }

        .grid.grid-cols-2 .flex {
            margin: 2px 0 !important;
        }

        .grid.grid-cols-2 .flex .w-40 {
            width: 110px !important;
            font-size: 9pt !important;
        }

        .grid.grid-cols-2 .flex span {
            font-size: 9pt !important;
        }

        /* Table wrapper */
        .overflow-x-auto {
            overflow: visible !important;
        }

        /* Table - Optimized */
        table {
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 0.06cm 0 !important;
            font-size: 9pt !important;
        }

        thead {
            background: #e5e7eb !important;
        }

        th {
            padding: 1px 2px !important;
            font-size: 7pt !important;
            font-weight: bold !important;
            border: 1px solid #666 !important;
            text-align: center !important;
        }

        td {
            padding: 1px 2px !important;
            font-size: 9pt !important;
            border: 1px solid #999 !important;
        }

        /* Narrow columns for numbers */
        th:nth-child(1), td:nth-child(1) { width: 25px !important; } /* No */
        th:nth-child(2), td:nth-child(2) { width: 55px !important; } /* Kode */
        th:nth-child(4), td:nth-child(4) { width: 35px !important; } /* SKS */
        th:nth-child(5), td:nth-child(5) { width: 40px !important; } /* Nilai */
        th:nth-child(6), td:nth-child(6) { width: 40px !important; } /* Grade */
        th:nth-child(7), td:nth-child(7) { width: 45px !important; } /* Bobot */

        tr.bg-gradient-to-r td {
            background: #f3f4f6 !important;
            font-weight: bold !important;
        }

        /* Islamic card */
        .bg-white.border {
            padding: 0.08cm !important;
            margin: 0.06cm 0 !important;
            border: 1px solid #999 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            background: white !important;
        }

        /* Summary grid */
        .grid.grid-cols-1,
        .grid.mt-6 {
            display: grid !important;
            grid-template-columns: 1fr 1fr !important;
            gap: 0.12cm !important;
            margin: 0.06cm 0 !important;
        }

        /* Card titles */
        .bg-white.border h3,
        .bg-white.border h4 {
            font-size: 8pt !important;
            margin: 0 0 1px 0 !important;
            padding: 0 0 1px 0 !important;
            border-bottom: 1px solid #999 !important;
        }

        /* Summary items */
        .space-y-4 > div,
        .space-y-2 > div {
            margin: 0.5px 0 !important;
            padding: 1.5px !important;
            border: 1px solid #ddd !important;
            border-radius: 0 !important;
        }

        .space-y-4 .text-sm,
        .space-y-2 .text-sm {
            font-size: 7pt !important;
        }

        .space-y-4 .text-2xl,
        .space-y-2 .text-2xl {
            font-size: 9pt !important;
        }

        .space-y-4 .text-3xl,
        .space-y-2 .text-3xl {
            font-size: 10pt !important;
        }

        .space-y-4 .text-xl,
        .space-y-2 .text-xl {
            font-size: 9pt !important;
        }

        /* Footer - Islamic */
        .mt-8.pt-6.text-center {
            display: none !important;
        }

        /* Remove all decorative gradients and colors */
        .bg-gradient-to-r, .bg-gradient-to-br, .bg-gradient-to-br {
            background: white !important;
        }

        .shadow, .shadow-md, .shadow-lg {
            box-shadow: none !important;
        }

        svg {
            width: 16px !important;
            height: 16px !important;
        }

        /* Status badges */
        .px-3.py-1 {
            padding: 1px 3px !important;
            font-size: 8pt !important;
            border: 1px solid #ccc !important;
            background: white !important;
        }

        /* Prevent page breaks */
        table, .grid {
            page-break-inside: avoid !important;
        }

        thead {
            display: table-header-group !important;
        }

        tr {
            page-break-inside: avoid !important;
        }

        /* Clean up spacing */
        .mb-6, .mb-8, .mt-6, .mt-8 {
            margin: 0.06cm 0 !important;
        }

        .pb-6 {
            padding-bottom: 0.06cm !important;
        }

        .pt-4, .pt-6 {
            padding-top: 0.06cm !important;
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function printWithWarning() {
    window.print();
}

function downloadPDF() {
    const element = document.querySelector('.bg-white.rounded-lg.shadow-lg');
    const nim = '2301001';
    const semester = '3';
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
        body.generating-pdf nav {
            display: none !important;
        }
        body.generating-pdf .bg-white.rounded-lg {
            box-shadow: none !important;
            border: 1px solid #333 !important;
            border-radius: 0 !important;
            margin: 0 !important;
            padding: 0.12cm !important;
        }
        body.generating-pdf .text-center.mb-8 {
            margin-bottom: 0.12cm !important;
            padding-bottom: 0.06cm !important;
            border-bottom: 2px solid #000 !important;
        }
        body.generating-pdf .text-center.mb-8 .flex {
            display: none !important;
        }
        body.generating-pdf h1.text-3xl {
            font-size: 14pt !important;
            margin: 0 0 2px 0 !important;
        }
        body.generating-pdf h2.text-xl {
            font-size: 11pt !important;
            margin: 4px 0 2px 0 !important;
        }
        body.generating-pdf .grid.grid-cols-2 {
            gap: 0.12cm !important;
            margin: 0.06cm 0 !important;
            padding: 0.06cm !important;
            border: 1px solid #999 !important;
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
        body.generating-pdf td span,
        body.generating-pdf td .px-3 {
            padding: 1px 3px !important;
            font-size: 8pt !important;
        }
        body.generating-pdf .bg-white.border {
            padding: 0.08cm !important;
            margin: 0.06cm 0 !important;
            border: 1px solid #999 !important;
            box-shadow: none !important;
            background: white !important;
        }
        body.generating-pdf .bg-white.border h3,
        body.generating-pdf .bg-white.border h4 {
            font-size: 8pt !important;
            margin: 0 0 1px 0 !important;
        }
        body.generating-pdf .space-y-4 {
            margin: 0 !important;
        }
        body.generating-pdf .space-y-4 > div,
        body.generating-pdf .space-y-2 > div {
            margin: 0.5px 0 !important;
            padding: 1.5px !important;
            border-radius: 0 !important;
            background: white !important;
            border: 1px solid #ddd !important;
        }
        body.generating-pdf .space-y-4 .text-sm,
        body.generating-pdf .space-y-2 .text-sm {
            font-size: 7pt !important;
        }
        body.generating-pdf .space-y-4 .text-2xl,
        body.generating-pdf .space-y-2 .text-2xl {
            font-size: 9pt !important;
        }
        body.generating-pdf .space-y-4 .text-3xl,
        body.generating-pdf .space-y-2 .text-3xl {
            font-size: 10pt !important;
        }
        body.generating-pdf .space-y-4 .text-xl,
        body.generating-pdf .space-y-2 .text-xl {
            font-size: 9pt !important;
        }
        body.generating-pdf .mt-8.pt-6.border-t-4 {
            display: none !important;
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
