<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan RT - Yatim</title>
    <style>
        @page {
            margin: 2cm 2.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .header p {
            margin: 3px 0;
            font-size: 11pt;
        }
        .title {
            text-align: center;
            margin: 30px 0 20px 0;
        }
        .title h2 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
        }
        .nomor {
            text-align: center;
            margin-bottom: 30px;
            font-size: 11pt;
        }
        .content {
            text-align: justify;
            margin: 20px 0;
        }
        .content p {
            margin: 10px 0;
        }
        .identity {
            margin: 20px 0 20px 50px;
        }
        .identity table {
            width: 100%;
        }
        .identity td {
            padding: 5px 0;
        }
        .identity td:first-child {
            width: 200px;
        }
        .identity td:nth-child(2) {
            width: 20px;
            text-align: center;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
        .signature-content {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        .signature-space {
            height: 80px;
        }
        .footer {
            margin-top: 30px;
            font-size: 10pt;
            font-style: italic;
        }
        .stamp-area {
            text-align: center;
            margin-top: 10px;
            font-size: 9pt;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>RUKUN TETANGGA (RT) _____ / RUKUN WARGA (RW) _____</h3>
        <p>Kelurahan/Desa: ____________________</p>
        <p>Kecamatan: ____________________</p>
        <p>Kabupaten/Kota: ____________________</p>
    </div>

    <div class="title">
        <h2>SURAT KETERANGAN</h2>
    </div>

    <div class="nomor">
        Nomor: _____ / RT. _____ / _________ / {{ date('Y') }}
    </div>

    <div class="content">
        <p>Yang bertanda tangan di bawah ini Ketua RT _____ / RW _____ Kelurahan/Desa _____________, Kecamatan _____________, Kabupaten/Kota _____________, dengan ini menerangkan bahwa:</p>
    </div>

    <div class="identity">
        <table>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>_____________________________________</td>
            </tr>
            <tr>
                <td>Tempat, Tanggal Lahir</td>
                <td>:</td>
                <td>_____________________________________</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>_____________________________________</td>
            </tr>
            <tr>
                <td>Agama</td>
                <td>:</td>
                <td>_____________________________________</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>:</td>
                <td>_____________________________________</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>_____________________________________</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>_____________________________________</td>
            </tr>
        </table>
    </div>

    <div class="content">
        <p>Adalah benar warga yang berdomisili di wilayah RT _____ / RW _____ dan kami nyatakan bahwa yang bersangkutan adalah <strong>ANAK YATIM</strong> (ayah/ibu kandung telah meninggal dunia).</p>
        
        <p><strong>Status Orang Tua:</strong></p>
        <div class="identity">
            <table>
                <tr>
                    <td>Nama Ayah Kandung</td>
                    <td>:</td>
                    <td>_____________________________________</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>☐ Masih Hidup &nbsp;&nbsp;&nbsp; ☐ Meninggal Dunia</td>
                </tr>
                <tr>
                    <td>Nama Ibu Kandung</td>
                    <td>:</td>
                    <td>_____________________________________</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>☐ Masih Hidup &nbsp;&nbsp;&nbsp; ☐ Meninggal Dunia</td>
                </tr>
            </table>
        </div>

        <p>Surat keterangan ini dibuat dengan sebenarnya dan dapat digunakan sebagaimana mestinya untuk keperluan <strong>PENDAFTARAN MAHASISWA BARU STAI AL-FATIH TANGERANG</strong>.</p>
    </div>

    <div class="signature">
        <div class="signature-content">
            <p>_____________, _______________</p>
            <p>Ketua RT _____ / RW _____</p>
            <div class="signature-space"></div>
            <p style="border-top: 1px solid #000; display: inline-block; padding-top: 5px; min-width: 180px;">
                ( ____________________ )
            </p>
            <div class="stamp-area">
                <p><em>(Tempel Materai 10.000 & Cap RT)</em></p>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Catatan:</strong></p>
        <ul>
            <li>Surat ini hanya berlaku untuk keperluan pendaftaran mahasiswa baru</li>
            <li>Harap dilengkapi dengan materai 10.000 dan cap RT</li>
            <li>Lampirkan fotocopy KTP Ketua RT dan KK yang bersangkutan</li>
        </ul>
    </div>
</body>
</html>
