@extends('layouts.mahasiswa')

@section('content')
<style>
    /* Override layout for PDF-like view */
    main {
        max-width: 210mm !important;
        margin: 20px auto !important;
        padding: 0 !important;
        background: white;
    }

    body {
        background: #f5f5f5 !important;
    }

    .pdf-wrapper {
        background: white;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        padding: 15px 25px;
        font-family: 'Times New Roman', Times, serif;
        font-size: 9pt;
        line-height: 1.2;
        color: #000;
        position: relative;
    }

    /* Watermark Background Logo - Hidden on web view for elegance */
    .watermark {
        display: none;
    }

    /* Show watermark only on print */
    @media print {
        .watermark {
            display: block;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.25;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            width: 700px;
            height: 700px;
            object-fit: contain;
        }
    }

    /* Content wrapper to stay above watermark */
    .content {
        position: relative;
        z-index: 1;
    }

    /* Kop Surat */
    .kop-surat {
        margin-bottom: 10px;
    }

    .kop-content {
        display: table;
        width: 100%;
        margin-bottom: 5px;
    }

    .kop-logo {
        display: table-cell;
        width: 60px;
        vertical-align: top;
    }

    .kop-logo img {
        width: 60px;
        height: 60px;
    }

    .kop-text {
        display: table-cell;
        text-align: center;
        vertical-align: top;
        padding-left: 15px;
    }

    .kop-text h1 {
        font-size: 14pt;
        font-weight: bold;
        color: #2D5F3F;
        margin-bottom: 3px;
        line-height: 1.1;
    }

    .kop-text p {
        font-size: 8pt;
        color: #000;
        margin: 1px 0;
        line-height: 1.3;
    }

    .kop-divider {
        border: 0;
        border-top: 2px solid #2D5F3F;
        margin: 5px 0 10px 0;
    }

    /* Document Title */
    .doc-title {
        text-align: center;
        margin-bottom: 8px;
    }

    .doc-title h2 {
        font-size: 12pt;
        font-weight: bold;
        text-decoration: underline;
        margin-bottom: 3px;
    }

    /* Mahasiswa Info */
    .mhs-info {
        margin-bottom: 8px;
    }

    .mhs-info p, .mhs-info table {
        margin: 2px 0;
        font-size: 9pt;
        line-height: 1.3;
    }

    /* Nilai Table */
    .nilai-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }

    .nilai-table th {
        background-color: #fff !important;
        color: #000 !important;
        padding: 4px 5px;
        text-align: center;
        font-size: 8pt;
        font-weight: bold;
        border: 1px solid #000;
    }

    .nilai-table td {
        padding: 3px 5px;
        border: 1px solid #000;
        font-size: 8pt;
    }

    .text-center {
        text-align: center;
    }

    /* Summary Info */
    .summary-info {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .summary-info table {
        border: none;
    }

    .summary-info td {
        border: none;
        padding: 2px 0;
        font-size: 9pt;
        line-height: 1.4;
    }

    /* Signature */
    .signature {
        margin-top: 15px;
    }

    .signature-box {
        float: right;
        text-align: center;
        width: 180px;
    }

    .signature p {
        margin: 2px 0;
        font-size: 9pt;
    }

    .signature-name {
        font-weight: bold;
        text-decoration: underline;
    }

    /* Action Buttons */
    .action-buttons {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        font-family: Arial, sans-serif;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
    }

    .btn-back {
        background: #6b7280;
    }

    .btn-back:hover {
        background: #4b5563;
    }

    .btn-pdf {
        background: #dc2626;
    }

    .btn-pdf:hover {
        background: #b91c1c;
    }

    .btn-print {
        background: #2563eb;
    }

    .btn-print:hover {
        background: #1d4ed8;
    }

    @media print {
        /* Hide ALL non-print elements */
        .action-buttons,
        nav, aside, header, footer,
        .sidebar, .navbar,
        [class*="sidebar"], [class*="navbar"],
        [class*="nav-"], [id*="sidebar"],
        button:not(.btn-print) {
            display: none !important;
        }

        /* Reset page for print */
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        main {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
            box-shadow: none !important;
        }

        .pdf-wrapper {
            box-shadow: none !important;
            margin: 0 !important;
            padding: 15px 25px !important;
            min-height: auto !important;
        }

        /* Ensure single page print */
        @page {
            size: A4 portrait;
            margin: 0;
        }

        /* Ensure table is visible on print */
        .nilai-table {
            display: table !important;
            width: 100% !important;
            border-collapse: collapse !important;
            page-break-inside: auto !important;
        }

        .nilai-table thead {
            display: table-header-group !important;
        }

        .nilai-table tbody {
            display: table-row-group !important;
        }

        .nilai-table tr {
            display: table-row !important;
            page-break-inside: avoid !important;
        }

        .nilai-table th,
        .nilai-table td {
            display: table-cell !important;
            border: 1px solid #000 !important;
        }

        .nilai-table th {
            background-color: #fff !important;
            color: #000 !important;
            font-weight: bold !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<!-- Action Buttons (hidden on print) -->
<div class="action-buttons">
    <a href="{{ route('mahasiswa.khs.index') }}" class="btn btn-back">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>
    <a href="{{ route('mahasiswa.khs.download-pdf', $khs->id) }}" class="btn btn-pdf">
        <i class="fas fa-file-pdf"></i>
        Download PDF
    </a>
    <button onclick="window.print()" class="btn btn-print">
        <i class="fas fa-print"></i>
        Print KHS
    </button>
</div>

<!-- PDF Wrapper -->
<div class="pdf-wrapper">
    <!-- Watermark -->
    <div class="watermark">
        <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo STAI Al-Fatih">
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="kop-content">
                <div class="kop-logo">
                    <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo STAI Al-Fatih">
                </div>
                <div class="kop-text">
                    <h1>STAI AL FATIH TANGERANG</h1>
                    <p>Jl. Raden Fatah, No. 5, RT. 004/RW. 006, Parung Serab, Kec. Ciledug, Kota</p>
                    <p>Tangerang, Banten, Kode Pos 15153</p>
                    <p>Email: info@staialfatih.or.id, Website: https://staialfatih.or.id</p>
                </div>
            </div>
            <hr class="kop-divider">
        </div>

        <!-- Document Title & Info -->
        <div class="doc-title">
            <h2>KARTU HASIL STUDI (KHS) MAHASISWA</h2>
        </div>

        <!-- Mahasiswa Info (Inline) -->
        <div class="mhs-info">
            <table style="border: none; border-collapse: collapse;">
                <tr>
                    <td style="width: 120px; border: none; padding: 2px 0;">Nama</td>
                    <td style="width: 10px; border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0;">{{ $khs->mahasiswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px 0;">NIM</td>
                    <td style="border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0;">{{ $khs->mahasiswa->nim ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px 0;">Semester</td>
                    <td style="border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0; color: #d97706;">
                        @php
                            // Calculate semester number
                            $tahunAkademik = (int) substr($khs->semester->tahun_akademik, 0, 4);
                            $angkatan = $khs->mahasiswa->angkatan;
                            $yearDiff = $tahunAkademik - $angkatan;
                            $jenisSemester = strpos(strtolower($khs->semester->nama_semester), 'ganjil') !== false ? 'ganjil' : 'genap';
                            $semesterNumber = ($yearDiff * 2) + ($jenisSemester === 'genap' ? 2 : 1);
                            
                            // Convert to roman
                            $romanNumerals = ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                            $roman = $romanNumerals[$semesterNumber] ?? $semesterNumber;
                            
                            // Terbilang
                            $terbilang = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas', 'dua belas'];
                            $terbilangSemester = $terbilang[$semesterNumber] ?? $semesterNumber;
                        @endphp
                        {{ $roman }} ({{ $terbilangSemester }})
                    </td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px 0;">Program Studi</td>
                    <td style="border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0;">{{ $khs->mahasiswa->programStudi->nama_prodi }}</td>
                </tr>
            </table>
        </div>

        <!-- Nilai Table -->
        <table class="nilai-table">
            <thead>
                <tr>
                    <th style="width: 35px;">NO</th>
                    <th style="width: 80px;">KODE</th>
                    <th>MATA KULIAH</th>
                    <th style="width: 45px;">SKS</th>
                    <th style="width: 70px;">NILAI HURUF</th>
                    <th style="width: 70px;">NILAI ANGKA</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalSks = 0;
                    $totalSksLulus = 0;
                @endphp
                @foreach($khs->mahasiswa->nilais->where('semester_id', $khs->semester_id) as $index => $nilai)
                    @php
                        $sks = $nilai->mataKuliah->sks ?? 0;
                        $totalSks += $sks;
                        if ($nilai->status === 'lulus') {
                            $totalSksLulus += $sks;
                        }
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $nilai->mataKuliah->kode_mk }}</td>
                        <td>{{ $nilai->mataKuliah->nama_mk }}</td>
                        <td class="text-center">{{ $sks }}</td>
                        <td class="text-center">{{ str_replace('+', '', $nilai->grade ?? '-') }}</td>
                        <td class="text-center">{{ $nilai->bobot ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Info -->
        <div class="summary-info">
            <table style="width: 100%; border: none; border-collapse: collapse;">
                <tr>
                    <td style="width: 200px; border: none; padding: 2px 0;">Jumlah SKS Semester</td>
                    <td style="width: 10px; border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0;">{{ $totalSks }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px 0;">SKS yang diluluskan</td>
                    <td style="border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0;">{{ $totalSksLulus }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px 0;">Indeks Prestasi Kumulatif (IPK) lalu</td>
                    <td style="border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0;">-</td>
                </tr>
                <tr>
                    <td colspan="3" style="border: none; padding: 8px 0 0 0;"></td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px 0;">Indeks Prestasi (IP)</td>
                    <td style="border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0;">{{ str_replace('.', ',', number_format($khs->ip, 2)) }}</td>
                </tr>
                <tr>
                    <td style="border: none; padding: 2px 0;">Indeks Prestasi Kumulatif (IPK)</td>
                    <td style="border: none; padding: 2px 0;">:</td>
                    <td style="border: none; padding: 2px 0;">{{ str_replace('.', ',', number_format($khs->ipk, 2)) }}</td>
                </tr>
            </table>
        </div>

        <!-- Signature -->
        <div class="signature">
            <div class="signature-box">
                @php
                    $tanggalSekarang = \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY');
                @endphp
                <p style="margin: 2px 0;">Tangerang, {{ $tanggalSekarang }}</p>
                <p style="margin: 2px 0;">PUKET I</p>
                <p style="margin: 2px 0;">Bid. Akademik dan Pengembangan</p>
                <p style="margin: 2px 0;">STAI AL FATIH</p>
                <br><br><br>
                <p class="signature-name" style="margin: 2px 0;">Satrio Purnomo Hidayat, M.Pd.</p>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>
</div>

@endsection
