<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Mahasiswa Telah Dibuat</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #2D5F3F 0%, #4A7C59 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 25px;
            font-size: 15px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border-left: 4px solid #2D5F3F;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .credentials-box h3 {
            margin: 0 0 15px 0;
            color: #2D5F3F;
            font-size: 16px;
        }
        .credential-item {
            margin: 12px 0;
            display: flex;
            align-items: center;
        }
        .credential-label {
            font-weight: 600;
            color: #555;
            min-width: 120px;
        }
        .credential-value {
            font-family: 'Courier New', monospace;
            background-color: #fff;
            padding: 8px 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            font-size: 14px;
            flex: 1;
        }
        .login-button {
            display: inline-block;
            background-color: #2D5F3F;
            color: white !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .login-button:hover {
            background-color: #1e4229;
        }
        .info-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #856404;
        }
        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 25px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 13px;
            color: #666;
        }
        .footer p {
            margin: 5px 0;
        }
        .islamic-quote {
            font-style: italic;
            color: #2D5F3F;
            margin-top: 15px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 Selamat!</h1>
            <p>Akun Mahasiswa Anda Telah Aktif</p>
        </div>

        <div class="content">
            <div class="greeting">
                <strong>Assalamu'alaikum Warahmatullahi Wabarakatuh,</strong><br>
                Saudara/i <strong>{{ $daftarUlang->pendaftar->nama_lengkap }}</strong>
            </div>

            <div class="message">
                <p>Alhamdulillah, kami dengan senang hati memberitahukan bahwa <strong>daftar ulang Anda telah diverifikasi</strong> dan akun mahasiswa Anda telah berhasil dibuat.</p>

                <p>Anda sekarang dapat mengakses Sistem Informasi Akademik (SIAKAD) STAI AL-FATIH untuk melihat jadwal kuliah, nilai, pembayaran, dan informasi akademik lainnya.</p>
            </div>

            <div class="credentials-box">
                <h3>📋 Informasi Login</h3>
                <div class="credential-item">
                    <span class="credential-label">NIM Sementara:</span>
                    <span class="credential-value">{{ $username }}</span>
                </div>
                <div class="credential-item">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
            </div>

            <div class="info-box">
                <p><strong>⚠️ Penting:</strong> Harap segera ganti password Anda setelah login pertama kali untuk keamanan akun Anda.</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="login-button">
                    🔐 Login ke SIAKAD
                </a>
                <p style="font-size: 13px; color: #666; margin-top: 10px;">
                    Atau kunjungi: <a href="{{ $loginUrl }}" style="color: #2D5F3F;">{{ $loginUrl }}</a>
                </p>
            </div>

            <div class="divider"></div>

            <div style="font-size: 14px; color: #555;">
                <p><strong>Langkah Selanjutnya:</strong></p>
                <ol style="padding-left: 20px;">
                    <li>Login menggunakan NIM dan password di atas</li>
                    <li>Lengkapi profil Anda jika diperlukan</li>
                    <li>Ganti password untuk keamanan</li>
                    <li>Cek jadwal kuliah dan informasi akademik lainnya</li>
                    <li>Pantau status pembayaran Anda</li>
                </ol>
            </div>

            <div class="divider"></div>

            <div style="font-size: 14px; color: #555;">
                <p><strong>Butuh Bantuan?</strong></p>
                <p>Jika Anda mengalami kesulitan login atau ada pertanyaan, silakan hubungi:</p>
                <ul style="padding-left: 20px; margin: 10px 0;">
                    <li>📧 Email: {{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}</li>
                    <li>📱 WhatsApp: {{ \App\Models\SystemSetting::get('spmb_whatsapp', '081234567890') }}</li>
                    <li>☎️ Telepon: {{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}</li>
                </ul>
            </div>
        </div>

        <div class="footer">
            <p><strong>STAI AL-FATIH Tangerang</strong></p>
            <p>{{ \App\Models\SystemSetting::get('institution_address', 'Tangerang, Banten') }}</p>
            <p class="islamic-quote">"طَلَبُ الْعِلْمِ فَرِيْضَةٌ عَلَى كُلِّ مُسْلِمٍ"</p>
            <p class="islamic-quote">"Menuntut ilmu adalah kewajiban bagi setiap muslim"</p>
        </div>
    </div>
</body>
</html>
