<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran Daftar Ulang - {{ $pembayaran->mahasiswa->nim }}</title>
    <style>
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
            .no-print {
                display: none;
            }
            @page {
                size: A4;
                margin: 1cm;
            }
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            background: #f9fafb;
            padding: 20px;
        }

        .kwitansi-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border: 3px solid #059669;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 3px double #059669;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            margin-bottom: 10px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
        }

        .institution-name {
            text-align: left;
        }

        .institution-name h1 {
            font-size: 24px;
            color: #047857;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .institution-name p {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
        }

        .kwitansi-title {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            padding: 15px;
            margin: 30px -40px 30px -40px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .kwitansi-title::before {
            content: "📋 ";
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 200px;
            font-weight: 600;
            color: #374151;
            flex-shrink: 0;
        }

        .info-value {
            flex: 1;
            color: #1f2937;
        }

        .amount-section {
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 2px solid #059669;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }

        .amount-label {
            font-size: 14px;
            color: #047857;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .amount-value {
            font-size: 32px;
            color: #047857;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .amount-words {
            font-size: 14px;
            color: #059669;
            font-style: italic;
            background: white;
            padding: 10px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .payment-info-box {
            background: #f0fdf4;
            border-left: 4px solid #059669;
            padding: 15px;
            margin: 20px 0;
        }

        .payment-info-box h3 {
            color: #047857;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .payment-info-box p {
            color: #374151;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 30px;
        }

        .signature-box {
            text-align: center;
            flex: 1;
        }

        .signature-box p {
            margin-bottom: 80px;
            color: #374151;
            font-size: 14px;
        }

        .signature-name {
            font-weight: bold;
            color: #1f2937;
            border-top: 2px solid #1f2937;
            display: inline-block;
            padding-top: 10px;
            min-width: 200px;
        }

        .footer-note {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px dashed #d1d5db;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            font-style: italic;
        }

        .islamic-ornament {
            text-align: center;
            color: #059669;
            font-size: 24px;
            margin: 20px 0;
        }

        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.4);
            transition: all 0.3s ease;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(5, 150, 105, 0.5);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-lunas {
            background: #d1fae5;
            color: #047857;
            border: 1px solid #059669;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #f59e0b;
        }
    </style>
</head>
<body>
    <div class="kwitansi-container">
        <!-- Header -->
        <div class="header">
            <div class="header-top">
                <div class="logo">S</div>
                <div class="institution-name">
                    <h1>SIAKAD Islamic University</h1>
                    <p>Jl. Pendidikan No. 123, Kota, Provinsi 12345</p>
                    <p>Telp: (021) 1234-5678 | Email: info@siakad.ac.id</p>
                </div>
            </div>
        </div>

        <!-- Islamic Ornament -->
        <div class="islamic-ornament">☪ ✦ ☪</div>

        <!-- Title -->
        <div class="kwitansi-title">
            Kwitansi Pembayaran Daftar Ulang
        </div>

        <!-- Payment Info Box -->
        <div class="payment-info-box">
            <h3>Informasi Pembayaran Daftar Ulang</h3>
            <p><strong>Pembayaran daftar ulang</strong> adalah biaya registrasi ulang mahasiswa untuk melanjutkan studi pada semester aktif.</p>
            <p>Pembayaran ini mencakup: Registrasi akademik, Kartu mahasiswa, dan Akses sistem pembelajaran.</p>
        </div>

        <!-- Student Information -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">No. Kwitansi</div>
                <div class="info-value">KWT/DU/{{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal Pembayaran</div>
                <div class="info-value">{{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d F Y') : '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NIM</div>
                <div class="info-value">{{ $pembayaran->mahasiswa->nim }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Nama Mahasiswa</div>
                <div class="info-value">{{ $pembayaran->mahasiswa->nama_lengkap }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Program Studi</div>
                <div class="info-value">{{ $pembayaran->mahasiswa->programStudi->nama ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Semester</div>
                <div class="info-value">{{ $pembayaran->semester->tahun_akademik ?? '-' }} - {{ ucfirst($pembayaran->semester->jenis ?? '-') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status Pembayaran</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $pembayaran->status }}">
                        {{ ucfirst(str_replace('_', ' ', $pembayaran->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Amount Section -->
        <div class="amount-section">
            <div class="amount-label">Total Pembayaran Daftar Ulang</div>
            <div class="amount-value">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
            <div class="amount-words">
                <strong>Terbilang:</strong> {{ ucwords(\App\Helpers\Terbilang::convert($pembayaran->jumlah)) }} Rupiah
            </div>
        </div>

        @if($pembayaran->keterangan)
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">Keterangan</div>
                <div class="info-value">{{ $pembayaran->keterangan }}</div>
            </div>
        </div>
        @endif

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mahasiswa,</p>
                <div class="signature-name">{{ $pembayaran->mahasiswa->nama_lengkap }}</div>
            </div>
            <div class="signature-box">
                <p>Petugas {{ ucfirst($pembayaran->operator->user->role ?? 'Operator') }},</p>
                <div class="signature-name">{{ $pembayaran->operator->user->name ?? '-' }}</div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <div class="islamic-ornament" style="font-size: 16px; margin-bottom: 10px;">☪ ✦ ☪</div>
            <p>Kwitansi ini sah sebagai bukti pembayaran daftar ulang dan tidak dapat dipindahtangankan.</p>
            <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
            <p style="margin-top: 10px; color: #059669; font-weight: bold;">Barakallahu fiikum - Semoga Allah memberkahi Anda</p>
        </div>
    </div>

    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">
        🖨️ Cetak Kwitansi
    </button>

    <script>
        // Auto print on load (optional - can be removed if not desired)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
