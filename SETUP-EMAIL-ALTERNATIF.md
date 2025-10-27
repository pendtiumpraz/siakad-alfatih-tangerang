# Setup Email - Solusi Alternatif

Jika Anda tidak bisa menggunakan App Password Gmail, berikut adalah solusi alternatif.

## ❌ Kenapa App Password Tidak Tersedia?

1. **2-Step Verification belum aktif** → Aktifkan dulu
2. **Akun Google Workspace** → Admin menonaktifkan App Password
3. **Akun baru** → Tunggu beberapa hari

---

## ✅ Solusi 1: Aktifkan 2-Step Verification Dulu

Sebelum bisa membuat App Password, **HARUS** aktifkan 2-Step Verification:

### Langkah-langkah:

1. Buka: https://myaccount.google.com/security
2. Scroll ke bagian **"Signing in to Google"**
3. Klik **"2-Step Verification"**
4. Klik **"Get Started"**
5. Ikuti petunjuk (biasanya pakai nomor HP)
6. Setelah aktif, baru bisa buat App Password

### Setelah 2-Step Verification aktif:

1. Kembali ke: https://myaccount.google.com/security
2. Di bagian **"2-Step Verification"**, klik
3. Scroll ke bawah, cari **"App passwords"**
4. Klik → Pilih "Mail" → "Other" → ketik "SIAKAD"
5. Copy password 16 karakter

---

## ✅ Solusi 2: Gunakan Mailtrap (Untuk Testing/Development)

**Mailtrap** adalah fake SMTP untuk development. Email tidak benar-benar terkirim, tapi Anda bisa lihat tampilannya.

### Cara Setup:

1. **Daftar gratis di:** https://mailtrap.io
2. **Buat inbox baru** (atau gunakan default)
3. **Copy credentials** di tab "SMTP Settings"
4. **Update `.env`:**

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@staialfatih.ac.id"
MAIL_FROM_NAME="SIAKAD STAI AL-FATIH"
```

### Kelebihan:
- ✅ Gratis untuk development
- ✅ Tidak perlu setup rumit
- ✅ Bisa lihat preview email
- ✅ Tidak spam email client

### Kekurangan:
- ❌ Email TIDAK benar-benar terkirim
- ❌ Hanya untuk testing, bukan production

---

## ✅ Solusi 3: Gunakan Brevo (dulu Sendinblue) - GRATIS & RECOMMENDED

**Brevo** memberikan **300 email gratis per hari** TANPA perlu kartu kredit.

### Cara Setup:

1. **Daftar gratis di:** https://www.brevo.com/
2. **Verifikasi email** Anda
3. **Buat SMTP Key:**
   - Klik nama Anda (kanan atas) → **SMTP & API**
   - Klik **"Create a new SMTP key"**
   - Copy **SMTP Key** yang muncul

4. **Update `.env`:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your_brevo_email@gmail.com
MAIL_PASSWORD=your_smtp_key_from_brevo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@staialfatih.ac.id"
MAIL_FROM_NAME="SIAKAD STAI AL-FATIH"
```

### Kelebihan:
- ✅ **300 email GRATIS per hari**
- ✅ Email benar-benar terkirim
- ✅ Dashboard untuk monitoring
- ✅ Cocok untuk production
- ✅ Tidak perlu kartu kredit

---

## ✅ Solusi 4: Gunakan Zoho Mail (FREE SMTP)

Zoho Mail menyediakan SMTP gratis untuk personal use.

### Cara Setup:

1. **Daftar akun gratis:** https://www.zoho.com/mail/
2. **Buat akun email** (contoh: admin@staialfatih.zohomails.com)
3. **Aktifkan SMTP:**
   - Settings → Mail Accounts → Account Details
   - Note down: smtp.zoho.com

4. **Update `.env`:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.zoho.com
MAIL_PORT=587
MAIL_USERNAME=admin@staialfatih.zohomails.com
MAIL_PASSWORD=your_zoho_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin@staialfatih.zohomails.com"
MAIL_FROM_NAME="SIAKAD STAI AL-FATIH"
```

---

## ✅ Solusi 5: Gmail dengan "Less Secure App" (DEPRECATED, tapi masih bisa)

⚠️ **Warning:** Google tidak merekomendasikan ini lagi, tapi kadang masih bisa.

### Cara Setup:

1. Buka: https://myaccount.google.com/lesssecureapps
2. Toggle **"Allow less secure apps"** ke ON
3. Update `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-gmail-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="SIAKAD STAI AL-FATIH"
```

⚠️ **Catatan:** Fitur ini akan dihapus Google, jangan gunakan untuk production.

---

## 🎯 Rekomendasi Berdasarkan Kebutuhan

### Untuk Development/Testing:
👉 **Mailtrap** - Gratis, mudah, untuk testing saja

### Untuk Production (Volume Rendah):
👉 **Brevo** - 300 email/hari gratis, cocok untuk kampus kecil

### Untuk Production (Volume Tinggi):
👉 **SendGrid** - 100 email/hari gratis, bisa upgrade
👉 **Amazon SES** - Sangat murah, $0.10 per 1000 email

### Jika Punya Custom Domain:
👉 **Google Workspace** - $6/bulan, professional
👉 **Zoho Mail** - Free tier tersedia

---

## 🔧 Setelah Update .env

Jangan lupa jalankan:

```bash
php artisan config:clear
php artisan cache:clear
```

---

## 🧪 Cara Test Email

### Test di Tinker:
```bash
php artisan tinker
```

```php
Mail::raw('Test email dari SIAKAD', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

### Test dengan Daftar Ulang:
1. Login sebagai admin
2. Buka menu **Daftar Ulang**
3. Klik salah satu yang **Pending**
4. Klik **"Verifikasi & Buat Akun"**
5. Cek email mahasiswa

---

## 📊 Perbandingan Service Email

| Service | Free Tier | Setup | Production Ready | Rekomendasi |
|---------|-----------|-------|------------------|-------------|
| **Gmail App Password** | ✅ 500/hari | Sedang | ⚠️ Cukup | ⭐⭐⭐ |
| **Brevo** | ✅ 300/hari | Mudah | ✅ Ya | ⭐⭐⭐⭐⭐ |
| **Mailtrap** | ✅ Unlimited | Mudah | ❌ Testing only | ⭐⭐⭐⭐ (dev) |
| **SendGrid** | ✅ 100/hari | Sedang | ✅ Ya | ⭐⭐⭐⭐ |
| **Zoho Mail** | ✅ Limited | Mudah | ✅ Ya | ⭐⭐⭐ |
| **Amazon SES** | ❌ Pay as you go | Sulit | ✅ Ya | ⭐⭐⭐⭐ |

---

## ❓ Masih Bingung?

**Saran Saya:**

1. **Untuk Testing (sekarang):** Pakai **Mailtrap** dulu
2. **Untuk Production (nanti):** Pakai **Brevo** (300 email gratis per hari)

**Langkah Tercepat (Mailtrap):**

1. Daftar di: https://mailtrap.io
2. Copy SMTP credentials dari dashboard
3. Update `.env` dengan credentials Mailtrap
4. `php artisan config:clear`
5. Test verifikasi daftar ulang
6. Lihat email di dashboard Mailtrap

Butuh bantuan setup? Beri tahu saya service mana yang mau digunakan!

---

**Dibuat oleh:** Tim Developer SIAKAD STAI AL-FATIH
**Terakhir Update:** 27 Oktober 2025
