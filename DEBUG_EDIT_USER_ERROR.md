# DEBUG GUIDE: Edit User Error

## 🔍 Diagnose Error Sebelum Fix

Sebelum melakukan fix, jalankan commands ini untuk diagnose masalah:

### 1. Check Migration Status
```bash
php artisan migrate:status
```
**Cari baris:**
```
Ran? 2025_11_13_012302_create_dosen_program_studi_table
```
**Expected:** ✅ Harus ada dan status "Ran"

---

### 2. Check Table Exists
```bash
php artisan tinker
```
Lalu jalankan:
```php
\Schema::hasTable('dosen_program_studi')
// Expected: true

\DB::table('dosen_program_studi')->count()
// Expected: angka berapa saja (0 atau lebih)

exit
```

---

### 3. Check Dosen Has ProgramStudis Relation
```bash
php artisan tinker
```
```php
$dosen = App\Models\Dosen::first();
$dosen->programStudis;
// Expected: Collection (bisa kosong atau ada isinya)
// Jika ERROR: ada masalah di model relation

exit
```

---

### 4. Check Laravel Logs
```bash
tail -50 storage/logs/laravel.log
```
**Cari error message yang contain:**
- `edit`
- `SuperAdminController`
- `programStudis`
- `dosen`

**Copy error lengkapnya!**

---

### 5. Check Permission
```bash
ls -la storage/logs/
```
**Expected:** File `laravel.log` harus writable (644 atau 666)

---

## 🚨 Common Errors & Solutions

### Error 1: "Class 'Schema' not found"
**Penyebab:** Missing import di controller

**Fix:**
```php
// Di SuperAdminController.php, pastikan ada:
use Illuminate\Support\Facades\Schema;
```

---

### Error 2: "Call to undefined method programStudis()"
**Penyebab:** Migration belum di-run atau Model belum ada relasi

**Fix:**
```bash
php artisan migrate
php artisan optimize:clear
```

---

### Error 3: "SQLSTATE[42S02]: Base table or view not found: 'dosen_program_studi'"
**Penyebab:** Migration belum di-run

**Fix:**
```bash
php artisan migrate
```

---

### Error 4: "Trying to get property 'nidn' of non-object"
**Penyebab:** `$user->dosen` is null

**Fix:** Sudah di-handle di commit `502c68a9` dengan `optional()` helper

---

### Error 5: "SQLSTATE[23000]: Integrity constraint violation"
**Penyebab:** Foreign key constraint (dosen_id tidak valid)

**Fix:**
```bash
# Check data integrity
php artisan tinker
```
```php
// Find dosen without valid user_id
App\Models\Dosen::whereNotIn('user_id', App\Models\User::pluck('id'))->get();

// Find dosen IDs that don't exist
\DB::table('dosen_program_studi')
   ->whereNotIn('dosen_id', App\Models\Dosen::pluck('id'))
   ->get();
```

---

## ✅ Step-by-Step Fix (Jalankan Berurutan!)

### Step 1: Pull Latest Code
```bash
cd /path/to/siakad-app
git pull origin main
```

### Step 2: Clear All Cache
```bash
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Step 3: Run Migration
```bash
php artisan migrate
```
**Expected output:**
```
Migrating: 2025_11_13_012302_create_dosen_program_studi_table
Migrated:  2025_11_13_012302_create_dosen_program_studi_table (XX.XXms)
```

### Step 4: Assign Existing Dosen
```bash
php artisan dosen:assign-prodi --all
```
**Expected output:**
```
🔄 Starting dosen to program studi assignment...
Found X dosen and X program studi

 X/X [============================] 100%

✅ Assignment completed!
```

### Step 5: Test Edit User
1. Login ke admin panel
2. Buka Users > Edit user dengan role dosen
3. **Jika masih error:**
   - Screenshot error message
   - Check `storage/logs/laravel.log`
   - Jalankan diagnose di atas

---

## 🔧 Manual Assign (If Command Fails)

Jika command `dosen:assign-prodi` gagal, assign manual via tinker:

```bash
php artisan tinker
```

```php
// Get all dosen
$dosens = App\Models\Dosen::all();

// Get first program studi
$prodi = App\Models\ProgramStudi::first();

// Assign each dosen
foreach ($dosens as $dosen) {
    if ($dosen->programStudis()->count() == 0) {
        $dosen->programStudis()->attach($prodi->id);
        echo "Assigned {$dosen->nama_lengkap}\n";
    }
}

exit
```

---

## 🎯 Verify Fix Worked

### Test 1: Check Dosen Has Program Studi
```bash
php artisan tinker
```
```php
$dosen = App\Models\Dosen::with('programStudis')->first();
echo "Dosen: {$dosen->nama_lengkap}\n";
echo "Program Studi: " . $dosen->programStudis->count() . "\n";
$dosen->programStudis->pluck('nama_prodi');
```
**Expected:** Setiap dosen punya minimal 1 program studi

---

### Test 2: Edit User via Browser
1. Login as super admin
2. Go to: `domain.com/admin/users`
3. Click "Edit" on dosen user
4. **Expected:** Form loaded tanpa error
5. Try save (tanpa change apapun)
6. **Expected:** Success message

---

## 📞 If Still Error - Send This Info

Jika setelah semua langkah masih error, kirim info ini:

1. **Laravel Log (Last 100 lines):**
   ```bash
   tail -100 storage/logs/laravel.log
   ```

2. **Migration Status:**
   ```bash
   php artisan migrate:status | grep dosen_program_studi
   ```

3. **Table Exists Check:**
   ```bash
   php artisan tinker
   ```
   ```php
   \Schema::hasTable('dosen_program_studi')
   \DB::table('dosen_program_studi')->count()
   ```

4. **Model Relation Check:**
   ```bash
   php artisan tinker
   ```
   ```php
   method_exists(App\Models\Dosen::first(), 'programStudis')
   ```

5. **PHP Version:**
   ```bash
   php -v
   ```

6. **Laravel Version:**
   ```bash
   php artisan --version
   ```

---

## 🎉 What Was Fixed

### Commit: `502c68a9`
- Wrapped dosen section with `@if($user->role === 'dosen')`
- Changed all `$user->dosen->property ?? ''` to `optional($user->dosen)->property`
- This prevents blade from trying to access properties on null object
- View won't even render dosen fields unless user is actually dosen role

### Commit: `0adf20f8`
- Load dosen separately from user in controller
- Set empty collection if programStudis fails to load
- Added artisan command `dosen:assign-prodi` for quick fix

### Commit: `d9895c95`
- Added program studi display in show page
- Load programStudis in show method with error handling

---

## 📝 Additional Notes

### Why Error Happens?
1. Migration tidak dijalankan → table `dosen_program_studi` tidak ada
2. Dosen existing tidak punya program studi assignment
3. View mencoba akses `$user->dosen` tapi null
4. Blade gagal compile karena try akses property of null

### How Fix Works?
1. `optional()` helper returns null-safe proxy
2. `@if` wrapper prevents rendering if not dosen
3. Controller loads relation defensively with try-catch
4. Command assigns existing dosen to program studi
5. Migration creates proper table structure

### Prevention
- Always run `php artisan migrate` after pull
- Always seed data properly on fresh installation
- Use `optional()` or null coalescing in blade for safety
- Add defensive checks in controller for new relations
