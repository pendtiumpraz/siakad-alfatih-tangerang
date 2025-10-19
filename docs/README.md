# SIAKAD STAI AL-FATIH - Dokumentasi Lengkap

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![Laravel](https://img.shields.io/badge/Laravel-11-red)
![PHP](https://img.shields.io/badge/PHP-8.2-purple)

## 📚 Daftar Isi

1. [Tentang Sistem](#tentang-sistem)
2. [Teknologi yang Digunakan](#teknologi-yang-digunakan)
3. [Fitur Utama](#fitur-utama)
4. [Role & Akses](#role--akses)
5. [Dokumentasi Lengkap](#dokumentasi-lengkap)
6. [Quick Start](#quick-start)

---

## 🎓 Tentang Sistem

**SIAKAD STAI AL-FATIH** adalah Sistem Informasi Akademik berbasis web yang dirancang khusus untuk mengelola seluruh proses akademik di Sekolah Tinggi Agama Islam (STAI) AL-FATIH Tangerang.

Sistem ini mengintegrasikan berbagai proses mulai dari **penerimaan mahasiswa baru (SPMB)** hingga **pengelolaan akademik** seperti jadwal kuliah, input nilai, KHS, pembayaran, dan pengumuman.

### 🌟 Keunggulan

- ✅ **Islamic Theme Design** - Interface dengan nuansa islami (hijau & emas)
- ✅ **Multi-Role System** - 4 role berbeda (Super Admin, Operator, Dosen, Mahasiswa)
- ✅ **SPMB Terintegrasi** - Sistem penerimaan mahasiswa baru yang lengkap
- ✅ **Real-time Notifications** - Sistem notifikasi untuk semua role
- ✅ **Responsive Design** - Dapat diakses dari desktop dan mobile
- ✅ **Dosen Wali & Ketua Prodi** - Sistem pengurus akademik
- ✅ **KHS & Nilai Digital** - Kartu hasil studi dan transkrip digital

---

## 🛠 Teknologi yang Digunakan

### Backend
- **Laravel 11** - PHP Framework
- **MySQL** - Database Management System
- **PHP 8.2+** - Programming Language

### Frontend
- **Tailwind CSS** - Utility-first CSS Framework
- **Alpine.js** - Lightweight JavaScript Framework
- **Font Awesome** - Icon Library
- **Google Fonts (Poppins)** - Typography

### Tools & Libraries
- **Spatie Laravel Permission** - Role & Permission Management
- **Laravel Excel** - Export Data
- **Intervention Image** - Image Processing
- **DomPDF** - PDF Generation

---

## 🎯 Fitur Utama

### 1. SPMB (Seleksi Penerimaan Mahasiswa Baru)
- 📝 Formulir pendaftaran online 8-step
- 💰 Sistem pembayaran pendaftaran
- ✅ Verifikasi dokumen oleh admin/operator
- 📊 Dashboard SPMB dengan statistik
- 📋 NIM Range Management
- 🎓 Daftar ulang mahasiswa baru

### 2. Manajemen Akademik
- 📅 Jadwal kuliah per semester
- 📊 Input & monitoring nilai
- 📜 Generate KHS (Kartu Hasil Studi)
- 👨‍🏫 Dosen wali & ketua prodi
- 📚 Kurikulum & mata kuliah
- 🏛️ Program studi & semester

### 3. Keuangan
- 💳 Pembayaran SPP & biaya kuliah
- 📤 Upload bukti pembayaran
- ✔️ Verifikasi pembayaran
- 📊 Laporan keuangan

### 4. Komunikasi
- 📢 Sistem pengumuman
- 🔔 Notifikasi real-time
- 📧 Multi-channel notifications
- 👁️ Read/unread tracking

### 5. User Management
- 👤 Multi-role system
- 🔐 Role-based access control
- ✏️ Profile management
- 🔑 Password management

---

## 👥 Role & Akses

### 1. Super Admin 🔴
**Akses Penuh** ke semua fitur sistem
- ✅ Manajemen user (CRUD)
- ✅ Role & permission
- ✅ Master data (Prodi, Kurikulum, Mata Kuliah, dll)
- ✅ SPMB management
- ✅ NIM range configuration
- ✅ Pengurus (Ketua Prodi & Dosen Wali)
- ✅ Pembayaran
- ✅ Pengumuman
- ✅ Laporan

### 2. Operator 🟡
**Fokus** pada operasional keuangan & SPMB
- ✅ SPMB verification (terbatas)
- ✅ Pembayaran management
- ✅ Pengumuman
- 👁️ Master data (read-only)

### 3. Dosen 🟢
**Fokus** pada proses pembelajaran
- ✅ Jadwal mengajar
- ✅ Input nilai mahasiswa
- ✅ Generate KHS
- ✅ Pengumuman
- 👁️ Master data (read-only)
- 👁️ Daftar mahasiswa bimbingan (jika dosen wali)

### 4. Mahasiswa 🔵
**Fokus** pada informasi akademik pribadi
- 👁️ Profile & biodata
- 👁️ Jadwal kuliah
- 👁️ Nilai & KHS
- 👁️ Pembayaran & upload bukti
- 👁️ Kurikulum
- 👁️ Pengumuman

---

## 📖 Dokumentasi Lengkap

Dokumentasi sistem dibagi menjadi beberapa bagian:

### Core Documentation
- **[Installation Guide](INSTALLATION.md)** - Panduan instalasi lengkap
- **[Database Schema](DATABASE.md)** - Struktur database & relasi
- **[Configuration](CONFIGURATION.md)** - Konfigurasi sistem

### Feature Documentation
- **[SPMB Flow](SPMB_FLOW.md)** - Alur lengkap SPMB dari pendaftaran hingga diterima
- **[Roles & Permissions](ROLES.md)** - Detail fitur per role
- **[Academic Features](ACADEMIC_FEATURES.md)** - Fitur akademik (Jadwal, Nilai, KHS)
- **[Payment System](PAYMENT_SYSTEM.md)** - Sistem pembayaran
- **[Notification System](NOTIFICATION_SYSTEM.md)** - Sistem notifikasi

### Technical Documentation
- **[API Documentation](API.md)** - API endpoints (jika ada)
- **[Troubleshooting](TROUBLESHOOTING.md)** - Solusi masalah umum
- **[Changelog](CHANGELOG.md)** - Riwayat perubahan

---

## 🚀 Quick Start

### Prerequisites
```bash
- PHP 8.2 or higher
- Composer
- MySQL 5.7 or higher
- Node.js & NPM (untuk compile assets)
```

### Installation

1. **Clone Repository**
```bash
git clone https://github.com/pendtiumpraz/siakad-alfatih-tangerang.git
cd siakad-app
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**
Edit `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

5. **Run Migration & Seeder**
```bash
php artisan migrate
php artisan db:seed
```

6. **Build Assets**
```bash
npm run build
```

7. **Start Server**
```bash
php artisan serve
```

8. **Access Application**
```
http://localhost:8000
```

### Default Credentials

**Super Admin:**
- Username: `admin`
- Password: `password`

**Operator:**
- Username: `operator`
- Password: `password`

**Dosen:**
- Username: `dosen1`
- Password: `password`

**Mahasiswa:**
- Username: `202301010001`
- Password: `password`

---

## 📞 Support

Jika ada pertanyaan atau masalah:

- 📧 Email: support@stai-alfatih.ac.id
- 📱 WhatsApp: +62 xxx-xxxx-xxxx
- 🌐 Website: https://stai-alfatih.ac.id

---

## 📄 License

Copyright © 2025 STAI AL-FATIH Tangerang. All rights reserved.

---

**Dibuat dengan ❤️ menggunakan Laravel & Claude Code**
