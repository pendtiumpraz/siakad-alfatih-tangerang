# Dosen Program Studi Assignment Seeder

## Overview

Sistem SIAKAD sekarang memiliki fitur untuk assign dosen ke multiple program studi. Setiap dosen **HARUS** di-assign minimal ke 1 program studi agar bisa mengakses sistem.

## Fresh Installation

Jika Anda melakukan **fresh install** atau **migrate:fresh**, semua dosen akan otomatis di-assign ke program studi melalui `DosenSeeder.php`.

```bash
php artisan migrate:fresh --seed
```

## Existing Database (Data Lama)

Jika database Anda sudah ada data dosen lama dan belum di-assign ke program studi:

### Option 1: Jalankan DosenProgramStudiSeeder

1. Edit file `database/seeders/DosenProgramStudiSeeder.php`
2. Sesuaikan mapping NIDN ke program studi di array `$assignments`:

```php
$assignments = [
    '0301108901' => ['PAI', 'ES'],    // NIDN Ahmad Fauzi -> PAI & ES
    '0415108802' => ['PAI'],          // NIDN Siti Nurhaliza -> PAI only
    '0520079001' => ['ES', 'PIAUD'],  // NIDN Budi Santoso -> ES & PIAUD
];
```

3. Jalankan seeder:

```bash
php artisan db:seed --class=DosenProgramStudiSeeder
```

**Note:** Dosen yang tidak ada di mapping akan di-assign ke **semua program studi** secara default.

### Option 2: Assign Manual via Web Interface

1. Login sebagai **Admin** atau **Super Admin**
2. Buka menu **User Management**
3. Edit setiap user dengan role **Dosen**
4. Centang checkbox **Program Studi** yang sesuai
5. Save

## Kode Program Studi STAI AL-FATIH

Program studi yang tersedia:

- **PAI** - Pendidikan Agama Islam (S1)
- **ES** - Ekonomi Syariah (S1)
- **PIAUD** - Pendidikan Islam Anak Usia Dini (S1)
- **MPI** - Manajemen Pendidikan Islam (S1)

## Struktur Database

### Table: `dosen_program_studi` (Pivot Table)

| Column              | Type      | Description                    |
|---------------------|-----------|--------------------------------|
| id                  | bigint    | Primary key                    |
| dosen_id            | bigint    | Foreign key to dosens table    |
| program_studi_id    | bigint    | Foreign key to program_studis  |
| created_at          | timestamp |                                |
| updated_at          | timestamp |                                |

**Unique Constraint:** (dosen_id, program_studi_id) - Prevent duplicate assignments

## Customize Assignment

### Edit DosenSeeder.php

Untuk menambah dosen baru dengan program studi assignment:

```php
$dosenData = [
    [
        'nidn' => '1234567890',
        'nama_lengkap' => 'Nama Dosen',
        'gelar_depan' => 'Dr.',
        'gelar_belakang' => 'M.Pd',
        'no_telepon' => '081234567890',
        'email_dosen' => 'email@example.com',
        'program_studi_codes' => ['PAI', 'ES'], // Assign ke PAI dan ES
    ],
];
```

### Edit DosenProgramStudiSeeder.php

Untuk assign dosen existing:

```php
$assignments = [
    'NIDN_DOSEN' => ['KODE_PRODI_1', 'KODE_PRODI_2'],
];
```

## Troubleshooting

### Error: "Anda belum di-assign ke program studi manapun"

**Cause:** Dosen belum di-assign ke program studi apapun.

**Solution:**
1. Run `DosenProgramStudiSeeder`, OR
2. Assign manual via admin panel

### Error 500 saat edit user dosen

**Cause:** Tabel `dosen_program_studi` belum ada.

**Solution:**
```bash
php artisan migrate
```

### Dosen tidak bisa lihat jadwal/nilai/KHS

**Cause:** Dosen belum di-assign ke program studi yang terkait dengan mata kuliah tersebut.

**Solution:** Assign dosen ke program studi yang sesuai via admin panel.

## Important Notes

1. **Minimal 1 Program Studi:** Setiap dosen HARUS di-assign minimal ke 1 program studi
2. **Multiple Assignment:** Dosen bisa di-assign ke multiple program studi
3. **Scope Terbatas:** Dosen hanya bisa melihat/mengelola data dari program studi yang di-assign
4. **Backward Compatible:** Sistem tetap berfungsi meskipun tabel pivot belum ada (dengan error handling)

## Migration Command

```bash
# Run migration
php artisan migrate

# Fresh migrate with seed
php artisan migrate:fresh --seed

# Seed only DosenProgramStudi
php artisan db:seed --class=DosenProgramStudiSeeder
```
