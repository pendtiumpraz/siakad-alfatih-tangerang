# SIAKAD - STAI AL-FATIH Tangerang

![Laravel](https://img.shields.io/badge/Laravel-11.x-red)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange)
![License](https://img.shields.io/badge/License-MIT-green)

Sistem Informasi Akademik (SIAKAD) untuk STAI AL-FATIH Tangerang - Sebuah sistem manajemen akademik berbasis web dengan tema Islamic yang elegan.

## 📋 Deskripsi

SIAKAD STAI AL-FATIH adalah aplikasi manajemen akademik yang dirancang khusus untuk institusi pendidikan tinggi Islam. Sistem ini menyediakan fitur lengkap untuk mengelola data mahasiswa, dosen, mata kuliah, jadwal, nilai, pembayaran, dan berbagai kebutuhan akademik lainnya.

### ✨ Fitur Utama

- **Multi-Role System** - 4 role pengguna (Super Admin, Operator, Dosen, Mahasiswa)
- **User Management** - Manajemen user dengan soft delete dan restore
- **Master Data Management**
  - Program Studi
  - Kurikulum
  - Mata Kuliah
  - Ruangan
  - Semester
- **Manajemen Akademik**
  - Jadwal Perkuliahan dengan deteksi konflik
  - Input dan Manajemen Nilai dengan filter
  - Generate KHS (Kartu Hasil Studi) otomatis
  - Export KHS ke PDF dengan design profesional
  - Perhitungan IP dan IPK otomatis
  - Dashboard real-time dengan data akademik
- **Sistem Pembayaran**
  - Upload bukti pembayaran dengan drag & drop interface
  - Verifikasi pembayaran oleh operator
  - Riwayat pembayaran mahasiswa
  - Notifikasi pembayaran pending
- **Profile Management**
  - Profile dosen dengan foto, gelar, email, dan nomor telepon
  - Profile mahasiswa dengan data lengkap dan email
- **PDF Generation**
  - Export KHS ke PDF format A4
  - Design print-friendly tanpa sidebar
  - Scale 95% untuk hasil optimal
- **Role & Permission Management** - Kontrol akses berbasis role
- **Islamic Design** - Interface dengan tema Islamic yang elegan (Soft Green, Gold, White)

## 🎨 Design Theme

Aplikasi ini menggunakan tema Islamic dengan warna:
- **Primary**: Soft Green (#4A7C59, #2D5F3F)
- **Accent**: Gold (#D4AF37)
- **Base**: White (#FFFFFF)
- **Background**: Light Gray (#F9FAFB)
- **Text**: Emerald-50 untuk kontras optimal

## 🚀 Teknologi

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL 8.0+
- **PDF Generation**: barryvdh/laravel-dompdf
- **Icons**: Heroicons (SVG)
- **Fonts**: Times New Roman (untuk PDF), Inter (untuk web)

## 📦 Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- MySQL 8.0+
- Node.js & NPM (v16+)

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/pendtiumpraz/siakad-alfatih-tangerang.git
   cd siakad-alfatih-tangerang
   ```

2. **Install dependencies**
   ```bash
   # Install PHP dependencies
   composer install

   # Install Node dependencies
   npm install
   ```

3. **Install PDF Package**
   ```bash
   composer require barryvdh/laravel-dompdf
   ```

4. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Konfigurasi database**

   Edit file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=siakad
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Setup storage dan permissions**
   ```bash
   # Create storage link
   php artisan storage:link

   # Set permissions (Linux/Mac)
   chmod -R 775 storage bootstrap/cache

   # Or for WSL/Windows
   chmod -R 777 storage bootstrap/cache
   ```

7. **Jalankan migrasi dan seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

8. **Build assets**
   ```bash
   npm run build
   ```

9. **Jalankan aplikasi**
   ```bash
   # Terminal 1 - Backend Server
   php artisan serve --port=3010

   # Terminal 2 - Vite Dev Server (optional, untuk development)
   npm run dev
   ```

10. **Akses aplikasi**

    Buka browser: `http://localhost:3010`

## 👤 Default Credentials

Setelah menjalankan seeder, gunakan kredensial berikut:

### Super Admin
- Username: `superadmin`
- Password: `password`

### Operator
- Username: `operator1`
- Password: `password`

### Dosen
- Username: `dosen1`
- Password: `password`
- NIDN: `0123456789`

### Mahasiswa
- Username: `mahasiswa1`
- Password: `password`
- NIM: `202301010001`

## 📊 Database Schema

Sistem ini memiliki 14 tabel utama:

1. `users` - Data pengguna sistem
2. `mahasiswas` - Data mahasiswa
3. `dosens` - Data dosen
4. `operators` - Data operator
5. `program_studis` - Program studi
6. `kurikulums` - Data kurikulum
7. `mata_kuliahs` - Mata kuliah
8. `ruangans` - Data ruangan
9. `semesters` - Data semester
10. `jadwals` - Jadwal perkuliahan
11. `nilais` - Data nilai mahasiswa
12. `khs` - Kartu Hasil Studi
13. `pembayarans` - Data pembayaran
14. `role_permissions` - Permission matrix

## 🔐 Role & Permissions

### Super Admin
- Full access ke semua modul
- User management (create, edit, delete, restore)
- Role & permission management
- Master data management (Program Studi, Kurikulum, Mata Kuliah, Ruangan, Semester)
- View pembayaran

### Operator
- Manajemen pembayaran (verifikasi, export)
- View master data (read-only)

### Dosen
- Profile management dengan foto, gelar, email, dan nomor telepon
- Manajemen jadwal kuliah dengan filter semester dan hari
- Input dan edit nilai dengan filter Program Studi dan Semester
- Generate KHS otomatis
- View master data (read-only)

### Mahasiswa
- Profile lengkap dengan email, nomor telepon
- Dashboard dengan data real-time (IP, IPK, SKS, Status)
- View jadwal kuliah
- View nilai dan KHS
- Export KHS ke PDF
- Upload bukti pembayaran dengan drag & drop
- View kurikulum
- Notifikasi pembayaran pending

## 📁 Struktur Project

```
siakad-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Super Admin controllers
│   │   │   ├── Operator/       # Operator controllers
│   │   │   ├── Dosen/          # Dosen controllers
│   │   │   │   ├── DosenController.php (profile)
│   │   │   │   ├── JadwalController.php
│   │   │   │   ├── NilaiController.php
│   │   │   │   └── KHSController.php
│   │   │   ├── Mahasiswa/      # Mahasiswa controllers
│   │   │   │   ├── MahasiswaDashboardController.php
│   │   │   │   └── MahasiswaController.php (profile, KHS, nilai)
│   │   │   ├── Master/         # Master data controllers
│   │   │   └── Auth/           # Authentication controllers
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/                 # Eloquent models (14 models)
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/                # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/              # Admin views
│   │   ├── operator/           # Operator views
│   │   ├── dosen/              # Dosen views
│   │   │   ├── profile.blade.php
│   │   │   ├── jadwal/
│   │   │   ├── nilai/
│   │   │   └── khs/
│   │   ├── mahasiswa/          # Mahasiswa views
│   │   │   ├── dashboard.blade.php
│   │   │   ├── profile.blade.php
│   │   │   ├── khs/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── show.blade.php
│   │   │   │   └── print.blade.php (PDF template)
│   │   │   ├── pembayaran/
│   │   │   └── jadwal/
│   │   ├── auth/               # Authentication views
│   │   ├── layouts/            # Layout templates
│   │   │   ├── admin.blade.php
│   │   │   ├── dosen.blade.php
│   │   │   └── mahasiswa.blade.php
│   │   └── components/         # Reusable components
│   ├── css/
│   └── js/
└── routes/
    └── web.php                 # Application routes
```

## 🛠️ Development

### Running in Development Mode

```bash
# Terminal 1 - Laravel Development Server
php artisan serve --port=3010

# Terminal 2 - Vite Hot Reload
npm run dev
```

### Useful Commands

```bash
# Clear all cache
php artisan optimize:clear

# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Run seeders
php artisan db:seed

# Refresh database (drop all tables and re-migrate)
php artisan migrate:fresh --seed

# List all routes
php artisan route:list

# Create controller
php artisan make:controller ControllerName

# Create model with migration
php artisan make:model ModelName -m

# Generate storage link
php artisan storage:link

# Clear view cache
php artisan view:clear

# Clear config cache
php artisan config:clear

# Clear route cache
php artisan route:clear
```

## 🐛 Troubleshooting

### Database Connection Error
- Pastikan MySQL server berjalan
- Periksa kredensial database di file `.env`
- Untuk WSL, gunakan IP Windows host (contoh: 172.27.144.1)
- Test koneksi: `php artisan db:show`

### 404 Not Found
- Jalankan `php artisan optimize:clear`
- Periksa route dengan `php artisan route:list`
- Pastikan `.htaccess` ada di folder `public/`

### View Not Found
- Jalankan `php artisan view:clear`
- Pastikan path view sudah benar (gunakan dot notation)

### Permission Denied / Storage Error
- Pastikan direktori `storage` dan `bootstrap/cache` writable
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```
- Generate storage link: `php artisan storage:link`

### PDF Generation Error
- Pastikan package `barryvdh/laravel-dompdf` terinstall
  ```bash
  composer require barryvdh/laravel-dompdf
  ```
- Clear cache: `php artisan config:clear`

### CSS Not Loading
- Build assets: `npm run build`
- Untuk development: `npm run dev`
- Clear browser cache

### Relationship Error
- Jalankan migrations: `php artisan migrate`
- Check model relationships
- Run: `composer dump-autoload`

## 🎯 Fitur Khusus

### 1. Dashboard Mahasiswa Real-Time
- IP Semester dan IPK dari database
- Total SKS Lulus
- Status akademik (Aktif/Cuti/Lulus)
- Jadwal hari ini berdasarkan hari aktif
- Pembayaran pending dengan notifikasi jatuh tempo

### 2. PDF Export KHS
- Design profesional format A4
- Times New Roman font untuk hasil print optimal
- Scale 95% untuk pas di satu halaman
- Tanpa sidebar atau elemen navigasi
- Color coding untuk grade (A=hijau, B=biru, C=kuning, D/E=merah)

### 3. Upload Bukti Pembayaran
- Drag & drop interface modern
- Preview gambar sebelum upload
- Support format: JPG, PNG, PDF
- Maksimal 2MB per file
- Auto-generate nama file dengan format terstruktur

### 4. Filter dan Search
- Filter jadwal dosen berdasarkan semester dan hari
- Filter nilai berdasarkan Program Studi dan Semester
- Pagination untuk data besar

### 5. Color Contrast Optimization
- Semua teks menggunakan `text-emerald-50` untuk kontras optimal
- Tidak ada lagi masalah "teks tidak kelihatan"

## 📝 License

This project is licensed under the MIT License.

## 👨‍💻 Developer

**Galih Prastowo**

- 📱 Phone: [081319504441](tel:081319504441)
- 💼 LinkedIn: [linkedin.com/in/pendtiumpraz](https://linkedin.com/in/pendtiumpraz)
- 🐙 GitHub: [@pendtiumpraz](https://github.com/pendtiumpraz)

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

## 🙏 Acknowledgments

- Laravel Framework
- Tailwind CSS
- Alpine.js
- barryvdh/laravel-dompdf
- All open source contributors

## ⭐ Show your support

Give a ⭐️ if this project helped you!

---

**Built with ❤️ for STAI AL-FATIH Tangerang**

*"طَلَبُ الْعِلْمِ فَرِيْضَةٌ عَلَى كُلِّ مُسْلِمٍ - Menuntut ilmu itu wajib atas setiap muslim"*
