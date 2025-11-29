<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Studi (KHS) - {{ $khs->mahasiswa->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 9pt;
            line-height: 1.2;
            padding: 15px 25px;
            color: #000;
            position: relative;
        }

        /* Watermark Background Logo */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: 0;
        }

        .watermark img {
            width: 400px;
            height: 400px;
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
            font-size: 13pt;
            font-weight: bold;
            color: #2D5F3F;
            margin-bottom: 2px;
        }

        .kop-text p {
            font-size: 8pt;
            color: #2D5F3F;
            margin: 0;
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

        .doc-title p {
            font-size: 9pt;
            color: #555;
        }

        /* Mahasiswa Info */
        .mhs-info {
            margin-bottom: 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 1px 0;
            font-size: 9pt;
        }

        .info-table td:first-child {
            width: 130px;
            font-weight: normal;
        }

        .info-table td:nth-child(2) {
            width: 10px;
        }

        .info-table td:last-child {
            font-weight: bold;
        }

        /* Nilai Table */
        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }

        .nilai-table th {
            background-color: #2D5F3F;
            color: white;
            padding: 4px 5px;
            text-align: center;
            font-size: 8pt;
            border: 1px solid #000;
        }

        .nilai-table td {
            padding: 3px 5px;
            border: 1px solid #000;
            font-size: 8pt;
        }

        .nilai-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Summary Box */
        .summary-box {
            margin-top: 8px;
            padding: 8px;
            border: 2px solid #2D5F3F;
            background-color: #f0f7f4;
        }

        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }

        .summary-label {
            display: table-cell;
            width: 180px;
            font-weight: bold;
            font-size: 9pt;
        }

        .summary-value {
            display: table-cell;
            text-align: left;
            font-weight: bold;
            color: #2D5F3F;
            font-size: 9pt;
        }

        /* Signature */
        .signature {
            margin-top: 15px;
        }

        .signature-box {
            float: right;
            text-align: center;
            width: 180px;
            font-size: 9pt;
        }

        .signature-box p {
            margin-bottom: 40px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            position: fixed;
            bottom: 10px;
            left: 25px;
            right: 25px;
            text-align: center;
            font-size: 7pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        /* Page break */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        <img src="{{ public_path('images/logo-alfatih.png') }}" alt="Logo STAI Al-Fatih">
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="kop-content">
                <div class="kop-logo">
                    <img src="{{ public_path('images/logo-alfatih.png') }}" alt="Logo STAI Al-Fatih">
                </div>
                <div class="kop-text">
                    <h1>SEKOLAH TINGGI AGAMA ISLAM AL-FATIH TANGERANG</h1>
                    <p>Jl. Raya Serang KM. 16 No. 10, Cikupa, Tangerang, Banten 15710</p>
                    <p>Telp: (021) 5960607 | Email: info@staialfatih.ac.id | Website: www.staialfatih.ac.id</p>
                </div>
            </div>
            <hr class="kop-divider">
        </div>

        <!-- Document Title -->
        <div class="doc-title">
            <h2>KARTU HASIL STUDI (KHS)</h2>
            <p>{{ $khs->semester->nama_semester }}</p>
        </div>

        <!-- Mahasiswa Info -->
        <div class="mhs-info">
            <table class="info-table">
                <tr>
                    <td>NIM</td>
                    <td>:</td>
                    <td>{{ $khs->mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <td>Nama Mahasiswa</td>
                    <td>:</td>
                    <td>{{ $khs->mahasiswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>:</td>
                    <td>{{ $khs->mahasiswa->programStudi->nama_prodi }}</td>
                </tr>
                <tr>
                    <td>Angkatan</td>
                    <td>:</td>
                    <td>{{ $khs->mahasiswa->angkatan }}</td>
                </tr>
            </table>
        </div>

        <!-- Nilai Table -->
        <table class="nilai-table">
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 80px;">Kode MK</th>
                    <th>Mata Kuliah</th>
                    <th style="width: 50px;">SKS</th>
                    <th style="width: 60px;">Nilai</th>
                    <th style="width: 60px;">Grade</th>
                    <th style="width: 60px;">Bobot</th>
                    <th style="width: 80px;">Bobot×SKS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalSks = 0;
                    $totalBobotXSks = 0;
                @endphp
                @foreach($khs->mahasiswa->nilais->where('semester_id', $khs->semester_id) as $index => $nilai)
                    @php
                        $bobot = $nilai->bobot ?? $khsService->getBobot($nilai->grade ?? 'E');
                        $bobotXSks = $bobot * ($nilai->mataKuliah->sks ?? 0);
                        $totalSks += $nilai->mataKuliah->sks ?? 0;
                        $totalBobotXSks += $bobotXSks;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center font-bold">{{ $nilai->mataKuliah->kode_mk }}</td>
                        <td>{{ $nilai->mataKuliah->nama_mk }}</td>
                        <td class="text-center font-bold">{{ $nilai->mataKuliah->sks }}</td>
                        <td class="text-center">{{ $nilai->nilai_akhir ?? '-' }}</td>
                        <td class="text-center font-bold">{{ str_replace('+', '', $nilai->grade ?? '-') }}</td>
                        <td class="text-center">{{ number_format($bobot, 2) }}</td>
                        <td class="text-center font-bold">{{ number_format($bobotXSks, 2) }}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #e0e0e0; font-weight: bold;">
                    <td colspan="3" class="text-right">TOTAL</td>
                    <td class="text-center">{{ $totalSks }}</td>
                    <td colspan="3"></td>
                    <td class="text-center">{{ number_format($totalBobotXSks, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Summary Box -->
        <div class="summary-box">
            <div class="summary-row">
                <div class="summary-label">Indeks Prestasi (IP) Semester Ini</div>
                <div class="summary-value">: {{ number_format($khs->ip, 2) }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Indeks Prestasi Kumulatif (IPK)</div>
                <div class="summary-value">: {{ number_format($khs->ipk, 2) }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Total SKS Semester Ini</div>
                <div class="summary-value">: {{ $khs->total_sks_semester }} SKS</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Total SKS Kumulatif</div>
                <div class="summary-value">: {{ $khs->total_sks_kumulatif }} SKS</div>
            </div>
        </div>

        <!-- Signature -->
        <div class="signature">
            <div class="signature-box">
                <p>Tangerang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
                <p>Ketua Program Studi</p>
                <br><br>
                <p class="signature-name">
                    @if($khs->mahasiswa->programStudi->ketuaProdi)
                        {{ $khs->mahasiswa->programStudi->ketuaProdi->nama_lengkap }}
                    @else
                        (_________________________)
                    @endif
                </p>
                @if($khs->mahasiswa->programStudi->ketuaProdi)
                    <p style="font-size: 9pt;">NIDN: {{ $khs->mahasiswa->programStudi->ketuaProdi->nidn }}</p>
                @endif
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Akademik STAI Al-Fatih Tangerang</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y HH:mm') }} WIB</p>
    </div>
</body>
</html>
