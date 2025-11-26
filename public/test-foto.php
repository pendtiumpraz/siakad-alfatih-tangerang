<!DOCTYPE html>
<html>
<head>
    <title>Debug Foto Mahasiswa</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .box { border: 2px solid #ddd; padding: 15px; margin: 10px 0; }
        .success { background: #d4edda; border-color: #28a745; }
        .error { background: #f8d7da; border-color: #dc3545; }
        img { max-width: 200px; border: 2px solid #007bff; }
    </style>
</head>
<body>
    <h1>Debug Foto Mahasiswa</h1>
    
    <div class="box">
        <h2>Instruksi:</h2>
        <ol>
            <li>Login ke sistem sebagai mahasiswa</li>
            <li>Buka halaman Profile</li>
            <li>Lihat apakah foto muncul</li>
            <li>Jika tidak, cek console browser (F12)</li>
        </ol>
    </div>
    
    <div class="box success">
        <h2>✅ Mahasiswa dengan Foto:</h2>
        <p><strong>NIM:</strong> 2024010001</p>
        <p><strong>Nama:</strong> Ahmad Fauzi</p>
        <p><strong>Foto ID:</strong> 1mK4Q8pDPuvQUF9sMZgEUrIHWTz_G9NFQ</p>
        <h3>Test URL Foto:</h3>
        <img src="https://drive.google.com/thumbnail?id=1mK4Q8pDPuvQUF9sMZgEUrIHWTz_G9NFQ&sz=w400" alt="Ahmad Fauzi">
        <p><small>Jika foto di atas muncul, berarti Google Drive URL bekerja dengan baik</small></p>
    </div>
    
    <div class="box error">
        <h2>❌ Mahasiswa TANPA Foto:</h2>
        <ul>
            <li>Fatimah Azzahra (2024010002)</li>
            <li>Muhammad Rizki (2024020001)</li>
            <li>Siti Aisyah (2024020002)</li>
            <li>Abdurrahman Wahid (2024030001)</li>
        </ul>
        <p><small>Jika Anda login sebagai salah satu dari mahasiswa di atas, wajar foto tidak muncul karena belum upload.</small></p>
    </div>
    
    <div class="box">
        <h2>Cara Upload Foto:</h2>
        <ol>
            <li>Login sebagai mahasiswa</li>
            <li>Buka halaman Profile</li>
            <li>Klik tombol "Edit Profil"</li>
            <li>Di bagian "Foto Profil", klik "Choose File"</li>
            <li>Pilih foto (JPG/PNG, max 2MB)</li>
            <li>Klik "Simpan Perubahan"</li>
            <li>Tunggu proses upload selesai</li>
            <li>Refresh halaman (F5)</li>
        </ol>
    </div>
</body>
</html>
