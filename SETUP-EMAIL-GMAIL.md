# Setup Email dengan Gmail untuk SIAKAD

Panduan lengkap untuk mengkonfigurasi Gmail agar bisa mengirim email otomatis dari aplikasi SIAKAD.

## 📋 Langkah-Langkah Setup

### 1. Buat App Password di Gmail

Karena Gmail tidak mengizinkan login langsung menggunakan password akun untuk keamanan, Anda harus membuat **App Password**.

#### Langkah-langkah:

1. **Buka Google Account Settings**
   - Kunjungi: https://myaccount.google.com/
   - Login dengan akun Gmail yang akan digunakan

2. **Aktifkan 2-Step Verification** (jika belum aktif)
   - Klik **Security** di menu kiri
   - Scroll ke bagian **2-Step Verification**
   - Klik **Get Started** dan ikuti petunjuk

3. **Buat App Password**
   - Tetap di halaman **Security**
   - Scroll ke bagian **2-Step Verification**
   - Klik **App passwords**
   - Pilih **Select app**: Mail
   - Pilih **Select device**: Other (Custom name)
   - Ketik nama: `SIAKAD STAI AL-FATIH`
   - Klik **Generate**
   - **Simpan password 16 karakter** yang muncul (tanpa spasi)

### 2. Update File .env

Buka file `.env` di root project dan update bagian mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com          # Ganti dengan email Gmail Anda
MAIL_PASSWORD=abcd efgh ijkl mnop      # Ganti dengan App Password (16 karakter)
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=email@gmail.com      # Ganti dengan email Gmail Anda
MAIL_FROM_NAME="${APP_NAME}"
```

**Contoh Konkret:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=admin@staialfatih.ac.id
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@staialfatih.ac.id
MAIL_FROM_NAME="SIAKAD STAI AL-FATIH"
```

### 3. Clear Cache

Setelah update `.env`, jalankan command untuk clear config cache:

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Test Email

Untuk test email berhasil terkirim atau tidak, Anda bisa:

#### Option 1: Gunakan Tinker
```bash
php artisan tinker
```

Kemudian jalankan:
```php
Mail::raw('Test email dari SIAKAD', function($message) {
    $message->to('test@example.com')
            ->subject('Test Email');
});
```

#### Option 2: Test melalui Daftar Ulang
- Verifikasi salah satu daftar ulang dari admin panel
- Cek email mahasiswa yang bersangkutan
- Email otomatis akan terkirim berisi username dan password

## 🔍 Troubleshooting

### Error: "Username and Password not accepted"

**Penyebab:**
- App Password belum dibuat atau salah
- 2-Step Verification belum diaktifkan
- Password masih menggunakan password akun, bukan App Password

**Solusi:**
- Pastikan sudah buat App Password (16 karakter)
- Jangan gunakan password akun Gmail biasa
- Pastikan tidak ada spasi di App Password di file `.env`

### Error: "Connection timeout"

**Penyebab:**
- Port 587 atau 465 diblokir oleh firewall/hosting
- MAIL_HOST salah

**Solusi:**
- Coba ganti `MAIL_PORT=465` dan `MAIL_ENCRYPTION=ssl`
- Hubungi provider hosting untuk unblock port SMTP

### Email masuk ke Spam

**Solusi:**
1. **Setup SPF Record** di DNS domain Anda:
   ```
   Type: TXT
   Name: @
   Value: v=spf1 include:_spf.google.com ~all
   ```

2. **Setup DKIM** (di Google Workspace jika menggunakan custom domain)

3. **Setup DMARC Record**:
   ```
   Type: TXT
   Name: _dmarc
   Value: v=DMARC1; p=none; rua=mailto:admin@yourdomain.com
   ```

### Email tidak terkirim tapi tidak ada error

**Penyebab:**
- Queue driver aktif dan jobs tidak dijalankan

**Solusi:**
Cek `.env` bagian queue:
```env
QUEUE_CONNECTION=sync
```

Jika menggunakan queue (redis/database), jalankan:
```bash
php artisan queue:work
```

## 📧 Alternatif Lain (Selain Gmail)

### Menggunakan Mailtrap (untuk Testing)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

### Menggunakan SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls
```

### Menggunakan Mailgun
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-mailgun-secret
```

## 🎯 Kapan Email Dikirim?

Email otomatis akan dikirim dalam kasus berikut:

### 1. Verifikasi Daftar Ulang
- **Trigger:** Admin klik tombol "Verifikasi & Buat Akun" di halaman daftar ulang
- **Recipient:** Email mahasiswa yang terdaftar
- **Content:** Username (NIM Sementara) dan Password untuk login

### Template Email:
- Subject: `Akun Mahasiswa STAI AL-FATIH Telah Dibuat`
- Berisi: NIM, Password, Link Login, dan Petunjuk
- Design: Professional dengan branding STAI AL-FATIH

## 📝 Catatan Penting

1. **Keamanan:**
   - Jangan commit file `.env` ke Git
   - Simpan App Password dengan aman
   - Ganti password secara berkala

2. **Limit Gmail:**
   - Gmail SMTP memiliki limit 500 email per hari untuk akun gratis
   - Untuk volume tinggi, gunakan Google Workspace atau layanan email transactional

3. **Monitoring:**
   - Cek log di `storage/logs/laravel.log` jika ada error
   - Monitor email masuk spam atau tidak

4. **Best Practice:**
   - Gunakan email khusus untuk aplikasi (bukan email personal)
   - Aktifkan alert di Gmail untuk aktivitas mencurigakan

## 🚀 Setelah Setup Berhasil

Setelah email berhasil dikonfigurasi, fitur yang akan aktif:

✅ Email otomatis saat daftar ulang diverifikasi
✅ Mahasiswa menerima username dan password
✅ Link login langsung ke SIAKAD
✅ Informasi kontak untuk bantuan

---

**Dibuat oleh:** Tim Developer SIAKAD STAI AL-FATIH
**Terakhir Update:** 27 Oktober 2025
