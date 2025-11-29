# Cara Enable PHP GD Extension

## Error yang Terjadi
```
The PHP GD extension is required, but is not installed.
```

## Penyebab
DomPDF library memerlukan GD extension untuk memproses gambar (logo) di PDF.

## Solusi

### Windows (XAMPP/Laragon)

1. **Buka file php.ini**
   - XAMPP: `C:\xampp\php\php.ini`
   - Laragon: `C:\laragon\bin\php\php8.x\php.ini`

2. **Cari baris berikut:**
   ```ini
   ;extension=gd
   ```

3. **Hapus tanda titik koma (;) di depannya:**
   ```ini
   extension=gd
   ```

4. **Save file php.ini**

5. **Restart Apache/Web Server**
   - XAMPP: Stop dan Start Apache di Control Panel
   - Laragon: Stop All → Start All

6. **Cek apakah GD sudah aktif:**
   ```bash
   php -m | findstr gd
   ```
   Atau buka: http://localhost/siakad-app/public (buat file phpinfo.php)

### Linux/Ubuntu

```bash
# Install GD extension
sudo apt-get update
sudo apt-get install php-gd

# Restart Apache/Nginx
sudo systemctl restart apache2
# atau
sudo systemctl restart nginx
sudo systemctl restart php8.1-fpm
```

### Verify GD is Enabled

Buat file `test-gd.php` di public folder:
```php
<?php
if (extension_loaded('gd')) {
    echo "GD Extension is enabled!";
    echo "<br>GD Version: " . gd_info()['GD Version'];
} else {
    echo "GD Extension is NOT enabled!";
}
```

Buka: http://localhost/siakad-app/public/test-gd.php

## Setelah GD Enabled

KHS PDF akan bisa:
- Menampilkan logo STAI AL FATIH
- Generate watermark background (optional)
- Process semua gambar dengan sempurna

## Troubleshooting

### GD masih belum aktif setelah di-enable?

1. **Pastikan edit php.ini yang benar:**
   ```bash
   php --ini
   ```
   Lihat "Loaded Configuration File" → Edit file itu

2. **Cek apakah ada php.ini lain:**
   - `php.ini-development`
   - `php.ini-production`
   
3. **Restart SEMUA service:**
   - Stop Apache
   - Stop MySQL (optional)
   - Close XAMPP/Laragon Control Panel
   - Buka lagi dan Start semua

4. **Cek di phpinfo():**
   Buat file `info.php`:
   ```php
   <?php phpinfo(); ?>
   ```
   Cari "gd" di halaman

### Error lain setelah enable GD?

Biasanya tidak ada masalah. GD adalah extension standar yang aman.

## Alternative: Temporary Disable Logo

Jika tidak bisa enable GD, bisa disable logo sementara (not recommended):
Edit `resources/views/mahasiswa/khs/pdf.blade.php` line ~52:
```php
<!-- Logo STAI AL FATIH -->
<!-- <img src="{{ public_path('images/logo-stai.png') }}" alt="Logo STAI AL FATIH"> -->
```

Tapi output PDF akan **tidak resmi** karena tanpa logo!
