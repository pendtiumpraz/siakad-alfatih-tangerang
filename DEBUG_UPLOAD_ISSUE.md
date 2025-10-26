# Debug Upload Issue - Reload Setelah 5-10 Detik

## Masalah
- Form submit → loading muncul → **tiba-tiba reload ke /spmb/register**
- Reload terjadi cepat (5-10 detik), **bukan timeout**
- Tidak ada error message yang muncul

## Kemungkinan Penyebab

### 1. ❌ Google Drive Tidak Terkoneksi
**Symptom:** Google Drive service = NULL
**Fix:** Admin harus OAuth connect ke Google Drive

### 2. ❌ Validation Error (Tidak Terlihat)
**Symptom:** Field yang required kosong atau format salah
**Fix:** Error message harus muncul di form

### 3. ❌ File Upload Error
**Symptom:** File size > limit atau MIME type tidak didukung
**Fix:** Cek size limit dan format file

### 4. ❌ Google Drive API Error
**Symptom:** Quota exceeded, permission denied, dll
**Fix:** Cek Google Cloud Console

## Cara Debug

### Step 1: Cek Browser Console

1. Buka form SPMB: `https://siakad.diproses.online/spmb/register`
2. Tekan **F12** → Tab **Console**
3. Submit form (dengan dokumen)
4. Lihat log yang muncul:

**Expected logs (Success):**
```
🔧 Initializing registration form...
✅ Form initialized
====== FORM SUBMIT STARTED ======
🚀 Final submission - showing loading overlay
Foto file: photo.jpg (245123 bytes)
🗑️ Cleared localStorage before submit
✅ Form will submit now...
```

**Expected logs (Error):**
```
⚠️ Page unloading while upload in progress!
🔄 Page is being hidden/reloaded
```

**Screenshot console dan kirim ke developer!**

### Step 2: Cek Laravel Logs

1. Akses: `https://siakad.diproses.online/debug-logs?filter=SPMB`
2. Cari log terakhir dengan keyword:
   - `====== SPMB REGISTRATION STARTED ======`
   - `VALIDATION FAILED`
   - `Google Drive service is NULL`
   - `Failed to pre-create`

**Expected logs (Success):**
```
====== SPMB REGISTRATION STARTED ======
Google Drive service: ACTIVE
✅ Validation passed
📁 Pre-creating Google Drive folders...
✅ Google Drive folders ready
```

**Expected logs (Error - No Google Drive):**
```
====== SPMB REGISTRATION STARTED ======
Google Drive service: NOT ACTIVE
❌ Google Drive service is NULL - cannot upload documents
```

**Expected logs (Error - Validation):**
```
====== SPMB REGISTRATION STARTED ======
====== VALIDATION FAILED ======
Errors: {"foto":["Foto 4x6 harus diupload sebelum submit pendaftaran."]}
```

**Screenshot logs dan kirim ke developer!**

### Step 3: Test dengan Minimal Data

Untuk isolate masalah, test step by step:

#### Test 1: Check Google Drive Status
```bash
curl https://siakad.diproses.online/oauth/google/status
```

Harus return: `{"connected": true}` atau `{"connected": false}`

Kalau `false` → **Admin harus OAuth connect dulu**

#### Test 2: Upload 1 Dokumen Saja
- Isi semua field **kecuali dokumen**
- Klik "Simpan Draft" di step 8
- Harus success tanpa upload dokumen

#### Test 3: Upload Foto Saja
- Isi semua field
- Upload **hanya foto** (4x6, JPG, < 500KB)
- Submit
- Cek apakah redirect ke `/spmb/result` atau reload

#### Test 4: Check File Size
File terlalu besar?
- Foto: max **500KB**
- Ijazah/Transkrip: max **2MB**
- KTP/KK/Akta/SKTM: max **1MB**

## Quick Fixes

### Fix 1: Connect Google Drive (Admin)

1. Login sebagai **super_admin**
2. Akses: `https://siakad.diproses.online/oauth/google/connect`
3. Grant permission untuk Google Drive
4. Test upload lagi

### Fix 2: Check File Validation

**Foto:**
- Format: JPG, JPEG, PNG
- Size: Max 500KB
- Rasio: 4x6 (portrait)

**Dokumen lain:**
- Format: PDF, JPG, JPEG, PNG
- Size: Lihat tabel di atas

### Fix 3: Check Shared Hosting Limits

DomaiNesia mungkin punya hard limit:
- PHP upload_max_filesize
- PHP post_max_size
- Memory limit

Cek current settings:
```bash
curl https://siakad.diproses.online/test-timeout.php
```

## Hasil yang Diharapkan

### ✅ Success Flow:
1. Console log: "Form submit started"
2. Console log: "Foto file: ..."
3. Laravel log: "SPMB REGISTRATION STARTED"
4. Laravel log: "✅ Validation passed"
5. Laravel log: "✅ Google Drive folders ready"
6. Laravel log: "Successfully uploaded Foto"
7. **Redirect to:** `/spmb/result?nomor_pendaftaran=SPMBxxxx`
8. Success message muncul

### ❌ Current Flow (Error):
1. Console log: "Form submit started"
2. Laravel log: "SPMB REGISTRATION STARTED"
3. Laravel log: "Google Drive service: NOT ACTIVE" ← ATAU
4. Laravel log: "====== VALIDATION FAILED ======" ← ATAU
5. Laravel log: "❌ Failed to pre-create folders"
6. **Reload to:** `/spmb/register` ← YOU ARE HERE
7. No error message shown ← BUG!

## Next Steps

1. ✅ Pull latest code: `git pull origin main`
2. ⏳ Buka browser Console (F12)
3. ⏳ Submit form dan screenshot console log
4. ⏳ Cek `/debug-logs?filter=SPMB` dan screenshot
5. ⏳ Kirim ke developer untuk analysis

## Common Errors dan Solusi

| Error | Symptom | Solution |
|-------|---------|----------|
| Google Drive NULL | "Google Drive tidak aktif" | Admin OAuth connect |
| Validation Failed | Form reload, no message | Check console & logs |
| File too large | "max:500" error | Compress file |
| Wrong format | "mimes:jpg,png" error | Convert file format |
| Aspect ratio | "rasio 4x6" error | Crop foto to 4x6 |
| Timeout | Reload after 60s+ | Apply NGINX_TIMEOUT_FIX.md |

## Contact

Jika sudah test semua dan masih error, kirim:
1. Screenshot browser console (F12)
2. Screenshot `/debug-logs?filter=SPMB`
3. Info: file size, format, jalur seleksi
