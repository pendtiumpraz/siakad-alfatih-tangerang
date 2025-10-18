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
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            line-height: 1.35;
            color: #000;
            padding: 12px;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            transform: scale(0.95);
            transform-origin: top center;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 13px;
            border-bottom: 3px solid #2D5F3F;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            color: #2D5F3F;
            margin-bottom: 2px;
        }

        .header h2 {
            font-size: 10px;
            font-weight: normal;
            color: #666;
            margin-bottom: 10px;
        }

        .header h3 {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            margin-bottom: 2px;
        }

        .header p {
            font-size: 10px;
            color: #666;
        }

        /* Student Info */
        .student-info {
            margin-bottom: 13px;
            padding: 8px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }

        .info-label {
            display: table-cell;
            width: 140px;
            font-size: 10px;
            color: #333;
        }

        .info-colon {
            display: table-cell;
            width: 10px;
            font-size: 10px;
        }

        .info-value {
            display: table-cell;
            font-size: 10px;
            font-weight: bold;
            color: #000;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 13px;
        }

        table th {
            background-color: #2D5F3F;
            color: white;
            padding: 5px 3px;
            font-size: 10px;
            font-weight: bold;
            text-align: center;
            border: 1px solid #1a3a25;
        }

        table td {
            padding: 4px 3px;
            font-size: 9.5px;
            border: 1px solid #ddd;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        /* Summary */
        .summary {
            margin-top: 13px;
            padding: 8px;
            background-color: #f0f7f3;
            border: 2px solid #2D5F3F;
            border-radius: 4px;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .summary-label {
            display: table-cell;
            width: 200px;
            font-size: 11px;
            font-weight: bold;
            color: #333;
        }

        .summary-value {
            display: table-cell;
            font-size: 11px;
            font-weight: bold;
            color: #2D5F3F;
        }

        /* Footer */
        .footer {
            margin-top: 26px;
            page-break-inside: avoid;
        }

        .signature-box {
            float: right;
            width: 200px;
            text-align: center;
        }

        .signature-box p {
            font-size: 10px;
            margin-bottom: 45px;
        }

        .signature-box .name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 4px;
            font-size: 10px;
        }

        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }

        /* Print specific */
        @media print {
            body {
                padding: 8px;
            }

            .no-print {
                display: none;
            }
        }

        .grade-a { font-weight: bold; color: #059669; }
        .grade-b { font-weight: bold; color: #3b82f6; }
        .grade-c { font-weight: bold; color: #f59e0b; }
        .grade-d { font-weight: bold; color: #ef4444; }
        .grade-e { font-weight: bold; color: #dc2626; }
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
            <table style="width: 100%; border: none;">
                <tr>
                    <td style="width: 50%; border: none; padding: 3px;">
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
                    <td style="width: 50%; border: none; padding: 3px;">
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
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Kode MK</th>
                    <th style="width: 43%;">Mata Kuliah</th>
                    <th style="width: 8%;">SKS</th>
                    <th style="width: 8%;">Nilai</th>
                    <th style="width: 8%;">Grade</th>
                    <th style="width: 8%;">Mutu</th>
                    <th style="width: 8%;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($nilais as $index => $nilai)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $nilai->mataKuliah->kode_mk ?? '-' }}</td>
                    <td>{{ $nilai->mataKuliah->nama_mk ?? '-' }}</td>
                    <td class="text-center">{{ $nilai->mataKuliah->sks ?? 0 }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_akhir ?? 0, 2) }}</td>
                    <td class="text-center">
                        <span class="grade-{{ strtolower($nilai->grade ?? 'e') }}">{{ $nilai->grade ?? '-' }}</span>
                    </td>
                    <td class="text-center">
                        @php
                            $mutu = 0;
                            switch($nilai->grade) {
                                case 'A': $mutu = 4.0; break;
                                case 'B': $mutu = 3.0; break;
                                case 'C': $mutu = 2.0; break;
                                case 'D': $mutu = 1.0; break;
                                case 'E': $mutu = 0.0; break;
                            }
                        @endphp
                        {{ number_format($mutu * ($nilai->mataKuliah->sks ?? 0), 2) }}
                    </td>
                    <td class="text-center">{{ $nilai->status == 'lulus' ? 'Lulus' : 'Tidak Lulus' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px; color: #999;">
                        Tidak ada data nilai
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span class="summary-label">Total SKS Semester Ini</span>
                <span class="summary-value">: {{ $khs->total_sks ?? 0 }} SKS</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Total SKS Lulus</span>
                <span class="summary-value">: {{ $khs->total_sks_lulus ?? 0 }} SKS</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Indeks Prestasi Semester (IPS)</span>
                <span class="summary-value">: {{ number_format($khs->ip_semester ?? 0, 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Indeks Prestasi Kumulatif (IPK)</span>
                <span class="summary-value">: {{ number_format($khs->ip_kumulatif ?? 0, 2) }}</span>
            </div>
        </div>

        <!-- Footer with Signature -->
        <div class="footer clearfix">
            <div class="signature-box">
                <p>Dicetak tanggal: {{ \Carbon\Carbon::now()->isoFormat('D MMMM YYYY') }}</p>
                <p style="margin-bottom: 55px;">Ketua Program Studi</p>
                <div class="name">
                    <strong>(___________________)</strong><br>
                    NIDN. ______________
                </div>
            </div>
        </div>
    </div>
</body>
</html>
