# ✅ Testing Checklist - Fitur Import & Edit Username

Server sudah jalan di: **http://127.0.0.1:8000**

---

## 🎯 **TEST 1: Download Master Data (NEW)**

### **Login:**
1. Buka browser: `http://127.0.0.1:8000/login`
2. Login sebagai **Super Admin**

### **Test Download Master Data:**

1. **Buka halaman Users:**
   ```
   http://127.0.0.1:8000/admin/users
   ```

2. **Klik tab "Import Data User"** (di bagian atas)

3. **Scroll ke section "Import Data Dosen"**

4. **Cari box kuning dengan tulisan:**
   > ⚠️ PENTING: Cek Master Data Sebelum Import!
   
   > 📥 Download Master Data (Excel) untuk Rujukan:

5. **Test Download:**
   - ✅ Klik **"Download Master Prodi"** (button hijau)
     - File `master_data_program_studi.xlsx` harus download
     - Buka file, cek ada data program studi (PAI-S1-L, MPI-S1-L, dll)
   
   - ✅ Klik **"Download Master Mata Kuliah"** (button hijau)
     - File `master_data_mata_kuliah.xlsx` harus download
     - Buka file, cek ada data mata kuliah (PAI-S1-L-101, PAI-S1-L-102, dll)

---

## 🎯 **TEST 2: Download Template Import**

Masih di halaman yang sama:

### **Test Template Mahasiswa:**
1. Section "Import Data Mahasiswa"
2. Klik **"Download Template Mahasiswa"**
3. File `template_import_mahasiswa.xlsx` download
4. Buka file, cek:
   - ✅ Ada 13 baris contoh data
   - ✅ Username = NIM (contoh: 2022001)
   - ✅ Kolom tempat_lahir, tanggal_lahir, alamat, no_telepon = KOSONG

### **Test Template Dosen:**
1. Section "Import Data Dosen"
2. Klik **"Download Template Dosen"**
3. File `template_import_dosen.xlsx` download
4. Buka file, cek:
   - ✅ Ada 11 baris contoh data
   - ✅ Username = NIDN (contoh: 0101018901)
   - ✅ Ada kolom `kode_mk` (mata kuliah) di column I
   - ✅ Beberapa dosen punya mata kuliah, beberapa kosong

---

## 🎯 **TEST 3: Import Mahasiswa**

### **Persiapan:**
1. Buka `template_import_mahasiswa.xlsx`
2. Edit baris pertama (ganti data dengan mahasiswa test):
   ```
   username: 2099999
   email: 2099999@staialfatih.ac.id
   nim: 2099999
   nama_lengkap: Test Mahasiswa Import
   kode_prodi: PAI-S1-L (copy dari master_data_program_studi.xlsx)
   angkatan: 2024
   jenis_kelamin: L
   status: aktif
   ```
3. Simpan file

### **Import:**
1. Kembali ke `http://127.0.0.1:8000/admin/users`
2. Tab "Import Data User" → Section "Import Data Mahasiswa"
3. Klik **"Pilih File Excel"** → Pilih template yang sudah diedit
4. Klik **"Import Mahasiswa"**

### **Expected Result:**
- ✅ Muncul pesan: "Berhasil import X mahasiswa"
- ✅ Tidak ada error
- ✅ Filter role "mahasiswa" → Cari NIM 2099999 → Ada!

---

## 🎯 **TEST 4: Import Dosen (dengan Mata Kuliah)**

### **Persiapan:**
1. Buka `master_data_program_studi.xlsx` → Copy `PAI-S1-L`
2. Buka `master_data_mata_kuliah.xlsx` → Filter prodi `PAI-S1-L` → Copy `PAI-S1-L-101,PAI-S1-L-102`
3. Buka `template_import_dosen.xlsx`
4. Edit baris pertama:
   ```
   username: 9999999999
   email: 9999999999@staialfatih.ac.id
   nidn: 9999999999
   nama_lengkap: Test Dosen Import
   kode_prodi: PAI-S1-L (paste dari master)
   kode_mk: PAI-S1-L-101,PAI-S1-L-102 (paste dari master)
   ```
5. Simpan file

### **Import:**
1. Kembali ke `http://127.0.0.1:8000/admin/users`
2. Tab "Import Data User" → Section "Import Data Dosen"
3. Klik **"Pilih File Excel"** → Pilih template yang sudah diedit
4. Klik **"Import Dosen"**

### **Expected Result:**
- ✅ Muncul pesan: "Berhasil import X dosen"
- ✅ Tidak ada error
- ✅ Filter role "dosen" → Cari NIDN 9999999999 → Ada!

### **Verify Mata Kuliah Ter-Assign:**
1. Klik **"Edit"** pada dosen test (NIDN: 9999999999)
2. Scroll ke section "Mata Kuliah yang Diampu"
3. ✅ Checkbox `PAI-S1-L-101` dan `PAI-S1-L-102` harus **CHECKED** (ter-assign otomatis!)

---

## 🎯 **TEST 5: Edit Username Mahasiswa (1x Only)**

### **Login sebagai Mahasiswa:**
1. Logout dari admin
2. Login dengan:
   - Username: `2099999` (mahasiswa test yang baru diimport)
   - Password: `mahasiswa_staialfatih` (default password)

### **Test Edit Username:**
1. Buka: `http://127.0.0.1:8000/mahasiswa/profile/edit-username`
2. **Pertama kali (HARUS BERHASIL):**
   - Username baru: `mahasiswa_test_baru`
   - Password: `mahasiswa_staialfatih`
   - Klik **"Simpan Username"**
   - ✅ Expected: "Username berhasil diubah!"
   - ✅ Redirect ke dashboard

3. **Coba edit lagi (HARUS DITOLAK):**
   - Buka lagi: `/mahasiswa/profile/edit-username`
   - ✅ Expected: Muncul box kuning "Username Sudah Pernah Diubah"
   - ✅ Form tidak bisa diisi
   - ✅ Tampil tanggal kapan username diubah

4. **Test Login dengan Username Baru:**
   - Logout
   - Login dengan username: `mahasiswa_test_baru` (yang baru)
   - Password: `mahasiswa_staialfatih`
   - ✅ Expected: Login berhasil!

---

## 🎯 **TEST 6: Edit Username Dosen (1x Only)**

### **Login sebagai Dosen:**
1. Logout
2. Login dengan:
   - Username: `9999999999` (dosen test yang baru diimport)
   - Password: `dosen_staialfatih` (default password)

### **Test Edit Username:**
1. Buka: `http://127.0.0.1:8000/dosen/profile/edit-username`
2. **Pertama kali (HARUS BERHASIL):**
   - Username baru: `dosen_test_baru`
   - Password: `dosen_staialfatih`
   - Klik **"Simpan Username"**
   - ✅ Expected: "Username berhasil diubah!"

3. **Coba edit lagi (HARUS DITOLAK):**
   - Buka lagi: `/dosen/profile/edit-username`
   - ✅ Expected: Muncul box kuning "Username Sudah Pernah Diubah"
   - ✅ Form tidak bisa diisi

4. **Test Login dengan Username Baru:**
   - Logout
   - Login dengan username: `dosen_test_baru`
   - Password: `dosen_staialfatih`
   - ✅ Expected: Login berhasil!

---

## 🎯 **TEST 7: URL Direct Access**

Test akses langsung via URL (login sebagai admin dulu):

```
✅ http://127.0.0.1:8000/admin/users/template/mahasiswa
✅ http://127.0.0.1:8000/admin/users/template/dosen
✅ http://127.0.0.1:8000/admin/users/master-data/program-studi
✅ http://127.0.0.1:8000/admin/users/master-data/mata-kuliah
```

Semua harus download file Excel.

---

## 📊 **Summary Testing**

| Fitur | Status | Notes |
|-------|--------|-------|
| Download Master Prodi | ⬜ | File download? Ada isi? |
| Download Master Mata Kuliah | ⬜ | File download? Ada isi? |
| Download Template Mahasiswa | ⬜ | 13 contoh, username=NIM? |
| Download Template Dosen | ⬜ | 11 contoh, username=NIDN, ada kode_mk? |
| Import Mahasiswa | ⬜ | Berhasil? Data masuk DB? |
| Import Dosen | ⬜ | Berhasil? Data masuk DB? |
| Mata Kuliah Auto-Assign | ⬜ | Checkbox ter-check otomatis? |
| Edit Username Mahasiswa (1x) | ⬜ | Pertama sukses, kedua ditolak? |
| Edit Username Dosen (1x) | ⬜ | Pertama sukses, kedua ditolak? |
| Login dengan Username Baru | ⬜ | Berhasil login? |

---

## ❌ **Common Errors & Solutions**

### **Error: "Excel class not found"**
```bash
composer dump-autoload
php artisan config:clear
# Restart: php artisan serve
```

### **Error: "kode_prodi not found"**
- Pastikan copy-paste dari master_data_program_studi.xlsx
- Case sensitive! Harus PERSIS sama

### **Error: "kode_mk not found"**
- Check di master_data_mata_kuliah.xlsx
- Jika tidak ada, kosongkan kolom kode_mk (assign manual nanti)

### **Error: "Username already taken"**
- Ganti username dengan yang lain
- Atau hapus user lama via admin panel

---

## ✅ **Success Criteria**

Semua fitur dianggap **SUKSES** jika:

1. ✅ Master data download (prodi & mata kuliah)
2. ✅ Template download (mahasiswa & dosen)
3. ✅ Import mahasiswa berhasil
4. ✅ Import dosen berhasil + mata kuliah ter-assign
5. ✅ Edit username mahasiswa: 1x sukses, 2x ditolak
6. ✅ Edit username dosen: 1x sukses, 2x ditolak
7. ✅ Login dengan username baru berhasil

---

**SELAMAT TESTING! 🚀**

Report hasil testingnya (mana yang sukses, mana yang error).
