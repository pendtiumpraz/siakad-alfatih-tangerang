# 📥 Cara Download Master Data untuk Import

## 🎯 Kenapa Harus Download Master Data?

Sebelum import mahasiswa/dosen, **WAJIB** punya data rujukan untuk:
- **kode_prodi** (Program Studi)
- **kode_mk** (Mata Kuliah) - khusus untuk dosen

Dengan download master data dalam format Excel, Anda bisa:
✅ **Copy-paste langsung** kode tanpa typo
✅ **Lihat semua data** yang tersedia di sistem
✅ **Offline reference** saat isi template import

---

## 📍 Cara Download Master Data

### **Lokasi Download:**

Menu: `Admin → Users` → Tab **"Import Data User"**

Scroll ke bagian **Import Data Dosen**, Anda akan melihat:

**⚠️ PENTING: Cek Master Data Sebelum Import!**
- 👁️ **Lihat Program Studi** (buka di browser)
- 👁️ **Lihat Mata Kuliah** (buka di browser)

**📥 Download Master Data (Excel) untuk Rujukan:**
- 📗 **Download Master Prodi** ⬅️ Klik ini
- 📘 **Download Master Mata Kuliah** ⬅️ Klik ini
- 📄 **Baca Panduan Lengkap**

---

## 📊 Isi File Master Data

### **1. Master Data Program Studi (master_data_program_studi.xlsx)**

File ini berisi kolom:

| Kode Prodi | Nama Program Studi | Jenjang | Akreditasi | Status | Deskripsi |
|------------|-------------------|---------|------------|--------|-----------|
| PAI-S1-L   | Pendidikan Agama Islam - Luring | S1 | B | Aktif | ... |
| PAI-S1-D   | Pendidikan Agama Islam - Daring | S1 | B | Aktif | ... |
| MPI-S1-L   | Manajemen Pendidikan Islam - Luring | S1 | B | Aktif | ... |
| PGMI-S1-D  | Pendidikan Guru MI - Daring | S1 | B | Aktif | ... |
| HES-S1-L   | Hukum Ekonomi Syariah - Luring | S1 | B | Aktif | ... |

**Gunakan kolom `Kode Prodi` untuk:**
- Import mahasiswa → isi kolom `kode_prodi`
- Import dosen → isi kolom `kode_prodi`

---

### **2. Master Data Mata Kuliah (master_data_mata_kuliah.xlsx)**

File ini berisi kolom:

| Kode Mata Kuliah | Nama Mata Kuliah | Program Studi | Kurikulum | SKS | Semester | Jenis | Deskripsi |
|------------------|------------------|---------------|-----------|-----|----------|-------|-----------|
| PAI-S1-L-101     | Ulumul Qur'an    | PAI-S1-L      | Kurikulum 2023 | 3 | 1 | wajib | ... |
| PAI-S1-L-102     | Bahasa Arab I    | PAI-S1-L      | Kurikulum 2023 | 3 | 1 | wajib | ... |
| PAI-S1-L-201     | Tafsir Maudhu'i  | PAI-S1-L      | Kurikulum 2023 | 3 | 2 | wajib | ... |
| MPI-S1-L-101     | Pengantar Pendidikan | MPI-S1-L | Kurikulum 2023 | 3 | 1 | wajib | ... |

**Gunakan kolom `Kode Mata Kuliah` untuk:**
- Import dosen → isi kolom `kode_mk`

---

## ✅ Cara Pakai Master Data

### **Skenario: Import Dosen dengan Mata Kuliah**

**Langkah 1: Download Master Data**
1. Buka `Admin → Users` → Tab "Import Data User"
2. Klik **"Download Master Prodi"** → Simpan file
3. Klik **"Download Master Mata Kuliah"** → Simpan file

**Langkah 2: Buka File Excel Master Data**
1. Buka `master_data_program_studi.xlsx`
2. Lihat kolom **Kode Prodi** → Catat kode yang akan dipakai
   - Contoh: `PAI-S1-L`, `MPI-S1-L`, `PGMI-S1-D`
3. Buka `master_data_mata_kuliah.xlsx`
4. Filter kolom **Program Studi** (contoh: `PAI-S1-L`)
5. Lihat kolom **Kode Mata Kuliah** → Catat kode yang akan dipakai
   - Contoh: `PAI-S1-L-101`, `PAI-S1-L-102`, `PAI-S1-L-201`

**Langkah 3: Download Template Import Dosen**
1. Klik **"Download Template Dosen"**
2. Buka file `template_import_dosen.xlsx`

**Langkah 4: Isi Template dengan Copy-Paste**
1. Dari `master_data_program_studi.xlsx`, **copy** kode_prodi yang valid
2. **Paste** ke kolom `kode_prodi` di template import
3. Dari `master_data_mata_kuliah.xlsx`, **copy** kode_mk yang valid
4. **Paste** ke kolom `kode_mk` di template import (pisah dengan koma jika multiple)

**Contoh:**
```excel
Template Import Dosen:
username: 0101018901
kode_prodi: PAI-S1-L          ← Copy dari master_data_program_studi.xlsx
kode_mk: PAI-S1-L-101,PAI-S1-L-102  ← Copy dari master_data_mata_kuliah.xlsx
```

**Langkah 5: Upload & Import**
1. Simpan template yang sudah diisi
2. Upload file di form import dosen
3. Klik **"Import Dosen"**

---

## 🔍 Tips Copy-Paste yang Benar

### ✅ **CARA BENAR:**

**Dari Master Data:**
```excel
Kode Prodi: PAI-S1-L
```

**Copy lalu Paste ke Template:**
```excel
kode_prodi: PAI-S1-L
```

**Multiple Mata Kuliah:**
```excel
Kode MK: PAI-S1-L-101
Kode MK: PAI-S1-L-102
Kode MK: PAI-S1-L-103
```

**Copy lalu gabung dengan koma di Template:**
```excel
kode_mk: PAI-S1-L-101,PAI-S1-L-102,PAI-S1-L-103
```

### ❌ **CARA SALAH:**

```excel
❌ kode_prodi: pai-s1-l        (huruf kecil semua)
❌ kode_prodi: PAI S1 L         (ada spasi)
❌ kode_prodi: PAI-S1-L         (ada spasi sebelum kode - copy salah)
❌ kode_mk: PAI-101             (tidak lengkap, kurang S1-L)
```

---

## 🛠️ Troubleshooting

### **Q: File download kosong atau tidak ada data?**
**A:** 
- Cek apakah program studi sudah di-seed ke database
- Jalankan seeder: 
  ```bash
  php artisan db:seed --class=StaiAlfatihProgramStudiSeeder
  php artisan db:seed --class=StaiAlfatihMataKuliahS1Seeder
  ```
- Refresh halaman dan download ulang

### **Q: Mata kuliah tidak sesuai prodi yang saya butuhkan?**
**A:**
- File `master_data_mata_kuliah.xlsx` berisi **semua** mata kuliah dari **semua** prodi
- Gunakan fitur **Filter** di Excel:
  1. Klik header kolom **Program Studi**
  2. Pilih **Filter**
  3. Centang hanya prodi yang Anda butuhkan (contoh: `PAI-S1-L`)
  4. Sekarang hanya mata kuliah PAI-S1-L yang tampil

### **Q: Kode mata kuliah di master data berbeda dengan yang di sistem?**
**A:**
- Master data diambil **langsung dari database**
- Jika kode berbeda, berarti database perlu diupdate
- Hubungi admin untuk sinkronisasi data
- Atau jalankan seeder ulang jika memang ada perubahan kurikulum

---

## 📋 Checklist Sebelum Import

Gunakan checklist ini untuk memastikan import berhasil:

- [ ] Download **master_data_program_studi.xlsx** ✅
- [ ] Download **master_data_mata_kuliah.xlsx** ✅
- [ ] Buka kedua file master data di Excel
- [ ] Identifikasi kode_prodi yang akan dipakai
- [ ] Identifikasi kode_mk yang akan dipakai (khusus dosen)
- [ ] Download template import (mahasiswa/dosen)
- [ ] **Copy-paste** (JANGAN ketik manual!) kode dari master data ke template
- [ ] Validasi format:
  - kode_prodi: `PAI-S1-L` (case sensitive, persis sama)
  - kode_mk: `PAI-S1-L-101,PAI-S1-L-102` (pisah koma, tanpa spasi)
- [ ] Simpan template yang sudah diisi
- [ ] Upload dan import

---

## 💡 Best Practice

1. **Selalu download master data terbaru** sebelum import (data bisa berubah)
2. **Buka 3 file sekaligus** di Excel:
   - `master_data_program_studi.xlsx`
   - `master_data_mata_kuliah.xlsx`
   - `template_import_dosen.xlsx`
3. **Gunakan 2 monitor** jika tersedia (1 untuk master data, 1 untuk template)
4. **Copy-paste bertahap**: Isi 1-2 baris dulu, test import, jika berhasil lanjutkan
5. **Simpan master data** sebagai referensi jangka panjang

---

## 🚀 Workflow Lengkap Import Dosen

```mermaid
1. Admin → Users → Import Data User
2. Download Master Prodi (Excel)
3. Download Master Mata Kuliah (Excel)
4. Buka master data, catat kode yang valid
5. Download Template Import Dosen
6. Copy-paste kode dari master data ke template
7. Isi data dosen lainnya (username, email, nama, dll)
8. Simpan template
9. Upload template di form import
10. Klik "Import Dosen"
11. Cek hasil import (berapa berhasil/gagal)
12. Jika ada error, perbaiki berdasarkan pesan error
13. Verifikasi data dosen di menu Users
```

---

## 📞 Bantuan

Jika masih ada kesulitan:

1. **Baca dokumentasi lengkap**: `PANDUAN_CEK_MASTER_DATA.md`
2. **Screenshot pesan error** saat import gagal
3. **Hubungi Administrator Sistem** dengan menyertakan:
   - Screenshot error
   - File template yang diupload
   - File master data yang digunakan

---

**Dokumen ini dibuat untuk mempermudah proses import dengan rujukan master data yang akurat.**

✅ **Dengan download master data Excel, risiko typo dan kesalahan kode berkurang drastis!**
