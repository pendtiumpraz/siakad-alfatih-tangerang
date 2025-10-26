# Fix Timeout di Shared Hosting (DomaiNesia)

## Masalah
- Form submit reload ke `/spmb/register` (tidak redirect ke `/spmb/result`)
- Tidak ada error log
- Ini karena **server timeout** saat upload ke Google Drive

## Solusi untuk Shared Hosting

### 1. Sudah Dikerjakan ✅
- File `.user.ini` sudah dibuat di `public/` folder
- Setting timeout PHP sudah ditambahkan ke `.htaccess`

### 2. Deploy File Baru
```bash
git pull origin main
```

Pastikan file ini ada di server:
- `public/.user.ini` ✅ (baru dibuat)
- `public/.htaccess` ✅ (sudah ada)

### 3. Restart PHP (jika ada akses)
Jika ada PHP Selector di cPanel:
1. Login cPanel → Select PHP Version
2. Klik "Save" untuk restart PHP

### 4. Test Upload
Test dengan **1-2 dokumen dulu**:
1. Upload hanya foto + ijazah
2. Cek apakah redirect ke `/spmb/result?nomor_pendaftaran=SPMBxxxx`
3. Jika berhasil, coba upload semua 7 dokumen

### 5. Cek Error Log
Jika masih gagal, cek di cPanel:
1. cPanel → File Manager
2. Buka `public_path/error_log` atau `storage/logs/laravel.log`
3. Atau akses: `https://siakad.diproses.online/debug-logs?filter=upload`

## Jika Masih Timeout

### Opsi 1: Hubungi DomaiNesia Support
Shared hosting biasanya punya **hard limit** (60-120s) yang tidak bisa diubah via `.user.ini`.

**Request ke support:**
```
Subject: Increase PHP Execution Time untuk domain siakad.diproses.online

Halo DomaiNesia,

Saya butuh increase PHP execution time untuk aplikasi SIAKAD:
- Domain: siakad.diproses.online
- Kebutuhan: max_execution_time = 300 (5 menit)
- Alasan: Upload multiple dokumen ke Google Drive API
- Path: public/.user.ini sudah dikonfigurasi

Mohon bantuan untuk enable atau increase timeout limit.

Terima kasih.
```

### Opsi 2: Upgrade Hosting Plan
Jika DomaiNesia tidak bisa increase timeout:
- Upgrade ke VPS atau Cloud Hosting
- VPS memberikan full control untuk setting timeout

### Opsi 3: Kurangi Jumlah Dokumen per Upload
Bagi upload jadi 2 step:
- Step 1: Upload foto + ijazah + transkrip + KTP
- Step 2: Upload KK + akta + SKTM

Tapi ini butuh perubahan code signifikan.

## Cek Timeout Setting Aktif

Buat file `public/test-timeout.php`:
```php
<?php
echo "Current Settings:\n";
echo "max_execution_time: " . ini_get('max_execution_time') . "\n";
echo "max_input_time: " . ini_get('max_input_time') . "\n";
echo "post_max_size: " . ini_get('post_max_size') . "\n";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "memory_limit: " . ini_get('memory_limit') . "\n";

echo "\nTesting 60 second sleep...\n";
sleep(60);
echo "✅ Success! PHP can run for 60+ seconds.\n";
?>
```

Akses: `https://siakad.diproses.online/test-timeout.php`

- Jika timeout < 60 detik = server limit aktif
- Jika berhasil show "✅ Success" = PHP timeout OK, tapi Nginx/Apache yang limit

**HAPUS file setelah test!**
```bash
rm public/test-timeout.php
```

## Expected Behavior

### ✅ Success Flow:
1. User klik "Submit Pendaftaran"
2. Loading modal muncul
3. Upload 7 dokumen ke Google Drive (30-60 detik)
4. Redirect ke: `/spmb/result?nomor_pendaftaran=SPMBxxxx`
5. Success message: "Pendaftaran berhasil!"
6. localStorage cleared

### ❌ Timeout Flow (Current):
1. User klik "Submit Pendaftaran"
2. Loading modal muncul
3. Upload mulai...
4. **Server timeout (60-120s)** sebelum selesai
5. Page reload ke `/spmb/register` ← YOU ARE HERE
6. Form kosong (localStorage cleared)

## Next Steps

1. ✅ Pull latest code dengan `.user.ini`
2. ⏳ Test upload 1-2 dokumen
3. ⏳ Jika masih timeout, contact DomaiNesia support
4. ⏳ Alternative: upgrade ke VPS

## Contact Support

**DomaiNesia:**
- Email: cs@domainesia.com
- Live Chat: https://www.domainesia.com
- WhatsApp: 0274 5305505
