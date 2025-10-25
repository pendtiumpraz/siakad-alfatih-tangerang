<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran Wisuda - {{ $pembayaran->mahasiswa->nim }}</title>
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
            border: 3px solid #ca8a04;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .kwitansi-container::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 1px solid #fbbf24;
            pointer-events: none;
        }

        .header {
            text-align: center;
            border-bottom: 3px double #ca8a04;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
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
            background: linear-gradient(135deg, #ca8a04 0%, #a16207 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
            box-shadow: 0 0 20px rgba(202, 138, 4, 0.3);
        }

        .institution-name {
            text-align: left;
        }

        .institution-name h1 {
            font-size: 24px;
            color: #a16207;
            margin-bottom: 5px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .institution-name p {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.4;
        }

        .graduation-banner {
            background: linear-gradient(135deg, #ca8a04 0%, #a16207 100%);
            color: white;
            padding: 20px;
            margin: 30px -40px 30px -40px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .graduation-banner h2 {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
        }

        .graduation-banner p {
            font-size: 14px;
            font-style: italic;
            opacity: 0.9;
        }

        .graduation-icon {
            text-align: center;
            font-size: 48px;
            margin: 20px 0;
        }

        .info-section {
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
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
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 3px solid #ca8a04;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(202, 138, 4, 0.2);
            position: relative;
            z-index: 1;
        }

        .amount-label {
            font-size: 14px;
            color: #a16207;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .amount-value {
            font-size: 36px;
            color: #a16207;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        }

        .amount-words {
            font-size: 14px;
            color: #92400e;
            font-style: italic;
            background: white;
            padding: 12px;
            border-radius: 6px;
            margin-top: 10px;
            border: 1px solid #fbbf24;
        }

        .payment-info-box {
            background: #fffbeb;
            border-left: 4px solid #ca8a04;
            padding: 15px;
            margin: 20px 0;
            position: relative;
            z-index: 1;
        }

        .payment-info-box h3 {
            color: #a16207;
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

        .congratulations-box {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 2px solid #fbbf24;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .congratulations-box h3 {
            color: #a16207;
            font-size: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .congratulations-box p {
            color: #92400e;
            font-size: 14px;
            line-height: 1.8;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            padding-top: 30px;
            position: relative;
            z-index: 1;
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
            border-top: 2px dashed #fbbf24;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            font-style: italic;
            position: relative;
            z-index: 1;
        }

        .islamic-ornament {
            text-align: center;
            color: #ca8a04;
            font-size: 24px;
            margin: 20px 0;
        }

        .print-button {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #ca8a04 0%, #a16207 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(202, 138, 4, 0.4);
            transition: all 0.3s ease;
        }

        .print-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(202, 138, 4, 0.5);
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
                <div class="logo">🎓</div>
                <div class="institution-name">
                    <h1>SIAKAD Islamic University</h1>
                    <p>Jl. Pendidikan No. 123, Kota, Provinsi 12345</p>
                    <p>Telp: (021) 1234-5678 | Email: info@siakad.ac.id</p>
                </div>
            </div>
        </div>

        <!-- Islamic Ornament -->
        <div class="islamic-ornament">☪ ★ ☪</div>

        <!-- Graduation Banner -->
        <div class="graduation-banner">
            <h2>🎓 Kwitansi Pembayaran Wisuda 🎓</h2>
            <p>Graduation Payment Receipt</p>
        </div>

        <!-- Congratulations Box -->
        <div class="congratulations-box">
            <h3>✨ Selamat atas Pencapaian Anda! ✨</h3>
            <p>Terima kasih atas dedikasi dan kerja keras Anda dalam menempuh pendidikan.</p>
            <p><strong>Semoga ilmu yang diperoleh bermanfaat dan berkah.</strong></p>
        </div>

        <!-- Payment Info Box -->
        <div class="payment-info-box">
            <h3>Informasi Pembayaran Wisuda</h3>
            <p><strong>Pembayaran wisuda</strong> adalah biaya yang diperlukan untuk mengikuti upacara wisuda dan administrasi kelulusan.</p>
            <p>Pembayaran ini mencakup: Toga dan atribut wisuda, Ijazah dan transkrip nilai, Upacara wisuda, dan Dokumentasi.</p>
        </div>

        <!-- Student Information -->
        <div class="info-section">
            <div class="info-row">
                <div class="info-label">No. Kwitansi</div>
                <div class="info-value">KWT/WSD/{{ str_pad($pembayaran->id, 6, '0', STR_PAD_LEFT) }}/{{ date('Y') }}</div>
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
                <div class="info-label">Nama Calon Wisudawan</div>
                <div class="info-value"><strong>{{ $pembayaran->mahasiswa->nama_lengkap }}</strong></div>
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
            <div class="amount-label">🎓 Total Pembayaran Wisuda 🎓</div>
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
                <p>Calon Wisudawan,</p>
                <div class="signature-name">{{ $pembayaran->mahasiswa->nama_lengkap }}</div>
            </div>
            <div class="signature-box">
                <p>Petugas {{ ucfirst($pembayaran->operator->user->role ?? 'Operator') }},</p>
                <div class="signature-name">{{ $pembayaran->operator->user->name ?? '-' }}</div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="footer-note">
            <div class="islamic-ornament" style="font-size: 16px; margin-bottom: 10px;">☪ ★ ☪</div>
            <p>Kwitansi ini sah sebagai bukti pembayaran wisuda dan tidak dapat dipindahtangankan.</p>
            <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
            <p style="margin-top: 15px; color: #a16207; font-weight: bold; font-size: 14px;">
                "Dan katakanlah: 'Ya Tuhanku, tambahkanlah kepadaku ilmu pengetahuan.'" (QS. Taha: 114)
            </p>
            <p style="margin-top: 5px; color: #ca8a04; font-weight: bold;">
                Tabaarakallahu fiikum - Semoga Allah memberkahi Anda
            </p>
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
