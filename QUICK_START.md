# 🚀 Quick Start Guide - SIAKAD STAI Al-Fatih

Panduan cepat untuk setup dan menjalankan aplikasi SIAKAD.

## 📋 Prerequisites

Pastikan sudah terinstall:
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js & NPM

---

## ⚡ Installation (5 Menit)

### 1. Clone & Setup

```bash
# Clone repository
git clone https://github.com/pendtiumpraz/siakad-alfatih-tangerang.git
cd siakad-alfatih-tangerang

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate
```

### 2. Configure Database

Edit `.env`:
```env
DB_DATABASE=siakad_alfatih
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create database:
```sql
CREATE DATABASE siakad_alfatih;
```

### 3. Migrate & Seed

```bash
# Run migrations
php artisan migrate

# Seed data (users, prodi, mata kuliah, ruangan, dosen)
php artisan db:seed
```

### 4. Setup Storage & Build Assets

```bash
# Create storage link
php artisan storage:link

# Build assets
npm run build
```

### 5. Run Application

```bash
# Start server
php artisan serve
```

**Buka browser:** http://localhost:8000

---

## 🔑 Default Login

| Role | Username | Password |
|------|----------|----------|
| Super Admin | `admin@siakad.ac.id` | `password` |
| Operator | `operator@siakad.ac.id` | `password` |
| Dosen | `dosen@siakad.ac.id` | `password` |
| Mahasiswa | `mahasiswa@siakad.ac.id` | `password` |

⚠️ **Ganti password setelah login pertama kali!**

---

## 🤖 Setup KHS Auto-Generate

### Test Manual Generate

```bash
# Generate KHS untuk semester aktif
php artisan khs:generate

# Generate untuk semester spesifik (ID semester)
php artisan khs:generate 1

# Generate dengan filter prodi
php artisan khs:generate 1 --prodi=1

# Force generate (skip confirmation)
php artisan khs:generate --force
```

### Setup Scheduler (Production)

```bash
# Edit crontab
crontab -e

# Tambahkan baris ini:
* * * * * cd /path/to/siakad-alfatih-tangerang && php artisan schedule:run >> /dev/null 2>&1
```

Ganti `/path/to/siakad-alfatih-tangerang` dengan path absolut aplikasi Anda.

### Verify Scheduler

```bash
# List scheduled tasks
php artisan schedule:list

# Expected output:
# 0 2 * * * auto-generate-khs ... Next Due: 1 day from now

# Test run scheduler
php artisan schedule:run
```

---

## 🎯 How to Use (Admin)

### 1. Setup Semester & KHS Settings

```
1. Login sebagai Super Admin
2. Go to: Admin → Semester → Edit
3. Set KHS settings:
   - Tanggal Generate KHS: (tanggal kapan auto-generate)
   - Auto Generate: ON (aktifkan auto-generate)
   - Show Signatures: centang kedua checkbox
   - Status: draft
4. Save
```

### 2. Assign Mata Kuliah ke Dosen

```
1. Go to: Admin → Users → Edit Dosen
2. Scroll ke bagian "Mata Kuliah yang Diampu"
3. Pilih Program Studi dari dropdown
4. Centang mata kuliah yang diampu
5. Klik "Pilih Semua" untuk select all MK
6. Save
```

### 3. Generate KHS

**Manual (via Web):**
```
1. Go to: Admin → Semester → Show
2. Click button "Generate KHS Manual"
3. Confirm dialog
4. Wait for completion
```

**Manual (via CLI):**
```bash
php artisan khs:generate 1
```

**Auto (via Scheduler):**
- Akan jalan otomatis setiap hari jam 2 pagi
- Hanya jika `khs_auto_generate = true`
- Hanya jika `khs_generate_date = today`

### 4. View KHS

```
Admin:
1. Go to: Admin → KHS
2. Filter by semester, prodi, IP range
3. Search by NIM/nama
4. Click "Detail" untuk lihat full KHS

Mahasiswa:
1. Go to: Mahasiswa → KHS
2. See all KHS per semester
3. Click "Lihat Detail"
4. Print if needed

Dosen:
1. Go to: Dosen → KHS
2. See mahasiswa bimbingan KHS
3. Filter by semester
4. Click "Detail"
```

---

## 🧪 Testing Commands

### Check Database

```bash
php artisan tinker
```

```php
// Check tables exist
Schema::hasTable('khs'); // true
Schema::hasTable('dosen_mata_kuliah'); // true

// Check seeded data
\App\Models\ProgramStudi::count(); // 3 (PAI, ES, PIAUD)
\App\Models\MataKuliah::count(); // 60+
\App\Models\Ruangan::count(); // 10 (5 offline + 5 online)

// Check Dosen MK assignment
$dosen = \App\Models\Dosen::first();
$dosen->mataKuliahs->pluck('kode_mk');
// ["PAI-101", "PAI-102", ...]

// Check ruangan online
\App\Models\Ruangan::where('jenis', 'online')->pluck('nama_ruangan');
// ["Ruang Daring 1", "Ruang Daring 2", ...]

exit
```

### Check Routes

```bash
# List all routes
php artisan route:list

# Filter by admin
php artisan route:list --path=admin

# Filter by KHS
php artisan route:list --path=khs
```

### View Logs

```bash
# Real-time log monitoring
tail -f storage/logs/laravel.log

# Filter KHS logs
grep "KHS" storage/logs/laravel.log

# Clear logs
echo "" > storage/logs/laravel.log
```

---

## 🔧 Common Commands

### Development

```bash
# Start dev server
php artisan serve

# Watch assets (auto-reload)
npm run dev

# Tinker (Laravel REPL)
php artisan tinker
```

### Database

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh install (WARNING: deletes all data)
php artisan migrate:fresh --seed

# Seed only
php artisan db:seed

# Specific seeder
php artisan db:seed --class=DosenSeeder
```

### Cache

```bash
# Clear ALL caches
php artisan optimize:clear

# Individual cache clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🐛 Troubleshooting

### Database Connection Error

```bash
# Test connection
php artisan tinker
DB::connection()->getPdo(); // Should return PDO instance

# Check MySQL is running
# Windows: Check MySQL service in Services
# Linux: sudo systemctl status mysql
```

**Fix:**
- Check `.env` database credentials
- Make sure MySQL is running
- Create database if not exists

### Migration Failed

```bash
# Rollback and retry
php artisan migrate:rollback
php artisan migrate

# If still fails, fresh install (WARNING: deletes data)
php artisan migrate:fresh
```

### Permission Issues

```bash
# Fix permissions (Linux/Mac)
chmod -R 775 storage bootstrap/cache

# Fix ownership (Linux - www-data user)
sudo chown -R www-data:www-data storage bootstrap/cache

# Windows: Make sure folders are writable
```

### KHS Not Generating

```bash
# Check scheduler
php artisan schedule:list

# Test manually
php artisan khs:generate

# Check semester settings
php artisan tinker
```

```php
$semester = \App\Models\Semester::find(1);
$semester->khs_auto_generate; // Should be true/1
$semester->khs_generate_date; // Should be set
$semester->khs_status; // Check current status

exit
```

### CSS Not Loading

```bash
# Rebuild assets
npm run build

# For development
npm run dev

# Clear view cache
php artisan view:clear
```

### Composer Memory Limit

```bash
# Increase memory for composer
php -d memory_limit=-1 /usr/local/bin/composer install
```

---

## 📚 What's Seeded?

Setelah `php artisan db:seed`, data berikut akan otomatis dibuat:

### Users (4)
- Super Admin, Operator, Dosen, Mahasiswa (see table above)

### Program Studi (3)
- PAI (Pendidikan Agama Islam)
- ES (Ekonomi Syariah)  
- PIAUD (Pendidikan Islam Anak Usia Dini)

### Kurikulum
- 1 kurikulum per program studi

### Mata Kuliah (60+)
- 20+ MK per prodi
- Grouped by semester (1-8)
- Complete with kode_mk, nama_mk, SKS

### Ruangan (10)
- 5 Offline: R101, R102, R103, R104, R105
- 5 Online: ONLINE-1, ONLINE-2, ONLINE-3, ONLINE-4, ONLINE-5

### Dosen Assignments
- Ahmad Fauzi → PAI-101, PAI-102, ES-101, ES-102
- Siti Nurhaliza → PAI-103, PAI-104, PAI-201
- Budi Santoso → ES-201, ES-301, PIAUD-101, PIAUD-201

### Semester
- Sample semester akademik

---

## 🎯 Next Steps

### For Admin:
1. ✅ Change default password
2. ✅ Setup semester & KHS settings
3. ✅ Assign mata kuliah to dosen
4. ✅ Setup cron for auto-generate
5. ✅ Test manual KHS generation

### For Dosen:
1. ✅ Login and change password
2. ✅ Create jadwal kuliah
3. ✅ Input nilai mahasiswa
4. ✅ View KHS mahasiswa bimbingan

### For Mahasiswa:
1. ✅ Login and change password
2. ✅ View jadwal kuliah
3. ✅ View nilai
4. ✅ View and print KHS

---

## 🆘 Need Help?

### Documentation
- **Full README**: See `README.md` for complete documentation
- **Implementation Plan**: See `IMPLEMENTATION_PLAN_MAJOR_FEATURES.md`

### Support
- **Email**: admin@stai-alfatih.ac.id
- **GitHub Issues**: [Create Issue](https://github.com/pendtiumpraz/siakad-alfatih-tangerang/issues)

### Check System Status

```bash
# Check PHP version
php -v  # Should be 8.2+

# Check Composer
composer --version

# Check Node & NPM
node -v
npm -v

# Check database connection
php artisan db:show

# List all artisan commands
php artisan list
```

---

## ✨ Key Features

### 1. Ruangan Online/Daring
- Ruangan offline: **cannot overlap** at all
- Ruangan online: **can overlap** for different mata kuliah
- Same mata kuliah: **cannot overlap** even in online room

### 2. Dosen-Mata Kuliah Assignment
- Admin assigns specific MK to each dosen
- Dosen only see assigned MK in jadwal/nilai dropdown
- Filtered by program studi assignment

### 3. KHS Auto-Generate
- Automatic IP/IPK calculation
- Manual trigger via web or CLI
- Auto-generate via scheduler (daily 2 AM)
- Settings per semester (date, auto-gen, signatures, status)

### 4. Multi-Role Access
- **Admin**: Full system management, KHS settings
- **Dosen**: Jadwal, nilai, KHS mahasiswa bimbingan
- **Mahasiswa**: View jadwal, nilai, KHS (read-only)
- **Operator**: Pembayaran, SPMB

---

**🎉 Selamat! Aplikasi SIAKAD siap digunakan!**

**Built with ❤️ for STAI Al-Fatih Tangerang**
