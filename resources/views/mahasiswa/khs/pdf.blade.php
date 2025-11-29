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
            opacity: 0.05;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            width: 500px;
            height: 500px;
            object-fit: contain;
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

        .doc-title p {
            font-size: 9pt;
            color: #555;
        }

        /* Mahasiswa Info */
        .mhs-info {
            margin-bottom: 8px;
        }

        .mhs-info p {
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
        @if($logoBase64)
        <img src="{{ $logoBase64 }}" alt="Logo STAI Al-Fatih">
        @endif
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Kop Surat -->
        <div class="kop-surat">
            <div class="kop-content">
                <div class="kop-logo">
                    @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo STAI Al-Fatih">
                    @endif
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
            <table style="width: 100%;">
                <tr>
                    <td style="width: 50%;">
                        <p><strong>Jumlah SKS Semester</strong> : {{ $totalSks }}</p>
                        <p><strong>SKS yang diluluskan</strong> : {{ $totalSksLulus }}</p>
                        <p><strong>Indeks Prestasi Kumulatif (IPK) lalu</strong> : -</p>
                    </td>
                    <td style="width: 50%;">
                        <p><strong>Indeks Prestasi (IP)</strong> : {{ str_replace('.', ',', number_format($khs->ip, 2)) }}</p>
                        <p><strong>Indeks Prestasi Kumulatif (IPK)</strong> : {{ str_replace('.', ',', number_format($khs->ipk, 2)) }}</p>
                    </td>
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

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Akademik STAI Al-Fatih Tangerang</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM Y HH:mm') }} WIB</p>
    </div>
</body>
</html>
