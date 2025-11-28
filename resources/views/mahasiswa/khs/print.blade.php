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
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 14pt;
            font-weight: bold;
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

    <!-- Header -->
    <div class="header">
        <h1>KARTU HASIL STUDI (KHS)</h1>
        <h2>STAI AL-FATIH TANGERANG</h2>
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
            <div>
                Tangerang, 
                @php
                    // Get Hijri date (approximate conversion)
                    $gregorianDate = now();
                    $hijriYear = $gregorianDate->year - 579;
                    $hijriMonths = ['Muharram', 'Safar', 'Rabiul Awal', 'Rabiul Akhir', 'Jumadil Awal', 'Jumadil Akhir', 
                                   'Rajab', 'Syaban', 'Ramadhan', 'Syawal', 'Dzulqadah', 'Dzulhijjah'];
                    $hijriMonth = $hijriMonths[$gregorianDate->month - 1];
                    
                    echo $gregorianDate->day . ' ' . $hijriMonth . ' ' . $hijriYear . ' H';
                @endphp
            </div>
            <div>{{ now()->translatedFormat('d F Y') }} M</div>
            <div style="margin-top: 10px; font-weight: bold;">PUKET I</div>
            <div>Bid. Akademik dan Pengembangan</div>
            <div>STAI AL-FATIH</div>
            <div class="signature-name">
                Satrio Purnomo Hidayat, M.Pd.
            </div>
        </div>
    </div>
</body>
</html>
