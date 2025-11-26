# 🧪 Test Import Dosen dengan Mata Kuliah

## 📋 **LANGKAH TESTING:**

### **Step 1: Download Master Data**

1. **Login sebagai Super Admin:** `http://127.0.0.1:8000/login`

2. **Buka:** `http://127.0.0.1:8000/admin/users`

3. **Klik tab "Import Data User"**

4. **Download 2 file master data:**
   - Klik **"Download Master Prodi"** (button hijau)
   - Klik **"Download Master Mata Kuliah"** (button hijau)

5. **Buka kedua file di Excel:**
   - `master_data_program_studi.csv` → Lihat kode prodi yang ada
   - `master_data_mata_kuliah.csv` → Lihat kode mata kuliah yang ada

**Contoh kode yang harus ada:**
```
Program Studi:
- PAI-S1-L
- MPI-S1-L
- PGMI-S1-D
- HES-S1-L

Mata Kuliah:
- PAI-S1-L-101 (Pendidikan Agama Islam I)
- PAI-S1-L-102 (Ulumul Quran)
- MPI-S1-L-101 (Ilmu Pendidikan Islam)
- dll...
```

---

### **Step 2: Download Template Dosen**

1. Masih di halaman yang sama
2. Scroll ke section **"Import Data Dosen"**
3. Klik **"Download Template Dosen"**
4. File `template_import_dosen.csv` akan terdownload

---

### **Step 3: Edit Template dengan Data Test**

1. **Buka file `template_import_dosen.csv` di Excel**

2. **HAPUS semua baris kecuali header** (baris pertama)

3. **Tambah 1 baris data test:**

   **Copy-paste ini ke Excel:**
   ```
   Column A (username): 8888888888
   Column B (email): 8888888888@staialfatih.ac.id
   Column C (no_telepon): 
   Column D (nidn): 8888888888
   Column E (nama_lengkap): Test Import Dosen Pak Ahmad
   Column F (gelar_depan): Dr.
   Column G (gelar_belakang): M.Pd.I
   Column H (kode_prodi): PAI-S1-L
   Column I (kode_mk): PAI-S1-L-101,PAI-S1-L-102
   ```

   **ATAU lebih mudah, copy baris ini dan paste ke Excel:**
   ```csv
   8888888888,8888888888@staialfatih.ac.id,,8888888888,Test Import Dosen Pak Ahmad,Dr.,M.Pd.I,PAI-S1-L,PAI-S1-L-101,PAI-S1-L-102
   ```

4. **PENTING - Pastikan:**
   - ✅ Kode prodi: `PAI-S1-L` (harus SAMA PERSIS dengan master data prodi)
   - ✅ Kode mata kuliah: `PAI-S1-L-101,PAI-S1-L-102` (pisahkan dengan koma, TANPA spasi setelah koma)
   - ✅ **TIDAK ADA spasi extra** di awal atau akhir setiap cell

5. **Save file:**
   - File → Save As
   - Format: **CSV (Comma delimited) (*.csv)**
   - Nama: `test_import_dosen.csv`

---

### **Step 4: Upload & Import**

1. **Kembali ke browser** `http://127.0.0.1:8000/admin/users`

2. **Section "Import Data Dosen":**
   - Klik **"Choose File"**
   - Pilih `test_import_dosen.csv`
   - Klik **"Upload & Import Dosen"**

3. **Expected Result:**
   ```
   ✅ Success: "Import selesai! Berhasil: 1, Dilewati: 0"
   ```

   **Kalau ada error, akan muncul detail seperti:**
   ```
   Import selesai! Berhasil: 0, Dilewati: 1
   
   Error:
   - Baris 2: Program studi 'PAI-S1-X' tidak ditemukan di database
   - Baris 3: Mata kuliah dengan kode 'PAI-S1-L-999' tidak ditemukan di database
   ```

---

### **Step 5: Verify - Cek Program Studi**

1. **Filter users:**
   - Role: "dosen"
   - Cari username: `8888888888`

2. **Lihat kolom "Prodi":**
   - ✅ Harus muncul: **PAI - Pendidikan Agama Islam (S1 - Linier)**
   - ❌ Kalau kosong = GAGAL assign

---

### **Step 6: Verify - Cek Mata Kuliah yang Diampu**

1. **Klik button "Edit"** dosen yang baru diimport

2. **Scroll ke bawah** sampai section **"Mata Kuliah yang Diampu"**

3. **Check checkbox:**
   - ✅ Checkbox **PAI-S1-L-101** harus **TER-CHECK** ✓
   - ✅ Checkbox **PAI-S1-L-102** harus **TER-CHECK** ✓

4. **Kalau TIDAK ter-check:**
   - Berarti ada masalah dengan sync mata kuliah
   - Cek log untuk detail error

---

## 🔍 **CEK LOG (Jika Gagal)**

### **Windows:**

```bash
# Buka PowerShell
cd D:\AI\siakad\siakad-app

# Lihat log terakhir
Get-Content storage\logs\laravel.log -Tail 50
```

**Cari baris:**
```
Dosen imported: Test Import Dosen Pak Ahmad, Prodi ID: X, Prodi: PAI-S1-L, Mata Kuliah assigned: 2
```

**Arti:**
- `Prodi ID: X` → ID program studi yang ter-assign (harus ada angka, bukan NULL)
- `Prodi: PAI-S1-L` → Kode prodi yang ter-assign
- `Mata Kuliah assigned: 2` → Jumlah mata kuliah yang ter-assign

**Kalau `Mata Kuliah assigned: 0`** = Mata kuliah tidak ter-assign!

**Kemungkinan penyebab:**
1. Kode mata kuliah di CSV **tidak match persis** dengan database
2. Ada spasi sebelum/setelah kode mata kuliah
3. Mata kuliah belum ada di database

---

## 🎯 **Test Case Lengkap:**

### **Test 1: Dosen dengan 2 mata kuliah**
```csv
9999999999,9999999999@staialfatih.ac.id,,9999999999,Pak Zaki Test,Dr.,M.Pd,PAI-S1-L,PAI-S1-L-101,PAI-S1-L-102
```
**Expected:** Prodi PAI-S1-L, MK: PAI-S1-L-101 ✓, PAI-S1-L-102 ✓

### **Test 2: Dosen tanpa mata kuliah**
```csv
7777777777,7777777777@staialfatih.ac.id,,7777777777,Bu Siti Test,,,MPI-S1-L,
```
**Expected:** Prodi MPI-S1-L, MK: (kosong - assign manual nanti)

### **Test 3: Dosen dengan mata kuliah tidak valid**
```csv
6666666666,6666666666@staialfatih.ac.id,,6666666666,Pak Umar Test,,,PAI-S1-L,INVALID-CODE
```
**Expected:** Import berhasil, tapi ada error message: "Mata kuliah dengan kode 'INVALID-CODE' tidak ditemukan"

---

## ✅ **Success Criteria:**

Import dianggap **SUKSES** jika:

1. ✅ Muncul message: "Import selesai! Berhasil: 1, Dilewati: 0"
2. ✅ User dosen muncul di list dengan username sesuai NIDN
3. ✅ Kolom "Prodi" menampilkan nama program studi (bukan kosong)
4. ✅ Di halaman edit dosen, checkbox mata kuliah **ter-check sesuai CSV**
5. ✅ Log menampilkan: "Mata Kuliah assigned: 2" (sesuai jumlah di CSV)

---

## 🚨 **Troubleshooting:**

### **Problem 1: Prodi Tidak Ter-Assign (Kolom Prodi Kosong)**

**Kemungkinan:**
- Kode prodi di CSV salah/typo (contoh: `PAI-S1` seharusnya `PAI-S1-L`)
- Ada spasi sebelum/setelah kode prodi di Excel

**Solusi:**
1. Download lagi master data prodi
2. Copy EXACT kode prodi dari master
3. Paste ke template (pastikan tidak ada spasi)
4. Test lagi

---

### **Problem 2: Mata Kuliah Tidak Ter-Assign (Checkbox Kosong)**

**Kemungkinan:**
- Kode mata kuliah salah/typo
- Ada spasi setelah koma: `PAI-S1-L-101, PAI-S1-L-102` ❌
- Format benar: `PAI-S1-L-101,PAI-S1-L-102` ✅

**Solusi:**
1. Download lagi master data mata kuliah
2. Copy EXACT kode mata kuliah (contoh: `PAI-S1-L-101`)
3. Di Excel, ketik: `PAI-S1-L-101,PAI-S1-L-102` (TANPA spasi setelah koma)
4. Test lagi

**Atau cek log:**
```bash
Get-Content storage\logs\laravel.log -Tail 50 | Select-String "Mata kuliah"
```

Akan muncul:
```
Baris 2: Mata kuliah dengan kode 'PAI-S1-L-999, PAI-S1-L-102' tidak ditemukan di database
```

Artinya ada spasi atau kode salah.

---

## 📊 **Report Format:**

Setelah test, report hasilnya seperti ini:

```
✅ Import berhasil: 1 dosen
✅ Username: 8888888888
✅ Password: dosen_staialfatih
✅ Prodi: PAI - Pendidikan Agama Islam (S1 - Linier) ✓
✅ Mata kuliah ter-assign: 2
   - PAI-S1-L-101 (Pendidikan Agama Islam I) ✓
   - PAI-S1-L-102 (Ulumul Quran) ✓
```

**ATAU kalau gagal:**

```
❌ Import gagal
❌ Prodi: (kosong)
❌ Mata kuliah: (tidak ada yang ter-check)
❌ Error di log: "Program studi 'PAI-S1-X' tidak ditemukan"
```

---

**Silakan test sekarang dan report hasilnya!** 🚀
