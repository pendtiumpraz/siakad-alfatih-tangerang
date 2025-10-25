# STAI AL-FATIH Seeder Documentation

## Overview

This documentation covers the seeder files created for **STAI AL-FATIH Tangerang** programs and courses based on data from [https://staialfatih.or.id/](https://staialfatih.or.id/).

## Created Seeders

### 1. StaiAlfatihProgramStudiSeeder.php
**Purpose:** Seeds all STAI AL-FATIH study programs (Program Studi)

**Location:** `/database/seeders/StaiAlfatihProgramStudiSeeder.php`

**Programs Included:**

#### Ilmu Al-Qur'an dan Tafsir (IQT)
- **S1 Daring** - `IQT-D-S1` - Ilmu Al-Qur'an dan Tafsir - Daring (4 years/8 semesters)
- **S1 Luring** - `IQT-L-S1` - Ilmu Al-Qur'an dan Tafsir - Luring (4 years/8 semesters)
- **S2 Daring** - `IQT-D-S2` - Ilmu Al-Qur'an dan Tafsir - Daring (2 years/4 semesters)
- **S2 Luring** - `IQT-L-S2` - Ilmu Al-Qur'an dan Tafsir - Luring (2 years/4 semesters)
- **S3 Daring** - `IQT-D-S3` - Ilmu Al-Qur'an dan Tafsir - Daring (3 years/6 semesters)
- **S3 Luring** - `IQT-L-S3` - Ilmu Al-Qur'an dan Tafsir - Luring (3 years/6 semesters)

#### Hukum Ekonomi Syariah (HES)
- **S1 Daring** - `HES-D-S1` - Hukum Ekonomi Syariah - Daring (4 years/8 semesters)
- **S1 Luring** - `HES-L-S1` - Hukum Ekonomi Syariah - Luring (4 years/8 semesters)
- **S2 Daring** - `HES-D-S2` - Hukum Ekonomi Syariah - Daring (2 years/4 semesters)
- **S2 Luring** - `HES-L-S2` - Hukum Ekonomi Syariah - Luring (2 years/4 semesters)
- **S3 Daring** - `HES-D-S3` - Hukum Ekonomi Syariah - Daring (3 years/6 semesters)
- **S3 Luring** - `HES-L-S3` - Hukum Ekonomi Syariah - Luring (3 years/6 semesters)

**Total Programs:** 12 programs (2 study fields × 3 levels × 2 modes)

**Accreditation:** All programs are accredited 'B' (Baik)

---

### 2. StaiAlfatihMataKuliahSeeder.php
**Purpose:** Seeds all courses (Mata Kuliah) for each STAI AL-FATIH program

**Location:** `/database/seeders/StaiAlfatihMataKuliahSeeder.php`

**Curriculum Structure:**

#### Ilmu Al-Qur'an dan Tafsir - S1 (144 SKS / 8 Semesters)
Total courses: **48 mata kuliah**

**Semester 1 (14 SKS):**
- Ulumul Qur'an (3 SKS)
- Bahasa Arab I (3 SKS)
- Tahsin Al-Qur'an (2 SKS)
- Aqidah Islamiyah (2 SKS)
- Pendidikan Pancasila (2 SKS)
- Bahasa Indonesia (2 SKS)

**Semester 2 (14 SKS):**
- Tafsir Maudhu'i (3 SKS)
- Bahasa Arab II (3 SKS)
- Tajwid (2 SKS)
- Hadits I (2 SKS)
- Sejarah Peradaban Islam (2 SKS)
- Bahasa Inggris (2 SKS)

**Semester 3-8:** Comprehensive curriculum covering:
- Tafsir (Tahlili I, II, III, Maudhu'i, bi al-Ma'tsur, bi ar-Ra'yi, Kontemporer)
- Qira'at & Tahfizh Al-Qur'an
- Arabic Language (Nahwu I-II, Sharaf I-II, Balaghah)
- Hadith Sciences (Hadits I-II, Musthalah Hadits)
- Islamic Jurisprudence (Fiqh I-II, Ushul Fiqh I-II)
- Tafsir Methodology (Ushul Tafsir, Manhaj Mufassirin, I'jaz Al-Qur'an)
- Supporting courses (KKN, Seminar, Skripsi)

#### Ilmu Al-Qur'an dan Tafsir - S2 (48 SKS / 4 Semesters)
Total courses: **12 mata kuliah**

Key courses:
- Metodologi Penelitian Lanjutan
- Hermeneutika Al-Qur'an
- Tafsir Tarbawi (Educational Tafsir)
- Tafsir Sosial Kemasyarakatan
- Living Qur'an
- Tafsir Nusantara
- Tesis (6 SKS)

#### Ilmu Al-Qur'an dan Tafsir - S3 (48 SKS / 6 Semesters)
Total courses: **7 mata kuliah**

Key courses:
- Epistemologi Tafsir
- Metodologi Penelitian Doktoral
- Kritik Tafsir
- Tafsir Lintas Mazhab
- Tafsir dan Konteks Keindonesiaan
- Disertasi (12 SKS)

#### Hukum Ekonomi Syariah - S1 (144 SKS / 8 Semesters)
Total courses: **50+ mata kuliah**

**Course Categories (based on actual HES curriculum):**
- **MPK** (Mata Kuliah Pengembangan Kepribadian): Bahasa Indonesia, Bahasa Inggris, Pancasila, PKN
- **MPB** (Mata Kuliah Pengembangan Berkehidupan Bermasyarakat): Aqidah Akhlak, Ulumul Qur'an, Ulumul Hadits
- **MKK** (Mata Kuliah Keilmuan dan Keterampilan): Sejarah Hukum, Pengantar Ilmu Hukum, Fiqh Muamalah
- **MKB** (Mata Kuliah Keahlian Berkarya): All core economic and sharia law courses

**Major Subjects:**
- Islamic Economic Law foundations (Pengantar Bisnis Syariah, Ekonomi Mikro/Makro Syariah)
- Banking & Finance (Bank & LKS, Hukum Perbankan Syariah, Pasar Modal Syariah, Fintech Syariah)
- Islamic Law (Fiqh Muamalah, Hukum Waris, Hukum Perikatan, Hukum Perdata Islam)
- Business Law (Hukum Bisnis, Hukum Perusahaan, Hukum Ketenagakerjaan)
- ZISWAF (Zakat, Infak, Sedekah, Wakaf)
- Supporting courses (Akuntansi Syariah, Asuransi Syariah, Manajemen Keuangan)
- Practical courses (PKL - Praktik Kerja Lapangan, Seminar, Skripsi)

#### Hukum Ekonomi Syariah - S2 (48 SKS / 4 Semesters)
Total courses: **12 mata kuliah**

Key courses:
- Metodologi Penelitian Hukum
- Filsafat Hukum Islam
- Ushul Fiqh Lanjutan
- Hukum Perbankan Syariah Lanjutan
- Manajemen Risiko LKS
- Kebijakan Ekonomi Syariah
- Tesis (6 SKS)

#### Hukum Ekonomi Syariah - S3 (48 SKS / 6 Semesters)
Total courses: **7 mata kuliah**

Key courses:
- Epistemologi Hukum Islam
- Metodologi Penelitian Doktoral
- Teori Hukum Ekonomi Islam
- Kritik Ekonomi Islam
- Hukum Ekonomi Global
- Disertasi (12 SKS)

---

## Execution Instructions

### Prerequisites

1. Ensure Laravel application is properly installed
2. Database connection is configured in `.env`
3. Migrations have been run: `php artisan migrate`

### Option 1: Run All Seeders (Recommended for Fresh Installation)

```bash
# Navigate to siakad-app directory
cd /path/to/siakad-app

# Run all seeders including STAI AL-FATIH
php artisan db:seed
```

This will run all seeders in the correct order as defined in `DatabaseSeeder.php`.

### Option 2: Run STAI AL-FATIH Seeders Only

```bash
# Run Program Studi seeder first
php artisan db:seed --class=StaiAlfatihProgramStudiSeeder

# Run Mata Kuliah seeder second (depends on programs and kurikulum)
php artisan db:seed --class=StaiAlfatihMataKuliahSeeder
```

### Option 3: Fresh Database with All Data

```bash
# Warning: This will delete all existing data!
php artisan migrate:fresh --seed
```

### Verification

After running the seeders, verify the data:

```bash
# Check Program Studi
php artisan tinker
>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', 'IQT-%')->count();
>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', 'HES-%')->count();

# Check Mata Kuliah for specific program
>>> $prodi = \App\Models\ProgramStudi::where('kode_prodi', 'IQT-D-S1')->first();
>>> $prodi->kurikulums()->first()->mataKuliahs()->count();
```

---

## Features

### 1. Idempotency
Both seeders are designed to be **idempotent** (safe to run multiple times):
- Checks for existing records before creating
- Skips duplicates with warning messages
- Uses transactions for data integrity

### 2. Data Validation
- Proper SKS allocation based on jenjang
- Correct semester distribution
- Valid course codes following naming conventions
- Proper jenis (wajib/pilihan) categorization

### 3. Transaction Safety
- Uses database transactions
- Automatic rollback on errors
- Error logging and reporting

### 4. Informative Output
- Progress messages for each program/course created
- Warning messages for skipped duplicates
- Success/error summaries

---

## Course Code Convention

### Format: `{KODE_PRODI}-{CATEGORY}-{SEMESTER}{NUMBER}`

**Examples:**
- `IQT-D-S1-101` - Ilmu Al-Qur'an Tafsir, Daring, S1, Semester 1, Course 01
- `HES-L-S1-MKB-301` - Hukum Ekonomi Syariah, Luring, S1, MKB category, Semester 3, Course 01

**Categories for HES Program:**
- **MPK**: Mata Kuliah Pengembangan Kepribadian
- **MPB**: Mata Kuliah Pengembangan Berkehidupan Bermasyarakat
- **MKK**: Mata Kuliah Keilmuan dan Keterampilan
- **MKB**: Mata Kuliah Keahlian Berkarya

---

## Total Data Summary

### Programs
- **12 Program Studi** (6 Daring + 6 Luring)
  - IQT: 6 programs (S1, S2, S3 × Daring/Luring)
  - HES: 6 programs (S1, S2, S3 × Daring/Luring)

### Courses (Per Program Type)
- **IQT S1**: ~48 mata kuliah × 6 programs (Daring/Luring) = ~288 courses
- **IQT S2**: ~12 mata kuliah × 6 programs = ~72 courses
- **IQT S3**: ~7 mata kuliah × 6 programs = ~42 courses
- **HES S1**: ~50 mata kuliah × 6 programs = ~300 courses
- **HES S2**: ~12 mata kuliah × 6 programs = ~72 courses
- **HES S3**: ~7 mata kuliah × 6 programs = ~42 courses

**Total Estimated Courses: ~816 mata kuliah records**

---

## Troubleshooting

### Error: "Class 'StaiAlfatihProgramStudiSeeder' not found"

**Solution:**
```bash
composer dump-autoload
```

### Error: Foreign key constraint fails

**Solution:** Ensure seeders run in correct order:
1. ProgramStudiSeeder (or StaiAlfatihProgramStudiSeeder)
2. KurikulumSeeder
3. MataKuliahSeeder (or StaiAlfatihMataKuliahSeeder)

### Duplicate Entry Error

**Solution:** The seeders check for duplicates, but if you encounter this:
```bash
# Delete STAI AL-FATIH data first
php artisan tinker
>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', 'IQT-%')->delete();
>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', 'HES-%')->delete();

# Then re-run seeders
php artisan db:seed --class=StaiAlfatihProgramStudiSeeder
php artisan db:seed --class=StaiAlfatihMataKuliahSeeder
```

---

## Data Source

All data is based on official information from:
- **Website:** [https://staialfatih.or.id/](https://staialfatih.or.id/)
- **HES Program Page:** [https://staialfatih.or.id/hukum-ekonomi-syariah/](https://staialfatih.or.id/hukum-ekonomi-syariah/)
- **Daring Program Page:** [https://staialfatih.or.id/perkuliahan-daring/](https://staialfatih.or.id/perkuliahan-daring/)
- **Accreditation:** SK Ban-PT: No. 8926/SK/BAN-PT/Akred/S/VI/2021 (for HES)

---

## Maintenance

### Adding New Programs

To add new STAI AL-FATIH programs:

1. Edit `StaiAlfatihProgramStudiSeeder.php`
2. Add new program to `$programStudis` array
3. Follow naming convention: `{PROGRAM}-{D|L}-{S1|S2|S3}`

### Adding New Courses

To add new courses:

1. Edit `StaiAlfatihMataKuliahSeeder.php`
2. Add courses to appropriate method (e.g., `getIQTS1MataKuliah()`)
3. Follow course code convention
4. Ensure proper SKS and semester allocation

---

## Contact & Support

For questions about STAI AL-FATIH programs:
- **Institution:** STAI AL-FATIH Tangerang
- **Website:** https://staialfatih.or.id/

For technical issues with seeders:
- Check Laravel logs: `storage/logs/laravel.log`
- Review error messages in terminal output
- Verify database connection and migrations

---

**Generated:** 2024-10-25
**Version:** 1.0.0
**Compatible with:** Laravel 10+, SIAKAD Application
