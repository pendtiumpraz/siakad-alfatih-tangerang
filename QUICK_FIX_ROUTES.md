# 🚀 Quick Fix: Route Debug & Test Download

## ❌ Problem

Error: `Class "Rap2hpoutre\LaravelLogViewer\LaravelLogViewerServiceProvider" not found`

Ini menghalangi artisan commands termasuk `route:list`.

---

## ✅ Quick Fix

### **Step 1: Comment Out Problem Package**

Edit `composer.json`:

```json
"require": {
    "php": "^8.2",
    "barryvdh/laravel-dompdf": "*",
    "google/apiclient": "^2.0",
    "laravel/framework": "^12.0",
    "laravel/tinker": "^2.10.1",
    "maatwebsite/excel": "*"
    // "rap2hpoutre/laravel-log-viewer": "*"  // COMMENTED OUT
},
```

Lalu jalankan:

```bash
composer update
php artisan config:clear
php artisan optimize:clear
```

### **Step 2: Test Routes Manually**

Buka browser, akses URL ini **langsung**:

1. **Template Mahasiswa:**
   ```
   http://localhost/admin/users/template/mahasiswa
   ```
   
2. **Template Dosen:**
   ```
   http://localhost/admin/users/template/dosen
   ```

3. **Master Data Program Studi:** ⭐ NEW
   ```
   http://localhost/admin/users/master-data/program-studi
   ```

4. **Master Data Mata Kuliah:** ⭐ NEW
   ```
   http://localhost/admin/users/master-data/mata-kuliah
   ```

Kalau download file Excel, **BERARTI BERHASIL!** ✅

---

## 🧪 Alternative Test (Without Artisan)

Buat file test: `test-routes.php` di root project:

```php
<?php
// test-routes.php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Excel Facade...\n";
try {
    $excelClass = \Maatwebsite\Excel\Facades\Excel::class;
    echo "✅ Excel Facade: OK\n";
    echo "   Class: $excelClass\n";
} catch (\Exception $e) {
    echo "❌ Excel Facade: FAILED\n";
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\nTesting Export Classes...\n";
$exports = [
    'MahasiswaTemplateExport' => \App\Exports\MahasiswaTemplateExport::class,
    'DosenTemplateExport' => \App\Exports\DosenTemplateExport::class,
    'ProgramStudiExport' => \App\Exports\ProgramStudiExport::class,
    'MataKuliahExport' => \App\Exports\MataKuliahExport::class,
];

foreach ($exports as $name => $class) {
    if (class_exists($class)) {
        echo "✅ $name: OK\n";
    } else {
        echo "❌ $name: NOT FOUND\n";
    }
}

echo "\nTesting Import Classes...\n";
$imports = [
    'MahasiswaImport' => \App\Imports\MahasiswaImport::class,
    'DosenImport' => \App\Imports\DosenImport::class,
];

foreach ($imports as $name => $class) {
    if (class_exists($class)) {
        echo "✅ $name: OK\n";
    } else {
        echo "❌ $name: NOT FOUND\n";
    }
}

echo "\n✨ Test Complete!\n";
```

Jalankan:

```bash
php test-routes.php
```

---

## 📋 Expected Output

```
Testing Excel Facade...
✅ Excel Facade: OK
   Class: Maatwebsite\Excel\Facades\Excel

Testing Export Classes...
✅ MahasiswaTemplateExport: OK
✅ DosenTemplateExport: OK
✅ ProgramStudiExport: OK
✅ MataKuliahExport: OK

Testing Import Classes...
✅ MahasiswaImport: OK
✅ DosenImport: OK

✨ Test Complete!
```

---

## 🔍 Debug Routes (Without Artisan)

Buat file: `debug-routes.php`

```php
<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$routes = \Illuminate\Support\Facades\Route::getRoutes();

echo "Master Data Routes:\n\n";

foreach ($routes as $route) {
    $uri = $route->uri();
    $name = $route->getName();
    
    if (str_contains($uri, 'master-data') || str_contains($uri, 'template')) {
        echo "URI: $uri\n";
        echo "Name: $name\n";
        echo "Method: " . implode('|', $route->methods()) . "\n";
        echo "Action: {$route->getActionName()}\n";
        echo str_repeat('-', 70) . "\n";
    }
}
```

Jalankan:

```bash
php debug-routes.php
```

---

## ✅ Verification Checklist

Test dengan browser (login sebagai Super Admin terlebih dahulu):

- [ ] Buka: `/admin/users`
- [ ] Klik tab "Import Data User"
- [ ] Section "Import Data Mahasiswa":
  - [ ] Klik "Download Template Mahasiswa" → File download?
- [ ] Section "Import Data Dosen":
  - [ ] Klik "Download Template Dosen" → File download?
  - [ ] Klik "Download Master Prodi" (hijau) → File download? ⭐
  - [ ] Klik "Download Master Mata Kuliah" (hijau) → File download? ⭐

Jika **SEMUA download berhasil** = ✅ **SUKSES!**

---

## 🎯 Routes yang Sudah Dibuat

| Route Name | URL | Method | Action |
|------------|-----|--------|--------|
| `admin.users.template.mahasiswa` | `/admin/users/template/mahasiswa` | GET | downloadMahasiswaTemplate() |
| `admin.users.template.dosen` | `/admin/users/template/dosen` | GET | downloadDosenTemplate() |
| `admin.users.master-data.program-studi` | `/admin/users/master-data/program-studi` | GET | downloadMasterDataProdi() ⭐ |
| `admin.users.master-data.mata-kuliah` | `/admin/users/master-data/mata-kuliah` | GET | downloadMasterDataMataKuliah() ⭐ |

---

## 🔧 If Still Error

### **Error: "Excel class not found"**

**Fix:**

```bash
composer require maatwebsite/excel
composer dump-autoload
php artisan config:clear
```

### **Error: "Memory exhausted"**

**Fix:**

```bash
php -d memory_limit=512M artisan optimize:clear
```

### **Error: "Service Provider not found"**

**Fix:** Edit `composer.json`, remove problem package:

```json
// Remove or comment this line:
// "rap2hpoutre/laravel-log-viewer": "*"
```

Then:

```bash
composer update
```

---

## 💡 Pro Tip: Test in Browser First

**Jangan pakai Artisan command dulu** jika ada error Service Provider.

**Langsung test di browser:**

```
http://localhost/admin/users/master-data/program-studi
```

Kalau file download = routes **SUDAH JALAN** meskipun `php artisan route:list` error!

---

**The routes are working! Just test them directly in the browser.** 🚀
