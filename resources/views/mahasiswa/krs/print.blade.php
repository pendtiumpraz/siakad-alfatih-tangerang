<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KRS - {{ $mahasiswa->nim }} - {{ $activeSemester->tahun_akademik }} {{ ucfirst($activeSemester->jenis) }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.3;
            color: #000;
            padding: 15px;
        }
        
        @page {
            size: A4;
            margin: 10mm;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .header h2 {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }

        .header p {
            font-size: 12px;
            margin: 2px 0;
        }

        /* Info Section */
        .info-section {
            margin: 10px 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 1px 5px;
            font-size: 11px;
            line-height: 1.4;
        }

        .info-table td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .info-table td:nth-child(2) {
            width: 10px;
        }

        /* KRS Table */
        .krs-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .krs-table th,
        .krs-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 10px;
        }

        .krs-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        .krs-table td {
            vertical-align: top;
        }

        .krs-table td:nth-child(1) {
            text-align: center;
            width: 40px;
        }

        .krs-table td:nth-child(2) {
            width: 100px;
        }

        .krs-table td:nth-child(4) {
            text-align: center;
            width: 60px;
        }

        .krs-table td:nth-child(5) {
            width: 80px;
        }

        .krs-table tfoot td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            width: 45%;
            text-align: center;
        }

        .signature-box p {
            margin-bottom: 65px;
            font-size: 11px;
        }
        
        .signature-box:first-child p:first-child {
            margin-bottom: 80px;
        }

        .signature-box .name {
            font-weight: bold;
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
            padding-bottom: 2px;
            margin-bottom: 5px;
        }

        .signature-box .title {
            font-size: 11px;
            margin-top: 0;
            margin-bottom: 0;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 10px;
        }

        .status-approved {
            background-color: #d4edda;
            color: #155724;
            border: 2px solid #155724;
        }

        .status-submitted {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 2px solid #0c5460;
        }

        /* Print Styles */
        @media print {
            body {
                padding: 0;
                line-height: 1.2;
            }

            .no-print {
                display: none;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            
            .header {
                margin-bottom: 10px;
                padding-bottom: 5px;
            }
            
            .signature-box p {
                margin-bottom: 55px;
            }
            
            .signature-box:first-child p:first-child {
                margin-bottom: 70px;
            }
            
            .signature-box .name {
                margin-bottom: 3px;
            }
            
            .signature-box .title {
                margin-top: 0;
            }
            
            .info-box {
                padding: 5px;
                margin: 8px 0;
            }
        }

        /* Print Button */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4A7C59;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .print-button:hover {
            background-color: #3d6849;
        }

        .info-box {
            background-color: #f8f9fa;
            border-left: 3px solid #4A7C59;
            padding: 8px;
            margin: 10px 0;
            font-size: 10px;
        }

        .info-box strong {
            display: block;
            margin-bottom: 5px;
            color: #4A7C59;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <button class="print-button no-print" onclick="window.print()">🖨️ Cetak KRS</button>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>STAI AL-FATIH CILEUNGSI</h1>
            <h2>KARTU RENCANA STUDI (KRS)</h2>
            <p>Semester {{ $activeSemester->tahun_akademik }} - {{ ucfirst($activeSemester->jenis) }}</p>
            
            @php
                $firstKrs = $krsItems->first();
                $status = $firstKrs->status ?? 'draft';
            @endphp
            
            @if($status == 'approved')
                <span class="status-badge status-approved">✓ DISETUJUI</span>
            @elseif($status == 'submitted')
                <span class="status-badge status-submitted">⏳ MENUNGGU PERSETUJUAN</span>
            @endif
        </div>

        <!-- Student Info -->
        <div class="info-section">
            <table class="info-table">
                <tr>
                    <td>NIM</td>
                    <td>:</td>
                    <td>{{ $mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <td>Nama Mahasiswa</td>
                    <td>:</td>
                    <td>{{ $mahasiswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>:</td>
                    <td>{{ $mahasiswa->programStudi->nama_prodi ?? '-' }} ({{ $mahasiswa->programStudi->jenjang ?? '-' }})</td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>:</td>
                    <td>{{ $activeSemester->tahun_akademik }} - {{ ucfirst($activeSemester->jenis) }}</td>
                </tr>
                <tr>
                    <td>Tanggal Cetak</td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</td>
                </tr>
            </table>
        </div>

        <!-- KRS Table -->
        <table class="krs-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode MK</th>
                    <th>Nama Mata Kuliah</th>
                    <th>SKS</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($krsItems as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->mataKuliah->kode_mk }}</td>
                        <td>{{ $item->mataKuliah->nama_mk }}</td>
                        <td>{{ $item->mataKuliah->sks }}</td>
                        <td>
                            @if($item->is_mengulang)
                                <strong>Mengulang</strong>
                            @else
                                {{ ucfirst($item->mataKuliah->jenis) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
                
                @if($krsItems->count() == 0)
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">
                            Tidak ada mata kuliah dalam KRS
                        </td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>TOTAL SKS:</strong></td>
                    <td><strong>{{ $totalSks }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <!-- Info Box -->
        <div class="info-box">
            <strong>Catatan Penting:</strong>
            1. KRS yang telah disetujui tidak dapat diubah kecuali ada persetujuan khusus dari akademik.<br>
            2. Mahasiswa wajib mengikuti seluruh mata kuliah yang tercantum dalam KRS ini.<br>
            3. Simpan KRS ini dengan baik sebagai bukti pengambilan mata kuliah semester ini.<br>
            4. Untuk perubahan KRS, hubungi bagian akademik dengan batas waktu yang telah ditentukan.
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <p>Mahasiswa</p>
                <p class="name">{{ $mahasiswa->nama_lengkap }}</p>
                <p class="title">NIM: {{ $mahasiswa->nim }}</p>
            </div>
            
            <div class="signature-box">
                <p>Mengetahui,<br>Bagian Akademik</p>
                @if($status == 'approved' && $firstKrs->approvedBy)
                    <p class="name">{{ $firstKrs->approvedBy->name }}</p>
                    <p class="title">{{ $firstKrs->approved_at ? $firstKrs->approved_at->format('d/m/Y') : '' }}</p>
                @else
                    <p class="name">(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</p>
                    <p class="title">&nbsp;</p>
                @endif
            </div>
        </div>

        <!-- Footer Note -->
        <div style="margin-top: 30px; text-align: center; font-size: 11px; color: #666;">
            <p>Dokumen ini dicetak secara otomatis dari Sistem Informasi Akademik STAI AL-FATIH</p>
            <p>Printed: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <script>
        // Auto print on load (optional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
