# 🚀 CI/CD Deployment Guide untuk DomaiNesia

## 📋 Daftar Isi
1. [Persiapan SSH Key](#persiapan-ssh-key)
2. [Setup GitHub Secrets](#setup-github-secrets)
3. [Konfigurasi Server DomaiNesia](#konfigurasi-server-domainesia)
4. [Workflow yang Tersedia](#workflow-yang-tersedia)
5. [Cara Deployment](#cara-deployment)
6. [Troubleshooting](#troubleshooting)

---

## 🔑 Persiapan SSH Key

### 1. Verifikasi SSH Key yang Ada

SSH key sudah tersedia di lokasi:
- **Private Key**: `/mnt/host/d/AI/SIAKAD/id_rsa`
- **Public Key**: `/mnt/host/d/AI/SIAKAD/id_rsa.pub`

### 2. Copy Public Key ke Server DomaiNesia

Anda perlu menambahkan public key ke server DomaiNesia:

```bash
# Lihat isi public key
cat /mnt/host/d/AI/SIAKAD/id_rsa.pub
```

**Output akan seperti:**
```
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC... your_email@example.com
```

#### Cara Menambahkan ke DomaiNesia:

**Opsi A: Via SSH Terminal**
```bash
# Login ke server DomaiNesia via SSH
ssh username@your-domain.com

# Tambahkan public key
mkdir -p ~/.ssh
chmod 700 ~/.ssh
nano ~/.ssh/authorized_keys
# Paste public key, save (Ctrl+X, Y, Enter)
chmod 600 ~/.ssh/authorized_keys
```

**Opsi B: Via cPanel File Manager**
1. Login ke cPanel DomaiNesia
2. Buka File Manager
3. Tampilkan hidden files (Settings)
4. Navigasi ke `/home/username/.ssh/`
5. Edit file `authorized_keys` atau buat baru
6. Paste public key Anda
7. Save

**Opsi C: Via cPanel SSH Access**
1. Login ke cPanel
2. Masuk ke menu "SSH Access"
3. Klik "Manage SSH Keys"
4. Import public key atau paste langsung
5. Authorize the key

---

## ⚙️ Setup GitHub Secrets

### 1. Akses GitHub Repository Settings

1. Buka repository GitHub Anda: `https://github.com/USERNAME/REPO-NAME`
2. Klik **Settings** (tab paling kanan)
3. Di sidebar kiri, klik **Secrets and variables** → **Actions**
4. Klik **New repository secret**

### 2. Tambahkan Secrets Berikut

#### SECRET 1: `SSH_PRIVATE_KEY`
**Value**: Isi dari file private key Anda

```bash
# Copy isi private key
cat /mnt/host/d/AI/SIAKAD/id_rsa
```

Copy **semua output** termasuk:
```
-----BEGIN OPENSSH PRIVATE KEY-----
b3BlbnNzaC1rZXktdjEAAAAABG5vbmUAAAAEbm9uZQAAAAAAAAABAAABlwAAAAdzc2gtcn
...
...
-----END OPENSSH PRIVATE KEY-----
```

**Paste** ke GitHub Secret dengan nama: `SSH_PRIVATE_KEY`

---

#### SECRET 2: `SSH_HOST`
**Value**: Domain atau IP server DomaiNesia Anda

Contoh:
```
siakad.yourdomain.com
```
atau
```
123.45.67.89
```

---

#### SECRET 3: `SSH_USER`
**Value**: Username SSH Anda di DomaiNesia

Biasanya sama dengan username cPanel, contoh:
```
username
```
atau
```
u1234567
```

---

#### SECRET 4: `DEPLOY_PATH`
**Value**: Path lengkap ke folder aplikasi di server

Untuk DomaiNesia, biasanya:
```
/home/username/public_html/siakad
```
atau jika subdomain:
```
/home/username/siakad.yourdomain.com
```

**Cara cek path yang benar:**
```bash
# Login via SSH dan jalankan
pwd
# Output akan menunjukkan path lengkap
```

---

### 3. Verifikasi Secrets

Setelah menambahkan semua secrets, Anda akan melihat 4 secrets:
- ✅ `SSH_PRIVATE_KEY`
- ✅ `SSH_HOST`
- ✅ `SSH_USER`
- ✅ `DEPLOY_PATH`

---

## 🖥️ Konfigurasi Server DomaiNesia

### 1. Setup Git di Server

```bash
# Login via SSH
ssh username@siakad.yourdomain.com

# Cek apakah git sudah terinstall
git --version

# Jika belum, hubungi support DomaiNesia untuk install git
# Atau gunakan softaculous jika tersedia
```

### 2. Clone Repository ke Server (First Time)

```bash
# Navigasi ke directory yang tepat
cd /home/username/

# Clone repository
git clone https://github.com/USERNAME/REPO-NAME.git siakad.yourdomain.com

# Masuk ke folder
cd siakad.yourdomain.com

# Setup permission
chmod -R 755 .
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 3. Setup Environment File

```bash
# Copy .env.example
cp .env.example .env

# Edit .env dengan kredensial database DomaiNesia
nano .env
```

**Isi .env untuk DomaiNesia:**
```env
APP_NAME="SIAKAD"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://siakad.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=username_siakad
DB_USERNAME=username_siakad
DB_PASSWORD=your_db_password

# ... sisanya sesuaikan
```

### 4. Install Dependencies

```bash
# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Install NPM dan build (jika Node.js tersedia)
npm install
npm run build

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Setup .htaccess untuk Laravel

Pastikan file `.htaccess` di `public_html` redirect ke folder `public`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

Atau jika menggunakan subdomain, arahkan document root ke folder `public`:
- Di cPanel → Domains → Manage
- Edit subdomain → Document Root → `/home/username/siakad.yourdomain.com/public`

---

## 🔄 Workflow yang Tersedia

### 1. CI (Continuous Integration)
**File**: `.github/workflows/ci.yml`

**Triggered by:**
- Push ke branch `main`, `master`, `develop`
- Pull request ke branch `main`, `master`, `develop`

**Jobs:**
- ✅ Run tests (PHP 8.2, 8.3)
- ✅ Build frontend assets
- ✅ Code quality check (Laravel Pint)
- ✅ Security vulnerability check

### 2. Deploy (Continuous Deployment)
**File**: `.github/workflows/deploy.yml`

**Triggered by:**
- Push ke branch `main` atau `master`
- Manual trigger (workflow_dispatch)

**Process:**
1. Checkout code
2. Install dependencies
3. Build frontend
4. Deploy via SSH ke server
5. Run migrations
6. Clear & cache config
7. Restart queue workers

### 3. Tests
**File**: `.github/workflows/tests.yml`

**Triggered by:**
- Push ke branch `master`, `*.x`
- Pull request
- Scheduled (daily)

**Matrix testing**: PHP 8.2, 8.3, 8.4

---

## 🚀 Cara Deployment

### Automatic Deployment (Recommended)

1. **Commit & Push perubahan:**
```bash
cd siakad-app
git add .
git commit -m "Update: fitur baru"
git push origin main
```

2. **GitHub Actions akan otomatis:**
   - Run CI tests
   - Build frontend
   - Deploy ke server DomaiNesia

3. **Monitor progress:**
   - Buka repository di GitHub
   - Klik tab **Actions**
   - Lihat workflow yang sedang berjalan

### Manual Deployment

Jika ingin deploy manual tanpa push:

1. Buka repository di GitHub
2. Klik tab **Actions**
3. Pilih workflow **Deploy to Server**
4. Klik **Run workflow**
5. Pilih branch (biasanya `main`)
6. Klik **Run workflow** (button hijau)

### Manual Deployment via SSH (Traditional)

```bash
# Login ke server
ssh username@siakad.yourdomain.com

# Masuk ke folder project
cd /home/username/siakad.yourdomain.com

# Enable maintenance mode
php artisan down

# Pull latest changes
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Run migrations
php artisan migrate --force

# Build frontend
npm install
npm run build

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Disable maintenance mode
php artisan up
```

---

## 🐛 Troubleshooting

### 1. SSH Connection Failed

**Error:**
```
Permission denied (publickey)
```

**Solution:**
- Pastikan public key sudah ditambahkan ke `~/.ssh/authorized_keys` di server
- Pastikan permission: `chmod 600 ~/.ssh/authorized_keys`
- Pastikan private key di GitHub Secrets sudah benar

### 2. Git Not Found di Server

**Error:**
```
bash: git: command not found
```

**Solution:**
- Hubungi support DomaiNesia untuk install Git
- Atau gunakan FTP deployment (alternatif workflow)

### 3. Permission Denied saat Deploy

**Error:**
```
Permission denied: storage/
```

**Solution:**
```bash
# Set permission yang benar
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 4. Database Connection Failed

**Error:**
```
SQLSTATE[HY000] [1045] Access denied
```

**Solution:**
- Cek kredensial database di `.env`
- Pastikan database sudah dibuat di cPanel
- Pastikan user database sudah memiliki privilege

### 5. Node/NPM Not Found

**Error:**
```
npm: command not found
```

**Solution A (Recommended):**
- Build asset di GitHub Actions (sudah included)
- Tidak perlu build di server

**Solution B:**
- Install Node.js via NVM:
```bash
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
source ~/.bashrc
nvm install 20
nvm use 20
```

### 6. Composer Not Found

**Solution:**
```bash
# Install Composer di server
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
# atau
alias composer='php ~/composer.phar'
```

### 7. Workflow Fails dengan "secrets not found"

**Solution:**
- Pastikan semua 4 secrets sudah ditambahkan:
  - `SSH_PRIVATE_KEY`
  - `SSH_HOST`
  - `SSH_USER`
  - `DEPLOY_PATH`
- Nama secret harus **EXACT MATCH** (case sensitive)

---

## 📞 Support

Jika mengalami kendala:

1. **Check GitHub Actions Logs:**
   - Repository → Actions → Pilih workflow yang failed
   - Baca error message

2. **Check Server Logs:**
```bash
# Laravel logs
tail -f storage/logs/laravel.log

# Apache/Nginx error logs (DomaiNesia)
tail -f ~/logs/error_log
```

3. **Contact DomaiNesia Support:**
   - https://www.domainesia.com/kontak
   - Live chat: 24/7
   - Email: support@domainesia.com

---

## 🎯 Best Practices

1. **Selalu test di local dulu** sebelum push
2. **Gunakan branch** `develop` untuk development
3. **Merge ke** `main` hanya untuk production-ready code
4. **Backup database** sebelum deploy:
   ```bash
   php artisan backup:run # jika sudah setup laravel-backup
   ```
5. **Monitor logs** setelah deployment
6. **Use maintenance mode** untuk update besar:
   ```bash
   php artisan down --secret="rahasia123"
   # Update...
   php artisan up
   # Akses saat maintenance: https://siakad.com/rahasia123
   ```

---

## 📝 Checklist Deployment

- [ ] SSH key sudah ditambahkan ke server
- [ ] 4 GitHub Secrets sudah dikonfigurasi
- [ ] Repository sudah di-clone ke server
- [ ] File `.env` sudah dikonfigurasi
- [ ] Database sudah dibuat dan migrasi berhasil
- [ ] Dependencies sudah terinstall
- [ ] Permission storage dan bootstrap/cache sudah 775
- [ ] Workflow deploy berjalan tanpa error
- [ ] Website bisa diakses dan berfungsi normal

---

**Last Updated:** 2025-10-20
**Version:** 1.0
**Author:** SIAKAD DevOps Team
