# ⚡ Quick Start - Setup CI/CD DomaiNesia

## 🎯 Langkah Cepat (5 Menit)

### 1. Tambahkan SSH Public Key ke Server DomaiNesia

```bash
# Tampilkan public key
cat /mnt/host/d/AI/SIAKAD/id_rsa.pub
```

**Login ke cPanel DomaiNesia:**
1. Masuk cPanel → SSH Access → Manage SSH Keys
2. Import Public Key atau paste manual
3. Authorize key tersebut

**Atau via terminal:**
```bash
ssh username@siakad.yourdomain.com
mkdir -p ~/.ssh
nano ~/.ssh/authorized_keys
# Paste public key, save
chmod 600 ~/.ssh/authorized_keys
```

---

### 2. Setup GitHub Secrets

Buka: `https://github.com/YOUR-USERNAME/siakad-app/settings/secrets/actions`

Tambahkan 4 secrets:

| Secret Name | Value | Contoh |
|------------|--------|--------|
| `SSH_PRIVATE_KEY` | Isi file `/mnt/host/d/AI/SIAKAD/id_rsa` | `-----BEGIN OPENSSH...` |
| `SSH_HOST` | Domain/IP server | `siakad.yourdomain.com` |
| `SSH_USER` | Username SSH | `username` atau `u1234567` |
| `DEPLOY_PATH` | Path ke aplikasi | `/home/username/siakad.yourdomain.com` |

**Cara copy private key:**
```bash
cat /mnt/host/d/AI/SIAKAD/id_rsa
# Copy SEMUA output (termasuk BEGIN dan END)
```

---

### 3. Setup Server (First Time Only)

```bash
# SSH ke server
ssh username@siakad.yourdomain.com

# Clone repository
cd /home/username/
git clone https://github.com/YOUR-USERNAME/siakad-app.git siakad.yourdomain.com
cd siakad.yourdomain.com

# Setup .env
cp .env.example .env
nano .env  # Edit DB credentials

# Install dependencies
composer install --no-dev --optimize-autoloader
php artisan key:generate
php artisan migrate --force

# Build frontend
npm install
npm run build

# Set permissions
chmod -R 775 storage bootstrap/cache
```

**Setup Domain di cPanel:**
- Domains → Subdomain → Add `siakad`
- Document Root → `/home/username/siakad.yourdomain.com/public`

---

### 4. Test Deployment

```bash
# Di local machine
cd siakad-app
git add .
git commit -m "Test: setup CI/CD"
git push origin main
```

**Monitor:**
- Buka `https://github.com/YOUR-USERNAME/siakad-app/actions`
- Lihat workflow "Deploy to Server" berjalan
- Tunggu hingga selesai ✅

**Cek website:**
- Buka `https://siakad.yourdomain.com`
- Pastikan aplikasi berjalan normal

---

## ✅ Checklist

- [ ] Public key di server (`~/.ssh/authorized_keys`)
- [ ] 4 GitHub Secrets sudah ditambahkan
- [ ] Repository di-clone ke server
- [ ] File `.env` sudah dikonfigurasi dengan DB credentials
- [ ] `php artisan migrate` berhasil
- [ ] Document root domain sudah diarahkan ke folder `public`
- [ ] Test push ke GitHub dan workflow berhasil
- [ ] Website bisa diakses dan berfungsi

---

## 🚀 Workflow Otomatis

Setelah setup selesai, setiap kali Anda push ke branch `main`:

1. ✅ Tests berjalan otomatis
2. ✅ Frontend di-build
3. ✅ Code quality check
4. ✅ Deploy otomatis ke server
5. ✅ Migration otomatis
6. ✅ Cache otomatis

**Tidak perlu SSH manual lagi!**

---

## 🐛 Troubleshooting Cepat

**SSH Connection Failed?**
```bash
# Cek apakah SSH key sudah ditambahkan
ssh username@siakad.yourdomain.com
# Jika berhasil login, berarti SSH key OK
```

**Secrets Not Found?**
- Cek nama secret harus PERSIS: `SSH_PRIVATE_KEY`, `SSH_HOST`, `SSH_USER`, `DEPLOY_PATH`
- Case sensitive!

**Deploy Failed?**
- Lihat error di GitHub Actions logs
- Cek apakah Git terinstall di server: `git --version`
- Cek apakah path `DEPLOY_PATH` benar

---

## 📖 Dokumentasi Lengkap

Lihat: [DEPLOYMENT.md](./DEPLOYMENT.md)

---

**Happy Deploying! 🎉**
