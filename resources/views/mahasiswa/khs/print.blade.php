<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Hasil Studi (KHS) - {{ $mahasiswa->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            padding: 40px;
            max-width: 210mm;
            margin: 0 auto;
            color: #000;
            position: relative;
        }

        /* Watermark Background Logo */
        body::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 900px;
            height: 900px;
            background-image: url('{{ asset('images/logo-alfatih.png') }}');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.25;
            z-index: 0;
            pointer-events: none;
        }

        /* Content wrapper to stay above watermark */
        body > * {
            position: relative;
            z-index: 1;
        }

        /* Kop Surat */
        .kop-surat {
            margin-bottom: 30px;
        }

        .kop-content {
            display: flex;
            align-items: flex-start;
            gap: 25px;
            margin-bottom: 15px;
        }

        .kop-logo {
            width: 150px;
            height: 150px;
            flex-shrink: 0;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h1 {
            font-size: 28pt;
            font-weight: bold;
            color: #2D5F3F;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .kop-text p {
            font-size: 15pt;
            color: #2D5F3F;
            margin: 1px 0;
            line-height: 1.3;
        }

        .garis-double {
            margin-top: 2px;
        }

        .garis-tipis {
            border-top: 1px solid #000;
            margin-bottom: 2px;
        }

        .garis-tebal {
            border-top: 4px solid #000;
        }

        /* Header Title KHS */
        .header-title {
            text-align: center;
            margin: 20px 0 30px 0;
        }

        .header-title h2 {
            font-size: 14pt;
            font-weight: bold;
            color: #000;
        }

        .info-section {
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            width: 150px;
            font-weight: normal;
        }

        .info-separator {
            width: 20px;
        }

        .info-value {
            flex: 1;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 11pt;
        }

        td {
            padding: 6px 8px;
            font-size: 11pt;
        }

        td.center {
            text-align: center;
        }

        .summary {
            margin-top: 20px;
            margin-bottom: 30px;
        }

        .summary-row {
            display: flex;
            margin-bottom: 5px;
        }

        .summary-label {
            width: 300px;
            font-weight: normal;
        }

        .summary-separator {
            width: 20px;
        }

        .summary-value {
            flex: 1;
            font-weight: bold;
        }

        .signature-section {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 250px;
        }

        .signature-name {
            margin-top: 60px;
            font-weight: bold;
            text-decoration: underline;
        }

        @media print {
            body {
                padding: 20px;
            }
            
            @page {
                size: A4 portrait;
                margin: 20mm;
            }
        }
    </style>
</head>
<body>
    @php
        $khs = $mahasiswa->khs()
            ->where('semester_id', request()->semester_id ?? $mahasiswa->khs()->latest()->first()?->semester_id)
            ->with('semester')
            ->first();
        
        if (!$khs) {
            $khs = $mahasiswa->khs()->with('semester')->latest()->first();
        }
    @endphp

    <!-- Kop Surat -->
    <div class="kop-surat">
        <div class="kop-content">
            <img src="{{ asset('images/logo-alfatih.png') }}" alt="Logo STAI AL-FATIH" class="kop-logo">
            <div class="kop-text">
                <h1>STAI AL FATIH TANGERANG</h1>
                <p>Jl. Raden Fatah No. 5, RT. 004/RW. 006, Parung Serab, Kec. Ciledug, Kota</p>
                <p>Tangerang, Banten, Kode Pos 15153</p>
                <p>Email: info@staialfatih.or.id, Website: https://staialfatih.or.id</p>
            </div>
        </div>
        <div class="garis-double">
            <div class="garis-tipis"></div>
            <div class="garis-tebal"></div>
        </div>
    </div>

    <!-- Header Title -->
    <div class="header-title">
        <h2>KARTU HASIL STUDI (KHS) MAHASISWA</h2>
    </div>

    <!-- Student Information -->
    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Nama</div>
            <div class="info-separator">:</div>
            <div class="info-value">{{ $mahasiswa->nama_lengkap }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">NIM</div>
            <div class="info-separator">:</div>
            <div class="info-value">{{ $mahasiswa->nim }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Semester</div>
            <div class="info-separator">:</div>
            <div class="info-value">
                @if($khs && $khs->semester_ke)
                    {{ $khs->semester_ke }} ({{ ucfirst($khs->semester->jenis ?? '') }})
                @else
                    -
                @endif
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Program Studi</div>
            <div class="info-separator">:</div>
            <div class="info-value">{{ $mahasiswa->programStudi->nama_prodi }}</div>
        </div>
    </div>

    <!-- Grades Table -->
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">NO</th>
                <th style="width: 12%;">KODE</th>
                <th style="width: 45%;">MATA KULIAH</th>
                <th style="width: 8%;">SKS</th>
                <th style="width: 15%;">NILAI HURUF</th>
                <th style="width: 15%;">NILAI ANGKA</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
                $nilaiList = \App\Models\Nilai::where('mahasiswa_id', $mahasiswa->id)
                    ->where('semester_id', $khs->semester_id ?? 0)
                    ->with('mataKuliah')
                    ->orderBy('id')
                    ->get();
            @endphp
            
            @forelse($nilaiList as $nilai)
            <tr>
                <td class="center">{{ $no++ }}</td>
                <td class="center">{{ $nilai->mataKuliah->kode_mk }}</td>
                <td>{{ $nilai->mataKuliah->nama_mk }}</td>
                <td class="center">{{ $nilai->mataKuliah->sks }}</td>
                <td class="center">{{ str_replace('+', '', $nilai->grade) }}</td>
                <td class="center">{{ number_format($nilai->bobot, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="center">Tidak ada data nilai</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary -->
    @if($khs)
    <div class="summary">
        <div class="summary-row">
            <div class="summary-label">Jumlah SKS Semester</div>
            <div class="summary-separator">:</div>
            <div class="summary-value">{{ $khs->total_sks_semester }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">SKS yang diluluskan</div>
            <div class="summary-separator">:</div>
            <div class="summary-value">{{ $khs->total_sks_semester }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Indeks Prestasi Kumulatif (IPK) lalu</div>
            <div class="summary-separator">:</div>
            <div class="summary-value">
                @php
                    // Get previous semester KHS
                    $previousKhs = $mahasiswa->khs()
                        ->where('semester_ke', '<', $khs->semester_ke)
                        ->orderBy('semester_ke', 'desc')
                        ->first();
                @endphp
                @if($previousKhs)
                    {{ number_format($previousKhs->ipk, 2) }}
                @else
                    -
                @endif
            </div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Indeks Prestasi (IP)</div>
            <div class="summary-separator">:</div>
            <div class="summary-value">{{ number_format($khs->ip, 2) }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Indeks Prestasi Kumulatif (IPK)</div>
            <div class="summary-separator">:</div>
            <div class="summary-value">{{ number_format($khs->ipk, 2) }}</div>
        </div>
    </div>
    @endif

    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            @php
                $tanggalSekarang = \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY');
            @endphp
            <p style="margin: 2px 0;">Tangerang, {{ $tanggalSekarang }}</p>
            <p style="margin: 2px 0;">PUKET I</p>
            <p style="margin: 2px 0;">Bid. Akademik dan Pengembangan</p>
            <p style="margin: 2px 0;">STAI AL FATIH</p>
            <br><br><br>
            <p style="margin: 2px 0; font-weight: bold; text-decoration: underline;">Satrio Purnomo Hidayat, M.Pd.</p>
        </div>
    </div>
</body>
</html>
