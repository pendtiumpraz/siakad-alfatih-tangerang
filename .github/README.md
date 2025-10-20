# 🔧 GitHub Workflows & CI/CD

Direktori ini berisi konfigurasi GitHub Actions untuk Continuous Integration dan Continuous Deployment (CI/CD) aplikasi SIAKAD.

---

## 📋 Workflows yang Tersedia

### 1. 🔄 CI (Continuous Integration)
**File:** `workflows/ci.yml`

**Trigger:**
- Push ke `main`, `master`, `develop`
- Pull request ke `main`, `master`, `develop`

**Jobs:**
- ✅ **Tests**: Run PHPUnit tests (PHP 8.2, 8.3)
- ✅ **Build Frontend**: Build assets dengan Vite
- ✅ **Code Quality**: Laravel Pint style check
- ✅ **Security**: Composer audit untuk vulnerability check

**Badge:**
```markdown
![CI](https://github.com/YOUR-USERNAME/siakad-app/workflows/CI/badge.svg)
```

---

### 2. 🚀 Deploy (SSH - Recommended)
**File:** `workflows/deploy.yml`

**Trigger:**
- Push ke `main` atau `master`
- Manual trigger (workflow_dispatch)

**Process:**
1. Build aplikasi
2. Deploy via SSH ke server DomaiNesia
3. Run migrations
4. Cache optimization
5. Restart queue workers

**Requirements:**
- SSH access ke server
- 4 GitHub Secrets (lihat [QUICK_START.md](./QUICK_START.md))

**Badge:**
```markdown
![Deploy](https://github.com/YOUR-USERNAME/siakad-app/workflows/Deploy/badge.svg)
```

---

### 3. 📤 Deploy FTP (Alternative)
**File:** `workflows/deploy-ftp.yml`

**Trigger:** Manual only (workflow_dispatch)

**Use Case:**
- Ketika SSH tidak tersedia
- Shared hosting tanpa Git access

**Requirements:**
- FTP credentials di GitHub Secrets:
  - `FTP_HOST`
  - `FTP_USERNAME`
  - `FTP_PASSWORD`
  - `FTP_REMOTE_PATH`

**Note:** Memerlukan manual post-deployment steps via cPanel

---

### 4. 🧪 Tests (Matrix Testing)
**File:** `workflows/tests.yml`

**Trigger:**
- Push ke `master`, `*.x`
- Pull request
- Scheduled (daily)

**Matrix:** PHP 8.2, 8.3, 8.4

---

### 5. 🐛 Issues & PRs Management
**Files:**
- `workflows/issues.yml`
- `workflows/pull-requests.yml`
- `workflows/update-changelog.yml`

**Purpose:** Automasi untuk issue dan PR management

---

## 📖 Dokumentasi

### Quick Start
**File:** [QUICK_START.md](./QUICK_START.md)

Setup CI/CD dalam 5 menit:
1. Setup SSH key
2. Configure GitHub Secrets
3. Initial server setup
4. Test deployment

### Full Documentation
**File:** [DEPLOYMENT.md](./DEPLOYMENT.md)

Dokumentasi lengkap mencakup:
- Persiapan SSH Key
- Setup GitHub Secrets (detail)
- Konfigurasi Server DomaiNesia
- Troubleshooting
- Best Practices

---

## 🔑 Required GitHub Secrets

### Untuk SSH Deployment (Recommended)

| Secret | Description | Example |
|--------|-------------|---------|
| `SSH_PRIVATE_KEY` | Private SSH key | `-----BEGIN OPENSSH PRIVATE KEY-----...` |
| `SSH_HOST` | Server hostname/IP | `siakad.yourdomain.com` |
| `SSH_USER` | SSH username | `username` atau `u1234567` |
| `DEPLOY_PATH` | Full path to app | `/home/username/siakad.yourdomain.com` |

### Untuk FTP Deployment (Alternative)

| Secret | Description | Example |
|--------|-------------|---------|
| `FTP_HOST` | FTP server | `ftp.yourdomain.com` |
| `FTP_USERNAME` | FTP username | `username@yourdomain.com` |
| `FTP_PASSWORD` | FTP password | `your-secure-password` |
| `FTP_REMOTE_PATH` | Remote directory | `/public_html/siakad/` |

---

## 🎯 Workflow Diagram

```
Push to main
    ↓
┌───────────────┐
│  CI Workflow  │
│   - Tests     │
│   - Build     │
│   - Quality   │
└───────┬───────┘
        ↓
    ✅ Pass?
        ↓
┌────────────────────┐
│  Deploy Workflow   │
│  - SSH to server   │
│  - Pull changes    │
│  - Install deps    │
│  - Run migrations  │
│  - Cache config    │
│  - Build frontend  │
└────────┬───────────┘
         ↓
    ✅ Success!
         ↓
    🎉 Live on Production
```

---

## 🔧 Local Development vs Production

### Local Development
```bash
# Run development server
composer dev
# atau
php artisan serve
npm run dev
```

### Production (Auto Deploy)
```bash
# Just commit and push
git add .
git commit -m "feat: new feature"
git push origin main

# GitHub Actions will:
# 1. Run tests
# 2. Build production assets
# 3. Deploy to server
# 4. Run migrations
# 5. Cache configs
```

---

## 🚀 Deployment Flow

### Automatic (Recommended)

```bash
# 1. Develop locally
git checkout -b feature/new-feature
# ... koding ...

# 2. Test locally
composer test
npm run build

# 3. Commit changes
git add .
git commit -m "feat: add new feature"
git push origin feature/new-feature

# 4. Create PR di GitHub
# - CI akan run tests
# - Code review

# 5. Merge to main
# - Deploy workflow otomatis jalan
# - App live in production

# 6. Verify
# - Cek https://siakad.yourdomain.com
```

### Manual Deployment

```bash
# Via GitHub UI:
# 1. Go to Actions tab
# 2. Select "Deploy to Server"
# 3. Click "Run workflow"
# 4. Select branch
# 5. Click "Run workflow" button
```

---

## 🐛 Debugging Workflows

### View Logs
1. Repository → **Actions** tab
2. Click workflow yang ingin dilihat
3. Click job yang failed
4. Expand step untuk lihat logs

### Re-run Failed Workflows
1. Buka failed workflow
2. Click **Re-run jobs** (kanan atas)
3. Pilih **Re-run all jobs**

### Cancel Running Workflow
1. Buka running workflow
2. Click **Cancel workflow** (kanan atas)

---

## 📊 Monitoring

### GitHub Actions Usage
- Settings → Billing → GitHub Actions
- Gratis untuk public repositories
- 2000 menit/bulan untuk private repos (free tier)

### Success Rate
- Actions tab → Insights
- Lihat workflow run history

---

## 🔒 Security Best Practices

### ✅ DO:
- ✅ Simpan semua credentials di GitHub Secrets
- ✅ Use SSH key authentication (bukan password)
- ✅ Review logs sebelum commit
- ✅ Test di staging dulu sebelum production
- ✅ Backup database sebelum migrate

### ❌ DON'T:
- ❌ Jangan commit `.env` file
- ❌ Jangan hardcode credentials
- ❌ Jangan skip tests
- ❌ Jangan force push ke main
- ❌ Jangan expose secrets di logs

---

## 📝 Checklist Pre-Deployment

- [ ] All tests passing ✅
- [ ] Code reviewed
- [ ] `.env.example` updated (jika ada env baru)
- [ ] Database migrations tested
- [ ] Frontend assets build successful
- [ ] No hardcoded credentials
- [ ] Changelog updated
- [ ] Documentation updated

---

## 🆘 Get Help

### Documentation
- [Quick Start Guide](./QUICK_START.md) - Setup dalam 5 menit
- [Full Deployment Guide](./DEPLOYMENT.md) - Dokumentasi lengkap

### Troubleshooting
Lihat section Troubleshooting di [DEPLOYMENT.md](./DEPLOYMENT.md)

### Support
- **GitHub Issues:** Buat issue baru di repository
- **DomaiNesia Support:** https://www.domainesia.com/kontak

---

## 📈 Workflow Statistics

Track workflow success rate dan duration di GitHub Actions → Insights.

**Recommended:**
- Success rate > 95%
- Deploy duration < 5 menit
- Test duration < 3 menit

---

## 🎓 Resources

- [GitHub Actions Docs](https://docs.github.com/en/actions)
- [Laravel Deployment](https://laravel.com/docs/deployment)
- [DomaiNesia Knowledge Base](https://www.domainesia.com/panduan)

---

**Last Updated:** 2025-10-20
**Maintained by:** SIAKAD DevOps Team
