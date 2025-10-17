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
  - Input dan Manajemen Nilai
  - Generate KHS (Kartu Hasil Studi) otomatis
  - Perhitungan IP otomatis
- **Sistem Pembayaran**
  - Upload bukti pembayaran
  - Verifikasi pembayaran oleh operator
  - Riwayat pembayaran mahasiswa
- **Role & Permission Management** - Kontrol akses berbasis role
- **Islamic Design** - Interface dengan tema Islamic yang elegan (Soft Green, Gold, White)

## 🎨 Design Theme

Aplikasi ini menggunakan tema Islamic dengan warna:
- **Primary**: Soft Green (#4A7C59, #2D5F3F)
- **Accent**: Gold (#D4AF37)
- **Base**: White (#FFFFFF)
- **Background**: Light Gray (#F9FAFB)
- **Text**: Cream (#F4E5C3)

## 🚀 Teknologi

- **Backend**: Laravel 11.x
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL 8.0+
- **Icons**: Font Awesome 6.4.0
- **Fonts**: Poppins (Google Fonts)

## 📦 Instalasi

### Prasyarat

- PHP >= 8.2
- Composer
- MySQL 8.0+
- Node.js & NPM

### Langkah Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/pendtiumpraz/siakad-alfatih-tangerang.git
   cd siakad-alfatih-tangerang
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Konfigurasi database**

   Edit file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=siakad
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Jalankan migrasi dan seeder**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Jalankan aplikasi**
   ```bash
   # Terminal 1 - Backend Server
   php artisan serve --port=3010

   # Terminal 2 - Vite Dev Server (optional, untuk development)
   npm run dev
   ```

8. **Akses aplikasi**

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

### Mahasiswa
- Username: `mahasiswa1`
- Password: `password`

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
- User management
- Role & permission management
- Master data management
- View pembayaran

### Operator
- Manajemen pembayaran
- View master data (read-only)

### Dosen
- Manajemen jadwal kuliah
- Input dan edit nilai
- Generate KHS
- View master data (read-only)

### Mahasiswa
- View jadwal kuliah
- View nilai dan KHS
- Upload bukti pembayaran
- View kurikulum

## 📁 Struktur Project

```
siakad-app/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Super Admin controllers
│   │   │   ├── Operator/       # Operator controllers
│   │   │   ├── Dosen/          # Dosen controllers
│   │   │   ├── Mahasiswa/      # Mahasiswa controllers
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
│   │   ├── mahasiswa/          # Mahasiswa views
│   │   ├── auth/               # Authentication views
│   │   ├── layouts/            # Layout templates
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

# List all routes
php artisan route:list

# Create controller
php artisan make:controller ControllerName

# Create model
php artisan make:model ModelName -m
```

## 🐛 Troubleshooting

### Database Connection Error
- Pastikan MySQL server berjalan
- Periksa kredensial database di file `.env`
- Untuk WSL, gunakan IP Windows host (contoh: 172.27.144.1)

### 404 Not Found
- Jalankan `php artisan optimize:clear`
- Periksa route dengan `php artisan route:list`

### View Not Found
- Jalankan `php artisan view:clear`
- Pastikan path view sudah benar

### Permission Denied
- Pastikan direktori `storage` dan `bootstrap/cache` writable
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

## 📝 License

This project is licensed under the MIT License.

## 👨‍💻 Developer

**Galih Prastowo**

- 📱 Phone: [081319504441](tel:081319504441)
- 💼 LinkedIn: [linkedin.com/in/pendtiumpraz](https://linkedin.com/in/pendtiumpraz)
- 🐙 GitHub: [@pendtiumpraz](https://github.com/pendtiumpraz)

## 🤝 Contributing

Contributions, issues, and feature requests are welcome!

## ⭐ Show your support

Give a ⭐️ if this project helped you!

---

**Built with ❤️ for STAI AL-FATIH Tangerang**

*"Tuntutlah ilmu dari buaian hingga liang lahat"*
