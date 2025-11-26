# 🌐 Google OAuth untuk Multi-Environment

## 📋 **3 Environment yang Perlu Disetup:**

1. **Localhost (Development Lokal):**
   ```
   http://127.0.0.1:8000
   ```

2. **Development Server:**
   ```
   https://siakad-dev.staialfatih.or.id
   ```

3. **Production Server:**
   ```
   https://siakad.staialfatih.or.id
   ```

---

## ✅ **SOLUSI: 1 OAuth Client untuk Semua Environment**

### **Keuntungan:**
- ✅ Lebih simple, 1 Client ID & Secret untuk semua
- ✅ Mudah maintenance
- ✅ Token bisa di-share antar environment (untuk testing)

### **Cara Setup:**

---

## 🔧 **STEP 1: Setup di Google Cloud Console**

### **1. Buka Google Cloud Console:**
```
https://console.cloud.google.com/
```

### **2. Pilih Project SIAKAD**
(Atau buat baru jika belum ada)

### **3. Enable Google Drive API:**
- APIs & Services → Library
- Search: "Google Drive API"
- Klik "ENABLE"

### **4. Buat OAuth Client ID:**

**a) Buka:** APIs & Services → Credentials

**b) Klik:** CREATE CREDENTIALS → OAuth client ID

**c) Configure OAuth Consent Screen** (jika belum):
   - User Type: **External**
   - App name: `SIAKAD STAI Al-Fatih`
   - User support email: admin@staialfatih.or.id
   - Developer contact: admin@staialfatih.or.id
   - Scopes: `https://www.googleapis.com/auth/drive.file`
   - Test users: (tambahkan email yang akan digunakan)

**d) Create OAuth Client ID:**
   - Application type: **Web application**
   - Name: `SIAKAD OAuth Client (All Environments)`
   
**e) Authorized redirect URIs** - **TAMBAHKAN KETIGA-TIGANYA:**

   ```
   http://127.0.0.1:8000/oauth/google/callback
   http://localhost:8000/oauth/google/callback
   https://siakad-dev.staialfatih.or.id/oauth/google/callback
   https://siakad.staialfatih.or.id/oauth/google/callback
   ```

**f) Klik:** CREATE

**g) Download JSON credentials:**
   - Simpan sebagai: `oauth_credentials.json`

---

## 📁 **STEP 2: Setup File Credentials**

### **Upload `oauth_credentials.json` ke SEMUA Environment:**

**1. Localhost:**
```
D:\AI\siakad\siakad-app\storage\app\google\oauth_credentials.json
```

**2. Development Server:**
```bash
# Via SSH atau File Manager
/path/to/siakad-dev/storage/app/google/oauth_credentials.json
```

**3. Production Server:**
```bash
# Via SSH atau File Manager
/path/to/siakad/storage/app/google/oauth_credentials.json
```

**Catatan:** File `oauth_credentials.json` **SAMA** untuk semua environment!

---

## ⚙️ **STEP 3: Config .env per Environment**

### **A) Localhost - `.env`**

```env
APP_URL=http://127.0.0.1:8000

# Google Drive OAuth
GOOGLE_DRIVE_ENABLED=true
GOOGLE_DRIVE_AUTH_TYPE=oauth
GOOGLE_DRIVE_CLIENT_ID=938032228387-b3n6jpqrriv2f8fup494u4ickm41hsb6.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=GOCSPX-your-client-secret
GOOGLE_DRIVE_REDIRECT_URI=http://127.0.0.1:8000/oauth/google/callback
GOOGLE_DRIVE_OAUTH_CREDENTIALS_PATH=storage/app/google/oauth_credentials.json
GOOGLE_DRIVE_ROOT_FOLDER_ID=1b6JKZteCp6SsQyt0FMNEYAPfXuCEn0a8
```

---

### **B) Development Server - `.env`**

```env
APP_URL=https://siakad-dev.staialfatih.or.id

# Google Drive OAuth
GOOGLE_DRIVE_ENABLED=true
GOOGLE_DRIVE_AUTH_TYPE=oauth
GOOGLE_DRIVE_CLIENT_ID=938032228387-b3n6jpqrriv2f8fup494u4ickm41hsb6.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=GOCSPX-your-client-secret
GOOGLE_DRIVE_REDIRECT_URI=https://siakad-dev.staialfatih.or.id/oauth/google/callback
GOOGLE_DRIVE_OAUTH_CREDENTIALS_PATH=storage/app/google/oauth_credentials.json
GOOGLE_DRIVE_ROOT_FOLDER_ID=1b6JKZteCp6SsQyt0FMNEYAPfXuCEn0a8
```

**ATAU pakai dynamic redirect URI (recommended):**

```env
GOOGLE_DRIVE_REDIRECT_URI=${APP_URL}/oauth/google/callback
```

---

### **C) Production Server - `.env`**

```env
APP_URL=https://siakad.staialfatih.or.id

# Google Drive OAuth
GOOGLE_DRIVE_ENABLED=true
GOOGLE_DRIVE_AUTH_TYPE=oauth
GOOGLE_DRIVE_CLIENT_ID=938032228387-b3n6jpqrriv2f8fup494u4ickm41hsb6.apps.googleusercontent.com
GOOGLE_DRIVE_CLIENT_SECRET=GOCSPX-your-client-secret
GOOGLE_DRIVE_REDIRECT_URI=https://siakad.staialfatih.or.id/oauth/google/callback
GOOGLE_DRIVE_OAUTH_CREDENTIALS_PATH=storage/app/google/oauth_credentials.json
GOOGLE_DRIVE_ROOT_FOLDER_ID=1b6JKZteCp6SsQyt0FMNEYAPfXuCEn0a8
```

**ATAU:**

```env
GOOGLE_DRIVE_REDIRECT_URI=${APP_URL}/oauth/google/callback
```

---

## 🚀 **STEP 4: Aktivasi per Environment**

### **1. Localhost:**

```bash
# Clear config
php artisan config:clear

# Buka browser
http://127.0.0.1:8000/oauth/google/connect
```

### **2. Development Server:**

```bash
# SSH ke server dev
ssh user@siakad-dev.staialfatih.or.id

# Clear config
cd /path/to/siakad-dev
php artisan config:clear

# Buka browser
https://siakad-dev.staialfatih.or.id/oauth/google/connect
```

### **3. Production Server:**

```bash
# SSH ke server production
ssh user@siakad.staialfatih.or.id

# Clear config
cd /path/to/siakad
php artisan config:clear

# Buka browser
https://siakad.staialfatih.or.id/oauth/google/connect
```

---

## 📊 **Database Tokens - PENTING!**

### **Token Tersimpan per Environment:**

Setiap environment punya **database terpisah**, jadi:

| Environment | Database | Token Storage |
|-------------|----------|---------------|
| Localhost | `siakad_local` | `google_drive_tokens` (localhost) |
| Dev Server | `siakad_dev` | `google_drive_tokens` (dev) |
| Production | `siakad_production` | `google_drive_tokens` (prod) |

**Artinya:**
- ✅ Aktivasi OAuth di **localhost** → token tersimpan di database localhost
- ✅ Aktivasi OAuth di **dev server** → token tersimpan di database dev
- ✅ Aktivasi OAuth di **production** → token tersimpan di database prod

**Kesimpulan:** Anda perlu aktivasi OAuth **3 kali** (sekali per environment)!

---

## 🎯 **WORKFLOW - Cara Kerja:**

### **Skenario 1: Test di Localhost**

1. **Setup `.env` localhost** dengan `APP_URL=http://127.0.0.1:8000`
2. **Aktivasi:** `http://127.0.0.1:8000/oauth/google/connect`
3. **Google redirect ke:** `http://127.0.0.1:8000/oauth/google/callback`
4. **Token tersimpan** di database localhost
5. **Upload file** di localhost akan upload ke Google Drive ✅

---

### **Skenario 2: Deploy ke Dev Server**

1. **Upload code** ke server dev
2. **Setup `.env` dev** dengan `APP_URL=https://siakad-dev.staialfatih.or.id`
3. **Aktivasi:** `https://siakad-dev.staialfatih.or.id/oauth/google/connect`
4. **Google redirect ke:** `https://siakad-dev.staialfatih.or.id/oauth/google/callback`
5. **Token tersimpan** di database dev
6. **Upload file** di dev akan upload ke Google Drive ✅

---

### **Skenario 3: Deploy ke Production**

1. **Upload code** ke server production
2. **Setup `.env` production** dengan `APP_URL=https://siakad.staialfatih.or.id`
3. **Aktivasi:** `https://siakad.staialfatih.or.id/oauth/google/connect`
4. **Google redirect ke:** `https://siakad.staialfatih.or.id/oauth/google/callback`
5. **Token tersimpan** di database production
6. **Upload file** di production akan upload ke Google Drive ✅

---

## 🔄 **ALTERNATIF: OAuth Client Terpisah (Optional)**

### **Kapan Perlu Terpisah?**

- Ingin file dari dev & production **TERPISAH** ke folder berbeda
- Ingin tracking lebih jelas mana upload dari dev vs prod
- Security requirement: prod & dev harus isolated

### **Setup:**

**1. Buat 3 OAuth Client terpisah di Google Cloud Console:**

| Client Name | Redirect URI |
|-------------|--------------|
| SIAKAD Localhost | `http://127.0.0.1:8000/oauth/google/callback` |
| SIAKAD Dev | `https://siakad-dev.staialfatih.or.id/oauth/google/callback` |
| SIAKAD Production | `https://siakad.staialfatih.or.id/oauth/google/callback` |

**2. Download 3 credentials JSON terpisah:**
- `oauth_credentials_local.json`
- `oauth_credentials_dev.json`
- `oauth_credentials_prod.json`

**3. Setup `.env` per environment:**

```env
# Localhost
GOOGLE_DRIVE_OAUTH_CREDENTIALS_PATH=storage/app/google/oauth_credentials_local.json

# Dev
GOOGLE_DRIVE_OAUTH_CREDENTIALS_PATH=storage/app/google/oauth_credentials_dev.json

# Production
GOOGLE_DRIVE_OAUTH_CREDENTIALS_PATH=storage/app/google/oauth_credentials_prod.json
```

**4. Bisa pakai Google Drive folder berbeda:**

```env
# Localhost
GOOGLE_DRIVE_ROOT_FOLDER_ID=folder-id-local

# Dev
GOOGLE_DRIVE_ROOT_FOLDER_ID=folder-id-dev

# Production
GOOGLE_DRIVE_ROOT_FOLDER_ID=folder-id-production
```

---

## ✅ **REKOMENDASI:**

### **Untuk STAI Al-Fatih:**

**Gunakan 1 OAuth Client untuk semua environment** (cara pertama)

**Alasan:**
- ✅ Lebih simple
- ✅ File dari dev & prod tersimpan ke 1 Google Drive yang sama
- ✅ Mudah maintenance
- ✅ Client ID & Secret sama untuk semua

**Tapi:**
- ⚠️ Perlu hati-hati saat test upload di dev (file akan masuk ke Drive production juga)
- ⚠️ Bisa set `GOOGLE_DRIVE_ENABLED=false` di `.env` localhost/dev untuk disable saat development

---

## 🧪 **TESTING CHECKLIST:**

### **✅ Localhost:**
- [ ] `.env` → `APP_URL=http://127.0.0.1:8000`
- [ ] Aktivasi: `http://127.0.0.1:8000/oauth/google/connect`
- [ ] Success message muncul
- [ ] Token tersimpan di database localhost
- [ ] Upload file test → file masuk Google Drive

### **✅ Development Server:**
- [ ] `.env` → `APP_URL=https://siakad-dev.staialfatih.or.id`
- [ ] Aktivasi: `https://siakad-dev.staialfatih.or.id/oauth/google/connect`
- [ ] Success message muncul
- [ ] Token tersimpan di database dev
- [ ] Upload file test → file masuk Google Drive

### **✅ Production Server:**
- [ ] `.env` → `APP_URL=https://siakad.staialfatih.or.id`
- [ ] Aktivasi: `https://siakad.staialfatih.or.id/oauth/google/connect`
- [ ] Success message muncul
- [ ] Token tersimpan di database production
- [ ] Upload file real → file masuk Google Drive

---

## ❌ **TROUBLESHOOTING:**

### **Error: "redirect_uri_mismatch"**

**Cause:** Redirect URI tidak terdaftar di Google Cloud Console

**Solution:**
1. Cek `.env` → `GOOGLE_DRIVE_REDIRECT_URI`
2. Pastikan URL **PERSIS SAMA** dengan yang didaftarkan di Google Cloud Console
3. Google Cloud Console → Credentials → Edit OAuth Client
4. Check "Authorized redirect URIs" → harus ada URL yang error
5. Kalau belum ada, **ADD URI** lalu SAVE

**Contoh error:**
```
Error: redirect_uri_mismatch
The redirect URI in the request: https://siakad-dev.staialfatih.or.id/oauth/google/callback
does not match the ones authorized for the OAuth client.
```

**Fix:**
- Buka Google Cloud Console
- Credentials → Edit OAuth Client
- Tambahkan: `https://siakad-dev.staialfatih.or.id/oauth/google/callback`
- SAVE

---

### **Error: "invalid_client"**

**Cause:** Client ID atau Client Secret salah

**Solution:**
1. Cek `.env`:
   ```env
   GOOGLE_DRIVE_CLIENT_ID=...
   GOOGLE_DRIVE_CLIENT_SECRET=...
   ```
2. Bandingkan dengan Google Cloud Console → Credentials
3. Copy ulang jika berbeda
4. Clear config: `php artisan config:clear`

---

### **Error: Access Blocked / App Not Verified**

**Cause:** OAuth consent screen masih dalam mode "Testing"

**Solution 1: Tambah Test Users**
- Google Cloud Console → OAuth consent screen
- Scroll ke "Test users"
- ADD USERS → tambahkan email yang mau dipakai
- Test users bisa akses meski app belum verified

**Solution 2: Publish App** (untuk production)
- OAuth consent screen → PUBLISH APP
- Submit untuk verification (butuh waktu beberapa hari)
- Setelah verified, semua user bisa akses

---

## 📋 **SUMMARY:**

| Step | Localhost | Dev Server | Production |
|------|-----------|------------|------------|
| **APP_URL** | `http://127.0.0.1:8000` | `https://siakad-dev.staialfatih.or.id` | `https://siakad.staialfatih.or.id` |
| **Redirect URI** | `http://127.0.0.1:8000/oauth/google/callback` | `https://siakad-dev.staialfatih.or.id/oauth/google/callback` | `https://siakad.staialfatih.or.id/oauth/google/callback` |
| **Aktivasi URL** | `http://127.0.0.1:8000/oauth/google/connect` | `https://siakad-dev.staialfatih.or.id/oauth/google/connect` | `https://siakad.staialfatih.or.id/oauth/google/connect` |
| **Credentials File** | Same `oauth_credentials.json` | Same `oauth_credentials.json` | Same `oauth_credentials.json` |
| **Client ID** | Same | Same | Same |
| **Client Secret** | Same | Same | Same |
| **Database** | siakad_local | siakad_dev | siakad_production |
| **Token Storage** | Separate | Separate | Separate |

---

## 🎯 **QUICK START:**

### **1. Setup Google Cloud Console (sekali aja):**
- Tambahkan **3 redirect URIs** ke 1 OAuth Client

### **2. Setup `.env` per environment:**
- Update `APP_URL` sesuai environment
- `GOOGLE_DRIVE_REDIRECT_URI=${APP_URL}/oauth/google/callback`

### **3. Aktivasi (per environment):**
```
{APP_URL}/oauth/google/connect
```

---

**Done! Sekarang siap untuk development, staging, dan production!** 🚀
