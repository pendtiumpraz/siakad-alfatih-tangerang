# Fix Daftar Ulangs Table Error

## Error yang Terjadi:
```
Column not found: 1054 Unknown column 'daftar_ulangs.deleted_at'
Base table or view already exists: 1050 Table 'daftar_ulangs' already exists
```

## Penyebab:
Table `daftar_ulangs` sudah dibuat sebelumnya tapi **tidak lengkap** (missing kolom `deleted_at`).

---

## Solusi Cepat (Pilih Salah Satu):

### Opsi 1: Via MySQL Command (RECOMMENDED)

Jalankan command ini di terminal server:

```bash
mysql -u root -p siakad_db -e "ALTER TABLE daftar_ulangs ADD COLUMN deleted_at TIMESTAMP NULL AFTER updated_at;"
```

Ganti:
- `root` dengan username MySQL Anda
- `siakad_db` dengan nama database Anda
- Password akan ditanyakan setelah enter

### Opsi 2: Via PHP Artisan (Tinker)

```bash
cd /home/cintaban/siakad.diproses.online
php artisan tinker
```

Kemudian paste code ini:

```php
DB::statement('ALTER TABLE daftar_ulangs ADD COLUMN deleted_at TIMESTAMP NULL AFTER updated_at');
DB::table('migrations')->insert([
    'migration' => '2025_10_26_195402_create_daftar_ulangs_table',
    'batch' => DB::table('migrations')->max('batch') + 1
]);
echo "Fixed!\n";
exit;
```

### Opsi 3: Manual via phpMyAdmin / Adminer

1. Login ke phpMyAdmin / Adminer
2. Pilih database `siakad_db`
3. Klik table `daftar_ulangs`
4. Tab **Structure** → **Add column**
5. Isi:
   - Name: `deleted_at`
   - Type: `TIMESTAMP`
   - Null: ✅ (centang)
   - After: `updated_at`
6. Save

Kemudian tambah record di table `migrations`:
- Tab **SQL** di phpMyAdmin
- Paste query ini:

```sql
INSERT INTO migrations (migration, batch)
VALUES ('2025_10_26_195402_create_daftar_ulangs_table',
        (SELECT MAX(batch) + 1 FROM (SELECT batch FROM migrations) as temp));
```

---

## Verifikasi Fix Berhasil:

Jalankan command ini:

```bash
php artisan migrate:status
```

Harusnya migration `2025_10_26_195402_create_daftar_ulangs_table` sudah muncul dengan status **Ran**.

Test akses halaman Daftar Ulang:
- Admin → Menu **Daftar Ulang**
- Seharusnya tidak error lagi

---

## Jika Masih Error:

Hubungi developer dengan info:
1. Screenshot error lengkap
2. Output dari: `php artisan migrate:status`
3. Output dari: `DESCRIBE daftar_ulangs;` (via MySQL)

---

**Dibuat:** 27 Oktober 2025
**Developer:** Tim SIAKAD STAI AL-FATIH
