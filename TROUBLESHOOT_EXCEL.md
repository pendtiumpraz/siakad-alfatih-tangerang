# 🔧 Troubleshooting: Excel Class Not Found

## ❌ Error yang Muncul

```
Class "Maatwebsite\Excel\Facades\Excel" not found
```

## ✅ Solusi Lengkap

### **Solusi 1: Install Package (RECOMMENDED)**

Jalankan command berikut **satu per satu**:

```bash
# 1. Clear cache terlebih dahulu
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear

# 2. Install package maatwebsite/excel
composer require maatwebsite/excel

# 3. Publish config (optional)
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"

# 4. Clear cache lagi
php artisan config:clear
php artisan cache:clear
```

### **Solusi 2: Fix Composer Dependencies**

Jika ada error dependency:

```bash
# Hapus vendor dan install ulang
rm -rf vendor
rm composer.lock
composer install
```

### **Solusi 3: Check Autoload**

```bash
composer dump-autoload
php artisan optimize:clear
```

---

## 🧪 Testing

Setelah install, test dengan:

```bash
php artisan tinker
```

Lalu ketik:

```php
class_exists('Maatwebsite\Excel\Facades\Excel');
// Should return: true

Excel::download(new \App\Exports\MahasiswaTemplateExport, 'test.xlsx');
// Should start download
```

---

## 🎯 Verifikasi Package Terinstall

Cek apakah package sudah terinstall:

```bash
composer show maatwebsite/excel
```

Output yang benar:

```
name     : maatwebsite/excel
descrip. : Laravel Excel library
versions : * 3.1.x-dev
type     : library
...
```

---

## 📋 Alternative Solution: Gunakan Class Langsung

Jika facade masih error, edit `SuperAdminController.php`:

### **Sebelum:**

```php
use Maatwebsite\Excel\Facades\Excel;

return Excel::download(new MahasiswaTemplateExport, 'template.xlsx');
```

### **Sesudah:**

```php
use Maatwebsite\Excel\Excel as ExcelExporter;

public function __construct(protected ExcelExporter $excel)
{
}

return $this->excel->download(new MahasiswaTemplateExport, 'template.xlsx');
```

---

## ⚠️ Error Lain yang Mungkin Muncul

### **1. Service Provider Not Found**

**Error:**
```
LaravelLogViewerServiceProvider not found
```

**Solusi:**
```bash
composer require rap2hpoutre/laravel-log-viewer
```

### **2. Memory Limit**

**Error:**
```
Allowed memory size exhausted
```

**Solusi:**

Edit `php.ini`:
```ini
memory_limit = 512M
```

Atau via command:
```bash
php -d memory_limit=512M artisan tinker
```

### **3. Timeout**

**Error:**
```
Maximum execution time exceeded
```

**Solusi:**

Edit `php.ini`:
```ini
max_execution_time = 300
```

---

## 🚀 Quick Fix (Production)

Jika semua solusi di atas gagal, gunakan **fallback approach**:

### **Edit SuperAdminController.php:**

```php
public function downloadMasterDataProdi()
{
    try {
        return Excel::download(new ProgramStudiExport, 'master_data_program_studi.xlsx');
    } catch (\Exception $e) {
        // Fallback: Export as JSON or CSV
        $data = ProgramStudi::where('is_active', true)->get();
        
        $csv = fopen('php://temp', 'w');
        fputcsv($csv, ['Kode Prodi', 'Nama Prodi', 'Jenjang', 'Akreditasi', 'Status']);
        
        foreach ($data as $row) {
            fputcsv($csv, [
                $row->kode_prodi,
                $row->nama_prodi,
                $row->jenjang,
                $row->akreditasi,
                $row->is_active ? 'Aktif' : 'Non-Aktif'
            ]);
        }
        
        rewind($csv);
        $output = stream_get_contents($csv);
        fclose($csv);
        
        return response($output, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="master_data_program_studi.csv"');
    }
}
```

---

## ✅ Checklist Debugging

- [ ] Package `maatwebsite/excel` ada di `composer.json`?
- [ ] Jalankan `composer install`
- [ ] Jalankan `composer dump-autoload`
- [ ] Clear semua cache (`php artisan optimize:clear`)
- [ ] Test `class_exists()` di tinker
- [ ] Cek `vendor/maatwebsite/excel` folder ada?
- [ ] Cek `config/excel.php` file ada?
- [ ] Try download template (test di browser)

---

## 📞 Masih Error?

Jika semua solusi di atas tidak berhasil:

1. **Screenshot error lengkap** (termasuk stack trace)
2. **Cek versi PHP**: `php -v` (minimal 8.1)
3. **Cek composer.json** dependency conflicts
4. **Cek Laravel version**: Laravel 12 compatible dengan Excel 3.1+
5. **Try manual download class** dari GitHub

---

## 🎯 Kesimpulan

**Yang paling sering berhasil:**

1. `composer require maatwebsite/excel` ← 90% sukses
2. `php artisan optimize:clear` ← Clear cache
3. Test download via browser ← Verify working

Jika masih error, gunakan **CSV fallback** (lebih reliable, tapi fitur terbatas).
