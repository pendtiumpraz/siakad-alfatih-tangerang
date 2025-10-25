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
- **Google Drive Integration**
  - Upload otomatis file ke Google Drive
  - OAuth 2.0 authentication dengan shared token
  - Auto-refresh token mechanism
  - Fallback ke local storage jika Google Drive gagal
  - Support untuk bukti pembayaran, dokumen mahasiswa, dan SPMB
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

## 🚀 Production Deployment

### Server Requirements

#### Minimum Server Specifications:
- **OS**: CentOS 7+, Ubuntu 20.04+, atau Amazon Linux 2
- **PHP**: 8.2+ dengan extensions:
  - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
  - GD atau Imagick (untuk image processing)
  - ZIP (untuk composer)
- **Web Server**: Apache 2.4+ atau Nginx 1.18+
- **Database**: MySQL 8.0+ atau MariaDB 10.6+
- **Memory**: Minimum 512MB RAM (Recommended: 1GB+)
- **Storage**: Minimum 5GB (Recommended: 10GB+ untuk file uploads)
- **SSL Certificate**: Required untuk production (gunakan Let's Encrypt)

### Step 1: Persiapan Server

#### Install Dependencies (CentOS/Amazon Linux):

```bash
# Update system
sudo yum update -y

# Install PHP 8.2 (menggunakan Remi repository)
sudo yum install -y epel-release
sudo yum install -y https://rpms.remirepo.net/enterprise/remi-release-7.rpm
sudo yum-config-manager --enable remi-php82

# Install PHP dan extensions
sudo yum install -y php php-cli php-fpm php-mysqlnd php-zip php-devel \
    php-gd php-mcrypt php-mbstring php-curl php-xml php-pear php-bcmath \
    php-json php-fileinfo php-tokenizer php-openssl

# Verify PHP version
php -v  # Should show PHP 8.2.x

# Install Composer
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version

# Install MySQL 8.0
sudo yum remove -y mysql mysql-server  # Remove old MySQL if exists
sudo rpm -Uvh https://dev.mysql.com/get/mysql80-community-release-el7-3.noarch.rpm
sudo yum install -y mysql-community-server

# Start MySQL service
sudo systemctl start mysqld
sudo systemctl enable mysqld

# Get temporary MySQL root password
sudo grep 'temporary password' /var/log/mysqld.log

# Secure MySQL installation
sudo mysql_secure_installation

# Install Node.js and NPM (for asset compilation)
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# Verify Node and NPM
node -v
npm -v

# Install Git
sudo yum install -y git

# Install Apache (atau gunakan Nginx)
sudo yum install -y httpd mod_ssl
sudo systemctl start httpd
sudo systemctl enable httpd
```

#### Install Dependencies (Ubuntu/Debian):

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2 dan extensions
sudo apt install -y software-properties-common
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install -y php8.2 php8.2-cli php8.2-fpm php8.2-mysql php8.2-zip \
    php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath \
    php8.2-json php8.2-fileinfo php8.2-tokenizer

# Verify PHP version
php -v

# Install Composer
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install MySQL 8.0
sudo apt install -y mysql-server mysql-client
sudo systemctl start mysql
sudo systemctl enable mysql
sudo mysql_secure_installation

# Install Node.js and NPM
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Git
sudo apt install -y git

# Install Apache
sudo apt install -y apache2
sudo systemctl start apache2
sudo systemctl enable apache2
```

### Step 2: Setup Database

```bash
# Login ke MySQL sebagai root
mysql -u root -p

# Buat database dan user
CREATE DATABASE siakad CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'siakad_user'@'localhost' IDENTIFIED BY 'YourStrongPassword123!';
GRANT ALL PRIVILEGES ON siakad.* TO 'siakad_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Test koneksi
mysql -u siakad_user -p siakad
```

### Step 3: Clone dan Setup Aplikasi

```bash
# Buat direktori untuk aplikasi
sudo mkdir -p /var/www/siakad
sudo chown -R $USER:$USER /var/www/siakad

# Clone repository
cd /var/www/siakad
git clone https://github.com/pendtiumpraz/siakad-alfatih-tangerang.git .

# Install PHP dependencies (production mode)
composer install --optimize-autoloader --no-dev

# Install Node dependencies dan build assets
npm install
npm run build

# Copy dan konfigurasi environment file
cp .env.example .env
nano .env  # Edit dengan konfigurasi production
```

### Step 4: Konfigurasi Environment (.env)

Edit file `.env` dengan konfigurasi production:

```env
APP_NAME="SIAKAD STAI AL-FATIH"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://siakad.staialfatih.ac.id

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siakad
DB_USERNAME=siakad_user
DB_PASSWORD=YourStrongPassword123!

# Cache Configuration (untuk production)
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail Configuration (jika ada)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# Google Drive Configuration
GOOGLE_DRIVE_ENABLED=true
GOOGLE_DRIVE_AUTH_TYPE=oauth
GOOGLE_DRIVE_CLIENT_ID=your-client-id.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=GOCSPX-your-client-secret
GOOGLE_DRIVE_REDIRECT_URI="${APP_URL}/oauth/google/callback"
GOOGLE_DRIVE_OAUTH_CREDENTIALS_PATH=storage/app/google/oauth_credentials.json
GOOGLE_DRIVE_ROOT_FOLDER_ID=your-root-folder-id

# Google Drive Folder Structure
GOOGLE_DRIVE_FOLDER_PEMBAYARAN="Pembayaran"
GOOGLE_DRIVE_FOLDER_DOKUMEN_MAHASISWA="Dokumen-Mahasiswa"
GOOGLE_DRIVE_FOLDER_SPMB="SPMB"

# File Naming Convention
GOOGLE_DRIVE_NAMING_SEPARATOR="_"
GOOGLE_DRIVE_NAMING_DATE_FORMAT="Ymd_His"
```

### Step 5: Generate App Key dan Setup Storage

```bash
# Generate application key
php artisan key:generate

# Setup storage dan permissions
php artisan storage:link
sudo chown -R apache:apache /var/www/siakad  # CentOS/Amazon Linux
# Atau
sudo chown -R www-data:www-data /var/www/siakad  # Ubuntu/Debian

# Set proper permissions
sudo chmod -R 755 /var/www/siakad
sudo chmod -R 775 /var/www/siakad/storage
sudo chmod -R 775 /var/www/siakad/bootstrap/cache

# Buat direktori untuk Google Drive credentials
mkdir -p /var/www/siakad/storage/app/google
chmod 755 /var/www/siakad/storage/app/google
```

### Step 6: Run Migrations dan Seeders

```bash
cd /var/www/siakad

# Run migrations
php artisan migrate --force

# Run seeders (hanya sekali saat pertama kali)
php artisan db:seed --force

# Clear dan optimize cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Step 7: Setup Google Drive OAuth 2.0

#### A. Buat Project di Google Cloud Console

1. **Buka Google Cloud Console**
   - Kunjungi: https://console.cloud.google.com/
   - Login dengan akun Google yang akan digunakan untuk menyimpan file

2. **Buat Project Baru**
   - Klik **Select a project** > **New Project**
   - Nama: `SIAKAD STAI AL-FATIH`
   - Organization: Kosongkan jika tidak ada
   - Klik **Create**

3. **Enable Google Drive API**
   - Di dashboard project, klik **APIs & Services** > **Library**
   - Search: `Google Drive API`
   - Klik **Google Drive API** > **Enable**

#### B. Setup OAuth Consent Screen

1. **Konfigurasi OAuth Consent**
   - Klik **APIs & Services** > **OAuth consent screen**
   - User Type: Pilih **Internal** (jika G Workspace) atau **External**
   - Klik **Create**

2. **App Information**
   - App name: `SIAKAD STAI AL-FATIH`
   - User support email: email Anda
   - Developer contact: email Anda
   - Klik **Save and Continue**

3. **Scopes**
   - Klik **Add or Remove Scopes**
   - Cari dan pilih: `https://www.googleapis.com/auth/drive.file`
   - Klik **Update** > **Save and Continue**

4. **Test Users** (jika External)
   - Tambahkan email yang akan digunakan untuk koneksi
   - Klik **Save and Continue**

#### C. Create OAuth Credentials

1. **Buat OAuth Client ID**
   - Klik **APIs & Services** > **Credentials**
   - Klik **Create Credentials** > **OAuth client ID**
   - Application type: **Web application**
   - Name: `SIAKAD Web Client`

2. **Authorized Redirect URIs**
   - Klik **Add URI**
   - Tambahkan: `https://siakad.staialfatih.ac.id/oauth/google/callback`
   - (Ganti dengan domain production Anda)
   - Klik **Create**

3. **Download Credentials**
   - Setelah dibuat, akan muncul popup dengan **Client ID** dan **Client Secret**
   - **COPY dan SIMPAN** kedua nilai ini
   - Klik **Download JSON** untuk download file credentials
   - Klik **OK**

#### D. Upload Credentials ke Server

```bash
# Upload file oauth_credentials.json yang sudah di-download ke server
# Letakkan di: /var/www/siakad/storage/app/google/oauth_credentials.json

# Contoh upload dengan SCP (dari komputer lokal):
scp /path/to/oauth_credentials.json user@server:/var/www/siakad/storage/app/google/

# Atau buat file manual di server:
sudo nano /var/www/siakad/storage/app/google/oauth_credentials.json

# Paste konten JSON dari Google Cloud Console:
{
  "web": {
    "client_id": "938032228387-xxx.apps.googleusercontent.com",
    "project_id": "siakad-stai-alfatih",
    "auth_uri": "https://accounts.google.com/o/oauth2/auth",
    "token_uri": "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
    "client_secret": "GOCSPX-xxxxxxxxxxxx",
    "redirect_uris": [
      "https://siakad.staialfatih.ac.id/oauth/google/callback"
    ]
  }
}

# Set permissions
chmod 644 /var/www/siakad/storage/app/google/oauth_credentials.json
chown apache:apache /var/www/siakad/storage/app/google/oauth_credentials.json
```

#### E. Update .env dengan OAuth Credentials

```bash
# Edit .env
nano /var/www/siakad/.env

# Update nilai berikut:
GOOGLE_DRIVE_ENABLED=true
GOOGLE_DRIVE_AUTH_TYPE=oauth
GOOGLE_DRIVE_CLIENT_ID=938032228387-xxx.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=GOCSPX-xxxxxxxxxxxx
GOOGLE_DRIVE_REDIRECT_URI=https://siakad.staialfatih.ac.id/oauth/google/callback
GOOGLE_DRIVE_OAUTH_CREDENTIALS_PATH=storage/app/google/oauth_credentials.json

# Clear config cache
php artisan config:clear
php artisan config:cache
```

#### F. Connect Google Drive (First Time Setup)

1. **Login sebagai Super Admin**
   - Buka browser: `https://siakad.staialfatih.ac.id`
   - Login dengan username: `superadmin` dan password default

2. **Connect Google Drive**
   - Kunjungi: `https://siakad.staialfatih.ac.id/oauth/google/connect`
   - Anda akan diarahkan ke halaman login Google
   - **Login dengan akun Google yang memiliki Google Drive**
   - Klik **Allow** untuk memberikan akses ke aplikasi
   - Setelah berhasil, akan redirect ke dashboard dengan pesan sukses

3. **Verify Connection**
   - Token akan disimpan di database tabel `google_drive_tokens`
   - Semua user akan menggunakan token yang sama (shared token)
   - Token akan auto-refresh sebelum expire

4. **Test Upload**
   - Login sebagai Operator atau Super Admin
   - Upload bukti pembayaran mahasiswa
   - File akan otomatis terupload ke Google Drive

### Step 8: Configure Apache Virtual Host

#### CentOS/Amazon Linux:

```bash
# Buat virtual host configuration
sudo nano /etc/httpd/conf.d/siakad.conf
```

Tambahkan konfigurasi berikut:

```apache
<VirtualHost *:80>
    ServerName siakad.staialfatih.ac.id
    ServerAdmin admin@staialfatih.ac.id
    DocumentRoot /var/www/siakad/public

    <Directory /var/www/siakad/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog /var/log/httpd/siakad_error.log
    CustomLog /var/log/httpd/siakad_access.log combined
</VirtualHost>
```

```bash
# Test configuration
sudo apachectl configtest

# Restart Apache
sudo systemctl restart httpd
```

#### Ubuntu/Debian:

```bash
# Buat virtual host configuration
sudo nano /etc/apache2/sites-available/siakad.conf
```

Tambahkan konfigurasi yang sama seperti di atas, kemudian:

```bash
# Enable site dan rewrite module
sudo a2ensite siakad.conf
sudo a2enmod rewrite
sudo a2dissite 000-default.conf  # Disable default site

# Test configuration
sudo apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```

### Step 9: Setup SSL Certificate (HTTPS)

#### Install Certbot dan Generate SSL:

```bash
# CentOS/Amazon Linux
sudo yum install -y certbot python3-certbot-apache

# Ubuntu/Debian
sudo apt install -y certbot python3-certbot-apache

# Generate SSL certificate
sudo certbot --apache -d siakad.staialfatih.ac.id

# Follow prompts:
# - Enter email address
# - Agree to Terms of Service
# - Choose whether to share email (optional)
# - Choose redirect HTTP to HTTPS: Yes (option 2)

# Test auto-renewal
sudo certbot renew --dry-run

# Setup auto-renewal cron job
sudo crontab -e

# Add this line:
0 0,12 * * * /usr/bin/certbot renew --quiet
```

### Step 10: Setup Firewall

```bash
# CentOS/Amazon Linux (firewalld)
sudo firewall-cmd --permanent --add-service=http
sudo firewall-cmd --permanent --add-service=https
sudo firewall-cmd --reload

# Ubuntu/Debian (ufw)
sudo ufw allow 'Apache Full'
sudo ufw enable
sudo ufw status
```

### Step 11: Setup Scheduled Tasks (Cron)

Laravel membutuhkan cron untuk scheduled tasks:

```bash
# Edit crontab
crontab -e

# Tambahkan baris berikut:
* * * * * cd /var/www/siakad && php artisan schedule:run >> /dev/null 2>&1
```

### Step 12: Optimize for Production

```bash
cd /var/www/siakad

# Clear all cache
php artisan optimize:clear

# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload -o

# Set proper ownership (pastikan sesuai dengan web server user)
sudo chown -R apache:apache /var/www/siakad  # CentOS
# atau
sudo chown -R www-data:www-data /var/www/siakad  # Ubuntu
```

### Step 13: Security Hardening

#### A. Secure File Permissions

```bash
# Set directory permissions
find /var/www/siakad -type d -exec chmod 755 {} \;

# Set file permissions
find /var/www/siakad -type f -exec chmod 644 {} \;

# Set storage and cache permissions
chmod -R 775 /var/www/siakad/storage
chmod -R 775 /var/www/siakad/bootstrap/cache

# Protect sensitive files
chmod 600 /var/www/siakad/.env
chmod 600 /var/www/siakad/storage/app/google/oauth_credentials.json
```

#### B. Hide Sensitive Information

Edit `/var/www/siakad/public/.htaccess`, tambahkan:

```apache
# Disable directory browsing
Options -Indexes

# Protect .env file
<Files .env>
    Order allow,deny
    Deny from all
</Files>

# Protect composer files
<FilesMatch "^(composer\.json|composer\.lock)$">
    Order allow,deny
    Deny from all
</FilesMatch>
```

#### C. Setup PHP Security (php.ini)

```bash
# Edit PHP configuration
sudo nano /etc/php.ini  # CentOS
# atau
sudo nano /etc/php/8.2/apache2/php.ini  # Ubuntu

# Update these settings:
expose_php = Off
display_errors = Off
log_errors = On
error_log = /var/log/php_errors.log
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
memory_limit = 256M

# Restart Apache
sudo systemctl restart httpd  # CentOS
sudo systemctl restart apache2  # Ubuntu
```

### Step 14: Setup Backup

#### A. Database Backup Script

```bash
# Buat backup script
sudo nano /usr/local/bin/backup-siakad-db.sh
```

Tambahkan script berikut:

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/siakad"
DATE=$(date +%Y%m%d_%H%M%S)
DB_NAME="siakad"
DB_USER="siakad_user"
DB_PASS="YourStrongPassword123!"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME > $BACKUP_DIR/siakad_db_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/siakad_db_$DATE.sql

# Delete backups older than 30 days
find $BACKUP_DIR -name "siakad_db_*.sql.gz" -mtime +30 -delete

echo "Backup completed: siakad_db_$DATE.sql.gz"
```

```bash
# Set executable
sudo chmod +x /usr/local/bin/backup-siakad-db.sh

# Create backup directory
sudo mkdir -p /var/backups/siakad

# Test backup
sudo /usr/local/bin/backup-siakad-db.sh

# Setup daily backup via cron
sudo crontab -e

# Add this line (backup every day at 2 AM):
0 2 * * * /usr/local/bin/backup-siakad-db.sh >> /var/log/siakad-backup.log 2>&1
```

#### B. File Backup Script

```bash
# Buat file backup script
sudo nano /usr/local/bin/backup-siakad-files.sh
```

Tambahkan script berikut:

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/siakad"
DATE=$(date +%Y%m%d_%H%M%S)
APP_DIR="/var/www/siakad"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup storage directory (uploaded files)
tar -czf $BACKUP_DIR/siakad_storage_$DATE.tar.gz -C $APP_DIR storage/app/public

# Delete backups older than 30 days
find $BACKUP_DIR -name "siakad_storage_*.tar.gz" -mtime +30 -delete

echo "File backup completed: siakad_storage_$DATE.tar.gz"
```

```bash
# Set executable
sudo chmod +x /usr/local/bin/backup-siakad-files.sh

# Test backup
sudo /usr/local/bin/backup-siakad-files.sh

# Setup weekly backup via cron
sudo crontab -e

# Add this line (backup every Sunday at 3 AM):
0 3 * * 0 /usr/local/bin/backup-siakad-files.sh >> /var/log/siakad-backup.log 2>&1
```

### Step 15: Monitoring dan Logging

#### Setup Log Rotation

```bash
# Buat log rotation config
sudo nano /etc/logrotate.d/siakad
```

Tambahkan konfigurasi:

```
/var/www/siakad/storage/logs/*.log {
    daily
    rotate 14
    compress
    delaycompress
    notifempty
    missingok
    create 0644 apache apache
}
```

### Step 16: Update dan Maintenance

#### Cara Update Aplikasi ke Versi Terbaru:

```bash
# Masuk ke direktori aplikasi
cd /var/www/siakad

# Backup dulu sebelum update
sudo /usr/local/bin/backup-siakad-db.sh
sudo /usr/local/bin/backup-siakad-files.sh

# Pull latest changes
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run new migrations (jika ada)
php artisan migrate --force

# Clear dan rebuild cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# Set proper permissions
sudo chown -R apache:apache /var/www/siakad  # CentOS
# atau
sudo chown -R www-data:www-data /var/www/siakad  # Ubuntu
```

### Troubleshooting Production

#### 1. Google Drive Upload Error

**Error**: "Service Account has no storage quota"
- **Solusi**: Pastikan menggunakan OAuth, bukan Service Account
- Check `.env`: `GOOGLE_DRIVE_AUTH_TYPE=oauth`

**Error**: "Google Drive has not been connected"
- **Solusi**: Admin harus connect dulu via `/oauth/google/connect`

**Error**: "Failed to refresh Google Drive token"
- **Solusi**: Disconnect dan reconnect via `/oauth/google/disconnect` dan `/oauth/google/connect`

#### 2. 500 Internal Server Error

```bash
# Check error logs
tail -f /var/log/httpd/siakad_error.log  # CentOS
tail -f /var/log/apache2/error.log  # Ubuntu

# Check Laravel logs
tail -f /var/www/siakad/storage/logs/laravel.log

# Common fixes:
php artisan optimize:clear
php artisan config:cache
sudo chown -R apache:apache /var/www/siakad/storage
sudo chmod -R 775 /var/www/siakad/storage
```

#### 3. Permission Denied Errors

```bash
# Fix ownership
sudo chown -R apache:apache /var/www/siakad  # CentOS
sudo chown -R www-data:www-data /var/www/siakad  # Ubuntu

# Fix permissions
sudo chmod -R 755 /var/www/siakad
sudo chmod -R 775 /var/www/siakad/storage
sudo chmod -R 775 /var/www/siakad/bootstrap/cache

# Fix SELinux (CentOS only)
sudo setenforce 0  # Temporary
sudo nano /etc/selinux/config  # Set SELINUX=disabled for permanent
```

#### 4. Database Connection Error

```bash
# Test database connection
php artisan db:show

# Test MySQL connection
mysql -u siakad_user -p siakad

# Check MySQL is running
sudo systemctl status mysqld  # CentOS
sudo systemctl status mysql  # Ubuntu
```

#### 5. SSL Certificate Issues

```bash
# Test SSL certificate
sudo certbot certificates

# Renew SSL manually
sudo certbot renew

# Check Apache SSL configuration
sudo apachectl -t -D DUMP_VHOSTS
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
