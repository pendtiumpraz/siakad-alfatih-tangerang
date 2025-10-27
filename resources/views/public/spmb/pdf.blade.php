<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Pendaftaran - {{ $pendaftar->nomor_pendaftaran }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
            padding: 20px;
        }

        .container {
            max-width: 100%;
        }

        .header {
            background: linear-gradient(135deg, #1e5128 0%, #2d6a4f 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .header-left {
            display: table-cell;
            vertical-align: middle;
        }

        .header-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
        }

        .logo {
            width: 50px;
            height: 50px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 15px;
        }

        .institution-name {
            font-size: 18pt;
            font-weight: bold;
            display: inline-block;
            vertical-align: middle;
        }

        .subtitle {
            color: #d4af37;
            font-size: 10pt;
            margin-top: 3px;
        }

        .academic-year {
            font-size: 9pt;
            color: #d4af37;
        }

        .year-value {
            font-size: 14pt;
            font-weight: bold;
        }

        .status-section {
            background: #f9fafb;
            padding: 15px;
            border-left: 4px solid #2d6a4f;
            margin-bottom: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 10pt;
            color: white;
        }

        .status-pending { background-color: #f59e0b; }
        .status-verified { background-color: #3b82f6; }
        .status-accepted { background-color: #10b981; }
        .status-rejected { background-color: #ef4444; }

        .main-content {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .reg-number-box {
            display: table-cell;
            width: 65%;
            vertical-align: top;
            padding-right: 15px;
        }

        .reg-number {
            background: #fffbeb;
            border: 2px solid #d4af37;
            border-radius: 8px;
            padding: 15px;
        }

        .reg-label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 5px;
        }

        .reg-value {
            font-size: 20pt;
            font-weight: bold;
            color: #1e5128;
        }

        .photo-box {
            display: table-cell;
            width: 35%;
            vertical-align: top;
            text-align: center;
        }

        .photo {
            width: 120px;
            height: 160px;
            object-fit: cover;
            border: 3px solid #1e5128;
            border-radius: 8px;
        }

        .photo-label {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section-title {
            font-size: 13pt;
            font-weight: bold;
            color: #1e5128;
            border-bottom: 2px solid #1e5128;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .data-grid {
            width: 100%;
            margin-bottom: 10px;
        }

        .data-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }

        .data-cell {
            display: table-cell;
            width: 50%;
            padding-right: 10px;
        }

        .data-label {
            font-size: 9pt;
            color: #666;
            margin-bottom: 2px;
        }

        .data-value {
            font-size: 10pt;
            font-weight: 600;
            color: #333;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-content">
                <div class="header-left">
                    <div class="institution-name">STAI AL-FATIH</div>
                    <div class="subtitle">Kartu Pendaftaran Mahasiswa Baru</div>
                </div>
                <div class="header-right">
                    <div class="academic-year">Tahun Akademik</div>
                    <div class="year-value">{{ date('Y') }}/{{ date('Y') + 1 }}</div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="status-section">
            <strong>Status Pendaftaran:</strong>
            @php
                $statusMap = [
                    'draft' => ['class' => 'status-pending', 'text' => 'Draft'],
                    'pending' => ['class' => 'status-pending', 'text' => 'Menunggu Verifikasi'],
                    'verified' => ['class' => 'status-verified', 'text' => 'Terverifikasi'],
                    'accepted' => ['class' => 'status-accepted', 'text' => 'DITERIMA'],
                    'rejected' => ['class' => 'status-rejected', 'text' => 'Tidak Diterima'],
                ];
                $status = $statusMap[$pendaftar->status] ?? ['class' => 'status-pending', 'text' => ucfirst($pendaftar->status)];
            @endphp
            <span class="status-badge {{ $status['class'] }}">{{ $status['text'] }}</span>
        </div>

        <!-- Main Content: Registration Number + Photo -->
        <div class="main-content">
            <div class="reg-number-box">
                <div class="reg-number">
                    <div class="reg-label">Nomor Pendaftaran</div>
                    <div class="reg-value">{{ $pendaftar->nomor_pendaftaran }}</div>
                    <div style="font-size: 8pt; color: #666; margin-top: 5px;">
                        Simpan nomor ini untuk keperluan administrasi
                    </div>
                </div>
            </div>
            @if($fotoBase64 ?? $pendaftar->foto_url)
                <div class="photo-box">
                    <img src="{{ $fotoBase64 ?? $pendaftar->foto_url }}" alt="Foto" class="photo">
                    <div class="photo-label">Pas Foto 4x6</div>
                </div>
            @endif
        </div>

        <!-- Data Pribadi -->
        <div class="section">
            <div class="section-title">Data Pribadi</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-label">Nama Lengkap:</div>
                        <div class="data-value">{{ $pendaftar->nama_lengkap }}</div>
                    </div>
                    <div class="data-cell">
                        <div class="data-label">NIK:</div>
                        <div class="data-value">{{ $pendaftar->nik ?? '-' }}</div>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-label">Jenis Kelamin:</div>
                        <div class="data-value">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </div>
                    <div class="data-cell">
                        <div class="data-label">Tempat, Tanggal Lahir:</div>
                        <div class="data-value">{{ $pendaftar->tempat_lahir }}, {{ $pendaftar->tanggal_lahir->format('d F Y') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jalur & Program Studi -->
        <div class="section">
            <div class="section-title">Program Studi</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-label">Jalur Pendaftaran:</div>
                        <div class="data-value">{{ strtoupper($pendaftar->jalur_pendaftaran ?? '-') }}</div>
                    </div>
                    <div class="data-cell">
                        <div class="data-label">Program Studi:</div>
                        <div class="data-value">{{ $pendaftar->jurusan->nama ?? '-' }}</div>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-label">Email:</div>
                        <div class="data-value">{{ $pendaftar->email }}</div>
                    </div>
                    <div class="data-cell">
                        <div class="data-label">No. Telepon:</div>
                        <div class="data-value">{{ $pendaftar->no_telepon }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Kartu pendaftaran ini dicetak pada {{ now()->format('d F Y, H:i') }} WIB</p>
            <p style="margin-top: 5px;">Untuk informasi lebih lanjut hubungi: {{ $spmbPhone }} atau email ke {{ $spmbEmail }}</p>
        </div>
    </div>
</body>
</html>
