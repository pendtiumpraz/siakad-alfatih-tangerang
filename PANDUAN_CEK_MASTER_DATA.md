# 📋 Panduan Cek Master Data untuk Import Dosen

Sebelum melakukan import dosen, **WAJIB** cek terlebih dahulu **kode_prodi** dan **kode_mk** (mata kuliah) yang tersedia di sistem.

---

## 🎯 Mengapa Harus Cek Master Data?

Template import dosen menggunakan:
- **`kode_prodi`** untuk menentukan program studi yang diajar dosen
- **`kode_mk`** untuk menentukan mata kuliah yang diampu dosen

Jika kode yang Anda masukkan **tidak sesuai** dengan yang ada di database, import akan **GAGAL** atau data tidak ter-assign dengan benar.

---

## 📌 Cara Cek Master Data

### **1. Login sebagai Super Admin atau Operator**

URL Login: `https://domain-anda.com/login`

### **2. Cek Program Studi (kode_prodi)**

**Menu: `Master Data` → `Program Studi`**

URL Langsung:
- Super Admin: `https://domain-anda.com/admin/program-studi`
- Operator: `https://domain-anda.com/operator/program-studi`

**Informasi yang Dicatat:**
- **Kode Prodi** (contoh: `PAI-S1-L`, `MPI-S1-D`, `PGMI-S1-D`)
- **Nama Prodi** (contoh: `Pendidikan Agama Islam`)
- **Jenjang** (S1, S2, S3)
- **Status Aktif**

**Contoh Data Program Studi:**

| Kode Prodi  | Nama Prodi                          | Jenjang | Status  |
|-------------|-------------------------------------|---------|---------|
| PAI-S1-L    | Pendidikan Agama Islam - Luring    | S1      | Aktif   |
| PAI-S1-D    | Pendidikan Agama Islam - Daring    | S1      | Aktif   |
| MPI-S1-L    | Manajemen Pendidikan Islam - Luring| S1      | Aktif   |
| MPI-S1-D    | Manajemen Pendidikan Islam - Daring| S1      | Aktif   |
| PGMI-S1-L   | Pendidikan Guru MI - Luring        | S1      | Aktif   |
| PGMI-S1-D   | Pendidikan Guru MI - Daring        | S1      | Aktif   |
| HES-S1-L    | Hukum Ekonomi Syariah - Luring     | S1      | Aktif   |
| HES-S1-D    | Hukum Ekonomi Syariah - Daring     | S1      | Aktif   |
| IQT-D-S1    | Ilmu Al-Quran dan Tafsir - Daring  | S1      | Aktif   |
| IQT-L-S1    | Ilmu Al-Quran dan Tafsir - Luring  | S1      | Aktif   |

---

### **3. Cek Mata Kuliah (kode_mk)**

**Menu: `Master Data` → `Mata Kuliah`**

URL Langsung:
- Super Admin: `https://domain-anda.com/admin/mata-kuliah`
- Operator: `https://domain-anda.com/operator/mata-kuliah`
- Dosen: `https://domain-anda.com/dosen/mata-kuliah`

**Informasi yang Dicatat:**
- **Kode MK** (contoh: `PAI-S1-L-101`, `MPI-S1-L-201`)
- **Nama MK** (contoh: `Ulumul Qur'an`, `Bahasa Arab I`)
- **SKS**
- **Semester**
- **Kurikulum** (terkait dengan prodi)

**💡 TIP: Gunakan Filter dan Search**
- Filter berdasarkan **Program Studi** untuk melihat MK yang sesuai
- Gunakan **Search** untuk mencari kode atau nama mata kuliah

**Contoh Data Mata Kuliah untuk PAI-S1-L:**

| Kode MK      | Nama Mata Kuliah  | SKS | Semester | Jenis  |
|--------------|-------------------|-----|----------|--------|
| PAI-S1-L-101 | Ulumul Qur'an     | 3   | 1        | Wajib  |
| PAI-S1-L-102 | Bahasa Arab I     | 3   | 1        | Wajib  |
| PAI-S1-L-103 | Tahsin Al-Qur'an  | 2   | 1        | Wajib  |
| PAI-S1-L-104 | Aqidah Islamiyah  | 2   | 1        | Wajib  |
| PAI-S1-L-201 | Tafsir Maudhu'i   | 3   | 2        | Wajib  |
| PAI-S1-L-202 | Bahasa Arab II    | 3   | 2        | Wajib  |
| PAI-S1-L-301 | Tafsir Tahlili I  | 3   | 3        | Wajib  |

---

## 📝 Cara Mengisi Template Import

### **Format Kode Prodi:**
- **Single prodi:** `PAI-S1-L`
- **Multiple prodi:** `PAI-S1-L,MPI-S1-L` (pisah dengan **koma**)

### **Format Kode Mata Kuliah:**
- **Kosong:** `` (biarkan kosong, assign manual nanti)
- **Single MK:** `PAI-S1-L-101`
- **Multiple MK:** `PAI-S1-L-101,PAI-S1-L-102,PAI-S1-L-103` (pisah dengan **koma**)

### **Contoh Template yang Benar:**

| username   | email                    | nidn       | nama_lengkap | kode_prodi | kode_mk                              |
|------------|--------------------------|------------|--------------|------------|--------------------------------------|
| 0101018901 | 0101018901@staialfatih...| 0101018901 | Ahmad Fauzi  | PAI-S1-L   | PAI-S1-L-101,PAI-S1-L-102,PAI-S1-L-103 |
| 0202019002 | 0202019002@staialfatih...| 0202019002 | Siti Nur...  | MPI-S1-L   | MPI-S1-L-101,MPI-S1-L-201           |
| 0303018703 | 0303018703@staialfatih...| 0303018703 | M. Yusuf     | PAI-S1-L,PAI-S1-D | PAI-S1-L-301,PAI-S1-D-301  |

---

## ⚠️ Kesalahan Umum

### ❌ **SALAH:**
```excel
kode_prodi: PAI        (terlalu pendek, tidak sesuai format)
kode_prodi: pai-s1-l   (huruf kecil semua)
kode_mk: PAI-101       (kurang lengkap, harus PAI-S1-L-101)
kode_mk: Ulumul Quran  (pakai nama, bukan kode)
```

### ✅ **BENAR:**
```excel
kode_prodi: PAI-S1-L
kode_prodi: PAI-S1-L,MPI-S1-L
kode_mk: PAI-S1-L-101
kode_mk: PAI-S1-L-101,PAI-S1-L-102,PAI-S1-L-103
kode_mk:                (kosong, assign manual nanti)
```

---

## 🔍 Cara Export Master Data untuk Referensi

### **1. Export Program Studi**
- Buka halaman Program Studi
- Klik tombol **"Export"** atau **"Download"** (jika tersedia)
- Atau **screenshot** dan catat di Excel sendiri

### **2. Export Mata Kuliah**
- Buka halaman Mata Kuliah
- Filter berdasarkan Program Studi yang relevan
- Klik **"Export"** atau **screenshot** data
- Catat kode_mk yang akan digunakan

---

## 📞 Troubleshooting

### **Masalah 1: Kode Prodi Tidak Ditemukan**
**Solusi:**
1. Cek ejaan kode_prodi (CASE SENSITIVE, harus huruf besar)
2. Pastikan format lengkap: `PAI-S1-L` (bukan `PAI` saja)
3. Cek status prodi **Aktif**
4. Jika tidak ada, hubungi Admin untuk menambahkan prodi baru

### **Masalah 2: Kode Mata Kuliah Tidak Ditemukan**
**Solusi:**
1. Cek ejaan kode_mk (CASE SENSITIVE)
2. Pastikan format lengkap: `PAI-S1-L-101`
3. Pastikan mata kuliah sudah di-seed ke database
4. Jika mata kuliah belum ada, **kosongkan kolom kode_mk** dan assign manual nanti setelah mata kuliah ditambahkan

### **Masalah 3: Import Berhasil Tapi Mata Kuliah Tidak Ter-Assign**
**Penyebab:**
- Kode mata kuliah tidak cocok dengan database
- Mata kuliah belum di-seed untuk prodi tersebut

**Solusi:**
- Cek log error di hasil import
- Edit dosen secara manual via menu **Admin → Users → Edit Dosen**
- Pilih mata kuliah yang tersedia dari checkbox

---

## 🎓 Best Practice

1. **Selalu cek master data SEBELUM import** ✅
2. **Gunakan template yang sudah didownload** dari sistem (sudah berisi contoh yang valid)
3. **Copy-paste kode dari halaman master data** untuk menghindari typo
4. **Test import dengan 1-2 data dulu** sebelum import banyak data
5. **Jika ragu, kosongkan kolom kode_mk** → Assign manual nanti lebih aman

---

## 📚 Referensi Cepat

### **Halaman-Halaman Penting:**

| Halaman               | URL (Admin)                              | URL (Operator)                            |
|-----------------------|------------------------------------------|-------------------------------------------|
| **Dashboard Admin**   | `/admin/dashboard`                       | `/operator/dashboard`                     |
| **Program Studi**     | `/admin/program-studi`                   | `/operator/program-studi`                 |
| **Mata Kuliah**       | `/admin/mata-kuliah`                     | `/operator/mata-kuliah`                   |
| **Import Users**      | `/admin/users` (tab Import)              | -                                         |
| **Manage Users**      | `/admin/users`                           | -                                         |

---

## ✅ Checklist Sebelum Import

- [ ] Login sebagai Super Admin
- [ ] Buka halaman Program Studi → Catat semua kode_prodi yang aktif
- [ ] Buka halaman Mata Kuliah → Filter per prodi → Catat kode_mk yang tersedia
- [ ] Download template import dosen dari sistem
- [ ] Isi data dosen dengan kode_prodi dan kode_mk yang SUDAH DICATAT
- [ ] Test import dengan 1-2 data dulu
- [ ] Jika berhasil, lanjutkan import semua data
- [ ] Cek hasil import di halaman Users → Filter role "Dosen"
- [ ] Verifikasi mata kuliah ter-assign dengan Edit dosen

---

## 💡 Tips Tambahan

**Jika Prodi Baru atau Mata Kuliah Belum Ada:**
1. Hubungi **Super Admin** untuk menambahkan prodi baru
2. Jalankan **seeder** untuk menambahkan mata kuliah:
   ```bash
   php artisan db:seed --class=StaiAlfatihMataKuliahS1Seeder
   ```
3. Setelah prodi/mata kuliah ditambahkan, **refresh halaman** master data
4. **Cek ulang** kode_prodi dan kode_mk yang tersedia
5. Lanjutkan import dosen

---

**Dokumen ini dibuat untuk memudahkan proses import dosen ke sistem SIAKAD STAI AL-FATIH Tangerang.**

Jika ada pertanyaan, hubungi Administrator Sistem.
