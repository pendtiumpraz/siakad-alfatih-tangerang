# STAI AL-FATIH Seeders - Quick Start Guide

## Quick Execution Commands

### Execute STAI AL-FATIH Seeders

```bash
# Navigate to application directory
cd /mnt/d/AI/SIAKAD/siakad-app

# Option 1: Run ONLY STAI AL-FATIH seeders
php artisan db:seed --class=StaiAlfatihProgramStudiSeeder
php artisan db:seed --class=StaiAlfatihMataKuliahSeeder

# Option 2: Run ALL seeders (includes STAI AL-FATIH + existing data)
php artisan db:seed

# Option 3: Fresh database with all seeders
php artisan migrate:fresh --seed
```

## What Gets Seeded

### Programs (12 total)
- **Ilmu Al-Qur'an dan Tafsir**: 6 programs (S1/S2/S3 × Daring/Luring)
- **Hukum Ekonomi Syariah**: 6 programs (S1/S2/S3 × Daring/Luring)

### Courses (~816 total)
- **IQT S1**: 48 courses × 2 modes = 96 courses
- **IQT S2**: 12 courses × 2 modes = 24 courses
- **IQT S3**: 7 courses × 2 modes = 14 courses
- **HES S1**: 50+ courses × 2 modes = 100+ courses
- **HES S2**: 12 courses × 2 modes = 24 courses
- **HES S3**: 7 courses × 2 modes = 14 courses

## Verify Installation

```bash
php artisan tinker

# Check programs
>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', '%IQT%')->count();
=> 6

>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', '%HES%')->count();
=> 6

# Check courses for IQT S1 Daring
>>> $prodi = \App\Models\ProgramStudi::where('kode_prodi', 'IQT-D-S1')->first();
>>> $prodi->kurikulums->first()->mataKuliahs->count();
=> 48

# List all STAI AL-FATIH programs
>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', 'IQT-%')
    ->orWhere('kode_prodi', 'LIKE', 'HES-%')
    ->get(['kode_prodi', 'nama_prodi', 'jenjang']);
```

## Files Created

1. **StaiAlfatihProgramStudiSeeder.php** - Program definitions
2. **StaiAlfatihMataKuliahSeeder.php** - Course definitions
3. **STAI_ALFATIH_SEEDER_README.md** - Complete documentation
4. **DatabaseSeeder.php** - Updated to include STAI AL-FATIH seeders

## Key Features

- **Idempotent**: Safe to run multiple times
- **Transactional**: Automatic rollback on errors
- **Validated**: Proper SKS and semester allocation
- **Comprehensive**: All S1, S2, S3 programs with complete curricula
- **Accurate**: Based on official staialfatih.or.id data

## Program Codes

| Program | Daring | Luring |
|---------|--------|--------|
| IQT S1  | IQT-D-S1 | IQT-L-S1 |
| IQT S2  | IQT-D-S2 | IQT-L-S2 |
| IQT S3  | IQT-D-S3 | IQT-L-S3 |
| HES S1  | HES-D-S1 | HES-L-S1 |
| HES S2  | HES-D-S2 | HES-L-S2 |
| HES S3  | HES-D-S3 | HES-L-S3 |

## Troubleshooting

**Class not found?**
```bash
composer dump-autoload
```

**Need to reset?**
```bash
# Delete STAI AL-FATIH data only
php artisan tinker
>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', 'IQT-%')->delete();
>>> \App\Models\ProgramStudi::where('kode_prodi', 'LIKE', 'HES-%')->delete();
```

For detailed documentation, see: `database/seeders/STAI_ALFATIH_SEEDER_README.md`
