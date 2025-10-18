<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KHS - {{ $mahasiswa->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
            padding: 15px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #D4AF37;
        }

        .header h1 {
            font-size: 24px;
            font-weight: bold;
            color: #4A7C59;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 11px;
            font-weight: normal;
            color: #666;
            margin-bottom: 15px;
        }

        .header h3 {
            font-size: 18px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 11px;
            color: #666;
        }

        /* Student Info */
        .student-info {
            margin-bottom: 20px;
            padding: 12px;
            background-color: #f9fafb;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .student-info table {
            width: 100%;
            border: none;
        }

        .student-info td {
            border: none;
            padding: 3px;
            vertical-align: top;
        }

        .info-row {
            margin-bottom: 5px;
        }

        .info-label {
            display: inline-block;
            width: 140px;
            font-size: 10pt;
            color: #333;
        }

        .info-colon {
            display: inline-block;
            width: 15px;
            font-size: 10pt;
        }

        .info-value {
            display: inline;
            font-size: 10pt;
            font-weight: bold;
            color: #000;
        }

        /* Table */
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .grades-table th {
            background-color: #4A7C59;
            color: white;
            padding: 8px 4px;
            font-size: 10pt;
            font-weight: bold;
            text-align: center;
            border: 1px solid #2D5F3F;
        }

        .grades-table td {
            padding: 6px 4px;
            font-size: 9.5pt;
            border: 1px solid #ddd;
        }

        .grades-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        /* Grade colors */
        .grade-a {
            font-weight: bold;
            color: #10b981;
        }

        .grade-ab {
            font-weight: bold;
            color: #059669;
        }

        .grade-b {
            font-weight: bold;
            color: #10b981;
        }

        .grade-bc {
            font-weight: bold;
            color: #3b82f6;
        }

        .grade-c {
            font-weight: bold;
            color: #f59e0b;
        }

        .grade-d {
            font-weight: bold;
            color: #ef4444;
        }

        .grade-e {
            font-weight: bold;
            color: #dc2626;
        }

        .status-lulus {
            color: #10b981;
            font-size: 8pt;
        }

        .status-tidak {
            color: #ef4444;
            font-size: 8pt;
        }

        /* Summary */
        .summary {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .summary-cards {
            width: 100%;
        }

        .summary-card {
            display: inline-block;
            width: 48%;
            vertical-align: top;
            padding: 12px;
            background-color: #f0f7f3;
            border: 2px solid #D4AF37;
            border-radius: 4px;
            margin-right: 2%;
        }

        .summary-card:last-child {
            margin-right: 0;
        }

        .summary-card h4 {
            font-size: 11pt;
            font-weight: bold;
            color: #4A7C59;
            margin-bottom: 10px;
            padding-bottom: 8px;
            border-bottom: 2px solid #D4AF37;
        }

        .summary-row {
            margin-bottom: 8px;
            font-size: 10pt;
        }

        .summary-label {
            color: #666;
        }

        .summary-value {
            font-weight: bold;
            color: #4A7C59;
            float: right;
        }

        .summary-highlight {
            background-color: #4A7C59;
            color: white;
            padding: 8px;
            border-radius: 4px;
            margin-top: 5px;
        }

        .summary-highlight .label {
            font-size: 9pt;
            font-weight: 600;
        }

        .summary-highlight .value {
            font-size: 20pt;
            font-weight: bold;
            float: right;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #D4AF37;
        }

        .signature-area {
            width: 100%;
        }

        .signature-box {
            display: inline-block;
            width: 30%;
            text-align: center;
            vertical-align: top;
        }

        .signature-box.center {
            width: 40%;
        }

        .signature-box p {
            font-size: 9pt;
            margin-bottom: 50px;
        }

        .signature-box .name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-size: 9pt;
            display: inline-block;
            min-width: 150px;
        }

        .institution-box {
            text-align: center;
            padding: 10px;
            background-color: #f0f7f3;
            border-radius: 4px;
        }

        .institution-box .name {
            color: #4A7C59;
            font-weight: bold;
            font-size: 11pt;
        }

        .institution-box .arabic {
            color: #666;
            font-size: 9pt;
            margin-top: 5px;
        }

        /* Keterangan */
        .keterangan {
            margin-top: 20px;
            font-size: 8.5pt;
            color: #666;
        }

        .keterangan p {
            margin-bottom: 3px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>STAI AL-FATIH</h1>
            <h2>Sekolah Tinggi Agama Islam Al-Fatih</h2>
            <h3>KARTU HASIL STUDI (KHS)</h3>
            <p>{{ $khs->semester->nama_semester ?? 'Semester' }} Tahun Akademik {{ $khs->semester->tahun_akademik ?? '-' }}</p>
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <table>
                <tr>
                    <td style="width: 50%;">
                        <div class="info-row">
                            <span class="info-label">NIM</span>
                            <span class="info-colon">:</span>
                            <span class="info-value">{{ $mahasiswa->nim }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Nama</span>
                            <span class="info-colon">:</span>
                            <span class="info-value">{{ $mahasiswa->nama_lengkap }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Program Studi</span>
                            <span class="info-colon">:</span>
                            <span class="info-value">{{ $mahasiswa->programStudi->nama_prodi ?? '-' }}</span>
                        </div>
                    </td>
                    <td style="width: 50%;">
                        <div class="info-row">
                            <span class="info-label">Semester</span>
                            <span class="info-colon">:</span>
                            <span class="info-value">{{ $mahasiswa->semester_aktif }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Tahun Akademik</span>
                            <span class="info-colon">:</span>
                            <span class="info-value">{{ $khs->semester->tahun_akademik ?? '-' }} {{ $khs->semester->jenis ?? '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-colon">:</span>
                            <span class="info-value">{{ ucfirst($mahasiswa->status) }}</span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Grades Table -->
        <table class="grades-table">
            <thead>
                <tr>
                    <th style="width: 4%;">No</th>
                    <th style="width: 10%;">Kode MK</th>
                    <th style="width: 35%;">Mata Kuliah</th>
                    <th style="width: 7%;">SKS</th>
                    <th style="width: 9%;">Nilai</th>
                    <th style="width: 8%;">Grade</th>
                    <th style="width: 8%;">Bobot</th>
                    <th style="width: 10%;">SKS x Bobot</th>
                    <th style="width: 9%;">Ket</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilais as $index => $nilai)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $nilai->mataKuliah->kode_mk ?? '-' }}</td>
                    <td>{{ $nilai->mataKuliah->nama_mk ?? '-' }}</td>
                    <td class="text-center" style="font-weight: 600;">{{ $nilai->mataKuliah->sks ?? 0 }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_akhir ?? 0, 2) }}</td>
                    <td class="text-center">
                        <span class="grade-{{ strtolower($nilai->grade ?? 'e') }}">{{ $nilai->grade ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        @php
                            $bobot = 0;
                            switch($nilai->grade) {
                                case 'A': $bobot = 4.0; break;
                                case 'AB': $bobot = 3.5; break;
                                case 'B': $bobot = 3.0; break;
                                case 'BC': $bobot = 2.5; break;
                                case 'C': $bobot = 2.0; break;
                                case 'D': $bobot = 1.0; break;
                                case 'E': $bobot = 0.0; break;
                            }
                        @endphp
                        {{ number_format($bobot, 1) }}
                    </td>
                    <td class="text-center" style="font-weight: 600;">
                        {{ number_format($bobot * ($nilai->mataKuliah->sks ?? 0), 1) }}
                    </td>
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
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary clearfix">
            <div class="summary-card">
                <h4>Prestasi Semester Ini</h4>
                <div class="summary-row clearfix">
                    <span class="summary-label">Total SKS Semester</span>
                    <span class="summary-value">{{ $khs->total_sks ?? 0 }} SKS</span>
                </div>
                <div class="summary-row clearfix">
                    <span class="summary-label">Total SKS Lulus</span>
                    <span class="summary-value">{{ $khs->total_sks_lulus ?? 0 }} SKS</span>
                </div>
                <div class="summary-highlight clearfix">
                    <span class="label">IP Semester</span>
                    <span class="value">{{ number_format($khs->ip_semester ?? 0, 2) }}</span>
                </div>
            </div>

            <div class="summary-card">
                <h4>Prestasi Kumulatif</h4>
                <div class="summary-row clearfix">
                    <span class="summary-label">Total SKS Lulus</span>
                    <span class="summary-value">{{ $khs->total_sks_lulus ?? 0 }} SKS</span>
                </div>
                <div class="summary-row clearfix">
                    <span class="summary-label">&nbsp;</span>
                    <span class="summary-value">&nbsp;</span>
                </div>
                <div class="summary-highlight clearfix">
                    <span class="label">IPK</span>
                    <span class="value">{{ number_format($khs->ip_kumulatif ?? 0, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer with Signature -->
        <div class="footer">
            <div class="signature-area clearfix">
                <div class="signature-box">
                    <p>Mengetahui,</p>
                    <div class="name">Ketua Program Studi</div>
                </div>

                <div class="signature-box center">
                    <div class="institution-box">
                        <div class="name">STAI AL-FATIH</div>
                        <div class="arabic">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</div>
                    </div>
                </div>

                <div class="signature-box">
                    <p>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM YYYY') }}<br>Dosen Wali</p>
                    <div class="name">__________________</div>
                </div>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="keterangan">
            <p><strong>Keterangan:</strong></p>
            <p>L = Lulus | TL = Tidak Lulus | K = Kosong</p>
            <p>Grade: A (85-100) | AB (80-84) | B (70-79) | BC (65-69) | C (55-64) | D (45-54) | E (0-44)</p>
        </div>
    </div>
</body>
</html>
