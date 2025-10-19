<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>KHS - {{ $mahasiswa->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
            padding: 15px;
        }
        .card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
            transform: scale(0.85);
            transform-origin: top center;
        }
        .header-section {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 2px solid #D4AF37;
        }
        .header-section .star-icon {
            width: 64px;
            height: 64px;
            fill: #D4AF37;
            margin: 0 auto 16px;
        }
        .header-section h2 {
            font-size: 30px;
            font-weight: bold;
            color: #4A7C59;
            margin: 0 0 4px 0;
        }
        .header-section .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin: 0 0 16px 0;
        }
        .header-section h3 {
            font-size: 24px;
            font-weight: bold;
            color: #1f2937;
            margin: 16px 0 8px 0;
        }
        .header-section .semester-info {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }
        .student-identity {
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #d1d5db;
        }
        .student-identity table {
            width: 100%;
        }
        .student-identity td {
            width: 50%;
            vertical-align: top;
            padding: 0 8px;
        }
        .info-item {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .info-label {
            display: inline-block;
            width: 160px;
            color: #6b7280;
        }
        .info-value {
            font-weight: bold;
            color: #1f2937;
        }
        .grades-table-wrapper {
            margin-bottom: 16px;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #d1d5db;
        }
        .grades-table thead tr {
            background-color: #4A7C59;
        }
        .grades-table th {
            padding: 8px 12px;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            color: #ffffff;
            border: 1px solid #d1d5db;
        }
        .grades-table th.text-left {
            text-align: left;
        }
        .grades-table td {
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #d1d5db;
        }
        .grades-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .grades-table tbody tr:last-child {
            background-color: #f3f4f6;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        .text-right { text-align: right; }
        .font-semibold { font-weight: 600; }
        .font-bold { font-weight: bold; }
        .grade-a { color: #15803d; font-weight: bold; }
        .grade-ab { color: #16a34a; font-weight: bold; }
        .grade-b { color: #22c55e; font-weight: bold; }
        .grade-bc { color: #3b82f6; font-weight: bold; }
        .grade-c { color: #f59e0b; font-weight: bold; }
        .grade-d { color: #ef4444; font-weight: bold; }
        .grade-e { color: #dc2626; font-weight: bold; }
        .status-lulus { color: #16a34a; font-size: 12px; }
        .status-tidak { color: #ef4444; font-size: 12px; }
        .summary-section {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }
        .summary-section table {
            width: 100%;
        }
        .summary-card {
            width: 48%;
            vertical-align: top;
            padding: 16px;
            background: #ffffff;
            border: 2px solid #F4E5C3;
            border-radius: 8px;
        }
        .summary-card h4 {
            font-weight: bold;
            color: #1f2937;
            margin: 0 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #D4AF37;
            font-size: 14px;
        }
        .summary-item {
            margin-bottom: 12px;
            font-size: 14px;
        }
        .summary-label {
            color: #6b7280;
        }
        .summary-value {
            font-weight: bold;
            color: #1f2937;
            font-size: 18px;
            float: right;
        }
        .summary-value-green {
            color: #16a34a;
        }
        .ip-box {
            padding: 12px;
            border-radius: 8px;
            margin-top: 4px;
            overflow: hidden;
        }
        .ip-box-gold {
            background-color: #D4AF37;
        }
        .ip-box-green {
            background-color: #4A7C59;
        }
        .ip-label {
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
        }
        .ip-value {
            color: #ffffff;
            font-weight: bold;
            font-size: 30px;
            float: right;
        }
        .status-semester {
            margin-bottom: 16px;
            padding: 12px;
            background-color: #f0fdf4;
            border-left: 4px solid #16a34a;
            border-radius: 0 8px 8px 0;
            page-break-inside: avoid;
        }
        .status-semester table {
            width: 100%;
        }
        .status-semester td {
            vertical-align: middle;
        }
        .status-left {
            width: 70%;
        }
        .status-right {
            width: 30%;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 12px 24px;
            background-color: #16a34a;
            color: #ffffff;
            border-radius: 8px;
            font-weight: bold;
            font-size: 20px;
        }
        .footer-section {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 2px solid #D4AF37;
            page-break-inside: avoid;
        }
        .footer-section table {
            width: 100%;
        }
        .footer-col {
            width: 30%;
            text-align: center;
            vertical-align: top;
        }
        .footer-col-center {
            width: 40%;
            text-align: center;
            vertical-align: top;
        }
        .footer-text {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 40px;
        }
        .footer-name {
            font-weight: bold;
            color: #1f2937;
            border-top: 2px solid #1f2937;
            padding-top: 8px;
            font-size: 12px;
        }
        .institution-box {
            padding: 16px 24px;
            background-color: #f0f7f3;
            border-radius: 8px;
            display: inline-block;
        }
        .institution-star {
            width: 48px;
            height: 48px;
            fill: #D4AF37;
            margin: 0 auto 8px;
        }
        .institution-name {
            color: #4A7C59;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 4px;
        }
        .institution-arabic {
            color: #6b7280;
            font-size: 12px;
        }
        .keterangan {
            margin-top: 16px;
            font-size: 11px;
            color: #6b7280;
        }
        .keterangan p {
            margin: 4px 0;
        }
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="card">
        <!-- Header -->
        <div class="header-section">
            <svg class="star-icon" viewBox="0 0 24 24">
                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
            </svg>
            <h2>STAI AL-FATIH</h2>
            <p class="subtitle">Sekolah Tinggi Agama Islam Al-Fatih</p>
            <h3>KARTU HASIL STUDI (KHS)</h3>
            <p class="semester-info">{{ $khs->semester->nama_semester ?? 'Semester' }} Tahun Akademik {{ $khs->semester->tahun_akademik ?? '-' }}</p>
        </div>

        <!-- Student Identity -->
        <div class="student-identity">
            <table>
                <tr>
                    <td>
                        <div class="info-item">
                            <span class="info-label">NIM</span>
                            <span>: <span class="info-value">{{ $mahasiswa->nim }}</span></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nama</span>
                            <span>: <span class="info-value">{{ $mahasiswa->nama_lengkap }}</span></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Program Studi</span>
                            <span>: <span class="info-value">{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</span></span>
                        </div>
                    </td>
                    <td>
                        <div class="info-item">
                            <span class="info-label">Semester</span>
                            <span>: <span class="info-value">{{ $mahasiswa->semester_aktif }}</span></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tahun Akademik</span>
                            <span>: <span class="info-value">{{ $khs->semester->tahun_akademik ?? '-' }} {{ $khs->semester->jenis ?? '-' }}</span></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span>: <span class="info-value" style="color: #16a34a;">{{ ucfirst($mahasiswa->status) }}</span></span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Grades Table -->
        <div class="grades-table-wrapper">
            <table class="grades-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th class="text-left" style="width: 10%;">Kode MK</th>
                        <th class="text-left" style="width: 30%;">Mata Kuliah</th>
                        <th style="width: 7%;">SKS</th>
                        <th style="width: 10%;">Nilai</th>
                        <th style="width: 8%;">Grade</th>
                        <th style="width: 8%;">Bobot</th>
                        <th style="width: 12%;">SKS x Bobot</th>
                        <th style="width: 10%;">Ket</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalSks = 0;
                        $totalBobot = 0;
                    @endphp
                    @forelse($nilais as $index => $nilai)
                    @php
                        $bobot = 0;
                        switch($nilai->grade) {
                            case 'A': $bobot = 4.0; break;
                            case 'A-': $bobot = 3.7; break;
                            case 'B+': $bobot = 3.3; break;
                            case 'B': $bobot = 3.0; break;
                            case 'B-': $bobot = 2.7; break;
                            case 'C+': $bobot = 2.3; break;
                            case 'C': $bobot = 2.0; break;
                            case 'C-': $bobot = 1.7; break;
                            case 'D': $bobot = 1.0; break;
                            case 'E': $bobot = 0.0; break;
                        }
                        $sks = $nilai->mataKuliah->sks ?? 0;
                        $sksBobot = $bobot * $sks;
                        $totalSks += $sks;
                        $totalBobot += $sksBobot;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $nilai->mataKuliah->kode_mk ?? '-' }}</td>
                        <td>{{ $nilai->mataKuliah->nama_mk ?? '-' }}</td>
                        <td class="text-center font-semibold">{{ $sks }}</td>
                        <td class="text-center">{{ number_format($nilai->nilai_akhir ?? 0, 2) }}</td>
                        <td class="text-center">
                            <span class="grade-{{ strtolower($nilai->grade ?? 'e') }}">{{ $nilai->grade ?? '-' }}</span>
                        </td>
                        <td class="text-center">{{ number_format($bobot, 1) }}</td>
                        <td class="text-center font-semibold">{{ number_format($sksBobot, 1) }}</td>
                        <td class="text-center">
                            <span class="{{ $nilai->status == 'lulus' ? 'status-lulus' : 'status-tidak' }}">
                                {{ $nilai->status == 'lulus' ? 'L' : 'TL' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center" style="padding: 20px; color: #999;">
                            Tidak ada data nilai
                        </td>
                    </tr>
                    @endforelse
                    @if($nilais->count() > 0)
                    <tr>
                        <td colspan="3" class="text-right font-bold">JUMLAH</td>
                        <td class="text-center font-bold">{{ $totalSks }}</td>
                        <td colspan="3" class="text-center">-</td>
                        <td class="text-center font-bold">{{ number_format($totalBobot, 1) }}</td>
                        <td class="text-center">-</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <table>
                <tr>
                    <td class="summary-card">
                        <h4>Prestasi Semester Ini</h4>
                        <div class="summary-item clearfix">
                            <span class="summary-label">Total SKS Semester</span>
                            <span class="summary-value">{{ $khs->total_sks ?? 0 }} SKS</span>
                        </div>
                        <div class="summary-item clearfix">
                            <span class="summary-label">Total SKS Lulus</span>
                            <span class="summary-value summary-value-green">{{ $khs->total_sks_lulus ?? 0 }} SKS</span>
                        </div>
                        <div class="ip-box ip-box-gold clearfix">
                            <span class="ip-label">IP Semester</span>
                            <span class="ip-value">{{ number_format($khs->ip_semester ?? 0, 2) }}</span>
                        </div>
                    </td>
                    <td style="width: 4%;"></td>
                    <td class="summary-card">
                        <h4>Prestasi Kumulatif</h4>
                        <div class="summary-item clearfix">
                            <span class="summary-label">Total SKS Tempuh</span>
                            <span class="summary-value">{{ $khs->total_sks ?? 0 }} SKS</span>
                        </div>
                        <div class="summary-item clearfix">
                            <span class="summary-label">Total SKS Lulus</span>
                            <span class="summary-value summary-value-green">{{ $khs->total_sks_lulus ?? 0 }} SKS</span>
                        </div>
                        <div class="ip-box ip-box-green clearfix">
                            <span class="ip-label">IPK</span>
                            <span class="ip-value">{{ number_format($khs->ip_kumulatif ?? 0, 2) }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Status Semester -->
        <div class="status-semester">
            <table>
                <tr>
                    <td class="status-left">
                        <p style="font-weight: bold; color: #1f2937; margin: 0 0 4px 0; font-size: 14px;">Status Semester:</p>
                        <p style="font-size: 14px; color: #6b7280; margin: 0;">Berdasarkan IP Semester {{ number_format($khs->ip_semester ?? 0, 2) }}, mahasiswa dinyatakan:</p>
                    </td>
                    <td class="status-right">
                        <span class="status-badge">LULUS</span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Footer -->
        <div class="footer-section">
            <table>
                <tr>
                    <td class="footer-col">
                        <p class="footer-text">Mengetahui,</p>
                        <p class="footer-name">Ketua Program Studi</p>
                    </td>
                    <td class="footer-col-center">
                        <div class="institution-box">
                            <svg class="institution-star" viewBox="0 0 24 24">
                                <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                            </svg>
                            <p class="institution-name">STAI AL-FATIH</p>
                            <p class="institution-arabic">Bismillahirrahmanirrahim</p>
                        </div>
                    </td>
                    <td class="footer-col">
                        <p class="footer-text">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>Dosen Wali</p>
                        <p class="footer-name">__________________</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Keterangan -->
        <div class="keterangan">
            <p><strong>Keterangan:</strong></p>
            <p>L = Lulus | TL = Tidak Lulus | K = Kosong</p>
            <p>Grade: A (90-100) | A- (85-89) | B+ (80-84) | B (75-79) | B- (70-74) | C+ (65-69) | C (60-64) | C- (55-59) | D (45-54) | E (0-44)</p>
        </div>
    </div>
</body>
</html>
