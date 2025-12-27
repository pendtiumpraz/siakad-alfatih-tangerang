@extends('layouts.legal')

@section('title', 'Privacy Policy')
@section('page-title', 'Kebijakan Privasi')

@section('content')
<p style="color: #555555; font-style: italic;">
    Terakhir diperbarui: {{ date('d F Y') }}
</p>

<h2><i class="fas fa-info-circle mr-2" style="color: #D4AF37;"></i> 1. Pendahuluan</h2>
<p>
    Selamat datang di Sistem Informasi Akademik (SIAKAD) STAI AL-FATIH. Kami berkomitmen untuk melindungi privasi dan keamanan data pribadi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi Anda.
</p>
<p>
    Dengan menggunakan layanan SIAKAD, Anda menyetujui praktik yang dijelaskan dalam Kebijakan Privasi ini.
</p>

<h2><i class="fas fa-database mr-2" style="color: #D4AF37;"></i> 2. Informasi yang Kami Kumpulkan</h2>

<h3>2.1 Informasi yang Anda Berikan</h3>
<ul>
    <li><strong>Data Identitas:</strong> Nama lengkap, NIM/NIDN, tempat dan tanggal lahir, jenis kelamin</li>
    <li><strong>Data Kontak:</strong> Alamat email, nomor telepon, alamat tempat tinggal</li>
    <li><strong>Data Akademik:</strong> Program studi, semester, mata kuliah, nilai, KHS, dan transkrip</li>
    <li><strong>Data Keuangan:</strong> Riwayat pembayaran SPP dan biaya akademik lainnya</li>
    <li><strong>Dokumen Pendukung:</strong> Foto, bukti pembayaran, dan dokumen akademik lainnya</li>
</ul>

<h3>2.2 Informasi yang Dikumpulkan Secara Otomatis</h3>
<ul>
    <li>Alamat IP dan informasi perangkat</li>
    <li>Log aktivitas sistem (waktu login, halaman yang diakses)</li>
    <li>Cookies dan teknologi pelacakan serupa</li>
</ul>

<h2><i class="fas fa-cogs mr-2" style="color: #D4AF37;"></i> 3. Penggunaan Informasi</h2>
<p>Kami menggunakan informasi yang dikumpulkan untuk:</p>
<ol>
    <li>Mengelola akun pengguna dan memberikan akses ke layanan SIAKAD</li>
    <li>Memproses pendaftaran mahasiswa baru dan daftar ulang</li>
    <li>Mengelola data akademik (KRS, KHS, nilai, dan jadwal)</li>
    <li>Memproses dan mencatat pembayaran SPP dan biaya akademik</li>
    <li>Mengirimkan pengumuman dan informasi penting terkait akademik</li>
    <li>Meningkatkan kualitas layanan dan pengalaman pengguna</li>
    <li>Memenuhi kewajiban hukum dan peraturan yang berlaku</li>
</ol>

<h2><i class="fas fa-share-alt mr-2" style="color: #D4AF37;"></i> 4. Berbagi Informasi</h2>
<p>Kami tidak menjual atau menyewakan data pribadi Anda kepada pihak ketiga. Informasi dapat dibagikan kepada:</p>
<ul>
    <li><strong>Internal STAI AL-FATIH:</strong> Dosen, staf akademik, dan bagian keuangan untuk keperluan operasional</li>
    <li><strong>Kementerian Pendidikan:</strong> Untuk pelaporan data PDDIKTI sesuai regulasi</li>
    <li><strong>Penyedia Layanan:</strong> Google Drive untuk penyimpanan dokumen (dengan enkripsi)</li>
    <li><strong>Otoritas Hukum:</strong> Jika diwajibkan oleh hukum atau perintah pengadilan</li>
</ul>

<h2><i class="fas fa-shield-alt mr-2" style="color: #D4AF37;"></i> 5. Keamanan Data</h2>
<p>Kami menerapkan langkah-langkah keamanan untuk melindungi data Anda:</p>
<ul>
    <li>Enkripsi data saat transmisi menggunakan HTTPS/SSL</li>
    <li>Password terenkripsi dengan algoritma bcrypt</li>
    <li>Akses berbasis peran (role-based access control)</li>
    <li>Backup data secara berkala</li>
    <li>Monitoring dan audit log aktivitas sistem</li>
</ul>

<h2><i class="fas fa-clock mr-2" style="color: #D4AF37;"></i> 6. Retensi Data</h2>
<p>
    Data pribadi Anda disimpan selama Anda terdaftar sebagai civitas akademika STAI AL-FATIH. Setelah Anda lulus atau tidak lagi terdaftar, data akademik akan disimpan sesuai dengan ketentuan retensi arsip perguruan tinggi yang berlaku (minimal 10 tahun untuk dokumen akademik).
</p>

<h2><i class="fas fa-user-check mr-2" style="color: #D4AF37;"></i> 7. Hak Pengguna</h2>
<p>Anda memiliki hak untuk:</p>
<ul>
    <li><strong>Akses:</strong> Melihat data pribadi Anda yang tersimpan di sistem</li>
    <li><strong>Koreksi:</strong> Memperbarui atau memperbaiki data yang tidak akurat</li>
    <li><strong>Portabilitas:</strong> Mengunduh data akademik Anda (KHS, transkrip)</li>
    <li><strong>Pengaduan:</strong> Mengajukan keluhan jika ada pelanggaran privasi</li>
</ul>
<p>
    Untuk menggunakan hak-hak ini, silakan hubungi bagian akademik atau administrator sistem.
</p>

<h2><i class="fas fa-cookie mr-2" style="color: #D4AF37;"></i> 8. Cookies</h2>
<p>
    SIAKAD menggunakan cookies untuk menjaga sesi login dan meningkatkan pengalaman pengguna. Cookies yang kami gunakan:
</p>
<ul>
    <li><strong>Session Cookies:</strong> Untuk menjaga status login Anda</li>
    <li><strong>CSRF Token:</strong> Untuk keamanan form</li>
    <li><strong>Remember Me:</strong> Opsional, untuk login otomatis</li>
</ul>

<h2><i class="fas fa-child mr-2" style="color: #D4AF37;"></i> 9. Perlindungan Anak</h2>
<p>
    SIAKAD tidak dirancang untuk digunakan oleh anak di bawah usia 17 tahun. Jika Anda adalah orang tua/wali dan mengetahui bahwa anak Anda telah memberikan informasi pribadi kepada kami, silakan hubungi kami.
</p>

<h2><i class="fas fa-edit mr-2" style="color: #D4AF37;"></i> 10. Perubahan Kebijakan</h2>
<p>
    Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan akan diberitahukan melalui pengumuman di sistem atau email. Penggunaan berkelanjutan setelah perubahan menunjukkan penerimaan Anda terhadap kebijakan yang diperbarui.
</p>

<h2><i class="fas fa-envelope mr-2" style="color: #D4AF37;"></i> 11. Hubungi Kami</h2>
<p>Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi:</p>
<div style="background: linear-gradient(to right, #f0fdf4, #dcfce7); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #2D5F3F; margin-top: 1rem;">
    <p style="margin-bottom: 0.5rem;"><strong style="color: #2D5F3F;">STAI AL-FATIH</strong></p>
    <p style="margin-bottom: 0.3rem;"><i class="fas fa-envelope mr-2" style="color: #D4AF37;"></i> Email: {{ \App\Models\SystemSetting::get('spmb_email', 'admin@staialfatih.ac.id') }}</p>
    <p style="margin-bottom: 0.3rem;"><i class="fas fa-phone mr-2" style="color: #D4AF37;"></i> Telepon: {{ \App\Models\SystemSetting::get('spmb_phone', '021-12345678') }}</p>
    <p style="margin-bottom: 0;"><i class="fab fa-whatsapp mr-2" style="color: #25D366;"></i> WhatsApp: {{ \App\Models\SystemSetting::get('spmb_whatsapp', '6281234567890') }}</p>
</div>

<p class="update-date">
    <i class="fas fa-calendar-alt mr-1"></i> Dokumen ini terakhir diperbarui pada {{ date('d F Y') }}
</p>
@endsection
