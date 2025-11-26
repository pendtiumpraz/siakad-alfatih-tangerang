# 🚨 URGENT FIX: Dependencies Missing

## ❌ Problem
```
Failed opening required 'google/apiclient-services/autoload.php'
```

**Vendor folder terhapus dan composer install dibatalkan.**

---

## ✅ SOLUTION - Pilih salah satu:

### **OPTION 1: Via Batch File (EASIEST)** ⭐ RECOMMENDED

1. **Double-click file ini:**
   ```
   INSTALL_DEPENDENCIES.bat
   ```

2. **Tunggu sampai selesai** (5-10 menit)
   - Jangan cancel/close window!
   - Tunggu sampai muncul: "Installation Complete!"

3. **Setelah selesai, jalankan server:**
   ```bash
   php artisan serve
   ```

---

### **OPTION 2: Via Command Manual**

Jalankan **satu per satu** di PowerShell/CMD:

```bash
# 1. Masuk ke folder project
cd D:\AI\siakad\siakad-app

# 2. Install dependencies (TUNGGU SAMPAI SELESAI!)
composer install --no-interaction

# 3. Generate autoload
composer dump-autoload -o

# 4. Clear cache
php artisan config:clear
php artisan cache:clear

# 5. Start server
php artisan serve
```

---

## ⏱️ PENTING!

- **Jangan cancel** `composer install`!
- Prosesnya **5-10 menit**
- Progress akan terlihat: "Installing... Extracting..."
- Tunggu sampai muncul: "Generating optimized autoload files"

---

## ✅ Success Indicator

Setelah `composer install` selesai, cek:

```bash
php artisan --version
```

Kalau muncul:
```
Laravel Framework 12.x.x
```

= ✅ **BERHASIL!**

Lalu jalankan:
```bash
php artisan serve
```

---

## 🔍 Verify Dependencies

Cek apakah file sudah ada:

```bash
dir vendor\google\apiclient-services\autoload.php
```

Kalau muncul file info = ✅ Dependencies installed!

---

## 📋 Expected Output dari Composer Install

```
Loading composer repositories with package information
Installing dependencies from lock file
Package operations: 150 installs, 0 updates, 0 removals
  - Installing vendor1/package1
  - Installing vendor2/package2
  ...
  - Extracting archive
  ...
Generating optimized autoload files
> @php artisan package:discover --ansi

   INFO  Discovering packages.

   ...

✓ All packages discovered successfully
```

**Total waktu: ~5-10 menit tergantung koneksi internet**

---

## ⚠️ Jika Masih Error

### **Error: "memory exhausted"**

Edit file: `composer.json`, tambahkan:

```json
"config": {
    "optimize-autoloader": true,
    "preferred-install": "dist",
    "sort-packages": true,
    "allow-plugins": {
        "pestphp/pest-plugin": true,
        "php-http/discovery": true
    },
    "process-timeout": 0
}
```

Lalu:
```bash
php -d memory_limit=512M composer install
```

### **Error: "timeout"**

```bash
composer config --global process-timeout 0
composer install
```

---

## 🎯 AFTER FIX

Setelah `composer install` berhasil dan server jalan:

1. ✅ Test login: `http://127.0.0.1:8000/login`
2. ✅ Test admin: `http://127.0.0.1:8000/admin/users`
3. ✅ Test download master data
4. ✅ Test download template

---

**JALANKAN: `INSTALL_DEPENDENCIES.bat` atau `composer install` sekarang!**

**JANGAN cancel lagi!** ⏱️ Tunggu sampai selesai.
